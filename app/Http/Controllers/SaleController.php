<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Layer;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;



class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sale');
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
        $productDet = ProductDetail::where('product_id', $request->product_id)->get();
        $data3 = [
            'customer_id' => $request->customer_id,
            'sale_price' => $request->sale_price,
            'sale_description' => $request->sale_description,
            'created_by' => Auth::id(),
            'product_id' => $request->product_id,
        ];
        $saleCre = Sale::create($data3);
        $saleId = $saleCre->id;
        $response = [];
        foreach ($productDet as $x => $product) {
            $stocks = Stock::where('part_id', $product->part_id)->orderBy('stock_qty', 'asc')->get();
            $totalStock = 0;
            $stock_id = [];
            $check_ttl = 0;
            foreach ($stocks as $x => $stock) {
                if ($check_ttl <= $request->stock_qty) {
                    $totalStock += $stock->stock_qty;
                    $stock_id[$x] = $stock->stock_id;
                    $check_ttl += $stock->stock_qty;
                }
            }
            $response[] = [$totalStock , $stock_id, $product->product_id];
        }

        // return $response;
        foreach ($response as $x => $stock) {
            $ttlQty = $request->stock_qty;
            $qty = 0;
            foreach ($stock[1] as $x => $stockTbl) {
                $stocks = Stock::where('stock_id', $stockTbl)->first();
                if ($stocks->stock_qty <= $ttlQty) {
                    $abc3 = $stocks->stock_qty;
                    $ttlQty -= $stocks->stock_qty;
                }else{
                    $abc3 = $ttlQty;
                }
                // $qty += $abc3;
                $data = [
                    'sale_qty' => $abc3,
                    'stock_id' => $stockTbl,
                    'layer_id' => $stocks->layer_id,
                    'part_id' => $stocks->part_id,
                    'sale_id' => $saleId,
                ];
                DB::table('sale_details')->insert($data);
            }
        }
        
        return response()->json(['msg' => ' &nbsp; Product Is Sold', 'sts' => 'success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $readytoSell = [];
        $query = Product::where('product_type', 'product')->get();
        return Datatables::of($query)
        ->addColumn('layers', function($query) {
            $productDet = ProductDetail::where('product_id', $query->product_id)->get();
            $li = '';
            foreach ($productDet as $key => $value) {
                $part = Part::where('part_id',$value->part_id)->first();
                $stocks = Stock::where('part_id', $value->part_id)
                ->where('product_id', $value->product_id)
                ->get();
                $ttlStock= 0;
                foreach ($stocks as $stock) {
                    $ttlStock += $stock->stock_qty;
                }

                $sales = Sale::where('product_id', $query->product_id)->first();
                if ($sales->part_id) {
                    $saleDets = Sale::find('part_id', $sales->part_id)->get();
                    foreach ($saleDets as $sale) {
                        $ttlStock -= $sale->sale_qty;
                    }
                }
                /* $sales = Sale::where('product_id', $query->product_id)
                ->where('part_id', $value->part_id)
                ->get();
                foreach ($sales as $sale) {
                    $ttlStock -= $sale->sale_qty;
                } */

                $li .= '<div class="badge badge-dark">'.$part->part_name.' ('.$ttlStock.')'.'</div><br>';
            }
            return $li;
        })
        ->addColumn('r2s', function($query)  use($readytoSell) {
            $productDet = ProductDetail::where('product_id', $query->product_id)->get();
            $li = '';
            foreach ($productDet as $key => $value) {
                $part = Part::where('part_id',$value->part_id)->first();
                $stocks = Stock::where('part_id', $value->part_id)
                ->where('product_id', $value->product_id)
                ->get();
                $ttlStock= 0;
                foreach ($stocks as $stock) {
                    $ttlStock += $stock->stock_qty;
                }
                $sales = Sale::where('product_id', $query->product_id)->first();
                if ($sales->part_id) {
                    $saleDets = Sale::find('part_id', $sales->part_id)->get();
                    foreach ($saleDets as $sale) {
                        $ttlStock -= $sale->sale_qty;
                    }
                }
                /* $sales = Sale::where('product_id', $query->product_id)
                ->where('part_id', $value->part_id)
                ->get();
                foreach ($sales as $sale) {
                    $ttlStock -= $sale->sale_qty;
                } */

                $readytoSell[] = $ttlStock;
                $li .= '<li>'.$part->part_name.'('.$ttlStock.')'.'</li>';
            }

            $max = max($readytoSell);
            $min = min($readytoSell);
            $max_idx = array_search($max,$readytoSell);
            $min_idx = array_search($min,$readytoSell);
            $res = 0;
            if($max_idx < $min_idx){
                $res = '<div class="badge badge-success">'.$min.'</div>';
            }else{
                $res = '<div class="badge badge-success">'.$min.'</div>';
            }

            return $res;
        })
        ->rawColumns(['layers', 'r2s'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function SaleLoadProductS2(Request $request)
    {
        $search = $request->searchTerm;
        if (empty($search)) {
            $output1 = DB::table('products')
            ->where('product_status', '1')
            ->where('product_type', 'product')
            ->get();
        }else{
            $output1 = DB::select("select * from products where product_status = 1 AND product_type = 'product' AND product_name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->product_id, 'text' => $value->product_name];
        }
        return response()->json($output);
    }

    public function SaleLoadCustomerS2(Request $request)
    {
        $search = $request->searchTerm;
        if (empty($search)) {
            $output1 = DB::table('users')
            ->where('user_status', '1')
            ->where('user_type', '5')
            ->get();
        }else{
            $output1 = DB::select("select * from users where user_status = 1 AND user_type = 5 AND name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->id, 'text' => $value->name];
        } 
        return response()->json($output);
    }

    public function getR2Sproducts(Request $request)
    {
        $productDet = ProductDetail::where('product_id', $request->product_id)->get();
            $li = '';
            $readytoSell = [];  
            foreach ($productDet as $key => $value) {
                $part = Part::where('part_id',$value->part_id)->first();
                $stocks = Stock::where('part_id', $value->part_id)
                ->where('product_id', $value->product_id)
                ->where('stock_status', '1')
                ->get();
                $ttlStock= 0;
                foreach ($stocks as $stock) {
                    $ttlStock += $stock->stock_qty;
                }

                $sales = Sale::where('product_id', $request->product_id)->first();

                    $saleDets = SaleDetail::where('part_id', $sales->part_id)->get();
                    foreach ($saleDets as $sale) {
                        $ttlStock -= $sale->sale_qty;
                    }



                $readytoSell[] = $ttlStock;
                $li .= '<li>'.$part->part_name.'('.$ttlStock.')'.'</li>';
            }

            $max = max($readytoSell);
            $min = min($readytoSell);
            $max_idx = array_search($max,$readytoSell);
            $min_idx = array_search($min,$readytoSell);
            $res = 0;
            if($max_idx < $min_idx){
                $res = $min;
            }else{
                $res = $min;
            }
        return response()->json($res);

        /* $productDet = ProductDetail::where('product_id', $request->product_id)->get();
            $li = '';
            $readytoSell = [];  
            foreach ($productDet as $key => $value) {
                $part = Part::where('part_id',$value->part_id)->first();
                $stocks = Stock::where('part_id', $value->part_id)
                ->where('product_id', $value->product_id)
                ->where('stock_status', '1')
                ->get();
                $ttlStock= 0;
                foreach ($stocks as $stock) {
                    $ttlStock += $stock->stock_qty;
                }
                $readytoSell[] = $ttlStock;
                $li .= '<li>'.$part->part_name.'('.$ttlStock.')'.'</li>';
            }

            $max = max($readytoSell);
            $min = min($readytoSell);
            $max_idx = array_search($max,$readytoSell);
            $min_idx = array_search($min,$readytoSell);
            $res = 0;
            if($max_idx < $min_idx){
                $res = $min;
            }else{
                $res = $min;
            }
        return response()->json($res); */
    }

    public function saleCustPrevD(Request $request)
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

    public function saleCustomerAcc(Request $request)
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

}
