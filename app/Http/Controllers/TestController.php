<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(){
        if(session()->exists('warning')){
            $msg = session()->get('warning');
            return view('main', compact('msg'));
        }
        return view('main');
    }
}
