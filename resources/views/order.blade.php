@extends('layouts.main')

@section('content')
    <h1>Orders</h1>
    <br>
    @foreach($orders as $order)

        <b>{{ $order->updated_at }}</b> - <i>{{ $order->send }}</i>

        <h3>WITH</h3>

        @if(isset($groups[$order->id]))
            @for( $s = 0; $s < count($groups[$order->id]) ; $s++)
                <dd>{{ $groups[$order->id][$s]['name'] }}</dd>
            @endfor
        @endif


        <ul>
            @for( $k = 0; $k < count($menus[$order->id]) ; $k++)

                <li>
                    {{ $menuDesc[$menus[$order->id][$k]['menu_id']]['name'] .
                     ' --- ' .
                      $menuDesc[$menus[$order->id][$k]['menu_id']]['price']}}
                </li>

            @endfor
        </ul>
    @endforeach
@endsection