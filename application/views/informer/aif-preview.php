<?php $i=1; foreach ($this->informers as $informer): ?>
<?php if ($i > 3) break; ?>
<div class="informers_item">
	<div class="informers_image">
		<a target="_blank" href="<?php  echo rtrim(URL::base(TRUE), '/') . $this -> uri($informer -> page);?>"><img src="<?php echo rtrim(URL::base(TRUE), '/').$this->photo($informer->page, '99x77').'?'.md5(date('YmdH')) ?>" /></a>
	</div>
	<div class="informers_name">
		<a target="_blank" href="<?php  echo rtrim(URL::base(TRUE), '/') . $this -> uri($informer -> page);?>"><?php if(trim($informer->title) != ''): ?><?php  echo Helper::filter($informer -> title);?><?php  else:?><?php  echo Helper::filter($informer -> page -> title);?><?php  endif;?></a>
	</div><div class="informers_clear"></div>
</div>
<?php $i=++$i; endforeach; ?>
