<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user');
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

        try {
            if ($request->email = User::where('email', $request->email)->first()->email) {
                return response()->json(['msg' => ' &nbsp; Email Already Exists','sts' => 'error']);
            }
        } catch (\Throwable $th) {
        }
        
        if ($request->file('user_pic') != null) {
            $image = $request->file('user_pic');
            $imageName = uniqid().'.'.$image->extension();
            $path = $request->file('user_pic')->storeAs('public/user/', $imageName);
            $user_pic = 'storage/user/'.$imageName;
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_phone' => $request->user_phone,
                'user_status' => $request->user_status,
                'user_type' => $request->user_type,
                'user_address' => $request->user_address,
                'user_pic' => $user_pic
            ];  
            $out = User::Create($data);
            if ($out) {
                return response()->json(['msg' => ' &nbsp; New User Is Added With Image','sts' => 'success']);
            }
        }else{
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_phone' => $request->user_phone,
                'user_status' => $request->user_status,
                'user_type' => $request->user_type,
                'user_address' => $request->user_address,
                'user_pic' => 'storage/user/user_default.png'
            ];  
            $out = User::Create($data);
            if ($out) {
                return response()->json(['msg' => ' &nbsp; New User Is Added Without Image','sts' => 'success']);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $q = User::All();
        return Datatables::of($q)
        ->editColumn('user_pic', function($q){
            $img = '<img src="'.asset($q->user_pic).'" height="60" class="rounded-circle" width="60" alt="No Pic Found">';
            return $img;
        })
        ->editColumn('user_type', function($q){
            if ($q->user_type == 1) {
                $label = '<span class="badge badge-primary">Admin</span>';
                return $label;
            }elseif ($q->user_type == 2) {
                $label = '<span class="badge badge-secondary">Cashier</span>';
                return $label;
            }elseif ($q->user_type == 3) {
                $label = '<span class="badge badge-secondary">Supplier</span>';
                return $label;
            }else {
                $label = '<span class="badge badge-secondary">Employee</span>';
                return $label;
            }
        })
        ->editColumn('user_status', function($q){
            if ($q->user_status == 1) {
                $label = '<span class="badge badge-success">Active</span>';
                return $label;
            }else{
                $label = '<span class="badge badge-danger">Deactive</span>';
                return $label;
            }
        })
        ->addColumn('action', function($q){
            $button = '<button id="'.$q->id.'" class="btn editUser btn-xs btn-info"><span class="fas fa-edit"></span></button>
            <button id="'.$q->id.'" class="btn deleteUser btn-xs btn-danger"><span class="fas fa-trash"></span></button>';
            return $button;
        })
        ->rawColumns(['user_pic','user_type','user_status','action'])
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
        return User::where('id', $request->id)->first();
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
        try {
            if($request->email != User::where('id', $id)->first()->email){
                try {
                    if ($request->email = User::where('email', $request->email)->first()->email) {
                        return response()->json(['msg' => ' &nbsp; Email Already Exists','sts' => 'error']);
                    }
                } catch (\Throwable $th) {
                }
            }
        } catch (\Throwable $th) {
        };

        if ($request->file('user_pic') != null) {
            $image = $request->file('user_pic');
            $imageName = uniqid().'.'.$image->extension();
            $path = $request->file('user_pic')->storeAs('public/user/', $imageName);
            $user_pic = 'storage/user/'.$imageName;
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'user_phone' => $request->user_phone,
                'user_status' => $request->user_status,
                'user_type' => $request->user_type,
                'user_address' => $request->user_address,
                'user_pic' => $user_pic
            ];  
            $out = User::where('id', $id)->Update($data);
            if ($out) {
                return response()->json(['msg' => ' &nbsp; User Is Updated','sts' => 'success']);
            }
        }else{
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'user_phone' => $request->user_phone,
                'user_status' => $request->user_status,
                'user_type' => $request->user_type,
                'user_address' => $request->user_address,
                'user_pic' => 'storage/user/user_default.png'
            ];  
            $out = User::where('id', $id)->Update($data);
            if ($out) {
                return response()->json(['msg' => ' &nbsp; User Is Updated','sts' => 'success']);
            }
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
        $res = User::where('id', $request->id)->delete();
        if ($res) {
            return response()->json(['msg' => ' &nbsp; User Is Deleted', 'sts' => 'error']);
        }
    }
}
