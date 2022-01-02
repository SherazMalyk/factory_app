<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Layer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('part');
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
            'part_name' => $request->part_name,
            'part_status' => $request->part_status,
            'part_unit' => $request->part_unit,
            'layer_id' => $request->layer_id,
        ];

        $parts = DB::table('parts')->where('layer_id', $request->layer_id)->get();
        foreach ($parts as $part) {
            if ($request->part_name == $part->part_name) {
                return response()->json(['msg' => ' &nbsp; Option Already Exists','sts' => 'error']);
            }
        };

        Part::create($data);
        return response()->json(['msg' => ' &nbsp; A New Option Is Added', 'sts' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function show(Part $part)
    {
        $q = Part::All();
        return Datatables::of($q)
        ->addColumn('model', function($q){
            $model = Layer::where('layer_id', $q->layer_id)->get();
            /* Layer::select('layer_name')->where('layer_id', $q->layer_id)->get(); */
            /* $model = DB::table('layers')->where('layer_id', $q->layer_id)->get(); */
            return $model[0]['layer_name'];
        })
        ->addColumn('status', function($q){
            if ($q->part_status == 1) {
                $label = '<span class="badge badge-success">Active</span>';
                return $label;
            }else{
                $label = '<span class="badge badge-danger">Deactive</span>';
                return $label;
            }
        })
        ->addColumn('action', function($q){
            $button = '<button id="'.$q->part_id.'" class="btn editPart btn-xs btn-info"><span class="fas fa-edit"></span></button>
            <button id="'.$q->part_id.'" class="btn deletePart btn-xs btn-danger"><span class="fas fa-trash"></span></button>';
            return $button;
        })
        ->rawColumns(['model','status','action'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $part = Part::where('part_id', $request->part_id)->first();
        $layer = Layer::where('layer_id', $part->layer_id)->first();
        $output = ['id' => $layer->layer_id, 'text' => $layer->layer_name];
        return response()->json([$part,$output,$request->part_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $part_id)
    {
        $data = [
            'part_name' => $request->part_name,
            'part_status' => $request->part_status,
            'part_unit' => $request->part_unit,
            'layer_id' => $request->layer_id,
        ];
        $res = Part::where('part_id', $part_id)->update($data);
        if ($res) {
            return response()->json(['msg' => ' &nbsp; Option Has Been Upated', 'sts' => 'success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $del = Part::where('part_id', $request->part_id)->delete();
        if ($del) {
            return response()->json(['msg' => ' &nbsp; Option Is Deleted', 'sts' => 'error']);
        }
    }

    public function loadPartS2(Request $request)
    {
        $search = $request->searchTerm;
        if (empty($search)) {
            $output1 = DB::table('layers')->where('layer_status', '1')->get();
        }else{
            $output1 = DB::select("select * from layers where layer_name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->layer_id, 'text' => $value->layer_name];
        }
        return response()->json($output);

        /* if (!isset($_POST['searchTerm'])) {
        $output = get($dbc, "products");
        }
        else{
            $search = $_POST['searchTerm'];   
            $output = mysqli_query($dbc,"select * from products where product_name like '%".$search."%' limit 3");
        }
        $users = userData($dbc, $_SESSION['user_login']) ;
        $branch_id=$users['branch_id'];
        $q=fetchRecord($dbc, "branches", "branch_id", $branch_id ); 

        $output2 = array();
        while ($r = mysqli_fetch_assoc($output)) {
            $output2[] = ['id' => $r['product_id'], 'text' => $r['product_name'],'product_price' => $r['product_price'],'product_desc' => $r['product_desc'],'type' => $q['branch_profit_type'],'amt' => $q['branch_profit_amt']];  
        }
        echo json_encode($output2);
        exit(); */
    }
}
