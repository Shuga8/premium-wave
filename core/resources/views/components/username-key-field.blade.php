@props(['placeholder' => 'username', 'btn' => 'btn--primary'])
<div class="input-group flex-fill w-auto">
	<input class="form-control bg--white" name="username" type="search" value="{{ request('username') }}"
		placeholder="username">
	<button class="btn {{ $btn }}" type="submit"><i class="la la-search"></i></button>
</div>
