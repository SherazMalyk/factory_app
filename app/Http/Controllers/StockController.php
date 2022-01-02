<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Layer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('stock');
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
            'layer_id' => $request->layer_id,
            'part_id' => $request->part_id,
            'stock_qty' => $request->stock_qty,
            'stock_status' => $request->stock_status,
            'created_by' => Auth::id(),
            'stock_description' => $request->stock_description,
        ];
        $out = Stock::create($data);
        if ($out) {
            return response()->json(['msg' => ' &nbsp; New Stock Is Added', 'sts' => 'success']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        $q = Stock::where('stock_unit','=',null);
        return Datatables::of($q)
        ->editColumn('product_id', function($q){
            $product_id = Product::where('product_id', $q->product_id)->first()->product_name;
            return $product_id;
        })
        ->editColumn('layer_id', function($q){
            $layer_id = Layer::where('layer_id', $q->layer_id)->first()->layer_name;
            return $layer_id;
        })
        ->editColumn('part_id', function($q){
            $part_id = Part::where('part_id', $q->part_id)->first()->part_name;
            return $part_id;
        })
        ->editColumn('created_by', function($q){
            $user = User::where('id', $q->created_by)->first()->name;
            return $user;
        })
        ->editColumn('stock_status', function($q){
            if ($q->stock_status == 1) {
                $label = '<span class="badge badge-success">In Stock</span>';
                return $label;
            }else{
                $label = '<span class="badge badge-danger">No Stock</span>';
                return $label;
            }
        })
        ->addColumn('action', function($q){
            $button = '<button id="'.$q->stock_id.'" class="btn editStock btn-xs btn-info"><span class="fas fa-edit"></span></button>
            <button id="'.$q->stock_id.'" class="btn deleteStock btn-xs btn-danger"><span class="fas fa-trash"></span></button>';
            return $button;
        })
        ->rawColumns(['product_id','layer_id','part_id','stock_status','action'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $stock = Stock::where('stock_id', $request->stock_id)->first();
        
        $products = Product::where('product_id', $stock->product_id)->first();
        $product = ['id' => $products->product_id, 'text' => $products->product_name];

        $parts = Part::where('part_id', $stock->part_id)->first();
        $part = ['id' => $parts->part_id, 'text' => $parts->part_name];

        $layers = Layer::where('layer_id', $stock->layer_id)->first();
        $layer = ['id' => $layers->layer_id, 'text' => $layers->layer_name];

        return response()->json([$stock, $product, $layer, $part]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $stock_id)
    {
        $data = [
            'product_id' => $request->product_id,
            'layer_id' => $request->layer_id,
            'part_id' => $request->part_id,
            'stock_qty' => $request->stock_qty,
            'stock_status' => $request->stock_status,
            'created_by' => Auth::id(),
            'stock_description' => $request->stock_description,
        ];
        $res = Stock::where('stock_id', $stock_id)->update($data);
        if ($res) {
            return response()->json(['msg' => '&nbsp; Stock Is Updated', 'sts' => 'success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $res = Stock::where('stock_id', $request->stock_id)->delete();
        if ($res) {
            return response()->json(['msg' => ' &nbsp; Stock Is Deleted', 'sts' => 'error']);
        }
    }

    public function stockLoadProductS2(Request $request)
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

    public function stockLoadPartS2(Request $request)
    {
        $search = $request->searchTerm;
        $layer_id = $request->layer_id;
        $product_id = $request->product_id;

        $productDets = ProductDetail::where('product_id', $product_id)
        ->where('layer_id', $layer_id)
        ->get();
        $part_ids = [];
        foreach ($productDets as $productDet) {
            $part_ids[] = $productDet->part_id;
        }

        if (empty($search)) {
            $output1 = DB::table('parts')
            ->where('part_status', '1')
            ->where('layer_id', $layer_id)
            ->whereIn('part_id',$part_ids)
            ->get();
        }else{
            $output1 = DB::select("select * from parts where part_status = 1 AND layer_id = $layer_id AND part_name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->part_id, 'text' => $value->part_name];
        }
        return response()->json($output);
    }

    public function StockloadLayerS2(Request $request)
    {
        $search = $request->searchTerm;
        $output1 = DB::table('product_details')->where('product_id',  $request->product_id)
        ->groupBy('layer_id')
        ->get();
        /* if (empty($search)) {
        }else{
            $output1 = DB::select("select * from product_details where product_id = $request->product_id AND part_name like '%".$search."%' limit 3");
        } */
        $output[]="";
        foreach ($output1 as $key => $value) {
            $layer = DB::table('layers')
            ->where('layer_status', '1')
            ->where('layer_id', $value->layer_id)
            ->first();
            if ($layer != null) {
                $output[] = ['id' => $layer->layer_id, 'text' => $layer->layer_name];
            }
        }
        return response()->json($output);
    }

    public function getWeight(Request $request)
    {
        $output = Part::where('part_id', $request->part_id)->first()->part_unit;
        return response()->json($output);
    }

}
