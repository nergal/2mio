<?php if ($this->beauty->count() AND $this->sex->count()): ?>
<div class="rel">
	<div class="box-shad left col2">
		<div class="stylehead">
			<div class="block-tab">
				<a href="<?php echo $this->uri($this->beauty_sec) ?>" class="none"><div class="left left-pink"></div>
				<div class="center center-pink"><h2 class="small-caps">красота</h2></div>
				<div class="right right-pink"></div></a>
			</div>
			<a href="<?php echo $this->uri($this->beauty_sec) ?>" class="ssmall none">все материалы</a>
		</div>
		<?php foreach ($this->beauty as $beauty_item): ?>
			<div class="col2 left" style="height:235px; overflow: hidden;">
				<a href="<?php echo $this->uri($beauty_item) ?>" class="none"><img src="<?php echo $this->photo($beauty_item, '130x95') ?>" alt="" class="pic_shad" /></a>
					<div style="padding-right: 8px;">
							<a href="<?php echo $this->uri($beauty_item) ?>" class="none visited"><h2 class="top"><?php echo Helper::filter($beauty_item->title) ?></h2></a>
							<p class="descr"><a href="<?php echo $this->uri($beauty_item) ?>"><?php echo Helper::filter($beauty_item->description) ?></a></p>
					</div>
			</div>
		<?php endforeach; ?>
		<div class="clear"></div>
	</div>
	<div class="box-shad right col2">
		<div class="stylehead">
			<div class="block-tab">
				<a href="<?php echo $this->uri($this->sex_sec) ?>" class="none"><div class="left left-gray"></div>
				<div class="center center-gray"><h2 class="small-caps">секс и отношения</h2></div>
				<div class="right right-gray"></div></a>
			</div>
			<a href="<?php echo $this->uri($this->sex_sec) ?>" class="ssmall none">все материалы</a>
		</div>
		<?php foreach ($this->sex as $sex_item): ?>						
			<div class="col2 left" style="height:235px; overflow: hidden;">
				<a href="<?php echo $this->uri($sex_item) ?>" class="none"><img src="<?php echo $this->photo($sex_item, '130x95') ?>" alt="" class="pic_shad" /></a>
					<div style="padding-right: 8px;">								
							<a href="<?php echo $this->uri($sex_item) ?>" class="none visited"><h2 class="top"><?php echo Helper::filter($sex_item->title) ?></h2></a>
							<p class="descr"><a href="<?php echo $this->uri($sex_item) ?>"><?php echo Helper::filter($sex_item->description) ?></a></p>
					</div>
			</div>
		<?php endforeach; ?>
		<div class="clear"></div>					
	</div>
	<div class="clear"></div>
</div>
<?php endif; ?>
