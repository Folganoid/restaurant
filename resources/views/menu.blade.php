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
            <div class="activeOrders" style="display: none">
                <select id="changeOrder"></select>

                <b class="adduser"> Add User</b>
                <select id="users" class="adduser">
                    @for( $i = 0 ; $i < count($users) ; $i++)
                        @if(Auth::id() != $users[$i]->id)
                        <option
                                class="chooseuser"
                                value="{{ $users[$i]->id }}">{{ $users[$i]->login }}
                        </option>
                        @endif
                    @endfor
                </select>

                <p>With: <span id="orderGroup"></span></p>
                <div>
                    <ul id="curMenu"></ul>
                </div>

                <br>
                {!! Form::open(['route' => 'send', 'method' => 'post']) !!}
                <input id="menuFormId" name="orderId" type="hidden" value=""/>
                <button class="menuFormSubmit" style="display: none;" type="submit"> Send the order</button>
                {!! Form::close() !!}
                <button class="delorder" type="button" style="display: none;">Cancel order</button>
                <button class="exitgroup" type="button">Exit from group order</button>


                <br>

            </div>
            <br>
            <button class="neworder" type="button">Create new order</button>
        </div>
    </div>

@endsection