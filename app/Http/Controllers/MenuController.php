<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    function index() {

        $categories = Category::all();
        $menus = Menu::all();

        return view('menu')->with(['categories' => $categories, 'menus' => $menus]);

    }
}
