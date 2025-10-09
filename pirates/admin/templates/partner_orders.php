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
							Заказы партнера
						</div>
			        </div>
			        <div class="col-sm-6 text-center">
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="/" target="_blank" class=""><i class="glyphicon glyphicon-share"></i> К сайту</a>
							</li>
							<li>
								<a href="index.php?task=logoutPartner"><i class="glyphicon glyphicon-log-out"></i> Выход</a>
							</li>
						</ul>
			        </div>
			    </div>
			    <div class="col-lg-12 clearfix ksenmart-mainmenu">
					<div class="path-menu main pull-left">
						<ul class="list-inline">
							<li class="ksenmart-mainmenu-home"> 
								<a href="javascript:void(0);" alt="Панель управления">
									<i class="glyphicon glyphicon-home"></i>
								</a>
							</li>
							<li class="ksenmart-mainmenu-child-component"> 
								<a href="javascript:void(0);" alt="Заказы" class="active">Заказы</a>
							</li>
						</ul>
					</div>
				    <ul class="set-menu pull-right list-inline">
				        <li>
					        <a href="#" data-toggle="modal" data-target="#KSLandingInfo" alt="Информация">
					        	<i class="fa fa-info"></i>
					        </a>
					    </li>
				    </ul>
			    </div>
			    <div class="col-lg-12">
					<div class="row">
						<div class="col-lg-5"></div>
						<form method="get" class="form-inline col-lg-7 pull-right list__filter text-right" role="form" action="partner.php">
							<div class="form-group">
								<input type="text" name="filter[text]" value="<?php echo (checkVar($filter['text']) ? checkVar($filter['text']) :''); ?>" placeholder="По тексту" class="form-control">
							</div>
							<div class="form-group">
								<select name="filter[subscribe_id]" class="form-control">
									<option value="">По продукту</option>
									<?php foreach($subscribes as $subscribe):?>
									<option value="<?php echo $subscribe['id']; ?>" <?php echo (checkVar($filter['subscribe_id']) == $subscribe['id'] ? 'selected' : ''); ?>><?php echo $subscribe['title']; ?></option>			
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success">Поиск</button>
							</div>
						</form>
					</div>
					<table class="cat">
						<thead>
							<tr>
								<th width="60px">#</th>
								<th>Покупатель</th>
								<th class="hidden-xs">Товар</th>
								<th class="hidden-xs">Шаблон</th>
								<th width="40%">Реферальные данные</th>
								<th>Дата</th>
							</tr>
						</thead>
						<tbody>
							<?php if (count($orders)): ?>
								<?php foreach($orders as $order): ?>
								<tr>
									<td><?php echo $order['id']; ?></td>
									<td>
										Имя : <?php echo $order['name']; ?><br>
										E-mail : <?php echo $order['email'] ; ?><br>
										Телефон : <?php echo $order['phone']; ?>
									</td>
									<td class="hidden-xs"><?php echo $order['subscribe_title']; ?></td>
									<td class="hidden-xs"><?php echo $order['template_title']; ?></td>
									<td><?php echo $order['referer_info']; ?></td>
									<td><?php echo $order['date']; ?></td>
								</tr>			
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="8" align="center"><h4>Нет заказов</h4></td>
								</tr>
							<?php endif; ?>
						</tbody>		
					</table>
					<?php include ROOT.'/admin/templates/pagination.php'; ?>
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