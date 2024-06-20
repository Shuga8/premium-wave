@php
	if (isset($seoContents) && count($seoContents)) {
	    $seoContents = (object) $seoContents;
	    $socialImageSize = explode('x', $seoContents->image_size);
	} elseif ($seo) {
	    $seoContents = $seo;
	    $seoContents->title = $pageTitle;
	    $socialImageSize = explode('x', getFileSize('seo'));
	    $seoContents->image = getImage(getFilePath('seo') . '/' . $seo->image);
	} else {
	    $seoContents = null;
	}
@endphp

<meta name="title" Content="{{ $general->sitename(__($seoContents->title)) }}">

@if ($seoContents)
	<meta name="description" content="{{ $seoContents->meta_description ?? $seoContents->description }}">
	<meta name="keywords" content="{{ implode(',', $seo->keywords ?? []) }}">
	<link type="image/x-icon" href="https://premiumwave.ca/wp-content/uploads/2024/06/cropped-PREMIUM-WAVES-3.png" rel="shortcut icon">
	{{--
    <!-- Apple Stuff --> --}}
	<link href="{{ siteLogo() }}" rel="apple-touch-icon">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="{{ $general->sitename($pageTitle) }}">
	{{--
    <!-- Google / Search Engine Tags --> --}}
	<meta itemprop="name" content="{{ $general->sitename($pageTitle) }}">
	<meta itemprop="description" content="{{ $seoContents->description }}">
	<meta itemprop="image" content="{{ $seoContents->image }}">
	{{--
    <!-- Facebook Meta Tags --> --}}
	<meta property="og:type" content="website">
	<meta property="og:title" content="{{ $seoContents->social_title }}">
	<meta property="og:description" content="{{ $seoContents->social_description }}">
	<meta property="og:image" content="{{ $seoContents->image }}" />
	@if (array_key_exists('extension', pathinfo($seoContents->image)))
		<meta property="og:image:type" content="{{ pathinfo($seoContents->image)['extension'] }}" />
	@endif
	<meta property="og:image:width" content="{{ $socialImageSize[0] }}" />
	<meta property="og:image:height" content="{{ $socialImageSize[1] }}" />
	<meta property="og:url" content="{{ url()->current() }}">
	{{--
    <!-- Twitter Meta Tags --> --}}
	<meta name="twitter:card" content="summary_large_image">
@endif
