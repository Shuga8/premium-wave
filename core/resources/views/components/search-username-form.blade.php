@props([
    'placeholder' => 'Search...',
    'btn' => 'btn--primary',
    'keySearch' => 'yes',
])

<form class="d-flex flex-wrap gap-2" action="" method="GET">
	@if ($keySearch == 'yes')
		<x-username-key-field btn="{{ $btn }}" />
	@endif
</form>
