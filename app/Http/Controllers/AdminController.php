<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    function menu() {
        if(Gate::denies('is-admin')) {
            return redirect('/')->with(['status' => 'Access denied!', 'class' => 'danger']);
        }
        return view('admin_menu');
    }

    function category() {
        if(Gate::denies('is-admin')) {
            return redirect('/')->with(['status' => 'Access denied!', 'class' => 'danger']);
        }
        return view('admin_category');
    }

    function order() {
        if(Gate::denies('is-admin')) {
            return redirect('/')->with(['status' => 'Access denied!', 'class' => 'danger']);
        }
        return view('admin_order');
    }
}
