<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    function index() {


        $categoryList = Category::all();
        $menuList = Menu::all();

        dd($menuList);

        return view('menu');

    }
}
