<div class="box-shad col2" >
	<div class="stylehead var1">
		<h1 class="small-caps"><?php echo $this->section->name ?></h1>
	</div>
	<?php foreach ($this->items as $item): ?>
	<div class="col2 left">
		<img src="http://static.flickr.com/57/199481087_33ae73a8de_s.jpg" alt="<?php echo Helper::filter($item->title); ?>" class="shad" />
					<a href="<?php echo $this->uri($item) ?>" class="none"><h2 class="top"><?php echo Helper::filter($item->title) ?></h2></a>
					<p><?php echo Text::limit_chars(Helper::filter($item->description)) ?></p>
	</div>
	<?php endforeach ?>
	<br />
	<p align="right"><?php echo $this->link($this->section, 'все статьи раздела') ?></a></p>
	<div class="clear"></div>
</div>
<div class="clear"></div>
