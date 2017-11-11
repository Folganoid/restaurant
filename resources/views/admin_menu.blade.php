@extends('layouts.main')

@section('content')
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
@endsection