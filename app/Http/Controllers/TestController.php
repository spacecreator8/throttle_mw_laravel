<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index(){
        if(session()->exists('token')){
            $token = session()->get('token');
            $user = User::where('token', $token)->first();
            $name = $user->name;
            $hello = "Hello $name !";
            return view('main', compact('hello'));
        }
        return view('main');
    }

    public function registration(){
        return view('reg');
    }
    public function storeRegistration(Request $request){
        if(session()->exists('warning')){
            $msg = session()->get('warning');
        }
        $data = $request->all();
        $er = [];
        if(!$data['name']){
            $er[] = 'Укажите имя.';
        }
        if(!$data['email']){
            $er[] = 'Укажите почту.';
        }
        if(!$data['password1'] || !$data['password2']){
            $er[] = 'Вам нужно ввести пароль два раза.';
        }
        if($data['password1'] != $data['password2']){
            $er[] = 'Пароли должны быть одинаковы.';
        }
        if($er){
            if(isset($msg)){
                return view('reg', compact('er', 'msg'));
            }
            return view('reg', compact('er'));
        }else{
            User::create([
                'name'=>$data['name'],
                'email'=>$data['email'],
                'password'=>$data['password1'],
            ]);
            return redirect()->route('login');
        }

    }
    public function login(){
        return view('login');
    }
    public function signin(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        if(Auth::attempt(['email'=>$email, 'password'=>$password])){
            $user = User::where('email', $email)->first();
            $token = Str::random(20);
            $user->token = $token;
            $user->save();
            session()->put('token', $token);
            return redirect()->route('test');
        }
        return redirect()->route('login');
    }
}
