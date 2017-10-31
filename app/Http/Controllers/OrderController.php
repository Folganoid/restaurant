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
    function index()
    {

        $id = Auth::id();
        $orders = User::find($id)->order; // orders for current user



        $ordersArr = $this->buildOrders($id, $orders);

        $group = Group::where('user_id', $id)->get();
        $groupList = [];


        for( $i = 0 ; $i < count($group) ; $i++) {
            $groupList[] = $group[$i]->order_id;
        }

        $groupOrders = Order::find($groupList);
        $groupOwners = [];

        for( $i = 0 ; $i < count($groupOrders) ; $i++) {
            $groupOwners[$groupOrders[$i]->user_id] = User::where('id', $groupOrders[$i]->user_id)->first();
        }

        $groupArr = $this->buildOrders($id, $groupOrders);


        return view('order')->with([
            'orders' => $orders,
            'menus' => $ordersArr['menus'],
            'menuDesc' => $ordersArr['menuDesc'],
            'groups' => $ordersArr['groups'],
            'groupOrders' => $groupOrders,
            'groupMenus' => $groupArr['menus'],
            'groupMenuDesc' => $groupArr['menuDesc'],
            'groupGroups' => $groupArr['groups'],
            'groupOwners' => $groupOwners,
        ]);

    }

    /**
     * Build order list
     *
     * @param $id
     * @param $orders
     * @return array
     */
    public function buildOrders($id, $orders): array
    {

        $menus = [];        // array current menus
        $menuDesc = [];     // array menu description
        $groups = [];       // array group users with

        for ($i = 0; $i < count($orders); $i++) {

            $orderId = $orders[$i]->id;
            $temp_group = Group::where('order_id', $orderId)->get();

            for ($s = 0; $s < count($temp_group); $s++) {
                $groups[$orderId][] = User::where('id', $temp_group[$s]->user_id)->first();
            }

            $menus[$orderId] = Order::find($orderId)->orderMenu;

            for ($z = 0; $z < count($menus[$orderId]); $z++) {
                $menuId = $menus[$orderId][$z]->menu_id;
                $menuDesc[$menuId] = Menu::where('id', $menuId)->first();
            }
        }

 //dd($groups);


    return ['menus' => $menus, 'menuDesc' => $menuDesc, 'groups' => $groups];

    }
}
