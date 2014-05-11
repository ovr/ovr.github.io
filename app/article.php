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
	<script src="/src/vendor/modernizr/modernizr.js"></script>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-20503308-1', 'dmtry.me');
		ga('send', 'pageview');
	</script>
	<style>
		#about {
			margin-top: 100px;
		}
	</style>
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
			<li><a href="/about" class="codrops-icon fa-2x pull-right">About me</a></li>
		</ul>
	</div><!-- /container -->
	<div class="container" id="about">
		<h1>Почему Phalcon это не обязательно хорошо?</h1>
		<?php
			echo file_get_contents(__DIR__ . '/data/cache/phalcon_It_is_not_the_best.html');
		?>
	</div>
	<script src="/src/lib/classie.js"></script>
	<script src="/src/lib/gnmenu.js"></script>
	<script>
		new gnMenu( document.getElementById( 'gn-menu' ) );
	</script>
</body>
</html>