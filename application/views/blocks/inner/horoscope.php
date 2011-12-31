<?php if ($this->enabled === TRUE): ?>
<div class="right userhoro horo box" style="width:145px;height:189px;">
	<h3 class="ssmall-caps">Мой гороскоп</h3>
	<div class="picture libra left"></div>

	<p class="top"><?php echo Helper::filter($this->horo[$this->horo_name]['title']) ?></p>

	<?php
		$date = array();
		foreach(array('date_start', 'date_end') as $key) {
			list($day, $month) = explode(' ', $this->horo[$this->horo_name][$key]);
			$date[] = $day.'.'.sprintf('%02d', __($month));
		}
		$date = implode(' - ', $date);
	?>
	<span class="gray small"><?php echo $date; ?></span>

	<div class="clear"></div>
	<div class="text">
		<p class="small" style="height:90px"><?php echo Helper::filter(Text::limit_chars($this->horo[$this->horo_name]['text'], 100)) ?></p>
		<a href="/horoscope/#<?php echo $this->horo_name ?>">Подробнее</a>
	</div>
</div>
<?php else: ?>
<div class="right userhoro horo box" style="width:145px;height:189px;">
	<h3 class="ssmall-caps">Мой гороскоп</h3>

	<div class="clear"></div>
	<div class="text" style="background-position:-3px 124px;">
		<p class="small" style="height:150px;">Чтобы включить твой личный гороскоп, <a href="/user/edit/">заполните дату рождения в настройках</a></p>
	</div>
</div>
<?php endif ?>
