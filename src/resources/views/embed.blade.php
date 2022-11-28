@php
   //dd($scripts, $html);
@endphp

{!! $html !!}

@foreach($scripts as $script)
	@push('ls-embed-scripts')
		{!! $script !!}
	@endpush
@endforeach
