<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;



class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account');
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

        if ($request->account_type == 'bank') {
            $data = [
                'user_id' => $request->user_id,
                'account_status' => $request->account_status,
                'account_type' => $request->account_type,
                'account_name' => $request->account_name,
                'account_no' => $request->account_no,
                'created_by' => Auth::id(),
            ];            
        }else{
            $data = [
                'user_id' => $request->user_id,
                'account_status' => $request->account_status,
                'account_type' => $request->account_type,
                'created_by' => Auth::id(),
            ];
        }

        $res = Account::create($data);
        if ($res) {
            return response()->json(['msg' => ' &nbsp; Account Detail Is Added', 'sts' => 'success']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        $q = Account::All();
        return Datatables::of($q)
        ->editColumn('user_id', function($q){
            $user = User::where('id', $q->user_id)->first()->name;
            return $user;
        })
        ->editColumn('account_status', function($q){
            if ($q->account_status == 1) {
                $label = '<span class="badge badge-success">Active</span>';
                return $label;
            }else{
                $label = '<span class="badge badge-danger">Deactive</span>';
                return $label;
            }
        })
        ->editColumn('created_by', function($q){
            $user = User::where('id', $q->created_by)->first()->name;
            return $user;
        })
        ->addColumn('action', function($q){
            $button = '<button id="'.$q->account_id.'" class="btn editAccount btn-xs btn-info"><span class="fas fa-edit"></span></button>
            <button id="'.$q->account_id.'" class="btn deleteAccount btn-xs btn-danger"><span class="fas fa-trash"></span></button>';
            return $button;
        })
        ->rawColumns(['account_status','action'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $account = Account::where('account_id', $request->account_id)->first();
        
        $users = User::where('id', $account->user_id)->first();
        $user = ['id' => $users->id, 'text' => $users->name];

        return response()->json([$account, $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $account_id)
    {
        if ($request->account_type == 'bank') {
            $data = [
                'user_id' => $request->user_id,
                'account_status' => $request->account_status,
                'account_type' => $request->account_type,
                'account_name' => $request->account_name,
                'account_no' => $request->account_no,
                'created_by' => Auth::id(),
            ];            
        }else{
            $data = [
                'user_id' => $request->user_id,
                'account_status' => $request->account_status,
                'account_type' => $request->account_type,
                'account_name' => NULL,
                'account_no' => NULL,
                'created_by' => Auth::id(),
            ];
        }
        $res = Account::where('account_id', $account_id)->update($data);
        if ($res) {
            return response()->json(['msg' => ' &nbsp; Account Detail is Updated','sts' => 'success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $res = Account::where('account_id', $request->account_id)->delete();
        if ($res) {
            return response()->json(['msg' => ' &nbsp; Account Detail Is Deleted', 'sts' => 'error']);
        }
    }

    public function AccLoadUserS2(Request $request)
    {
        $search = $request->searchTerm;
        if (empty($search)) {
            $output1 = DB::table('users')
            ->where('user_status', '1')
            ->get();
        }else{
            $output1 = DB::select("select * from users where user_status = 1 AND name like '%".$search."%' limit 3");
        }
        $output[]="";
        foreach ($output1 as $key => $value) {
            $output[] = ['id' => $value->id, 'text' => $value->name];
        }
        return response()->json($output);
    }

}
