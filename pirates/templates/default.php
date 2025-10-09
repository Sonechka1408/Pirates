<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Mr Chistoff</title>
		
		<link type="text/css" rel="stylesheet" href="../css/bootstrap.css" /> 
		<link type="text/css" rel="stylesheet" href="../css/style.css" /> 
 		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../css/style.js"></script>
		<script type="text/javascript" charset="utf-8" src="//api-maps.yandex.ru/services/constructor/1.0/js/?sid=mPv4D_Wu9y5GHWbbtOwxRbsjb6JlsQfv&id=mymap"></script>
		<link rel="stylesheet" type="text/css" href="../css/jquery.countdown.css"> 
		<script type="text/javascript" src="../css/jquery.plugin.js"></script> 
		<script type="text/javascript" src="../css/jquery.countdown.js"></script>
		<script>
			<?php echo $js; ?>
		</script>
	</head>
	<body>
		<div id="dark"></div> 
		<div id="wind">
			<span class="close">x</span>
			<form method="post">
				<h3>Форма</h3>
				<div class="row">
					<label>Ваше имя</label>
					<input type="text" class="inputbox" name="name" value="" required="required"/>
				</div>
				<div class="row">
					<label>Ваш телефон</label>
					<input type="text" class="inputbox" name="phone" value="" required="required"/>
				</div>
				<div class="row">
					<label>Ваш E-mail</label>
					<input type="text" name="email" class="inputbox" value="" required="required" />
				</div>
				<div class="row">
					<input type="submit" class="button" value="Заказать" />
				</div>
				<input type="hidden" name="subscribe_id" value="" />
			</form>
		</div>
		<div id="success">
			<span class="close">x</span>
			<h3>Спасибо.</h3>
			В ближайшее время мы с вами свяжемся.
		</div>	
		<div id="all">
			<div id="block1">
				<div class="top-bg"></div>
				<div class="container">
					<div class="row">
						<div class="visual">
							<!-- <div class="slide">
								<img src="../css/i/bg1.jpg" />
								<h3>
									Сервис №1 по<br />
									<strong>химчистке мебели</strong><br />
									<span>в Москве и Московской области</span>
								</h3>
								<img src="../css/i/bg2.jpg" />
							</div> -->
							
							<div class="slide">
								<?php echo getBlock(1)->data['content']; ?>
								<h3 class="gr">
									Сервис №1 по<br />
									<strong><?php echo getBlock(2)->data['content']; ?></strong><br />
									<span>в Москве и Московской области</span>
								</h3>
							</div>
							<!--
							<div class="slide">
								<img src="../css/i/bg3.jpg" />
								<h3 class="gr">
									Сервис №1 по<br />
									<strong>химчистке матрасов</strong><br />
									<span>в Москве и Московской области</span>
								</h3>
							</div>
							<div class="slide">
								<img src="../css/i/bg4.jpg" />
								<h3 class="gr">
									Сервис №1 по<br />
									<strong>химчистке ковров</strong><br />
									<span>в Москве и Московской области</span>
								</h3>
							</div>
							-->
						</div>
						<div class="col-xs-12 tops">
							<div class="logo"></div>
							<div class="infs">
								<div class="inf1">Вернем 100%<br /> если не понравится</div>
								<div class="inf2">3207<br /> довольных клиенов</div>
							</div>
							<div class="phones">
								<div class="kru"><span>Круглосуточно, без выходных</span></div>
								<div class="nums"><span>+7 495</span> 777-45-67</div>
								<div class="addr">
									<a class="choos" href="#"><span>Москва</span></a> ул. Долгорукова, 5, офис 21
									<ul>
										<li><a href="#">Зеленоград</a></li>
										<li><a href="#">Московская область</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="prices">
							<div class="pr1">От 2000 руб</div>
							<div class="pr2"><span>24</span><br /> часа в сутки</div>
							<div class="pr3"><span>7</span><br /> дней в неделю</div>
						</div>
						<div class="formm">
							<div class="for"><span>Отправь заявку</span><br /> на химчистку ковра</div>
							<form class="form js-order_form-full" name="order" method="POST">
							<div class="inp1">
								<input type="text" class="inputbox" name="name" value="" placeholder="Ваше имя" />
							</div>
							<div class="inp2">
								<input type="text" class="inputbox" name="phone" value="" placeholder="Ваш телефон" />
							</div>
							<div class="recal">Позвоним в течении<br /> 15 мин</div>
							<div class="sub"><input type="submit" class="button" value="Отправить заявку" /></div>
							<input type="hidden" value="21" name="pid">
							</form>
							<div class="nor">Вы получите качественный сервисза<br /> умеренную плату</div>
							<div class="writ">
								<p>Запись на завтра<br /> закончится через</p>
								<div id="note" class="tim note2"></div>
							</div>
							<div class="slog">Свежие пятна удаляются быстрее!</div>
						</div>
					</div>
				</div>
			</div>
			<div id="block2">
				<div class="container">
					<div class="row">
						<div class="feaut">
							<ul>
								<li>Оплата по факту</li>
								<li>Чистота без запаха</li>
								<li>Чистота с гарантией</li>
								<li>Только русские мастера</li>
								<li>Мы принимаем карты</li>
								<li>Экологичные материалы</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div id="block3">
				<div class="container">
					<div class="row">
						<div class="backs">
							<img src="../css/i/block3.jpg" />
						</div>
						<div class="txt">
							<h3 class="heads">Вы можете нам доверять</h3>
							<div class="t2">
								<span>Нам доверяют чистку мебели</span><br />
								<span><strong>в Доме Правительства Российской Федерации</strong></span><br />
								<span class="ot">и других значимых организациях</span>
							</div>
							<div class="t3"></div>
							<div class="t4">А также, тысячи мам со всей Москвы и Московской области остаются<br /> довольны нашими услугами </div>
							<div class="t5"><a class="button show-form" href="javascript:void()"  subscribe_id="26">Отправить заявку</a></div>
						</div>
					</div>
				</div>
			</div>
			<div id="block4">
				<div class="container">
					<div class="row">
						<h3 class="heads">Химчистка диванов</h3>
						<div class="diva">
							<div class="itm">
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-sofa-1 active" >
										<img src="images/sofa2.jpg" />
									</div>
								</div>
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-sofa-2" >
										<img src="images/sofa3.jpg" />
									</div>
								</div>
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-sofa-3" >
										<img src="images/sofa4.jpg" />
									</div>
								</div>
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-sofa-4" >
										<img src="images/sofa5.jpg" />
									</div>
								</div>
									<h3 class="title_properties">Тип вашего дивана</h3>
									<ul class="nav type" role="tablist">
									  <li class="active"><a href="#tab-sofa-1" role="tab" data-toggle="tab">два места</a></li>
									  <li><a href="#tab-sofa-2" role="tab" data-toggle="tab">три места</a></li>
									  <li><a href="#tab-sofa-3" role="tab" data-toggle="tab">четыре места</a></li>
									  <li><a href="#tab-sofa-4" role="tab" data-toggle="tab">пять мест</a></li>
									</ul>

								<div class="tab-content txt">
									<div class="tab-pane tab-sofa-1 active" >
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content txt">
									<div class="tab-pane tab-sofa-2" >
										<div class="txt">
											<p>2 Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content txt">
									<div class="tab-pane tab-sofa-3" >
										<div class="txt">
											<p>3 Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content txt">
									<div class="tab-pane tab-sofa-4" >
										<div class="txt">
											<p>3 Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content price_cena">
									<div class="tab-pane tab-sofa-1 active" >
										<div class="price">
											Цена: <strong>500</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="22">Вызвать мастера</a>
									</div>
								</div>
								<div class="tab-content price_cena">
									<div class="tab-pane tab-sofa-2" >
										<div class="price">
											Цена: <strong>600</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="23">Вызвать мастера</a>
									</div>
								</div>
								<div class="tab-content price_cena">
									<div class="tab-pane tab-sofa-3" >
										<div class="price">
											Цена: <strong>700</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="24">Вызвать мастера</a>
									</div>
								</div>
								<div class="tab-content price_cena">
									<div class="tab-pane tab-sofa-4" >
										<div class="price">
											Цена: <strong>800</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="25">Вызвать мастера</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="block5">
				<div class="container">
					<div class="row">
						<h3 class="heads">Химчистка мягкой мебели</h3>
						<div class="cat-itms">
							<div class="itm">
								<img src="../images/img1.jpg" />
								<div class="desc">
									<div class="descl">
										<h3>Кресло</h3>
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
									<div class="descr">
										<div class="price">
											Цена: <strong>500</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="1">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<img src="../images/img2.jpg" />
								<div class="desc">
									<div class="descl">
										<h3>Кресло-кровать</h3>
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
									<div class="descr">
										<div class="price">
											Цена: <strong>700</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="2">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<img src="../images/img3.jpg" />
								<div class="desc">
									<div class="descl">
										<h3>Пуф, банкетка</h3>
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
									<div class="descr">
										<div class="price">
											Цена: <strong>150</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="3">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<img src="../images/img4.jpg" />
								<div class="desc">
									<div class="descl">
										<h3>Стул, табурет</h3>
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
									<div class="descr">
										<div class="price">
											Цена: <strong>150</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="4">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<img src="../images/img5.jpg" />
								<div class="desc">
									<div class="descl">
										<h3>Стул с мягкой спинкой</h3>
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
									<div class="descr">
										<div class="price">
											Цена: <strong>250</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="5">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<img src="../images/img6.jpg" />
								<div class="desc">
									<div class="descl">
										<h3>Офисное кресло</h3>
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
									<div class="descr">
										<div class="price">
											Цена: <strong>500</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="6">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-mattress-1 active" >
										<img src="images/img7.jpg" />
									</div>
								</div>
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-mattress-2" >
										<img src="images/img7.jpg" />
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-mattress-1 active" >
										<h3>Матрац</h3>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-mattress-2" >
										<h3>Матрац2</h3>
									</div>
								</div>
									<p class="title_properties">Зависит от размера:</p>
									<ul class="nav type" role="tablist">
									  <li class="active"><a href="#tab-mattress-1" role="tab" data-toggle="tab">односпальный</a></li>
									  <li><a href="#tab-mattress-2" role="tab" data-toggle="tab">двуспальный</a></li>
									</ul>

								<div class="tab-content descl">
									<div class="tab-pane tab-mattress-1 active" >
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-mattress-2" >
										<div class="txt">
											<p>2 Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-mattress-1 active" >
										<div class="price">
											Цена: <strong>1500</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="7">Вызвать мастера</a>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-mattress-2" >
										<div class="price">
											Цена: <strong>1500</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="8">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-theft-1 active" >
										<img src="images/img8.jpg" />
									</div>
								</div>
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-theft-2" >
										<img src="images/img8.jpg" />
									</div>
								</div>
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-theft-3" >
										<img src="images/img8.jpg" />
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-theft-1 active" >
										<h3>Подушка</h3>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-theft-2" >
										<h3>Подушка2</h3>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-theft-3" >
										<h3>Подушка3</h3>
									</div>
								</div>
									<p class="title_properties">Зависит от размера:</p>
									<ul class="nav type" role="tablist">
									  <li class="active"><a href="#tab-theft-1" role="tab" data-toggle="tab">маленькая</a></li>
									  <li><a href="#tab-theft-2" role="tab" data-toggle="tab">средняя</a></li>
									  <li><a href="#tab-theft-3" role="tab" data-toggle="tab">большая</a></li>
									</ul>

								<div class="tab-content descl">
									<div class="tab-pane tab-theft-1 active" >
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-theft-2" >
										<div class="txt">
											<p>2 Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-theft-3" >
										<div class="txt">
											<p>3 Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-theft-1 active" >
										<div class="price">
											Цена: <strong>300</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="9">Вызвать мастера</a>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-theft-2" >
										<div class="price">
											Цена: <strong>400</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="10">Вызвать мастера</a>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-theft-3" >
										<div class="price">
											Цена: <strong>500</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="11">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<img src="../images/img9.jpg" />
								<div class="desc">
									<div class="descl">
										<h3>Кухонный уголок</h3>
										<div class="txt">
											<p>Четырехместный угловой диван или кровать больших размеров</p>
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
									<div class="descr">
										<div class="price">
											Цена: <strong>1200</strong> руб
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="12">Вызвать мастера</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="block6">
				<div class="container">
					<div class="row">
						<h3 class="heads">Химчистка ковров и ковролина</h3>
						<div class="cat-itms">
							<div class="itm">
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-carpet_extractor-1 active" >
										<img src="images/img10.jpg" />
									</div>
								</div>
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-carpet_extractor-2" >
										<img src="images/img10.jpg" />
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-carpet_extractor-1 active" >
										<h3>Экстракторная</h3>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-carpet_extractor-2" >
										<h3>Экстракторная</h3>
									</div>
								</div>
									<p class="title_properties">Зависит от типа покрытия:</p>
									<ul class="nav type" role="tablist">
									  <li class="active"><a href="#tab-carpet_extractor-1" role="tab" data-toggle="tab">ковер</a></li>
									  <li><a href="#tab-carpet_extractor-2" role="tab" data-toggle="tab">ковролин</a></li>
									</ul>

								<div class="tab-content descl">
									<div class="tab-pane tab-carpet_extractor-1 active" >
										<div class="txt">
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-carpet_extractor-2" >
										<div class="txt">
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-carpet_extractor-1 active" >
										<div class="price">
											Цена: <strong>140</strong> руб/м&sup2;
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="13">Вызвать мастера</a>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-carpet_extractor-2" >
										<div class="price">
											Цена: <strong>150</strong> руб/м&sup2;
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="14">Вызвать мастера</a>
									</div>
								</div>
							</div>
							<div class="itm">
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-carpet_impact-1 active" >
										<img src="images/img11.jpg" />
									</div>
								</div>
								<div class="tab-content tab-content-img">
									<div class="tab-pane tab-carpet_impact-2" >
										<img src="images/img11.jpg" />
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-carpet_impact-1 active" >
										<h3>Роторная</h3>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-carpet_impact-2" >
										<h3>Роторная</h3>
									</div>
								</div>
									<p class="title_properties">Зависит от типа покрытия:</p>
									<ul class="nav type" role="tablist">
									  <li class="active"><a href="#tab-carpet_impact-1" role="tab" data-toggle="tab">ковер</a></li>
									  <li><a href="#tab-carpet_impact-2" role="tab" data-toggle="tab">ковролин</a></li>
									</ul>

								<div class="tab-content descl">
									<div class="tab-pane tab-carpet_impact-1 active" >
										<div class="txt">
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descl">
									<div class="tab-pane tab-carpet_impact-2" >
										<div class="txt">
											<p>Время сушки: 1-5 дней</p>
										</div>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-carpet_impact-1 active" >
										<div class="price">
											Цена: <strong>500</strong> руб/м&sup2;
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="15">Вызвать мастера</a>
									</div>
								</div>
								<div class="tab-content descr">
									<div class="tab-pane tab-carpet_impact-2" >
										<div class="price">
											Цена: <strong>600</strong> руб/м&sup2;
										</div>
										<a class="button show-form" href="javascript:void()"  subscribe_id="16">Вызвать мастера</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="block7">
				<div class="container">
					<div class="row">
						<h3 class="heads">Отзывы</h3>
						<div class="reviews">
							<div class="itm">
								<div class="ava">
									<img src="../images/ava5.jpg" />
									Денис Добровольский
								</div>
								<div class="txt">
									<h3><span>Отлично очистили бабушкин диван!</span></h3>
									<p>Из трех предложенных вариантов мне понравились Mr Chistoff! Самое сложное было выбрать! Спасибо компании за отличную работу уборку и сжатые сроки!</p>
								</div>
							</div>
							<div class="itm">
								<div class="ava">
									<img src="../images/ava.jpg" />
									Эрика Чистоплюева
								</div>
								<div class="txt">
									<h3><span>Уборка гостинной комнаты прошла на ура!</span></h3>
									<p>Отличная работа! Не все способны на лету понять пожелания незаурядной личности, но Mr Chistoff с успехом справился с этой задачей! </p>
									<p>Мне чисто и я счастлива!</p>
								</div>
							</div>
							<div class="more"><a href="#">Еще 1024 отзыва</a></div>
							<div class="info">
								Мы публикуем все отзывы наших клиентов, независимо от уровня оценки наших работ. Только честное и открытое сотрудничество позволяет нам удовлетворить потребности большинства самых требовательных клиентов.<br /> <a href="#">Обращайтесь</a> к нам и вы не пожалеете!
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="block8">
				<div class="container">
					<div class="row">
						<div class="spec">
							<h3>Есть соска?<br /> <strong>Получи скидку!</strong></h3>
							<h4>Специальное предложение владельцам сосок!</h4>
							<p>Если у вас есть маленький ребенок, то мы с радостью предоставим вам скидку <span>10%</span>  — мы за продолжение рода и готовы поддержать вас в трудную минуту.</p>
							<a class="button show-form" href="javascript:void()"  subscribe_id="17">Получить скидку</a>
						</div>
					</div>
				</div>
			</div>
			<div id="block9">
				<div class="container">
					<div class="row">
						<h3 class="heads">Наша компания</h3>
						<div class="dir">
							<img src="../images/ava6.jpg" />
							<div class="txt">
								<h3>Захар Горелов</h3>
								<p><strong>У меня у самого есть ребенок и я знаю как важно держать дом в чистоте для его здоровья. </strong></p>
								<p>В свою компанию я принимаю только опытных и вежливых профессионалов, задача которых не только устранить вашу проблему, но и сделать так, чтобы в следующий раз вы захотели обратиться именно в нашу компанию.</p>
							</div>
						</div>
						<div class="teams">
							<h3 class="headsm">Наша команда по уборке</h3>
							<div class="team">
								<img src="../images/ava2.jpg" />
								<div>
									<p>Лера<br /> Чисточистая</p>
									<p><span>VIP Менеджер</span></p>
									<a class="button show-form sm" href="javascript:void()"  subscribe_id="18">Связаться</a>
								</div>
							</div>
							<div class="team">
								<img src="../images/ava3.jpg" />
								<div>
									<p>Антон<br /> Скороходов</p>
									<p><span>VIP Менеджер</span></p>
									<a class="button show-form sm" href="javascript:void()"  subscribe_id="19">Связаться</a>
								</div>
							</div>
							<div class="team">
								<img src="../images/ava4.jpg" />
								<div>
									<p>Вася<br /> Обломов</p>
									<p><span>VIP Менеджер</span></p>
									<a class="button show-form sm" href="javascript:void()"  subscribe_id="20">Связаться</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="block10">
			<div id="mymap">
				<div class="backdrop"></div>
			</div>
				<div class="container">
					<div class="row">
						<div class="prices">
							<div class="pr2"><span>24</span><br> часа в сутки</div>
							<div class="pr3"><span>7</span><br> дней в неделю</div>
							<div class="writ">
								<p>Запись на 24/09<br> закончится через</p>
								<div id="note2" class="tim note2"></div>
							</div>
						</div>
						<div class="formm">
							<div class="logo"></div>
							<div class="tech">
								ООО «Мистер чистофф»<br>
								ИНН 234 234  12312351<br>
								ОГРН 234234-1231
							</div>
							<div class="for"><span>Отправь заявку</span><br> на химчистку ковра</div>
							<form class="form js-order_form-full" name="order" method="POST">
								<div class="inp1">
									<input type="text" class="inputbox" name="name" value="" placeholder="Ваше имя">
								</div>
								<div class="inp2">
									<input type="text" class="inputbox" name="phone" value="" placeholder="Ваш телефон">
								</div>
								<div class="recal">Позвоним в течении<br> 15 мин</div>
								<div class="sub"><input type="submit" class="button" value="Отправить заявку"></div>
								<input type="hidden" value="27" name="pid">
							</form>
							<div class="nor">Вы получите качественный сервисза<br> умеренную плату</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>