<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('product_detail');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductDetail  $productDetail
     * @return \Illuminate\Http\Response
     */
    public function show(ProductDetail $productDetail)
    {
        /* $part = Part::where('layer_id', $q[0]['layer_id'])->get();
        print_r($part[0]['part_name']);
        exit(); */

        /* $q = Product::All();
        return Datatables::of($q)
        ->addColumn('product_status', function($q){
            if ($q->product_status == 1) {
                $label = '<span class="badge badge-success">Active</span>';
                return $label;
            }else{
                $label = '<span class="badge badge-danger">Deactive</span>';
                return $label;
            }
        })
        ->addColumn('layer_name', function($q){
            $layer_id = ProductDetail::where('product_id', $q->product_id)->get();
            foreach ($layer_id as $key => $value) {
                $label = '<span class="badge badge-danger">'.$value->layer_id.'</span>';
            }
            return $label;
        })
        ->rawColumns(['product_status', 'layer_name'])
        ->make(true); */

        /* $q = ProductDetail::All();
        return Datatables::of($q)
        ->editColumn('layer_id', function($q){
            $layer = Layer::where('layer_id', $q->layer_id)->first();
            return $layer->layer_name;
        })
        ->addColumn('part_id', function($q){
            $part = Part::where('layer_id', $q->layer_id)->get();
            $label= "";
            foreach ($part as $key => $value) {
                $label = '<span class="badge badge-danger">'.$value->part_name.'</span>';
            }
            return $label;
        })
        ->addColumn('action', function($q){
            $button = '<button id="'.$q['product_detail_id'].'" class="btn editPart btn-xs btn-info"><span class="fas fa-edit"></span></button>
            <button id="'.$q['product_detail_id'].'" class="btn deletePart btn-xs btn-danger"><span class="fas fa-trash"></span></button>';
            return $button;
        })
        ->rawColumns(['layer_id','part_id','action'])
        ->make(true); */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductDetail  $productDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductDetail $productDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductDetail  $productDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductDetail $productDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductDetail  $productDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductDetail $productDetail)
    {
        //
    }
}
