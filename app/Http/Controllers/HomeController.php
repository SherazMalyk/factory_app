<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Layer;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $product_ids = Product::All();

        $rawProIds = Product::where('product_type', 'raw')
        ->where('product_status', '1')
        ->get();
        $totalPrice = 0;
        foreach ($rawProIds as $key => $value) {
            $stock_ids = DB::table('stocks')->where('product_id', $value->product_id)->get();
            foreach ($stock_ids as $stock_id) {
                $totalPrice += $stock_id->stock_price;
            }
        }

        $ProIds = Product::where('product_type', 'product')
        ->where('product_status', '1')
        ->get();
        $totalQty = 0;

        foreach ($ProIds as $key => $value) {

            //$proArr=[];
            /* $greater = 0;
            $productDets = ProductDetail::where('product_id', $value->product_id)->get();
            foreach ($productDets as $productDet) {
                $stock_idsForCPs = DB::table('stocks')->where('product_id', $value->product_id)
                ->where('stock_status', '1')
                ->where('part_id', $productDet->part_id)
                ->get();
                $ttlpartQty = 0;
                foreach ($stock_idsForCPs as $stock_idsForCP) {
                    $ttlpartQty += $stock_idsForCP->stock_qty;
                }
                if ($greater == 0) {
                    $greater = $ttlpartQty;
                }
                if ($greater < $ttlpartQty ) {
                    $greater = $ttlpartQty; */
                    //$lowerPart = Part::where('part_id', $productDet->part_id)
                    //->where('part_status', '1')
                    //->where('layer_id', $productDet->layer_id)
                    //->first();
                    //$greater =$lowerPart->part_name."".$lowerPart->layer_id; 
                //}
                /*
                $proArr[] = [$partName,$ttlpartQty]; */
            //}//productDet

            /* foreach ($proArr as $key => $value) {
                $greater = $value->$ttlpartQty;
                for ($i=0; $i < count($proArr[$key]); $i++) { 
                    if ($greater < $proArr[$key]->$value->$ttlpartQty) {
                        $greater = $proArr[$key]->$value->$ttlpartQty;
                    }
                }
            } */

            $stock_ids = DB::table('stocks')->where('product_id', $value->product_id)
            ->where('stock_status', '1')
            ->get();
            foreach ($stock_ids as $stock_id) {
                $totalQty += $stock_id->stock_qty;
            }
        }

        $totalProducts = sizeof($product_ids);
        $users = User::where('user_status', '1')->get();
        $totalUser = count($users);

        
        
        return view('home', compact(['totalProducts','totalPrice','totalQty','totalUser']));
    }

    public function loadlayersDet()
    {
        $parts = Part::where('part_status', '1')->get();
        foreach ($parts as $part) {
            $layer = Layer::where('layer_id', $part->layer_id)->first();
            $stocks = Stock::where('layer_id', $layer->layer_id)
            ->where('stock_status', '1')
            ->where('part_id', $part->part_id)
            ->get();
            $qty = 0;
            foreach ($stocks as $stock) {
                $qty += $stock->stock_qty;
            }
            if ($qty > 0) {
                $response[] = '<div class="col-md-2 col-sm-6 col-12">
                        <div class="info-box bg-info">
                          <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">'. $part->part_name.' {'.$layer->layer_name .'}</span>
                            <span class="info-box-number">'.$qty.'</span>
                            <span class="progress-description">
                            </span>
                          </div>
                        </div>
                      </div>';
            }
        }
        return response()->json($response);
    }

    public function invoiceData(Request $request)
    {
        $stock = Stock::where('stock_id', $request->stock_id)->first();

        $accounts = Transaction::where('stock_id', $stock->stock_id)->first()->account_id;
        $account = Account::where('account_id', $accounts)->first();

        $user = User::where('id', $stock->created_by)->first();        
        $createdBy = '<strong class="text-uppercase"> &emsp; '.$user->name.'</strong><br>
        <strong>Phone: </strong> '.$user->phone.'<br>
        <strong>Email: </strong> '.$user->email.'<br>
        <strong>Address: </strong> '.$user->user_address.'<br>';

        $user = User::where('id', $stock->supplier_id)->first();
        $supplier = '<strong class="text-uppercase"> &emsp; '.$user->name.'</strong><br>
        <strong>Phone: </strong> '.$user->phone.'<br>
        <strong>Email: </strong> '.$user->email.'<br>
        <strong>Address: </strong> '.$user->user_address.'<br>';


        return response()->json([$createdBy,$supplier,$account]);
    }

    public function newTable()
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

}
