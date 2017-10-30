<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use App\Menu;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    /**
     * Index
     */
    function index() {

        $id = Auth::id();
        $orders = User::find($id)->order; // orders for current user

        $menus = [];        // array curent menus
        $menuDesc = [];     // array of menu description
        $groups = [];

        for($i = 0 ; $i < count($orders); $i++ ) {

            $orderId = $orders[$i]->id;

            $temp_group = Group::where('order_id', $orderId)->get();

            for ($s = 0; $s < count($temp_group) ; $s++) {

                $groups[$orderId][] = User::where('id', $temp_group[$s]->user_id)->first();

            }

            $menus[$orderId] = Order::find($orderId)->orderMenu;

            for( $z = 0 ; $z < count($menus[$orderId]) ; $z++) {

                $menuId = $menus[$orderId][$z]->menu_id;
                $menuDesc[$menuId] = Menu::where('id', $menuId)->first();
            }
        }

       // dd($groups);

        return view('order')->with(['orders' => $orders, 'menus' => $menus, 'menuDesc' => $menuDesc, 'groups' => $groups]);

    }
}
