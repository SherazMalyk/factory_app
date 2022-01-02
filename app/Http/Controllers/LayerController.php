<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;


class LayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layer');
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
            'layer_name' => $request->layer_name,
            'layer_status' => $request->layer_status,
        ];
        $layers = Layer::All();
        $count = count($layers);
        if ($count == 0) {
            Layer::create($data);
            return response()->json(['msg' => ' &nbsp A  New Layer Is Added', 'sts' => 'success']);
        }else{
            try {
                if ($request->layer_name = DB::table('layers')->where('layer_name', $request->layer_name)->first()->layer_name) {
                    return response()->json(['msg' => ' &nbsp; Layer Already Exists','sts' => 'error']);
                }
            } catch (\Throwable $th) {

            }
            Layer::create($data);
            return response()->json(['msg' => ' &nbsp A  New Layer Is Added', 'sts' => 'success']);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Layer  $layer
     * @return \Illuminate\Http\Response
     */
    public function show(Layer $layer)
    {
        $query = Layer::All();
        return Datatables::of($query)
        ->addColumn('status', function($query){
            if ($query->layer_status == 1) {
                $label = '<span class="badge badge-success">Active</span>';
                return $label;
            }else{
                $label = '<span class="badge badge-danger">Deactive</span>';
                return $label;
            }
        })
        ->addColumn('action', function($query){
            $button = '<button id="'.$query->layer_id.'" class="btn editLayer btn-xs btn-info"><span class="fas fa-edit"></span></button>
            <button id="'.$query->layer_id.'" class="btn deleteLayer btn-xs btn-danger"><span class="fas fa-trash"></span></button>';
            return $button;
        })
        ->rawColumns(['status','action'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Layer  $layer
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        return Layer::where('layer_id', $request->layer_id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Layer  $layer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $layer_id)
    {
        $data = [
            'layer_name' => $request->layer_name,
            'layer_status' => $request->layer_status,
        ];

        try {
            if($request->layer_name != Layer::where('layer_id', $layer_id)->first()->layer_name){
                try {
                    if($request->layer_name == Layer::where('layer_name', $request->layer_name)->first()->layer_name){
                        return response()->json(['msg' => ' &nbsp; Layer Already Exists','sts' => 'error']);
                    }
                } catch (\Throwable $th) {
                }
            }
        } catch (\Throwable $th) {
        };

        Layer::where('layer_id', $layer_id)->update($data);
        return response()->json(['msg' => ' &nbsp; Layer Has Been Updated','sts' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Layer  $layer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $out = DB::table('layers')->where('layer_id', $request->layer_id)->delete();
        if ($out) {
            return response()->json(['msg' => ' &nbsp; Layer Is Deleted','sts' => 'error']);
        }
    }
}
