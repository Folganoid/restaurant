<h2>Edit Category #{{ $category->id }}</h2>
<br>
{{ Form::model($category, ['route' => ['admin.category.update', $category->id], 'method' => 'post']) }}
{{ Form::text('name') }}
{{ Form::token() }}
{{ Form::hidden('id') }}
<br>
{{ Form::submit('Update') }}
{{ Form::close() }}