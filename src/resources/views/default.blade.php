
<div>
	@if($embed['title'])
		<h2>{{ htmlspecialchars($embed['title'], ENT_IGNORE) }}</h2>
	@endif

	@if($embed['description'])
		<p>{{ htmlspecialchars($embed['description'], ENT_IGNORE) }}</p>
	@endif

	@if($embed['image'])
		<img src="{{ $embed['image'] }}" alt="{{ $embed['title'] }}">
	@endif
</div>