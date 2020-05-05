<!DOCTYPE html>
<!--[if IE 8 ]><html class="no-js oldie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" lang="{{ config('app.locale') }}"> <!--<![endif]-->
<head>

	<!--- basic page needs
	================================================== -->
	<meta charset="utf-8">
	<title>{{ isset($post) && $post->seo_title ? $post->seo_title :  __(lcfirst('Title')) }}</title>
	<meta name="description" content="{{ isset($post) && $post->meta_description ? $post->meta_description : __('description') }}">
	<meta name="author" content="@lang(lcfirst ('Author'))">
	@if(isset($post) && $post->meta_keywords)
		<meta name="keywords" content="{{ $post->meta_keywords }}">
	@endif
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- mobile specific metas
	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="{{ asset('css/bootstrap-tagsinput.css') }}">
	<!-- CSS
	================================================== -->
	<link rel="stylesheet" href="{{ asset('css/base.css') }}">
	<link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
	<link rel="stylesheet" href="{{ asset('css/main.css') }}">
	<link rel="stylesheet" href="{{ asset('css/front.css') }}">
	@yield('css')

	<style>
		.search-wrap .search-form::after {
			content: "@lang('Press Enter to begin your search.')";
		}
	</style>


	<!-- script
	================================================== -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

	<!-- favicons
	================================================== -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">

</head>

<body id="top">
	<header class="front-sidebar">
		<div class="logo-div">
			<a href="">
				<img src="{{ asset('images/logo.png') }}" alt="" class="logo-img">
			</a>
		</div>
		<ul class="menubar-ul">
			<li @if ($page_title === 'account') class="active menubar-li" @else class="menubar-li" @endif>
				<img src="{{ asset('images/account_img.png') }}" alt=""><a>Account</a>
			</li>
			<li @if ($page_title === 'posts') class="active menubar-li" @else class="menubar-li" @endif>
				<img src="{{ asset('images/repair_img.png') }}" alt=""><a href = "{{ route('post') }}">Reparaties</a>
			</li>
			<li @if ($page_title === 'account') class="active menubar-li" @else class="menubar-li"" @endif>
				<img src="{{ asset('images/facturen_img.png') }}" alt=""><a>Facturen</a>
			</li>
			<li id="logout" class="menubar-li">
				<img src="{{ asset('images/facturen_img.png') }}" alt=""><a>Log out</a>
			</li>
		</ul>
		<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hide">
			{{ csrf_field() }}
		</form>
	</header>
   @yield('main')

   <div id="preloader">
    	<div id="loader"></div>
   </div>

   <!-- Java Script
   ================================================== -->
   <script src="https://code.jquery.com/jquery-3.2.0.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <script src="{{ asset('js/bootstrap-tagsinput.min.js') }}"></script>
   <script src="{{ asset('js/plugins.js') }}"></script>
   <script src="{{ asset('js/main.js') }}"></script>
   <script>
		$(function() {
			$('#logout').click(function(e) {
				e.preventDefault();
				$('#logout-form').submit()
			})

			$('#menubar-li').click(function(e) {
				$(".menubar-li").removeClass("active");
			})
		})
   </script>

   @yield('scripts')

</body>

</html>
