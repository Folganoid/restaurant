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
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{

    protected $groupOrders;
    protected $orders;
    protected $users;


    public function index()
    {
        $categories = Category::all();
        $menus = Menu::all();
        $this->users = User::select('id', 'login')->orderBy('login')->get();

        return view('menu')->with([
            'categories' => $categories,
            'menus' => $menus,
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

        $this->orders = Order::where('user_id', Auth::id())->where('send', 0)->
            select('send', 'created_at', 'id', 'user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->get();

        $this->groupOrders = Order::whereIn('orders.id', $tmpGroupArr)
            ->where('orders.send', 0)
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select('users.id', 'users.login', 'orders.send', 'orders.created_at', 'orders.id', 'orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->get();

        return response()->json(json_encode([$this->orders, $this->groupOrders]));
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

        $owner = Order::where('user_id', Auth::id())->where('id', $data['order'])->first();
        $group = Group::where('order_id', $data['order'])->where('user_id', Auth::id())->first();

        if ($group OR $owner OR Gate::allows('is-admin')) {

            $menu = new OrderMenu;
            $menu->order_id = $data['order'];
            $menu->menu_id = $data['menu'];
            $menu->save();

            return response()->json('{"status": "ok"}');
        }
        else {
            return response()->json('{"status": "false"}');
        }
    }

    /**
     * delete menu from order
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function menuDelete($id)
    {
            $orderMenu = OrderMenu::find($id);
            $owner = Order::where('user_id', Auth::id())->where('id', $orderMenu->order_id)->first();
            $group = Group::where('order_id', $orderMenu->order_id)->where('user_id', Auth::id())->first();

        if ($group OR $owner OR Gate::allows('is-admin')) {
            OrderMenu::destroy($id);
            return response()->json('{"status": "ok"}');
        }
        else {
            return response()->json('{"status": "false"}');
        }
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

        $order = Order::where('orders.id', $id)
            ->join('order_menus', 'orders.id', '=', 'order_menus.order_id')
            ->join('menus', 'menus.id', '=', 'order_menus.menu_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select(
                'users.id',
                'users.login',
                'menus.name',
                'menus.price',
                'orders.created_at'
            )
            ->get();

        $sum = 0;
        $menus = '';
        for( $i = 0 ; $i < count($order) ; $i++) {
            $sum += $order[$i]->price;
            $menus .= $order[$i]->name . ' | ';
        }

        if (isset($order[0])) {
           $str = $order[0]->created_at . ', by ' . $order[0]->login . ' --- ' . $menus . ' - ' . $sum;
           $content = Storage::get('log.txt');
           Storage::put('log.txt', $content .= $str . "\n");
        }

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

    public function menuUserDelSelf($id) {

        Group::where('user_id', Auth::id())->where('order_id', $id)->delete();
        return response()->json('{"status": "ok"}');

    }

}
