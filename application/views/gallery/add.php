<?php $this->extend('layout') ?>

<?php $this->block('meta') ?><title>Добавить фото в галлерею</title><?php $this->endblock('meta') ?>

<?php $this->block('content') ?>
<div class="content-wrapper">
	<div class="breadcrumbs"><a href="/">ХОЧУ!ua</a> / <a href="/gallery">Фотогалерея</a> / <?php echo $this->link($this->gallery) ?> / <span>Добавить фото</span></div>
	<div class="rel">
		    <div id="edit-form">
				<h3>Редактирование профиля</h3>
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
							<?php foreach ( (array) $this->success as $success): ?>
								<p><?php echo $success ?></p>
							<?php endforeach ?>
						</div>
					<?php endif ?>
<script src="/js/validation/jquery.validate.min.js" type="text/javascript"></script>

<script type="text/javascript">

$().ready(function() {

    $("#formAddPhoto").validate({
	debug: true,
	errorLabelContainer: "#messageBox",
	rules: {
            file: {
        		required: true
    	    },
            title: {
	        	required: true,
	        	minlength: 10,
	        	maxlength: 255
    	    },
            description: {
	        	required: true,
	        	minlength: 10,
	        	maxlength: 1000
    	    },
    	    <?php if ($this->is_contest_section == TRUE): ?>
    	    age: {
    	    	required: true,
	        	maxlength: 2,
	        	number: true
    	    },
    	    social_state: {
        	    required: true,
        	    number: true
    	    },
    	    <?php endif ?>
            email: {
	        	required: true,
	        	minlength: 5,
	        	maxlength: 255,
	        	email: true
    	    }
	},
	messages: {
    	    file: {
        	required: $.format("Необходимо выбрать файл фото для загрузки!")
    	    },
    	    title: {
        	required: $.format("Заголовок обязателен для заполнения!"),
        	minlength: $.format("Заголовок должен быть не меньше {0} символов!"),
        	maxlength: $.format("Заголовок должен быть не больше {0} символов!")
    	    },
    	    description: {
        	required: $.format("Описание обязательно для заполнения!"),
        	minlength: $.format("Описание должно быть не меньше {0} символов!"),
        	maxlength: $.format("Описание должно быть не больше {0} символов!")
    	    },
    	    <?php if ($this->is_contest_section == TRUE): ?>
    	    age: {
            	required: $.format("Вы должны указать свой возраст!"),
            	maxlength: $.format("Вы не можете вводить такой возраст")
        	},
        	social_state: {
            	required: $.format("Вы должны указать свой социальный статус!")
			},
    	    <?php endif ?>
    	    email: {
        	required: $.format("Поле почты обязательно для заполнения!"),
        	email: $.format("Поле с почтой должно содержать корректый почтовый ящик!"),
        	minlength: $.format("Почта должна быть не меньше {0} символов!"),
        	maxlength: $.format("Почта должна быть не больше {0} символов!")
    	    }
	},
	errorElement: 'p',
	submitHandler: function(form) {
	    form.submit();
	}
    });
});
</script>
<style>
#messageBox {
    background-color: #FDDFDE;
    border: 1px solid #FBC7C6;
    padding: 14px;
    color: #404040;
    margin: 15px;
    font-size: 0.9em;
    display: none;
}
</style>
					<div id="messageBox"></div>
					<form id="formAddPhoto" method="post" action="" enctype="multipart/form-data">
						<?php if ( ! Auth::instance()->logged_in()): ?>
						<div class="clear">
							<label for="photo_author">Ваше имя<span></span></label>
							<input class="fancy" type="text" value="" id="photo_author" name="author" />
						</div>
						<?php endif ?>
						<div class="clear">
							<label for="photo_file">Фотография<span></span></label>
							<input class="fancy" type="file" value="" id="photo_file" name="file" />
						</div>
						<div class="clear">
							<label for="photo_title">Заголовок фото<span></span></label>
							<input class="fancy" type="text" value="<?php (( ! isset($this->data['title'])) ?: print($this->data['title'])); ?>" id="photo_title" name="title" />
						</div>
						<div class="clear">
							<label for="photo_desc">Описание фото<span></span></label>
							<textarea class="fancy" id="photo_desc" name="description" style="height:42px"><?php (( ! isset($this->data['description'])) ?: print($this->data['description'])); ?></textarea>
						</div>

						<div class="clear">
							<label for="photo_email">Email<span></span></label>
							<input class="fancy" type="text" value="<?php (( ! isset($this->data['email'])) ?: print($this->data['email'])); ?>" id="photo_email" name="email" />
						</div>

						<div class="clear">
							<label for="photo_phone">Контактный телефон<span></span></label>
							<input class="fancy" type="text" value="<?php (( ! isset($this->data['phone'])) ?: print($this->data['phone'])); ?>" id="photo_phone" name="phone" />
						</div>

						<?php if ($this->is_contest_section == TRUE): ?>
						<div class="clear">
							<label for="photo_age">Ваш возраст<span></span></label>
							<input class="fancy" type="text" value="<?php (( ! isset($this->data['age'])) ?: print($this->data['age'])); ?>" id="photo_age" name="age" />
						</div>
						<div class="clear">
							<label for="photo_social_state">Ваш социальный статус<span></span></label>
							<select class="fancy" id="photo_social_state" name="social_state">
								<option></option>
								<option value="1">топ-менеджмер</option>
								<option value="2">квалифицированный специалист</option>
								<option value="3">офисный работник</option>
								<option value="4">рабочий/тех. рабочий</option>
								<option value="5">другое</option>
							</select>
						</div>
						<div class="clear">
							<label for="photo_subscribe_seldiko">Подписатся на рассылку Seldiko?<span></span></label>
							<input type="checkbox" value="yes" <?php (( ! isset($this->data['subscribe_seldiko'])) ?: ($this->data['subscribe_seldiko'] ? 'checked="checked"' : '')); ?> id="photo_subscribe_seldiko" name="subscribe_seldiko" style="width:16px;margin-top:7px" />
						</div>
						<?php endif; ?>

						<div>
							<input type="submit" value="Добавить" />
						</div>
					</form>
				</div>
		    </div>
	</div>
</div>
<?php $this->endblock('content') ?>