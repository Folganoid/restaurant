<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderMenu;
use App\User;
use App\Menu;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gate;


class OrderController extends Controller
{
    /**
     * Index
     */
    public function index()
    {

        $id = Auth::id();
        $orders = $this->buildOrder([$id]);


        $groupId = Group::where('user_id', $id)->select('order_id')->get()->toArray();

        $groupTmpArr = [];
        for ( $i = 0 ; $i < count($groupId) ; $i++) {
            $groupTmpArr[] = $groupId[$i]['order_id'];
        }

        $foreignOrders = $this->buildOrder($groupTmpArr, 'orders.id');


        return view('order')->with([
            'orders' => $orders['orders'],
            'groups' => $orders['groups'],
            'foreignOrders' => $foreignOrders['orders'],
            'foreignGroups' => $foreignOrders['groups'],
        ]);

    }

    public function buildOrder(array $id, string $where = 'user_id')
    {

        $orders = Order::whereIn($where, $id)
            ->join('order_menus', 'orders.id', '=', 'order_menus.order_id')
            ->join('menus', 'menus.id', '=', 'order_menus.menu_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select(
                'orders.id',
                'orders.send',
                'orders.user_id',
                'users.login',
                'order_menus.order_id',
                'order_menus.menu_id',
                'menus.name',
                'menus.portion',
                'menus.price',
                'menus.category_id',
                'orders.created_at',
                'orders.updated_at'
            )
            ->orderBy('orders.send')
            ->orderBy('orders.updated_at', 'DESC')
            ->get();

        $ordersArr = [];
        $orderIds = [];

        for ($i = 0 ; $i < count($orders) ; $i++ )
        {
            $ordersArr[$orders[$i]->order_id][] = $orders[$i];
            if (!in_array($orders[$i]->order_id, $orderIds)) $orderIds[] = $orders[$i]->order_id;
        }

        $groupUsers = Group::whereIn('order_id', $orderIds)
            ->join('users', 'users.id', '=', 'groups.user_id')
            ->get();

        $groupsArr = [];

        for ($i = 0 ; $i < count($groupUsers) ; $i++ )
        {
            $groupsArr[$groupUsers[$i]->order_id][] = $groupUsers[$i];
        }

        return ['orders' => $ordersArr, 'groups' => $groupsArr];

    }

    public function orderCreate() {

        $order = new Order;
        $order->user_id = Auth::id();
        $order->send = 0;
        $order->save();

        return response()->json('{"status": "ok"}');
    }

    public function orderDelete($id)
    {

        $order = Order::find($id);

        if (Gate::allows('is-admin') OR $order->user_id == Auth::id()) {
            OrderMenu::where('order_id', $id)->delete();
            Group::where('order_id', $id)->delete();
            Order::destroy($id);
        }

            return response()->json('{"status": "ok"}');

    }
}