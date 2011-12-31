<?php $this->extend('layout') ?>

<?php $this->block('content') ?>
    <div class="content-wrapper">
	<div class="rel error410">
	    <h1 class="top3">Регистрация и авторизация на сайте временно закрыта</h1>

	    <div class="top4">
    	    <?php if (isset($message) AND ! empty($message)): ?>   
    		<p><?php echo $message; ?></p>
    	    <?php endif ?>
    		<p>К сожалению, на нашем сайте временно закрыта регистрация новых пользователей и вход старых пользователей на сайт. Для доступа к форуму, Вы можете воспользоваться <a href="http://forum.bt-lady.com.ua/ucp.php?mode=login">форумной формой входа</a>.</p>
    		<br />
    		
    		<p>Мы просим прощение за доставленные неудобства.</p>
	    </div>
	</div>
   </div>
<?php $this->endblock('content') ?>
