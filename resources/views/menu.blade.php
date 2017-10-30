@extends('layouts.main')

@section('content')
    <h1>Menu</h1>
    <br>
    @if(!$categories->isEmpty())
        @foreach($categories as $category)
            <br>
            <h3>{{ $category->name }}</h3>
            <br>
            @foreach($menus as $menu)
                @if ($category->id == $menu->category_id)
                    <dd>{{ $menu->name . ' --- ' . $menu->price }} <button class="order_menu" value="{{ $menu->id }}">Добавить в заказ</button></dd>
                @endif
            @endforeach
        @endforeach
    @endif
@endsection