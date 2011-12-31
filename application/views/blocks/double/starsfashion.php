<?php if ($this->fashion->count() AND $this->stars->count()): ?>
<div class="rel">
	<div class="box-shad left col2">
		<div class="stylehead">
			<div class="block-tab">
				<a href="<?php echo $this->uri($this->stars_sec) ?>" class="none"><div class="left left-green"></div>
				<div class="center center-green"><h2 class="small-caps">звезды</h2></div>
				<div class="right right-green"></div></a>
			</div>
			<a href="<?php echo $this->uri($this->stars_sec) ?>" class="ssmall none">все материалы</a>
		</div>
		<?php foreach ($this->stars as $star_item): ?>						
			<div class="col2 left" style="height:235px; overflow: hidden;">
				<a href="<?php echo $this->uri($star_item) ?>" class="none"><img src="<?php echo $this->photo($star_item, '130x95') ?>" alt="" class="pic_shad" /></a>
					<div style="padding-right: 8px;">								
							<a href="<?php echo $this->uri($star_item) ?>" class="none visited"><h2 class="top"><?php echo Helper::filter($star_item->title) ?></h2></a>
							<p class="descr"><a href="<?php echo $this->uri($star_item) ?>"><?php echo Helper::filter($star_item->description) ?></a></p>
					</div>
			</div>
		<?php endforeach; ?>
		<div class="clear"></div>
	</div>
	<div class="box-shad right col2">
		<div class="stylehead">
			<div class="block-tab">
				<a href="<?php echo $this->uri($this->fashion_sec) ?>" class="none"><div class="left left-blue"></div>
				<div class="center center-blue"><h2 class="small-caps">стиль и мода</h2></div>
				<div class="right right-blue"></div></a>
			</div>
			<a href="<?php echo $this->uri($this->fashion_sec) ?>" class="ssmall none">все материалы</a>
		</div>
		<?php foreach ($this->fashion as $fashion_item): ?>
			<div class="col2 left" style="height:235px; overflow: hidden;">
				<a href="<?php echo $this->uri($fashion_item) ?>" class="none"><img src="<?php echo $this->photo($fashion_item, '130x95') ?>" alt="" class="pic_shad" /></a>
					<div style="padding-right: 8px;">
							<a href="<?php echo $this->uri($fashion_item) ?>" class="none visited"><h2 class="top"><?php echo Helper::filter($fashion_item->title) ?></h2></a>
							<p class="descr"><a href="<?php echo $this->uri($fashion_item) ?>"><?php echo Helper::filter($fashion_item->description) ?></a></p>
					</div>
			</div>
		<?php endforeach; ?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<?php endif; ?>
