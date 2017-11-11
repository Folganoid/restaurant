<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\Order;
use App\OrderMenu;
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

        $menu = Menu::find($id);
        $categories = Category::all()->toArray();

        $catArr = [];
        for($i = 0 ; $i < count($categories) ; $i++) {
            $catArr[$categories[$i]['id']] = $categories[$i]['name'];
        }

        return view('admin_menu_edit')->with(['menu' => $menu, 'cat' => $catArr]);
    }

    /**
     *update menu
     * @param Request $request
     */
    public function menuUpdate(Request $request)
    {

        $request->validate(
            [
                'price' => [
                    'required',
                    'regex:/^[1-9]\d{0,7}(?:\.\d{0,2})?$/'
                ]
            ]
        );

        $menu = Menu::find($request->id);

        $menu->name = $request->name;
        $menu->price = $request->price;
        $menu->portion = $request->portion;
        $menu->category_id = $request->category_id;
        $menu->save();

        return redirect(route('admin.menu'))->with(['status' => 'Menu was update', 'class' => 'success']);
    }


    /**
     * delete menu with depends
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function menuDelete($id) {

        OrderMenu::where('menu_id', $id)->delete();
        Menu::destroy($id);

        return redirect(route('admin.menu'))->with(['status' => 'Menu deleted', 'class' => 'success']);
    }



    /**
     * edit category
     *
     * @param $id
     */
    public function categoryEdit($id) {
        $category = Category::find($id);
        return view('admin_category_edit')->with(['category' => $category]);
    }

    /**
     * category update
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function categoryUpdate(Request $request) {
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->save();

        return redirect(route('admin.menu'))->with(['status' => 'Category was update !', 'class' => 'success']);
    }

    /**
     * category delete
     * @param $id
     */
    public function categoryDelete($id) {

        $menus = Menu::where('category_id', $id)->get();
        $menusArr = [];

        for ($i = 0 ; $i < count($menus) ; $i++) {
            $menusArr[] = $menus[$i]->id;
        }

        OrderMenu::whereIn('menu_id', $menusArr)->delete();
        Menu::where('category_id', $id)->delete();
        Category::destroy($id);

        return redirect(route('admin.menu'))->with(['status' => 'Category deleted !', 'class' => 'success']);
    }

}
