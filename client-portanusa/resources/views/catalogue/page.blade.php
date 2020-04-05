<!DOCTYPE html>
<html lang="en">
<head>
<title>{{ $title }}</title>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="/styles/bootstrap4/bootstrap.min.css">
<link href="/plugins/noty/noty.css" rel="stylesheet" type="text/css" />
<link href="/plugins/hovercss/css/hover.css" rel="stylesheet" type="text/css" />
<link href="/plugins/fontawesome/css/all.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="/css/catalogue.css">
<link rel="icon" href="<?= DisplayMenu::getIcon() ?>" />
</head>

<body>
	<div class="navbar-content">
		<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand mr-5" href="/ecatalog">
					<img src="<?= DisplayMenu::getLogo() ?>" height="50px" />
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href="/">Website</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<div class="slider-content mb-5">
		<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
			</ol>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="http://via.placeholder.com/1440x400" class="img-fluid" />
				</div>
				<div class="carousel-item">
					<img src="http://via.placeholder.com/1440x400" class="img-fluid" />
				</div>
				<div class="carousel-item">
					<img src="http://via.placeholder.com/1440x400" class="img-fluid" />
				</div>
			</div>
			<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
	<div class="desc-content">
		<div class="container">
			<div class="row">
				<div class="col-md-6 pr-0">
					<a href="javascript:;" onclick="videoModal('ucuTlsXXfG4')">
						<div class="video-content">
							<img src="http://img.youtube.com/vi/ucuTlsXXfG4/0.jpg" class="w-100" height="400px" />
							<div class="play-icon">
								<img src="/images/youtube-ico.png" />
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-6 pl-0 d-md-block d-none">
					<div class="desc">
						<div class="desc-text d-flex align-items-center">
							<h4 class="w-100">
								Tutorial<br/>
								<span>Panduan lengkap belanja di Portanusa.com</span>
							</h4>
						</div>
						<div class="arrow left"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 pr-0 d-md-block d-none">
					<div class="desc">
						<div class="desc-text d-flex align-items-center">
							<h4 class="w-100">
								Porta Nusa<br/>
								<span>Mengenal lebih dekat dengan profil Porta Nusa</span>
							</h4>
						</div>
						<div class="arrow right"></div>
					</div>
				</div>
				<div class="col-md-6 pl-0">
					<a href="javascript:;" onclick="videoModal('ucuTlsXXfG4')">
						<div class="video-content">
							<img src="http://img.youtube.com/vi/ucuTlsXXfG4/0.jpg" class="w-100" height="400px" />
							<div class="play-icon">
								<img src="/images/youtube-ico.png" />
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 pr-0">
					<a href="javascript:;" onclick="videoModal('ucuTlsXXfG4')">
						<div class="video-content">
							<img src="http://img.youtube.com/vi/ucuTlsXXfG4/0.jpg" class="w-100" height="400px" />
							<div class="play-icon">
								<img src="/images/youtube-ico.png" />
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-6 pl-0 d-md-block d-none">
					<div class="desc">
						<div class="desc-text d-flex align-items-center">
							<h4 class="w-100">
								Roadshow<br/>
								<span>Kegiatan roadshow Porta Nusa terbaru</span>
							</h4>
						</div>
						<div class="arrow left"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="download-content pt-5 pb-5">
		<img class="background-image" src="/images/shop_background.jpg" class="w-100" />
		<div class="overlay-blue"></div>
		<div class="overlay-black"></div>
		<div class="content">
			<div class="container">
				<div class="row">
					<div class="col-sm-3">
						<div class="left-content">
							<h3 class="mb-1">Download</h3>
							<div class="border w-25 mb-4"></div>
							<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pulvinar nisl at metus lobortis iaculis. </h5>
						</div>
					</div>
					<div class="col-sm-9">
						<div class="right-content p-5">
							<h3 class="text-center mb-1">Informasi <span>E-Katalog</span></h3>
							<h5 class="text-center">Download semua informasi terkait e-katalog portanusa.</h5>
							@if(!empty($catalogues) && count($catalogues) > 0)
							<ul class="mt-4">
							@foreach($catalogues as $catalogue)
								<a href="{{ $asset_domain."/".$file_path."/".$catalogue->file_catalog }}" target="_blank">
									<li class="p-4">
										<i class="far fa-file-pdf mr-3"></i> {{ $catalogue->title }} <i class="fas fa-arrow-circle-down float-right"></i>
									</li>
								</a>
							@endforeach
							</ul>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-content pt-5 pb-5">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<h4 class="mb-3">Porta Nusa</h4>
					<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pulvinar nisl at metus lobortis iaculis. Lorem ipsum dolor sit amet</h5>
				</div>
				<div class="col-sm-4 offset-sm-2 align-self-center">
					<ul class="socmed-icon">
						<li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
						<li><a href="#"><i class="fab fa-instagram"></i></a></li>
						<li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
						<li><a href="#"><i class="fab fa-twitter"></i></a></li>
						<li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
						<li><a href="#"><i class="fab fa-youtube"></i></a></li>
						<li><a href="#"><i class="fab fa-blogger-b"></i></a></li>
					</ul>
				</div>
			</div>
			<div class="border"></div>
			<div class="row">
				<div class="offset-sm-8"></div>
				<div class="col-sm-4">
					<h5 class="text-right">Copyright &copy; 2019 Portanusa All Rights Reserved.</h5>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="videoCatalogueModal" tabindex="-1" role="dialog" aria-labelledby="videoCatalogueModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="videoCatalogueModalTitle">Video</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<iframe width="100%" height="400" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script src="/js/jquery-3.3.1.min.js"></script>
	<script src="/styles/bootstrap4/popper.js"></script>
	<script src="/styles/bootstrap4/bootstrap.min.js"></script>
	<script src="/plugins/noty/noty.js"></script>

	<script>
		$(function(){
			var height_navbar = $('.navbar-content .navbar').outerHeight();
			$('body').css('padding-top', height_navbar+'px');

			$("#videoCatalogueModal").on('hidden.bs.modal', function (e) {
				$("#videoCatalogueModal iframe").attr("src", $("#videoCatalogueModal iframe").attr("src"));
			});
		})

		function videoModal(url){
			$('#videoCatalogueModal iframe').attr('src', 'https://www.youtube.com/embed/'+url);
			$('#videoCatalogueModal').modal('show');
		}
	</script>

</body>

</html>