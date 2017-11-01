@extends('layouts.main')

@section('content')

    <script src="{{ asset('js/menu.js') }}"></script>
    <div class="row">
        <div class="col">
            <h1>Menu</h1>
            <br>
            @if(!$categories->isEmpty())
                @foreach($categories as $category)
                    <br>
                    <h3>{{ $category->name }}</h3>
                    <br>
                    @foreach($menus as $menu)
                        @if ($category->id == $menu->category_id)
                            <dd>{{ $menu->name . ' --- ' . $menu->price }}
                                <button class="order_menu" value="{{ $menu->id }}">Добавить в заказ</button>
                            </dd>
                        @endif
                    @endforeach
                @endforeach
            @endif
            <br>
        </div>

        <div class="col">
            <h2>Active orders</h2>
            <br>
            <select id="changeOrder">
                @for( $i = 0 ; $i < count($orders) ; $i++)
                    <option class="changeMenuItem" value="{{ $orders[$i]->id }}">{{ $orders[$i]->created_at }}</option>
                @endfor
                @for( $i = 0 ; $i < count($groupOrders) ; $i++)
                    <option class="changeMenuItem" value="{{ $groupOrders[$i]->id }}">{{ $groupOrders[$i]->created_at }}; owner - {{ $groupOrders[$i]->user_id }}</option>
                @endfor
            </select>
            <br>
            <div>
                <ul id="curMenu"></ul>
            </div>

        </div>
    </div>

@endsection