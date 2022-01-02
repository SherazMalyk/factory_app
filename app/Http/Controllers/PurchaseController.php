<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Layer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'stock_qty' => $request->stock_qty,
            'stock_unit' => $request->stock_unit,
            'stock_price' => $request->stock_price,
            'created_by' => Auth::id(),
            'stock_description' => $request->stock_description,

        ];
        $stock = Stock::create($data);
        $stockId = $stock->id;
        $data2 = [
            'stock_id' => $stockId,
            'user_id' => $request->supplier_id,
            'account_id' => $request->account_id,
            'tran_purchase_price' => $request->stock_price,
            'tran_paid_amount' => $request->tran_paid_amount,
            'tran_due_amount' => $request->tran_due_amount,
            'tran_description' => $request->stock_description,
        ];
        $out = Transaction::create($data2);
        if ($out) {
            return response()->json(['msg' => ' &nbsp; Purchase Is Added', 'sts' => 'success']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        $q = Stock::where('stock_unit','!=',null);
        return Datatables::of($q)
        ->editColumn('product_id', function($q){
            $product = Product::where('product_id', $q->product_id)->first()->product_name;
            return $product;
        })
        ->addColumn('paid_amount', function($q){
            $paidA = Transaction::where('stock_id', $q->stock_id)->first()->tran_paid_amount;
            return $paidA;
        })
        ->addColumn('remaining_amount', function($q){
            $remainA = Transaction::where('stock_id', $q->stock_id)->first()->tran_due_amount;
            return $remainA;
        })
        ->editColumn('supplier_id', function($q){
            $user = User::where('id', $q->supplier_id)->first()->name;
            return $user;
        })
        ->editColumn('created_by', function($q){
            $user = User::where('id', $q->created_by)->first()->name;
            $label = '<span class="badge badge-info">'.$user.'</span>';
            return $label;
        })
        ->addColumn('action', function($q){
            $button = '<button id="'.$q->stock_id.'" class="btn editPurchase btn-xs btn-info"><span class="fas fa-edit"></span></button>
            <button id="'.$q->stock_id.'" class="btn deletePurchase btn-xs btn-danger"><span class="fas fa-trash"></span></button>
            <a href="/invoice/'.$q->stock_id.'" class="btn btn-xs btn-primary"><span class="fas fa-print"></span></a>';
            return $button;
        })
        ->rawColumns(['product_id','paid_amount','remaining_amount','supplier_id','created_by','action'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $stock = Stock::where('stock_id', $request->stock_id)->first();
        
        $products = Product::where('product_id', $stock->product_id)->first();
        $product = ['id' => $products->product_id, 'text' => $products->product_name];

        $users = User::where('id', $stock->supplier_id)->first();
        $user = ['id' => $users->id, 'text' => $users->name];

        $transaction = Transaction::where('stock_id', $request->stock_id)->first();

        $accounts = Account::where('account_id', $transaction->account_id)->first();
        $account = ['id' => $accounts->account_id, 'text' => $accounts->account_type.'('.$accounts->account_name.')'];

        return response()->json([$stock, $product, $user, $transaction, $account]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $stock_id)
    {
        $data = [
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'stock_qty' => $request->stock_qty,
            'stock_unit' => $request->stock_unit,
            'stock_price' => $request->stock_price,
            'created_by' => Auth::id(),
            'stock_description' => $request->stock_description,
        ];
        Stock::where('stock_id', $stock_id)->update($data);

        $data2 = [
            'stock_id' => $stock_id,
            'user_id' => $request->supplier_id,
            'account_id' => $request->account_id, 
            'tran_purchase_price' => $request->stock_price,
            'tran_paid_amount' => $request->tran_paid_amount,
            'tran_due_amount' => $request->tran_due_amount,
            'tran_description' => $request->stock_description,
        ];
        $res = Transaction::where('stock_id',$stock_id)->update($data2);
        if ($res) {
            return response()->json(['msg' => '&nbsp; Purchase Is Updated', 'sts' => 'success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $res = Stock::where('stock_id', $request->stock_id)->delete();
        $res2 = Transaction::where('stock_id', $request->stock_id)->delete();
        if ($res) {
            return response()->json(['msg' => ' &nbsp; Purchase Is Deleted', 'sts' => 'error']);
        }
    }
    
    public function PurcLoadSupplierS2(Request $request)
    {
        $search = $request->searchTerm;
        if (empty($search)) {
            $output1 = DB::table('users')
            ->where('user_status', '1')
            ->where('user_type', '3')
            ->get();
        }else{
            $output1 = DB::select("select * from users where user_status = 1 AND user_type = 3 AND name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->id, 'text' => $value->name];
        }
        return response()->json($output);
    }

    public function PurcLoadProductS2(Request $request)
    {
        $search = $request->searchTerm;
        if (empty($search)) {
            $output1 = DB::table('products')
            ->where('product_status', '1')
            ->where('product_type', 'raw')
            ->get();
        }else{
            $output1 = DB::select("select * from products where product_status = 1 AND product_type = 'raw' AND product_name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->product_id, 'text' => $value->product_name];
        }
        return response()->json($output);
    }

    public function purchaseSupAcc(Request $request)
    {
        $search = $request->searchTerm;
        if (empty($search)) {
            $output1 = DB::table('accounts')
            ->where('account_status', '1')
            ->where('user_id', $request->user_id)
            ->get();
        }else{
            $output1 = DB::select("select * from accounts where account_status = 1 AND user_id = $request->user_id AND account_name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->account_id, 'text' => $value->account_type.'('.$value->account_name.')'];
        }

        return response()->json($output);
    }

    public function purSuppPrevD(Request $request)
    {
        
        $outputs = Transaction::where('user_id', $request->user_id)->get();
        //Transaction::where('user_id', $request->user_id)->latest('tran_id')->first()->tran_due_amount;
        $ttlpurchase = 0;
        $ttlpaid = 0;
        foreach ($outputs as $output) {
            $ttlpurchase += $output->tran_purchase_price;
            $ttlpaid += $output->tran_paid_amount;
        }
        $due = $ttlpurchase - $ttlpaid;
        return response()->json($due);
    }

}
