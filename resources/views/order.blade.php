@extends('layouts.main')

@section('content')


<div class="row">
    <div class="col-lg-6">
    <h1>My Orders</h1>
    <br>

    @foreach($orders as $k => $order)
        <dd>{{ $order[0]->created_at }} / {{ $order[0]->updated_at }} --- {{ ($order[0]->send == 0) ? 'Not sended' : 'Sended'}}</dd>
        <dd>
            WITH:
            @if(isset($groups[$k]))
            @for( $i = 0 ; $i < count($groups[$k]) ; $i++)
                {{ $groups[$k][$i]->login . ' ' }}
            @endfor
            @endif
        </dd>
    <ul>
        @for( $i = 0 ; $i < count($order) ; $i++)
            <li>{{ $order[$i]->name }}</li>
        @endfor
    </ul>
    @endforeach
    </div>

    <div class="col-lg-6">
        <h1>Foreign orders</h1>
        <br>

        @foreach($foreignOrders as $k => $order)
            <dd>{{ $order[0]->created_at }} / {{ $order[0]->updated_at }} --- {{ ($order[0]->send == 0) ? 'Not sended' : 'Sended'}}</dd>

            <dd>Owner - {{ $order[0]->login }}</dd>
            <dd>
                WITH:
                @if(isset($foreignGroups[$k]))
                @for( $i = 0 ; $i < count($foreignGroups[$k]) ; $i++)
                    {{ $foreignGroups[$k][$i]->login . ' ' }}
                @endfor
                @endif
            </dd>
            <ul>
                @for( $i = 0 ; $i < count($order) ; $i++)
                    <li>{{ $order[$i]->name }}</li>
                @endfor
            </ul>
        @endforeach
    </div>
</div>

@endsection