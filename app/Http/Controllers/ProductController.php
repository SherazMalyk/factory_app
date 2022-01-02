<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Part;
use App\Models\Layer;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('product');
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
            'product_name' => $request->product_name,
            'product_status' => $request->product_status,
            'product_type' => $request->product_type,
        ];
        $product = Product::create($data);
        $productId = $product->product_id;
        $layerids = $request->layerS2;
        $partids = $request->partS2;
        foreach ($layerids as $index => $value) {
            $data = [
                'product_id' => $productId,
                'part_id' => $partids[$index],
                'layer_id' => $value,
            ];
            ProductDetail::create($data);
            /* DB::table('product_details')->insert($data); */
        }
        return response()->json(['msg' => ' &nbsp; Product Is Added With Details', 'sts' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        /* $layer_ids = Product::find(1)->product_details;
        foreach ($layer_ids as $layer_id) {
            $out = $layer_id->layer_id;
        }
        print_r($out);
        exit(); */
        $q = Product::All();
        return Datatables::of($q)
        ->editColumn('product_status', function($q){
            if ($q->product_status == 1) {
                $label = '<span class="badge badge-success">Active</span>';
                return $label;
            }else{
                $label = '<span class="badge badge-danger">Deactive</span>';
                return $label;
            }
        })
        ->editColumn('product_type', function($q){
            if ($q->product_type == 'raw') {
                $label = '<span class="badge badge-primary">Raw</span>';
                return $label;
            }else{
                $label = '<span class="badge badge-primary">Product</span>';
                return $label;
            }
        })
        ->editColumn('layer_name', function($q){
            $layer_ids = Product::find($q->product_id)->product_details;
            $label= '';
            foreach ($layer_ids as $layer_id) {
                /* $out = $layer_id->layer_id; */
                $out = Layer::where('layer_id', $layer_id->layer_id)->first();
                $label .= ' <span class="badge badge-primary">'.$out->layer_name.'</span> ';
            }
            return $label;
        })
        ->editColumn('part_name', function($q){
            $part_ids = Product::find($q->product_id)->product_details;
            $label= '';
            foreach ($part_ids as $part_id) {
                $out = Part::where('part_id', $part_id->part_id)->first();
                $label .= ' <span class="badge badge-info">'.$out->part_name.'</span> ';
            }
            return $label;
        })
        ->addColumn('action', function($q){
            $button = '<a href="editPDetails/'.$q->product_id.'" class="btn editPDetails btn-xs btn-info"><span class="fas fa-edit"></span></a>
            <button id="deletePDetails" onclick="deletePDetails('.$q->product_id.')" class="btn deletePDetails btn-xs btn-danger"><span class="fas fa-trash"></span></button>';
            return $button;
        })
        ->rawColumns(['product_type','product_status', 'layer_name', 'part_name', 'action'])
        ->make(true); /* /'.$q->product_id.' */
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $product_id)
    {
        // return $product_id;
        return view('product', compact('product_id'));
    }

    public function edit2(Request $request)
    {
        $products = Product::where('product_id', $request->product_id)->first();
        $productsDetails = Product::find($request->product_id)->product_details;
        foreach ($productsDetails as $i) {
            $layer = DB::table('layers')->where('layer_id', $i->layer_id)->first();
            $layers [] = [ 'id' => $layer->layer_id, 'text' => $layer->layer_name];
        }
        foreach ($productsDetails as $i) {
            $part = DB::table('parts')->where('part_id', $i->part_id)->first();
            $parts[] = [ 'id' => $part->part_id, 'text' => $part->part_name];
        }
        return response()->json([$products,$productsDetails,$layers,$parts]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product_id)
    {
        $data = [
            'product_name' => $request->product_name,
            'product_status' => $request->product_status,
            'product_type' => $request->product_type,
        ];
        
        Product::where('product_id', $product_id)->update($data);

        $layer_ids = $request->layerS2;
        $part_ids = $request->partS2;

        $INproduct_detail_ids = $request->product_detail_id;
        $countInIds = count($INproduct_detail_ids);
        $DBproduct_detail_ids = ProductDetail::where('product_id', $product_id)->get();
        $countDbIds = count($DBproduct_detail_ids);
        
        if ($countInIds == $countDbIds) {
            foreach ($INproduct_detail_ids as $key => $product_detail_id) {
                $data2 = [
                    'product_id' => $product_id,
                    'part_id' => $part_ids[$key],
                    'layer_id' => $layer_ids[$key]
                    
                ];
                ProductDetail::where('product_detail_id', $product_detail_id)->update($data2);
            }
            return response()->json(['msg' => ' &nbsp; Product Has Been Upated With Details', 'sts' => 'success']);
        }else{
            ProductDetail::where('product_id', $product_id)->delete();
            foreach ($INproduct_detail_ids as $key => $product_detail_id) {
                $data2 = [
                    'product_id' => $product_id,
                    'part_id' => $part_ids[$key],
                    'layer_id' => $layer_ids[$key]
                    
                ];
                ProductDetail::create($data2);
            }  
            return response()->json(['msg' => ' &nbsp; Product Has Been Upated With Details', 'sts' => 'success']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $delDET = ProductDetail::where('product_id', $request->product_id)->delete();
        $delPRO = Product::where('product_id', $request->product_id)->delete();
        if ($delDET) {
            return response()->json(['msg' => ' &nbsp; Product Is Deleted', 'sts' => 'error']);
        }
    }
    public function loadLayerInS2(Request $request)
    {
        $search = $request->searchTerm;
        if (empty($search)) {
            $output1 = DB::table('layers')->get();
        }else{
            $output1 = DB::select("select * from layers where layer_name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->layer_id, 'text' => $value->layer_name];
        }
        return response()->json($output);

    }

    public function loadPartInS2(Request $request)
    {
        $search = $request->searchTerm;
        $layer_id = $request->layer_id;
        if (empty($search)) {
            $output1 = DB::table('parts')
            ->where('layer_id', $layer_id)
            ->where('part_status', '1')
            ->get();
        }else{
            $output1 = DB::select("select * from parts where part_status = '1' AND layer_id = $layer_id AND part_name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->part_id, 'text' => $value->part_name];
        }
        return response()->json($output);

    }

}
