<!--Гороскоп-->
<?php if ( ! empty($this->horo)): ?>
<div class="box horo">
	<h3 class="ssmall-caps">ваш гороскоп на сегодня &mdash; <?php echo Date::formatted_time('now', 'd M') ?></h3>
	<div class="horo">
		<div>
			<div class="left">
				<div class="picture <?php echo $this->horo_name ?>"></div>
				<span><?php echo $this->horo[$this->horo_name]['date_start'].'<br />'.$this->horo[$this->horo_name]['date_end']; ?></span>
			</div>
			<div class="text">
				<p class="top"><?php echo Helper::filter($this->horo[$this->horo_name]['title']) ?></p>
				<?php echo text::limit_chars(Helper::filter($this->horo[$this->horo_name]['text']), 100) ?>
				<a href="/horoscope/#<?php echo $this->horo_name ?>">Подробнее</a>
			</div>
			<ul class="zodiak">
				<?php foreach ($this->horo as $key => $value): ?>
					<li><a href="/horoscope/#<?php echo $key ?>" id="<?php echo $key ?>" ></a></li>
				<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php endif ?>
<!--Конец Гороскоп-->
