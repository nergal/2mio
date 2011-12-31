<?php
	if ( ! Request::initial()->is_ajax()) {
		$this->extend('layout');

		$this->block('meta');
		echo "<title>Авторизация | Женский сайт Хочу.ua</title>";
		$this->endblock('meta');
	}
?>

<?php $this->block('content') ?>
		    <div id="login-form">
				<h3>Пожалуйста, авторизируйтесь на Хочу.ua</h3>
				<div class="login line">
						<?php if (isset($this->errors) AND ! empty($this->errors)): ?>
							<div class="message error txtC"><ul>
							<?php foreach ( (array) $this->errors as $error): ?>
								<li><?php echo $error ?></li>
							<?php endforeach ?>
							</ul></div>							
						<?php endif ?>

						<?php if (isset($this->info) AND ! empty($this->info)): ?>
							<div class="message notice txtC"><ul>
							<?php foreach ( (array) $this->info as $message): ?>
								<li><?php echo $message ?></li>
							<?php endforeach ?>
							</ul></div>
						<?php endif ?>

						<?php if (isset($this->messages) AND ! empty($this->messages)): ?>
							<div class="message success txtC"><ul>
							<?php foreach ( (array) $this->messages as $message): ?>
								<li><?php echo $message ?></li>
							<?php endforeach ?>
							</ul></div>
						<?php endif ?>
						
					<form action="/login/" method="post">
						<div class="unit" style="width:290px;">
							<label for="form-email">E-mail / Логин</label>
							<input type="text" name="email" id="form-email" />
							
							<div>
								<input style="float:left" type="checkbox" name="remember" id="form-remember" />
								<label for="form-remember" style="line-height:12px">&nbsp;запомнить меня на неделю</label>
							</div>
						</div>
						<div class="unit" style="width:230px;">
							<label for="form-passw">Пароль</label>
							<input type="password" name="pass" id="form-passw" />
						</div>
						<div class="lastUnit">
							<input type="hidden" name="referrer" value="<?php echo $this->referrer; ?>" />
							<input type="submit" value="Войти" />
						</div>
					</form>
				</div>
				<div class="line">
					<div class="register unit size1of2">
						<h3>Ещё нет аккаунта?</h3>
						<p class="desc">Быстрая регистрация</p>

						<form action="/register/" method="post" class="register">
							<div class="unit size1of1">
								<label for="form-email">E-mail:</label>
								<input type="text" name="email" id="reg-email" />
							</div>
							<div class="unit size1of1">
								<label for="form-login">Логин:</label>
								<input type="text" name="login" id="reg-login" />
							</div>
							<div class="unit size1of1">
								<label for="form-passw">Желаемый пароль:</label>
								<input type="password" name="pass" id="reg-passw" />
							</div>
							<div class="lastUnit txtC size1of1">
								<input type="submit" value="Зарегистрировать" />
							</div>
						</form>
					</div>
					<div class="social lastUnit size1of2">
						<h3>Авторизация через социальные сети</h3>

						<div class="line">
							<a href="/user/facebook/" rel="facebook"><img src="/i/icons/facebook.png" width="48" height="48" /></a>
							<a href="/user/twitter/" rel="twitter"><img src="/i/icons/twitter.png" width="48" height="48" /></a>
							<a href="/user/vkontakte/" rel="vkontakte"><img src="/i/icons/vkontakte.png" width="48" height="48" /></a>
						</div>
						<div class="line" style="display:none">
							<a href="#" rel="google"><img src="/i/icons/google.png" width="48" height="48" /></a>
							<a href="#" rel="mailru"><img src="/i/icons/mailru.png" width="48" height="48" /></a>
							<a href="#" rel="lj"><img src="/i/icons/lj.png" width="48" height="48" /></a>
						</div>
					</div>
				</div>
		    </div>
<?php $this->endblock('content') ?>