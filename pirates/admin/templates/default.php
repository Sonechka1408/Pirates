<!DOCTYPE html>
<html lang="ru">
	<head>
		<title>Администрирование</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		
		<link href="./css/summernote.css" rel="stylesheet">
		<link href="./css/style.css" rel="stylesheet">
		<link href="./css/ksen.css" rel="stylesheet">

	</head>
	<body ng-app="KSLanding">
		<div class="container">
			<div class="row container-main">
			    <div class="col-lg-12 row">
			        <div class="col-sm-6">
						<div class="km-path">
							<a href="index.php?view=main">
								<div class="logo"></div>
							</a>
							<span></span>
							<?php echo $lang['menu_' . $view]; ?>
						</div>
			        </div>
			        <div class="col-sm-6 text-center">
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="/" target="_blank" class=""><i class="glyphicon glyphicon-share"></i> К сайту</a>
							</li>
							<li>
								<a href="index.php?task=logout"><i class="glyphicon glyphicon-log-out"></i> Выход</a>
							</li>
						</ul>
			        </div>
			    </div>
			    <div class="col-lg-12 clearfix ksenmart-mainmenu">
					<div class="path-menu main pull-left">
						<ul class="list-inline">
							<li class="ksenmart-mainmenu-home"> 
								<a href="index.php?view=main" alt="Панель управления"<?php echo ($view == 'main' ? ' class="active"' : ''); ?>>
									<i class="glyphicon glyphicon-home"></i>
								</a>
							</li>
							<li class="ksenmart-mainmenu-child-component"> 
								<a href="index.php?view=subscribes" alt="Продукты"<?php echo ($view == 'subscribes' ? ' class="active"' : ''); ?>>Продукты</a>
							</li>
							<li class="ksenmart-mainmenu-child-component"> 
								<a href="index.php?view=orders" alt="Заказы"<?php echo ($view == 'orders' ? ' class="active"' : ''); ?>>Заказы</a>
							</li>
							<li class="ksenmart-mainmenu-child-component"> 
								<a href="index.php?view=shares" alt="Акции"<?php echo ($view == 'shares' ? ' class="active"' : ''); ?>>Акции</a>
							</li>
						</ul>
					</div>
				    <ul class="set-menu pull-right list-inline">
				        <li>
					        <a href="#" data-toggle="modal" data-target="#KSLandingInfo" alt="Информация">
					        	<i class="fa fa-info"></i>
					        </a>
					    </li>
				        <li>
					        <a href="index.php?view=settings"<?php echo ($view == 'settings' ? ' class="active"' : ''); ?> alt="Настройки">
					        	<i class="fa fa-cog"></i>
					        </a>
					    </li>
				    </ul>
			    </div>
			    <div class="col-lg-12">
			        <?php include ROOT.'/admin/templates/'.$view.'.php'; ?>
			    </div>
			</div>
		</div>
		<div class="modal fade" id="KSLandingInfo" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-normal-width">
		        <div class="modal-content form-horizontal">
		            <div class="modal-header clearfix">
		                <h4 class="modal-title pull-left">Информация</h4>
		                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
		            </div>
		          	<div class="modal-body">
						<div class="text-center">
							&copy; ООО «<a href="http://www.ldmco.ru/" title="" target="_blank">L.D.M. & Co</a>», 2014
						</div>
		          	</div>
		        </div>
		  	</div>
		</div>
		
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>

		<script src="js/summernote.min.js"></script>
		<script src="js/default.js"></script>
		<script src="js/app.js"></script>
	</body>
</html>