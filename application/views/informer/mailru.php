<?php 
// huge hack for timestamp
if ( ! function_exists("mailru_datetime")) {
	function mailru_datetime ($str)
	{

		list($date, $time) = explode(' ', $str);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);

		$mailru_date = $month.'.'.$year.'<b>|</b>'.$hour.':'.$minute;

		return $mailru_date;
	}	
}
?>

<img src="<?php echo URL::base(TRUE) ?>i/logo.png" width="120" height="50" alt="" class="title" />

<?php $i=1; foreach ($this->informers as $informer): ?>
	<?php if ($i == 1): ?>
		
		<div class="main">
			<a href="<?php echo rtrim(URL::base(TRUE), '/').$this->uri($informer->page); ?>"><img src="<?php echo rtrim(URL::base(TRUE), '/').$this->photo($informer->page, '70x50') ?>" width="70" height="50" alt="" /></a>
			<span class="date"><?php echo mailru_datetime($informer->page->date) ?></span>
			<a href="<?php echo rtrim(URL::base(TRUE), '/').$this->uri($informer->page); ?>"><?php echo rtrim($informer->title, ''); ?></a>
			<div class="clear"></div>
		</div>
		<ul>
		
	<?php else: ?>
	
		<li style="list-style-type: none;">
			<span class="date"><?php echo mailru_datetime($informer->page->date) ?></span>
			<a href="<?php echo rtrim(URL::base(TRUE), '/').$this->uri($informer->page); ?>"><?php echo rtrim($informer->title, ''); ?></a>
		</li>
	
	<?php endif; ?>
		
		</ul>

<?php $i += $i; endforeach; ?>

