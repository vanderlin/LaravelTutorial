{{ Form::open(array('route' => 'posts.store')) }}
Title: {{ Form::text('title'); }}
{{ Form::submit('Create Post'); }}
{{ Form::close() }}

<ul>
@foreach(Post::all() as $post)
	<li> 
		Title: {{ $post->title }} 
		{{ Form::open(array('url' => 'posts/'.$post->id, 'method'=>'delete')) }}
		{{ Form::submit('Delete'); }}
		{{ Form::close() }}
	</li> 

@endforeach
</ul>

