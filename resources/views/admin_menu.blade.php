@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-6">
    <h1>Admin menu</h1>
    <br />
    <a href="#">Add menu</a>
    <a href="#">Add category</a>
    <br />
    @for( $z = 0 ; $z < count($categories) ; $z++)
        <br>
        <h3>{{ $categories[$z]->name }}</h3>
        <a href="{{ route('admin.category.edit', ['id' => $categories[$z]->id]) }}">Edit category</a>
        <a href="{{ route('admin.category.delete', ['id' => $categories[$z]->id]) }}">Delete category</a>
        <br>
        <table>
            <tr>
                <td>Id</td>
                <td>Name</td>
                <td>Portion</td>
                <td>Price</td>
                <td>Category</td>
                <td></td>
                <td></td>
            </tr>
            @for( $i = 0 ; $i < count($menus) ; $i++)
                @if($categories[$z]->id == $menus[$i]->category_id)
                <tr>
                    <td>{{ $menus[$i]->id }}</td>
                    <td>{{ $menus[$i]->name }}</td>
                    <td>{{ $menus[$i]->portion }}</td>
                    <td>{{ $menus[$i]->price }}</td>
                    <td>{{ $menus[$i]->category->name }}</td>
                    <td><a href="{{ route('admin.menu.edit', ['id' => $menus[$i]->id]) }}">Edit menu</a></td>
                    <td><a href="{{ route('admin.menu.delete', ['id' => $menus[$i]->id]) }}">Delete menu</a></td>
                </tr>
                @endif
            @endfor
        </table>
    @endfor
        </div>
        <div class="col-6">
            <h2>Add category</h2>
            <br>
            {!! Form::open(['route' => 'admin.category.add', 'method' => 'post']) !!}
            <input name="category" type="text"/>
            <button type="submit">Add category</button>
            {!! Form::close() !!}
            <br>
            <h2>Add menu</h2>
            <br>
            {!! Form::open(['route' => 'admin.menu.add', 'method' => 'post']) !!}
            <input name="name" type="text" placeholder="name" required/>
            <br>
            <input name="price" type="text" placeholder="price" required/>
            <br>
            <input name="portion" type="text" placeholder="portion" required/>
            <br>
            <select name="category_id">
                @for( $c = 0 ; $c < count($categories) ; $c++)
                    <option value="{{ $categories[$c]->id }}">{{ $categories[$c]->name }}</option>
                @endfor
            </select>
            <br>
            <button type="submit">Add menu</button>
            {!! Form::close() !!}

        </div>
    </div>


@endsection