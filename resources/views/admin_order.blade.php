@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <h1>Orders</h1>
            <br>
    @for( $z = 0 ; $z < count($orders) ; $z++)

        @if($orders[$z]->send == 1)
            <dd>
                {{ $orders[$z]->created_at }} / {{ $orders[$z]->updated_at }} - by {{ $orders[$z]->login }}
                <a href="#">view</a>
            </dd>
        @endif

    @endfor
        </div>

        <div class="col-lg-6">
            <h1>Active orders</h1>
            <br>
            @for( $z = 0 ; $z < count($orders) ; $z++)

                @if($orders[$z]->send == 0)
                    <dd>
                        {{ $orders[$z]->created_at }} / {{ $orders[$z]->updated_at }} - by {{ $orders[$z]->login }}
                        <a href="#">view</a>
                    </dd>
    @endif

    @endfor
        </div>
    </div>




@endsection