<?php $url = URL::site(Route::get('category')->uri(array('category' => 'recommends'))); ?>
<style>
	a.recommends {text-decoration: none; color: #353535;}
	a.recommends:hover {text-decoration: underline;}
</style>
<div class="box">
	<a href="<?php echo $url; ?>" class="recommends"><h2 class="small-caps logo">рекомендует</h2></a>

	<?php foreach ($this->data as $item): ?>
	<div class="rel white">
		<a href="<?php echo $this->uri($item)?>"><img src="<?php echo $this->photo($item, '123x80') ?>" width="123" height="80" alt="<?php echo Helper::filter($item->title) ?>" /></a>
		<?php echo $this->link($item) ?>
	</div>
	<?php endforeach ?>
</div>
