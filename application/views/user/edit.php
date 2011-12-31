<?php
	$this->extend('layout');

	$this->block('meta');
	echo '<title>'.$this->user->get_user_title().' | Женский сайт Хочу.ua</title>';
	$this->endblock('meta');
?>

<?php $this->block('content') ?>
<link rel="stylesheet" type="text/css" href="/js/autocomplete/autocomplete.css">
<script type="text/javascript" src="/js/autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/js/autocomplete/jquery.select-autocomplete.js"></script>

<div class="subcontainer">
	<div class="heading rel">
		<div class="ribbon usermenu" style="margin-left:-7px;">
			Редактирование профиля <span class="top" style="line-height:25px;"><?php echo $this->link($this->user) ?></span>
			<span class="bottom_span"></span>
		</div>
		<div class="logout left user_exit" style="margin-left:-10px;">
			<a href="/logout/">Отменить и выйти</a>
		</div>
		<div class="clear"></div>
	</div>

	<div class="tops rel">
		<ul class="tabs" style="display:block;height:31px;margin-bottom:-4px;">
			<li class="tableft2 ssmall-caps active" href="#private"><div class="lts"></div><div class="cts">мои личные данные</div><div class="rts"></div></li>
			<li class="tabright2 ssmall-caps" href="#services"><div class="lts"></div><div class="cts">мои сервисы</div><div class="rts"></div></li>
		</ul>
		<div class="clear"></div>

		<div class="panes box-shad">
		    <div id="edit-form">
				<div class="login form">
					<div class="message error<?php (isset($this->errors) AND ! empty($this->errors)) ?: print(' hidden') ?>">
						<h4>Ошибки заполнения формы</h4>
						<ul id="messageBox">
						<?php if (isset($this->errors) AND ! empty($this->errors)): ?>
							<?php foreach ( (array) $this->errors as $error): ?>
								<li><?php echo $error ?></li>
							<?php endforeach ?>
						<?php endif ?>
						</ul>
					</div>

					<form id="form-edit-form" method="post">
						<?php if ( ! $this->user->has('roles', ORM::factory('role', array('name' => 'social')))): ?>
							<div class="clear">
								<label for="username">Логин <span>Логин для входа на сайт</span></label>
								<input class="fancy" type="text" value="<?php echo $this->user->username ?>" id="username" name="username" />
							</div>
							<div class="clear">
								<label for="user_password">Пароль <span>Не меньше 6 символов</span></label>
								<input class="fancy" type="password" autocomplete="off" value="" id="user_password" name="password" />
							</div>
							<div class="clear">
								<label for="user_password_confirm">Пароль ещё раз <span>Для избежания опечаток</span></label>
								<input class="fancy" type="password" autocomplete="off" value="" id="user_password_confirm" name="password_confirm" />
							</div>
							<div class="clear">
								<div class="right">
									<span class="ch">
										<?php echo Form::checkbox('show_email', NULL, ((bool) $this->user->show_email), array('id' => 'user_show_email', 'style' => 'margin:0;padding:0;width:auto;')) ?>
									</span>
									<label for="user_show_email"><span style="text-align:left;width:8em;">Отображать<br /> в профиле</span></label>
								</div>

								<label for="user_email">E-mail <span>Для связи с администрацией</span></label>
								<input class="fancy short" type="text" autocomplete="off" value="<?php echo $this->user->email ?>" id="user_email" name="user_email" />
							</div>
							<div class="hrdiv"></div>
						<?php endif; ?>

						<div class="clear">
							<label for="user_firstname">Имя <span>Необязательное поле</span></label>
							<input type="text" class="fancy" id="user_firstname" name="firstname" value="<?php echo $this->user->firstname ?>" />
						</div>
						<div class="clear">
							<label for="user_lastname">Фамилия <span>Необязательное поле</span></label>
							<input type="text" class="fancy" id="user_lastname" name="lastname" value="<?php echo $this->user->lastname ?>" />
						</div>
						<div class="clear" style="position:relative;z-index:11;">
							<label for="user_country">Страна <span>Необязательное поле</span></label>

							<?php
								$countries = array();
								$_uk = array();
								foreach ($this->countries as $item) {
									if ( ! in_array($item->id, array(174, 125))) {
										$countries[$item->id] = $item->name;
									} else {
										$_uk[$item->id] = $item->name;
									}
								}
								krsort($_uk);
								$countries = array(NULL => NULL) + $_uk + $countries;

								echo Form::select(
									NULL,
									$countries,
									$this->country->id,
									array(
										'data-placeholder' => 'Выберите страну',
										'style' => 'width:390px;',
										'id' => 'user_country',
										'class' => 'chzn-select'
									)
								)
							?>
						</div>
						<div class="clear" style="position:relative;z-index:10;">
							<label for="user_from">Город <span>Необязательное поле</span></label>

							<?php

							$_ky = array();

							if (array_key_exists(2533, $this->cities)) {
								$_ky[2533] = $this->cities[2533];
								unset($this->cities[2533]);
							}
							$this->cities = array(NULL => NULL) + $_ky + $this->cities;

							echo Form::select(
								NULL,
								$this->cities,
								max(
									intVal($this->user->city_id),
									intVal($this->city_id)
								),
								array(
									'data-placeholder' => 'Выберите город',
									'style' => 'width:390px;',
									'id' => 'user_from',
									'class' => 'chzn-select'
								)
							) ?>

							<input type="hidden" value="<?php echo $this->user->city_id ?>" id="city_id" name="city_id" />
							<input type="hidden" value="<?php echo $this->user->user_from ?>" id="user_from_hidden" name="user_from" />
						</div>
						<div class="clear">

							<div class="right">
								<span class="ch">
									<?php echo Form::checkbox('show_birthday', NULL, ((bool) $this->user->show_birthday), array('id' => 'user_show_birthday', 'style' => 'margin:0;padding:0;width:auto;')) ?>
								</span>
								<label for="user_show_birthday"><span style="text-align:left;width:8em;">Отображать<br /> в профиле</span></label>
							</div>
							<label for="user_birthday">День рождения <span>Необязательное поле</span></label>
							<?php
								$date_value = '';
								if ($this->user->user_birthday) {
								    try {
									$date_value = ' value="'. date_format(new DateTime($this->user->user_birthday), 'm/d/Y').'"';
									$date_value.= ' data-value="'.date_format(new DateTime($this->user->user_birthday), 'Y-m-d').'"';
								    } catch (Exception $e) { }
								}
							?>
							<input class="fancy date-field short" type="text" <?php echo $date_value ?> id="user_birthday" name="user_birthday" style="background:url(/i/calend.png) no-repeat 250px 1px;" />
						</div>
						<div class="clear">
							<label for="user_interests">Интересы <span>Необязательное поле</span></label>
							<textarea class="fancy" id="user_interests" name="user_interests"><?php echo $this->user->user_interests ?></textarea>
						</div>

						<div class="clear">
							<label for="user_icq">Номер ICQ <span>Необязательное поле</span></label>
							<input class="fancy" type="text" value="<?php echo $this->user->user_icq ?>" id="user_icq" name="user_icq" />
						</div>
						<div class="clear">
							<label for="user_skype">Skype <span>Необязательное поле</span></label>
							<input class="fancy" type="text" value="<?php echo $this->user->user_skype ?>" id="user_skype" name="user_skype" />
						</div>
						<div class="clear">
							<label for="user_website">Сайт <span>Необязательное поле</span></label>
							<input class="fancy" type="text" value="<?php echo $this->user->user_website ?>" id="user_website" name="user_website" />
						</div>
						<p align="center">
							<input type="submit" value="Сохранить изменения в моих личных данных" class="blue_sub" style="//width:360px;" />
						</p>
					</form>
				</div>
		    </div>
		    <div id="services-form" class="hidden">
		    	<div style="margin:15px;">
			    	<br />
			    	<p>Будьте добры, выберите те сервисы, которые Вы хотите видеть на своей персональной странице портала:</p>
					<form class="form2" method="post">
						<input type="hidden" name="services" value="yes" />
				    	<table width="100%" class="paddtable">
				    		<tr>
				    			<td width="50%">
				    				<span class="ch"><?php echo Form::checkbox('service_notepad', NULL, ((bool) $this->user->service_notepad), array('id' => 'service_notepad')) ?></span>
				    				<label for="service_notepad">Мой блокнот <span>Выбранные Вами публикации</span></label>
				    			</td>
				    			<td width="50%">
				    				<span class="ch"><?php echo Form::checkbox('service_blog', NULL, ((bool) $this->user->service_blog), array('id' => 'service_blog')) ?></span>
				    				<label for="service_blog">Мой блог <span>Ваш личный дневник</span></label>
				    			</td>
				    		</tr>
				    		<tr>
				    			<td>
				    				<span class="ch"><?php echo Form::checkbox('service_cookbook', NULL, ((bool) $this->user->service_cookbook), array('id' => 'service_cookbook')) ?></span>
				    				<label for="service_cookbook">Моя кулинарная книга <span>Выбранные Вами рецепты</span></label>
				    			</td>
				    			<td>
				    				<span class="ch"><?php echo Form::checkbox('service_video', NULL, ((bool) $this->user->service_video), array('id' => 'service_video')) ?></span>
				    				<label for="service_video">Избранное видео <span>Понравившиеся Вам видеоролики</span></label>
				    			</td>
				    		</tr>
				    	</table>

				    	<div class="hrdiv"></div>

				    	<p>В скором времени будут доступны дополнительные сервисы:</p>
				    	<table width="100%" class="paddtable">
				    		<tr>
				    			<td width="50%">
				    				<span class="ch disch"><input type="checkbox" disabled="disabled" id="service_colors" /></span>
				    				<label for="service_colors" class="dis">Мой цветотип <span>Определение Вашего цветотипа</span></label>
				    			</td>
				    			<td width="50%">
				    				<span class="ch disch"><input type="checkbox" disabled="disabled" id="service_tests" /></span>
				    				<label for="service_tests" class="dis">Мои тесты <span>Пройденные Вами тесты</span></label>
				    			</td>
				    		</tr>
				    		<tr>
				    			<td>
				    				<span class="ch disch"><input type="checkbox" disabled="disabled" id="service_body" /></span>
				    				<label for="service_body" class="dis">Идеальное тело <span>Самый эффективный путь к красоте</span></label>
				    			</td>
				    			<td>
				    				<span class="ch disch"><input type="checkbox" disabled="disabled" id="service_dress" /></span>
				    				<label for="service_dress" class="dis">Мой гардероб <span>Подобранные для Вас фешн-тесты</span></label>
				    			</td>
				    		</tr>
				    		<tr>
				    			<td>
				    				<span class="ch disch"><input type="checkbox" disabled="disabled" id="service_diet" /></span>
				    				<label for="service_diet" class="dis">Мои диеты <span>Избранные диеты</span></label>
				    			</td>
				    			<td>
				    				<span class="ch disch"><input type="checkbox" disabled="disabled" id="service_beauty" /></span>
				    				<label for="service_beauty" class="dis">Моя косметичка <span>Ваши отзывы и комментарии о косметике</span></label>
				    			</td>
				    		</tr>
				    	</table>
				    	<p align="center">
			    	    	<input type="submit" class="blue_sub" value="Сохранить изменения в моих сервисах" style="//width:340px;" />
			    		</p>
			    	</form>

		    	</div>
		    </div>
		</div>
	</div>
</div>
<?php $this->endblock('content') ?>