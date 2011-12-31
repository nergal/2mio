<?php $this->extend('layout') ?>

<?php $this->block('content') ?>
<style type="text/css">
.subcontainer { padding: 0 1px 0 21px; }
</style>
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
	
			<form action="" method="post" enctype="multipart/formdata">
				<div class="clear">
					<label for="user_avatar">Аватар <span>JPG, GIF, PNG; до 1MB</span></label>
					<input class="fancy" type="file" autocomplete="off" value="" id="user_avatar" name="avatar" />
				</div>
				<p align="center">
					<input type="submit" value="сохранить аватар" class="blue_sub" style="width:170px;" />
				</p>
			</form>
		</div>
	</div>
</div>
<?php $this->endblock('content') ?>