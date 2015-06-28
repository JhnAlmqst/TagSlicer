<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="/i/favicon.png">
	<link href="/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="/css/bootstrap/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
	<link href="/css/styles.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap/bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/scripts.js"></script>
    <title>{{ title }}</title>
</head>
<body>
	<header class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 col-xs-12">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>					
						<a class="navbar-brand" href="/">{{ sitename }}</a>
					</div>
					<div class="collapse navbar-collapse" id="navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li{{ controller == 'elements' ? ' class="active"' : '' }}>
								<a href="/elements/">Список</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
	<div class="main-container container">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 col-xs-12">
				{{ content }}
			</div>
		</div>
	</div>
</body>
</html>