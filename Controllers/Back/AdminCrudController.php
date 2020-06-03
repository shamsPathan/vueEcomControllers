<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminUser;
use Illuminate\Http\Request;

class AdminCrudController extends Controller
{
    public function addNewUser(Request $request){
        $request->validate([
            'full_name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:admin_users',
            'phone' => 'required',
            'password' => 'required|min:6',
        ]);

        $data['full_name']  = $request->input('full_name');
        $data['username']   = $request->input('username');
        $data['email']      = $request->input('email');
        $data['phone']      = $request->input('phone');
        $data['password']   = bcrypt($request->input('password'));


        AdminUser::create($data);
        return $data;
    }
    public function updateUser(Request $request, $id){

        $data['full_name']  = $request->input('full_name');
        $data['username']   = $request->input('username');
        $data['email']      = $request->input('email');
        $data['phone']      = $request->input('phone');

        $currentId          = AdminUser::where('id', $id)->first();
        $currentPassword    = $currentId->password;
        if($request->input('password') != $currentPassword)
        {
            $data['password'] = bcrypt($request->input('password'));
                
        } else {
            $data['password'] = $currentPassword; 
        }


        AdminUser::findOrFail($id)->update($data);
        return $data;        

    }
    public function searchAdmin(Request $request)
    {
        if ($search = $request->get('q')) {
            $admin = AdminUser::where(function($query) use ($search){
                $query->where('username','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->get()->skip(1);
        }else{
            $admin = AdminUser::get()->skip(1);
        }

        return $admin;
    }
}
