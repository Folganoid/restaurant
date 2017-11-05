<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\Order;
use App\Group;
use App\OrderMenu;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Gate;

class MenuController extends Controller
{

    protected $groupOrders;
    protected $orders;
    protected $users;


    public function index()
    {
        $categories = Category::all();
        $menus = Menu::all();
        $orders = $this->getOrders();
        $this->users = User::select('id', 'login')->orderBy('login')->get();

        return view('menu')->with([
            'categories' => $categories,
            'menus' => $menus,
            'orders' => $orders['orders'],
            'groupOrders' => $orders['groupOrders'],
            'users' => $this->users,
        ]);
    }

    /**
     * get orders from db
     */
    public function getOrders()
    {
        $groups = Group::where('user_id', Auth::id())->get();

        $tmpGroupArr = [];
        for ($i = 0; $i < count($groups); $i++) {
            $tmpGroupArr[] = $groups[$i]->order_id;
        }

        $this->orders = Order::where('user_id', Auth::id())->where('send', 0)->get();

        $this->groupOrders = Order::whereIn('orders.id', $tmpGroupArr)
            ->where('orders.send', 0)
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->get();

        return ['orders' => $this->orders, 'groupOrders' => $this->groupOrders];
    }

    /**
     * read menu list
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function menuRead($id)
    {

        $users = Group::where('order_id', $id)->join('users', 'users.id', '=', 'groups.user_id')
            ->select('users.id', 'users.login')
            ->get();

        $data = OrderMenu::where('order_id', $id)->join('menus', 'menus.id', '=', 'order_menus.menu_id')->
        select(
            'order_menus.id',
            'order_menus.menu_id',
            'menus.name',
            'order_menus.order_id',
            'menus.price',
            'menus.portion'
        )->get();

        return response()->json(json_encode([$data, $users]));

    }

    /**
     * add menu in order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function menuCreate(Request $request)
    {
        $data = $request->all();

        $menu = new OrderMenu;
        $menu->order_id = $data['order'];
        $menu->menu_id = $data['menu'];
        $menu->save();

        return response()->json('{"status": "ok"}');
    }

    /**
     * delete menu from order
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function menuDelete($id)
    {
        try {
            $orderMenu = OrderMenu::find($id);
        } catch (\Exception $e) {
            return redirect('/menu')->with(['status' => 'Error, item do not exist!', 'class' => 'danger']);
        }

        try {
            $owner = Order::where('user_id', Auth::id())->first();
        } catch (\Exception $e) {
        }

        try {
            $group = Group::where('order_id', $orderMenu->order_id)->where('user_id', Auth::id())->first();
        } catch (\Exception $e) {
        }

        if (!$group AND !$owner) {
            if (Gate::denies('is-admin')) {
                return redirect('/')->with(['status' => 'Access denied!', 'class' => 'danger']);
            }
        }

        OrderMenu::destroy($id);
        return response()->json('{"status": "ok"}');
    }

    /**
     * make orders.send - true
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function menuSend(Request $request)
    {

        $id = $request->orderId;
        $order = Order::find($id);

        if ($order->user_id != Auth::id()) {
            if (Gate::denies('is-admin')) {
                return redirect('/menu')->with(['status' => 'Access denied!', 'class' => 'danger']);
            }
        }

        $order->send = TRUE;
        $order->save();

        return redirect('/order')->with(['status' => 'Order was sending !', 'class' => 'success']);
    }

    /**
     * Add user in group
     * @param Request $request
     */
    public function menuUserAdd(Request $request)
    {
        $data = $request->all();
        $order = Order::find($data['order']);

        if ($order->user_id != Auth::id()) {
            if (Gate::denies('is-admin')) {
                return response()->json('{"status": "false"}');
            }
        }

        $check = Group::where('user_id', $data['user'])->where('order_id', $data['order'])->first();
        if(!$check) {

            $group = new Group;
            $group->user_id = $data['user'];
            $group->order_id = $data['order'];
            $group->save();

            return response()->json('{"status": "ok"}');

        }
    }

    /**
     * remove user from group
     * @param Request $request
     */
    public function menuUserDel(Request $request)
    {
        $data = $request->all();

        $order = Order::find($data['order']);

        if ($order->user_id != Auth::id()) {
            if (Gate::denies('is-admin')) {
                return response()->json('{"status": "false"}');
            }
        }

        Group::where('user_id', $data['user'])->where('order_id', $data['order'])->delete();
        return response()->json('{"status": "ok"}');
    }

}
