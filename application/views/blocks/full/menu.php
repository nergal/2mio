<div class="topmenu">
	<div class="menu1">
		<ul>
			<?php
				$items = array();
				$model = ORM::factory('menu');
				// $active = $this->id;
				$active = NULL;
			?>
			<li class="color0"><a href="<?php echo URL::base() ?>"<?php echo (($active === NULL) ? ' class="current"' : '') ?>><img src="/i/home.png" alt="home"></a></li>
			<?php foreach ($this->main as $key => $item): ?>
				<li class="drop color<?php echo intVal($key) + 1; ?><?php echo ($item == end($this->main)) ? ' last' : '' ?>"><?php echo $this->link($item, NULL, (($active == $item->id) ? array('class' => 'active') : NULL)) ?>
				<?php
				    $items = $model->get_tree($item->id);
				?>
		<?php if ($items): ?>
		<ul class="hidden">
				<?php foreach ($items as $key => $subitem): ?>
					<li<?php echo (($subitem == end($items)) ? ' class="last"' : '') ?>><?php echo $this->link($subitem) ?></li>
				<?php endforeach; ?>
		</ul>
		<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
		<span class="m1-l"></span>
		<span class="m1-r"></span>
	</div>
	<div class="menu2">
		<ul class="color0">
			<li class="first"><a href="http://forum.bt-lady.com.ua/">Форум</a></li>
			<li class="none"><a href="/horoscope/<?php // echo URL::site(Route::get('category')->uri(array('category' => 'horoscope'))); ?>">Гороскопы</a></li>
			<li class="none"><a href="<?php echo URL::site(Route::get('category')->uri(array('category' => 'wiki/dreambook'))); ?>">Сонник</a></li>
			<li class="none"><a href="<?php echo URL::site(Route::get('category')->uri(array('category' => 'wiki/sex'))); ?>">Энциклопедии</a></li>
			<li class="none"><a href="<?php echo URL::site(Route::get('category')->uri(array('category' => 'cosmetik-opinions'))); ?>">Косметичка</a></li>
			<li class="none"><a href="/consult/?aux_page=aux_consult.html">Консультации</a></li>
			<li class="none"><a href="http://forum.bt-lady.com.ua/blog.php">Блоги </a></li>
			<li class="none"><a href="/video">Видео</a></li>
			<li class="none last"><a href="/cat-fashion/nedelya-mody/" style="color:#AA304B;font-weight:bold;">Неделя моды</a></li>
		</ul>
	</div>
</div>
