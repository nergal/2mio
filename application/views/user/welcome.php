<?php $i_src = URL::base(TRUE).'i/welcome/'; ?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body style="margin:0;padding:0;">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
P, IMG, H1, H2, H3, span, table, tr, td, a {margin:0; padding:0; border:none;outline-color:none;border-collapse:collapse;font-family: Arial, Helvetica,  Nimbus Sans L, sans-serif;font-size:14px;}

</style>
	<center style="margin:0;padding:0;">
		<table width="727" height="1250" style="border-collapse:collapse;margin:0; padding:0; border:none;background:#ffffff;font-family: Arial, Helvetica,  Nimbus Sans L, sans-serif;" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;width:25px;">&nbsp;</td>
				<td style="padding:7px 5px 10px 5px;width:677px;height:85px;" align="center">
					<a href="http://bt-lady.com.ua/" alt="Женский журнал ХОЧУ. Лучший украинский женский сайт."><img src="<?php echo $i_src; ?>logo.png" width="178" height="85" border="0"></a>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;width:25px;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;"><img src="<?php echo $i_src; ?>welcome-left.png" width="25" height="46" border="0"></td>
				<td valign="top" style="background:url(<?php echo $i_src; ?>bg_welcome.png) repeat-x darkred;text-align:center; height:36px;padding-top:10px;color:white;font-size:16px;line-height:16px;">Добро пожаловать в женский клуб &laquo;Хочу&raquo;!</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;"><img src="<?php echo $i_src; ?>welcome-right.png" width="25" height="46" border="0"></td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="color:#252525;height:110px;" align="center">
					
					<h1 style="font-size:17px;height:40px;line-height:45px;">Привет, <?php echo $this->to; ?>!</h1>
					<p style="font-size:15px;">Мы очень рады, что ты присоединилась к нам, и теперь в клубе &laquo;Хочу&raquo;<br> на одну интересную женщину больше!</p>
					<br>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="color:#252525;" align="center">
					<h2 style="background:url(<?php echo $i_src; ?>bg_h2.png) no-repeat center center;font-size:13px;height:27px;">ЗДЕСЬ ТЫ МОЖЕШЬ:</h2>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="color:#252525;height:220px;" align="center" >
					<table style="margin-left:8px;padding:0;border-collapse:collapse;" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td><a href="http://forum.bt-lady.com.ua/" alt="Общаться на форуме"><img src="<?php echo $i_src; ?>forum.png" width="140" height="140" border="0"></a></td>
							<td><a href="http://forum.bt-lady.com.ua/blog.php" alt="Вести свой блог"><img src="<?php echo $i_src; ?>blog.png" width="140" height="140" border="0"></a></td>
							<td><a href="#" alt="Сохранять интересные статьи и рецепты"><img src="<?php echo $i_src; ?>recipe.png" width="140" height="140" border="0"></a></td>
							<td><a href="http://bt-lady.com.ua/cat-competitions/" alt="Выигрывать призы в конкурсах!"><img src="<?php echo $i_src; ?>prize.png" width="140" height="140" border="0"></a></td>
						</tr>
						<tr>
							<td colspan="2" ><br><a href="http://www.facebook.com/portal.hochu" alt="Все самое лучшее в facebook. Подпишись!"><img src="<?php echo $i_src; ?>to_fb.png" width="277" height="33" border="0"></a></td>
							<td colspan="2"><br><a href="http://vkontakte.ru/club22275376" alt="Все женские разговорчики ВКонтакте! Присоединяйся!"><img src="<?php echo $i_src; ?>to_vk.png" width="277" height="33" border="0"></a></td>
						</tr>
					</table>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="color:#252525;" align="center">
					<h2 style="background:url(<?php echo $i_src; ?>bg_h2.png) no-repeat center center;font-size:13px;height:27px;">ПОСЛЕДНИЕ ИНТЕРЕСНЫЕ НОВОСТИ</h2>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="color:#252525;height:290px;padding-top:15px;" align="center" valign="top">

					<table style="margin-left:30px;padding:0;border-collapse:collapse;" width="560" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<?php foreach($this->news as $news_item): ?>
							<?php $news_url =  rtrim(URL::base(TRUE), '/').$this->uri($news_item); ?>
							<td style="padding:0 3px;">
								<p><a href="<?php echo $news_url; ?>" alt="<?php echo $news_item->title; ?>"><img src="<?php echo $this->photo($news_item, '143x108').'?'.md5(date('YmdH')) ?>" width="143" height="108" style="margin-bottom:10px;" border="0"></a></p>
								<p style="margin-bottom:10px;"><a href="<?php echo $news_url; ?>" style="font-size:13px;color:#466b85;text-decoration:none;font-weight:bold;"><?php echo $news_item->title ?></a></p>
								<p style="font-size:12px;color:#353535;margin-bottom:20px;"><?php echo Helper::filter($news_item->description); ?></p>
								<p><a href="<?php echo $news_url; ?>" style="font-size:11px;color:#3388cb;margin-bottom:20px;">Подробнее...</a></p><br>
							</td>
							<?php endforeach; ?>
						</tr>
					</table>

				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="color:#252525;" align="center">
					<h2 style="background:url(<?php echo $i_src; ?>bg_h2.png) no-repeat center center;font-size:13px;height:27px;">НАШИ САМЫЕ ПОПУЛЯРНЫЕ СЕРВИСЫ</h2>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="color:#252525;height:160px;" align="center">
					<table style="margin-left:22px;padding:0;border-collapse:collapse;" width="450" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td><a href="http://bt-lady.com.ua/cat-shoping/overstock/" alt="Распродажа"><img src="<?php echo $i_src; ?>overstock.png" width="140" height="140" border="0"></a></td>
							<td><a href="http://bt-lady.com.ua/cat-cosmetik-opinions/" alt="Косметичка"><img src="<?php echo $i_src; ?>cosmetik.png" width="140" height="140" border="0"></a></td>
							<td><a href="http://bt-lady.com.ua/horoscope/" alt="Гороскоп"><img src="<?php echo $i_src; ?>horoscope.png" width="140" height="140" border="0"></a></td>
						</tr>
					</table>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="color:#252525;" align="center">
					<p style="font-size:14px;">Управлять своими данными, подписками и комментариями<br> ты сможешь <a href="http://bt-lady.com.ua/user/<?php if ($this->user) echo $this->user->id; ?>/" style="color:#2877ba;">в личном кабинете</a></p>
					<br>
					<p style="font-size:14px;"> А еще наш сайт подстраивается под тебя!<br>Теперь на страницах сайта будет только твой гороскоп,<br> погода в твоем городе и темы на форуме, которые тебе интересны!</p><br>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;">&nbsp;</td>
				<td style="text-align:center;color:#252525;height:30px;background:#f0f0f0;">
					<p style="font-size:12px;">По всем вопросам, связанным с работой сайта, обращайтесь, пожалуйста, по адресу <a href="mailto:lady@bt-lady.com.ua" style="color:#2877ba;">lady@bt-lady.com.ua</a></p>
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
			
			<tr>
				<td style="background:url(<?php echo $i_src; ?>left-wr.png) repeat-y;height:13px;">&nbsp;</td>
				<td >
					&nbsp;
				</td>
				<td style="background:url(<?php echo $i_src; ?>right-wr.png) repeat-y;">&nbsp;</td>
			</tr>
		</table>
	</center>
	
</body>
</html>


