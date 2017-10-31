<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\Order;
use App\Group;
use App\OrderMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    protected $groupOrders;
    protected $orders;


    public function index() {

        $categories = Category::all();
        $menus = Menu::all();
        $orders = $this->getOrders();

        return view('menu')->with([
            'categories' => $categories,
            'menus' => $menus,
            'orders' => $orders['orders'],
            'groupOrders' => $orders['groupOrders']
        ]);
    }

    /**
     * get orders from db
     */
    public function getOrders() {
        $groups = Group::where('user_id', Auth::id())->get();

        $tmpGroupArr = [];
        for ($i = 0 ; $i < count($groups) ; $i++ ) {
            $tmpGroupArr[] = $groups[$i]->order_id;
        }

        $this->orders = Order::where('user_id' , Auth::id())->where('send', 0)->get();
        $this->groupOrders = Order::whereIn('id', $tmpGroupArr)->where('send', 0)->get();

        return ['orders' => $this->orders, 'groupOrders' => $this->groupOrders];
    }

    /**
     * api
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function menuApi(Request $request, $id) {

        $data = OrderMenu::where('order_id', $id)->join('menus', 'menus.id', '=', 'order_menus.menu_id')->get();
        return response()->json(json_encode($data->toArray()));

    }
}
