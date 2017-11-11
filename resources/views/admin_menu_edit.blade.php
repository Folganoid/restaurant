<h2>Edit Menu #{{ $menu->id }}</h2>
<br>
{{ Form::model($menu, ['route' => ['admin.menu.update', $menu->id], 'method' => 'post']) }}
{{ Form::text('name') }}
{{ Form::token() }}
{{ Form::hidden('id') }}

<br>
{{ Form::text('price') }}
<br>
{{ Form::number('portion') }}
<br>
{{ Form::select('category_id', $cat, $menu->category_id) }}
<br>
{{ Form::submit('Update') }}

{{ Form::close() }}