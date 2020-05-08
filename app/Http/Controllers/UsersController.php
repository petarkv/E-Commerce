<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class UsersController extends Controller
{
    /*public function userLoginRegister(){
        return \view('users.login_register');
    }*/

    public function getLoginRegister(){
        return \view('users.login_register');
    }

    public function postUserLogin(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                return \redirect('/');
            }else{
                return \redirect()->back()->with('flash_message_error','Invalid Email or Password');
            }
        }
    }

    public function postUserRegister(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();            
            //echo "<pre>"; print_r($data); die;
            //Check if User already exists
            $usersCount = User::where('username',$data['username'])->count();
            if ($usersCount>0) {
                return \redirect()->back()->with('flash_message_error','Username already exists!');
            }else{
            $usersCount = User::where('email',$data['email'])->count();
            if ($usersCount>0) {
                return \redirect()->back()->with('flash_message_error','Email already exists!');
            }
            
            }
                $user = new User;
                $user->name = $data['name'];
                $user->surname = $data['surname'];          
                $user->username = $data['username'];
                $user->email = $data['email'];
                $user->password = \bcrypt($data['password']);
                $user->save();
                if (Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])) {
                    return \redirect('/');
                }
        }
    }

    public function checkEmail(Request $request){
        //Check if User already exists  
        $data = $request->all();    
        $usersCount = User::where('email',$data['email'])->count();
        if ($usersCount>0) {
            echo "false";
        }else{
            echo "true"; die;
        }        
    }
    
    public function checkUsername(Request $request){
        //Check if User already exists  
        $data = $request->all();    
        $usersCount = User::where('username',$data['username'])->count();
        if ($usersCount>0) {
            echo "false";
        }else{
            echo "true"; die;
        }        
    } 

    public function logout(){
        Auth::logout();
        return \redirect('/');
    }

}
