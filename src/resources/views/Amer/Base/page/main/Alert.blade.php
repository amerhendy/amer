
@push('scripts')
@loadOnce('Noty')
<script>
@if (Alert::count())
            @foreach (Alert::getMessages() as $type => $messages)
			new Noty({
				type:'{{$type}}',
				layout:'top',
				theme:"sunset",
				timeout:5000,
				text: `@foreach ($messages as $message) {!! $message !!} @endforeach`,
			}).show();
            @endforeach
	@endif

	</script>
@endLoadOnce
	@endpush
