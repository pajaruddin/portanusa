<!DOCTYPE html>
<html lang="en">
	
<head>
<title>@yield('title')</title>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="/styles/bootstrap4/bootstrap.min.css">
<link href="/plugins/noty/noty.css" rel="stylesheet" type="text/css" />
<link href="/plugins/hovercss/css/hover.css" rel="stylesheet" type="text/css" />
<link href="/plugins/fontawesome/css/all.css" rel="stylesheet" type="text/css">
@stack('plugin_styles')

@stack('custom_styles')
<link rel="stylesheet" href="/styles/responsive.css">
<link rel="stylesheet" href="/css/navbar.css">
<link rel="stylesheet" href="/css/footer.css">
<link rel="icon" href="<?= DisplayMenu::getIcon() ?>" />

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5cbec344d6e05b735b43d14a/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</head>

<body>

<div class="super_container">
	
	<!-- Header -->
	<div class="navbar-container">
		@include('layouts.navbar')
		<div class="navbar-porta">
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
				<div class="container">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNav">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item dropdown">
								<a class="nav-link" href="javascript:;"><i class="fas fa-bars mr-2 d-none d-md-inline-block"></i> Brands</a>
								<ul class="dropdown-menu">
									{!! DisplayMenu::categoryNavigation() !!}
								</ul>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link" href="#">Category</a>
								<ul class="dropdown-menu subject-navigation">
									{!! DisplayMenu::subjectNavigation() !!}
								</ul>
							</li>
							{!! DisplayMenu::eventNavigation() !!}
						</ul>
						<!-- <a class="nav-link-catalogue hvr-pulse-grow" target="_blank" href="/ecatalog">
							<img src="/images/button-lkpp-new.png" height="50px" />
						</a> -->
					</div>
				</div>
			</nav>
		</div>
	</div>
	

	@yield('content')

	<!-- Footer -->
	@include('layouts.footer')

	@include('user.register')
	@include('user.signIn')
</div>

<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/styles/bootstrap4/popper.js"></script>
<script src="/styles/bootstrap4/bootstrap.min.js"></script>
<script src="/plugins/greensock/TweenMax.min.js"></script>
<script src="/plugins/greensock/TimelineMax.min.js"></script>
<script src="/plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="/plugins/greensock/animation.gsap.min.js"></script>
<script src="/plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="/plugins/easing/easing.js"></script>
<script src="/plugins/noty/noty.js"></script>
@stack('plugin_scripts')
@stack('custom_scripts')
<script>
function redirectUrl(url){
	window.location.href = url;
}

$(function(){
	var height_navbar = $('.navbar-container').outerHeight();
	$('body').css('padding-top', height_navbar+'px');

	var width_body = $('body').outerWidth();
	if(width_body <= 768){
		$('.dropdown .nav-link').click(function(){
			if($(this).parent().find('.dropdown-menu').first().is(":visible")){
				$('.dropdown .dropdown-menu').hide();
			}else{
				$('.dropdown .dropdown-menu').hide();
				$(this).parent().find('.dropdown-menu').first().toggle();
			}
		})
		$('.dropdown-submenu').click(function(){
			if($(this).find('.dropdown-menu').is(":visible")){
				$('.dropdown-submenu .dropdown-menu').hide();
			}else{
				$('.dropdown-submenu .dropdown-menu').hide();
				$(this).find('.dropdown-menu').toggle();
			}
		})

		$('.navbar-container').css('position', 'relative');
		$('body').css('padding-top', '0px');
		var top = $('.navbar-porta').offset().top;
		var height_navbar = $('.navbar-porta').outerHeight();
		$(window).scroll(function (event) {
			var y = $(this).scrollTop();
			if (y > top) {
				$('.navbar-porta').css('position', 'fixed');
				$('.navbar-porta').css('left', '0');
				$('.navbar-porta').css('top', '0');
				$('.navbar-porta').css('width', '100%');
				$('body').css('padding-top', height_navbar+'px');
			} else {
				$('body').css('padding-top', '0px');
				$('.navbar-porta').css('position', 'relative');
			}
		});
	}
})
</script>
@if (session('success_newsletter'))
<script>
	$(function(){
		new Noty({
			type: "success",
			text: "{{ session('success_newsletter') }}",
			layout: 'center',
			timeout: 2000,
			modal: true
		}).show();
	});
</script>
@endif
@if (session('failed_newsletter'))
<script>
	$(function(){
		new Noty({
			type: "error",
			text: "{!! session('failed_newsletter') !!}",
			layout: 'center',
			timeout: 2000,
			modal: true
		}).show();
	});
</script>
@endif
</body>

</html>