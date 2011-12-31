<?php
	$this->extend('layout');

	$this->block('meta');
	echo "<title>Сброс пароля | Женский сайт Хочу.ua</title>";
	$this->endblock('meta');
?>

<?php $this->block('content') ?>
		    <div id="edit-form">
				<h3>Сброс пароля</h3>
				<br />
				<div class="login form">
					<?php if (isset($this->errors) AND ! empty($this->errors)): ?>
						<div class="message error txtC">
							<h4>Ошибки заполнения формы</h4>
							<ul>
							<?php foreach ( (array) $this->errors as $error): ?>
								<li><?php echo $error ?></li>
							<?php endforeach ?>
							</ul>
						</div>
					<?php endif ?>
					<?php if (isset($this->success) AND ! empty($this->success)): ?>
						<div class="message success txtC">
							<ul>
							<?php foreach ( (array) $this->success as $success): ?>
								<li><?php echo $success ?></li>
							<?php endforeach ?>
							</ul>
						</div>
					<?php endif ?>

					<form method="post">
						<?php if ($this->first_stage === TRUE): ?>
						<div>
							<label for="email">Ваш email<span></span></label>
							<input class="fancy" type="text" value="" id="email" name="email" />
						</div>
						<?php else: ?>
						<div>
							<label for="newpass">Новый пароль<span></span></label>
							<input type="password" class="fancy" id="newpass" name="password" />
						</div>
						<div>
							<label for="newpass2">Подтверждение пароля<span></span></label>
							<input type="password" class="fancy" id="newpass2" name="password_confirm" />
						</div>
						<?php endif; ?>


						<div>
							<input type="submit" value="Сбросить" />
						</div>
					</form>
				</div>
		    </div>
<?php $this->endblock('content') ?>