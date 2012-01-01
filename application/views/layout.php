<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<!--[if IE]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"><![endif]-->
<!--[if !IE]><!--><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml"><!--<![endif]-->
	<head>
		<meta name="verify-reformal" content="6b69faa3ecfd6caf74d7780c" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<?php
			$this->block('meta');
			echo Meta::render_meta();
			$this->endblock('meta');
		?>
		
		<script type="text/javascript" src="/js/adriver.core.2.js"></script>
		<script language="JavaScript">var N = 3;var ar_bn1= Math.floor(Math.random()*N+1);</script>

		<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?34"></script>
		<script type="text/javascript">VK.init({apiId: <?php echo Kohana::$config->load('oauth.vkontakte.key') ?>, onlyWidgets: true});</script>

		<?php echo Asset::render(); ?>
	</head>
<body>

    <?php echo $this->banner('jstop'); ?>


<div class="wrapper" id="wrapper">
	<div class="limiter">
		<div class="header">
			<div class="logosection">

				<div class="logo"><a href="<?php echo URL::base(TRUE) ?>"><img src="/i/logo.png" alt="hochu.ua" /></a></div>
				<div class="banner728x90"><?php echo $this->banner('728x90'); ?></div>

			</div>
			<div class="clear"></div>

			<?php
				$cache_instance = Cache::instance('memcache');
				if ( ! ($cached_menu = $cache_instance->get('main_menu')))
				{
					$cached_menu = $this->get_blocks(array('menu'), 'full');
				 	$cache_instance->set_with_tags('main_menu', $cached_menu, 0, array('menu_main'));
				}

				echo $cached_menu;
			?>

		</div>
		<div class="clear"></div>
		<div class="container">

			<?php if (empty($this->no_right)):  ?>
				<div class="content">
					<div class="button-ny">
						<a href="http://ny.bt-lady.com.ua"><img src="/i/newyear/ny_knopka.png" alt="спецпроект Новый год" /></a>
					</div>
			<?php else: ?>
				<div class="content" style="margin-right: 60px !important;">
			<?php endif; ?>
					<?php $this->block('content') ?>
					<?php $this->endblock('content') ?>
			</div>

		</div>

		<?php if (empty($this->no_right)):  ?>
			<div class="rightcol">
				<form class="search" action="http://google.com.ua/search" method="GET" onsubmit="$('input[name=q]').val('site:<?php echo $_SERVER['HTTP_HOST']?> '+$('form.search input.input').val())">
					<input type="hidden" name="q" />
					<?php if (Auth::instance()->logged_in() AND $user = Auth::instance()->get_user()): ?>
						<div class="login-status line">
							<div class="unit">Вы вошли как <?php echo $this->link($user); ?></div>
							<!-- <a class="button unit" href="/logout/">выйти</a> -->
							<div class="left">
							    <a href="/logout/" class="user_exit">Выход</a>
							</div>
							<?php if (Auth::instance()->logged_in('admin') OR Auth::instance()->logged_in('moderator')): ?>
							<a class="button unit red" href="/admin/" style="float:left">админка</a>
							<?php endif ?>
						</div>
						<input type="text" class="input wide" value="Введите поисковый запрос" onblur="if (this.value==''){this.value='Введите поисковый запрос';this.style.color='#999999';}" onfocus="this.value='';this.style.color='#454545';" />
					<?php else: ?>
						<!--
						<div class="right">
						    <input type="submit" value="Поиск" />
						</div>
						-->
				
						<a id="auth-button" rel="#overlay" href="/login/" class="socnetlink"></a>
						<input type="text" class="input" value="Введите поисковый запрос" onblur="if (this.value==''){this.value='Введите поисковый запрос';this.style.color='#999999';}" onfocus="this.value='';this.style.color='#454545';" />					
					<?php endif ?>
				</form>

				<div class="banner300x250">
					<?php echo $this->banner('300x250'); ?>
				</div>

			    <?php $this->block('advert') ?>
					<?php
						if (empty ($this->no_right))
						{
							if (strlen($_SERVER['REQUEST_URI'])>1) {
								echo $this->get_blocks(array('media' => array('media' => 'photo')), 'right');
								echo $this->get_blocks(array('media' => array('media' => 'video')), 'right');
							}
							
							echo $this->get_blocks(array('contests','adverts','specprojects'), 'right');
							
							if ( ! isset($this->index)) 
							{
								echo $this->get_blocks(array('informers' => array('name' => 'marketgid')), 'right');
								echo $this->get_blocks(array('informers' => array('name' => 'redtram')), 'right');
								echo $this->get_blocks(array('informers' => array('name' => 'meta')), 'right');
								echo $this->get_blocks(array('informers' => array('name' => 'aif')), 'right');
							}
							
							if(strlen($_SERVER['REQUEST_URI'])>1) // если внутренняя старница
							{
							    echo $this->get_blocks(array('social', 'horoscope', 'top'), 'right');
							} else {
							    echo $this->get_blocks(array('links', 'social', 'horoscope', 'top'), 'right');
							}
						}
					?>
			    <?php $this->endblock('advert') ?>
			</div>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
</div>

<div class="clear"></div>
<div class="footer">
	<div class="limiter">
		<table  class="footerlinks">
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		<div class="copyright">
			<div class="left">
				&copy; 2005-2011, <a href="http://bt-lady.com.ua" class="ssmall-caps black none">bt-lady.com.ua</a>
				<div class="counters">
				    <?php echo $this->banner('analitics'); ?>
				    <?php echo $this->banner('counters'); ?> 
				</div>
				<ul>
					<li><a href="/page-partners/" class="ssmall-caps none">рекламодателям</a></li>
					<li><a href="/page-about/" class="ssmall-caps none">о нас</a></li>
					<li><a href="mailto:lady@bt-lady.com.ua" class="ssmall-caps none">написать письмо</a></li>

				</ul>
			</div>
			<div class="right">Авторские права статей защищены в соответствии с ЗУ об авторском праве. Использование материалов в интернете возможно только с указанием гиперссылки на портал, открытой для индексации. Использование материалов в печатных изданиях возможно только с письменного разрешения редакции.</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="apple_overlay" id="overlay">
		<div class="contentWrap"></div>
	</div>

</div>
	<?php echo $this->banner('js-place'); ?>

	<?php echo $this->get_blocks('ask', 'full'); ?>

</body>

</html>
