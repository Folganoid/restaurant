<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\Order;
use Illuminate\Http\Request;
use Gate;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    function menu() {
        if(Gate::denies('is-admin')) {
            return redirect('/')->with(['status' => 'Access denied!', 'class' => 'danger']);
        }

        $menu = Menu::all();
        $category = Category::all();
        return view('admin_menu')->with(['menus' => $menu, 'categories' => $category]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    function order() {
        if(Gate::denies('is-admin')) {
            return redirect('/')->with(['status' => 'Access denied!', 'class' => 'danger']);
        }

        $orders = Order::orderBy('send')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->orderBy('created_at', 'DESC')
            ->select('users.login', 'orders.created_at', 'orders.updated_at', 'orders.id', 'orders.send' )
            ->get();
        return view('admin_order')->with('orders', $orders);
    }

    /**
     * @param $id
     */
    function menuEdit($id) {
        dd($id);
    }

    /**
     * @param $id
     */
    function categoryEdit($id) {
        dd($id);
    }
}
