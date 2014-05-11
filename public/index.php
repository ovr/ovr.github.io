<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Dmitry Patsura Blog - About Me</title>
	<link rel="stylesheet" type="text/css" href="/src/vendor/bootstrap/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/src/vendor/fontawesome/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="/css/demo.css" />
	<link rel="stylesheet" type="text/css" href="/css/component.css" />
	<link rel="stylesheet" type="text/css" href="/css/about.css	" />
	<script src="/src/vendor/modernizr/modernizr.js"></script>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-20503308-1', 'dmtry.me');
		ga('send', 'pageview');
	</script>
</head>
<body>
	<div class="container">
		<ul id="gn-menu" class="gn-menu-main">
			<li class="gn-trigger">
				<a class="gn-icon gn-icon-menu"><span>Menu</span></a>
				<nav class="gn-menu-wrapper">
					<div class="gn-scroller">
						<ul class="gn-menu">
							<li><a class="gn-icon gn-icon-cog">About me</a></li>
							<li><a class="gn-icon gn-icon-cog">Skills</a></li>
							<li><a class="gn-icon gn-icon-help">Github Activity</a></li>
							<li><a class="gn-icon gn-icon-help">Contact</a></li>
						</ul>
					</div><!-- /gn-scroller -->
				</nav>
			</li>
			<li><a href="https://github.com/ovr" class="codrops-icon fa-github fa-2x pull-right">Github Profile</a></li>
			<li><a href="skype:izaets28rus?chat" class="codrops-icon fa-skype  fa-2x pull-right">Contact me by Skype</a></li>
		</ul>
	</div><!-- /container -->
	<div class="container" id="about">
		<div class="page-header">
			<h1 class="brand" style="color: #9c9c9c;">
				Patsura Dmitry
				<small style="color: #99c356;">Web-Backend PHP Developer</small>
			</h1>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<img src="/img/572096.jpg">
			</div>
			<div class="col-lg-6 col-md-12">
				Text about me bla bla bla
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<h3>Programming Languages</h3>
				<ul class="list-unstyled list-inline">
					<li>PHP,</li>
					<li>C/C++</li>
				</ul>
			</div>
			<div class="col-lg-6 col-md-12">
				<h3>Tools</h3>
				<ul class="list-unstyled list-inline">
					<li>PhpStorm,</li>
					<li>Git,</li>
					<li>HG (Mercurial),</li>
					<li>PHPUnit,</li>
					<li>Unix,</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<h3>PHP Frameworks</h3>
				<ul class="list-unstyled list-inline">
					<li>Zend Framework 1 &amp; 2 (not work with big project),</li>
					<li>Phalcon PHP,</li>
					<li>Symfony 2,</li>
				</ul>
			</div>
			<div class="col-lg-6 col-md-12">
				<h3>Frontend Frameworks</h3>
				<ul class="list-unstyled list-inline">
					<li>JQuery,</li>
					<li>Bootstrap,</li>
					<li>Backbone,</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<h3>Work ex</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<h3>Education</h3>
				BGPU 2014-2017 Math, Programming profile
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<h3>Contact Me</h3>
				skype: izaets28rus
				email: zaets28rus@gmail.com
			</div>
		</div>
	</div>
	<script src="/src/lib/classie.js"></script>
	<script src="/src/lib/gnmenu.js"></script>
	<script>
		new gnMenu( document.getElementById( 'gn-menu' ) );
	</script>
</body>
</html>