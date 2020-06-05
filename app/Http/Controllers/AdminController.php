<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\User;
use App\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getLogin() {        
        return view('admin.admin_login');
    }

    public function postLogin(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $adminCount = Admin::where(['username' => $data['username'],'password' => md5($data['password']),'status'=>'1'])->count();
            if($adminCount > 0){
            //if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password'], 'admin'=>'1'])) {
                //echo "Success"; die;
                //Session::put('adminSession', $data['email']);
                Session::put('adminSession', $data['username']);
                return \redirect('/admin/dashboard');
            }else{
                //echo "Failed"; die;
                return \redirect('/admin')->with('flash_message_error', 'Invalid Username or Password');
            }
        }
        #return view('admin.admin_login');
    }

    public function getDashboard() {
        #if (Session::has('adminSession')) {
            # code...
        #}else {
            #return \redirect('/admin')->with('flash_message_error', 'Please login to access');
        #}
        return view('admin.dashboard');
    }

    public function getSettings() {
        $adminDetails = Admin::where(['username'=>Session::get('adminSession')])->first();
        //echo "<pre>"; print_r($adminDetails); die;
        return \view('admin.settings')->with(compact('adminDetails'));
    }

    
    public function checkPassword(Request $request){
        $data = $request->all();
        //$current_password = $data['current_pwd'];
        //$check_password = User::where(['admin'=>'1'])->first();
       
        $adminCount = Admin::where(['username' => Session::get('adminSession'),'password' => md5($data['current_pwd'])])->count();
        //if (Hash::check($current_password, $check_password->password)) {
        if($adminCount == 1){
            echo "true"; die;
        }else{
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; \print_r($data); die;
            //$check_password = User::where(['email'=>Auth::user()->email])->first();
            //$current_password = $data['current_pwd'];

            $adminCount = Admin::where(['username' => Session::get('adminSession'),'password' => md5($data['current_pwd'])])->count();

            //if (Hash::check($current_password, $check_password->password)) {
            if($adminCount == 1){
                //$password = \bcrypt($data['new_pwd']);
                $password = md5($data['new_pwd']);
                //User::where('id','1')->update(['password'=>$password]);
                Admin::where('username',Session::get('adminSession'))->update(['password'=>$password]);
                return \redirect('/admin/settings')->with('flash_message_success','Password updated Successfully.');
            }else{
                return \redirect('/admin/settings')->with('flash_message_error','Password update Failed.');
            }
        }
    }

    public function logout() {
        Session::flush();
        return \redirect('/admin')->with('flash_message_success', 'Logged out Successfully');
    }
}
