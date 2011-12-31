<div id="add-question">
	<?php
		$uri = URL::site(Route::get('consult-add')->uri());
	?>
	<form action="<?php echo $uri ?>" method="post" class="form center">
		<?php if (empty($this->errors)): ?>
			<div class="message notice txtC">
				Чтобы войти, Вы должны быть <a href="http://svetlovodsk.com.ua/index.php?do=register">зарегистрированны на основном сайте</a>.
			</div>
		<?php else: ?>
			<?php foreach ( (array) $this->errors as $error): ?>
				<div class="message error txtC"><?php echo $error ?></div>
			<?php endforeach ?>
		<?php endif ?>

		<div>
			<label for="form-speciality">Специализация<span>Направление консультации</span></label>
			<select id="form-speciality" name="speciality">
				<?php foreach ($this->specialities as $speciality): ?>
					<option<?php echo (($speciality->id == $this->id) ? ' selected="selected"' : ''); ?> value="<?php echo $speciality->id ?>"><?php echo $speciality->name ?></option>
				<?php endforeach ?>
			</select>
		</div>

		<div>
			<label for="form-consultant">Консультант<span>Кому Вы задаёте вопрос?</span></label>
			<select id="form-consultant" name="consultant">
				<?php foreach ($this->consultants as $consultant): ?>
					<option value="<?php echo $consultant->id ?>"><?php echo $consultant->user->username ?></option>
				<?php endforeach ?>
			</select>
		</div>

		<div>
			<label for="form-title">Заголовок<span>Коротко о главном</span></label>
			<input type="text" id="form-title" name="title" />
		</div>

		<div>
			<label for="form-body">Суть вопроса<span></span></label>
			<textarea id="form-body" name="body"></textarea>
		</div>

		<div>
			<label for="form-author">Автор<span>Для тех, кто хочет остаться анонимом</span></label>
			<input type="text" id="form-author" name="author" />
		</div>

		<div class="line">
			<input type="submit" value="Задать вопрос" class="button" />
		</div>
	</form>
</div>