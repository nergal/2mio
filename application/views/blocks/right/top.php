<style>
.unvisible {
  display: none;
}
</style>
<!--Завершающий таб-->
<div class="tops rel" style="width:312px;">
	<div class="tabs">
		<div class="tableft active ssmall-caps">самое популярное</div>
		<div class="tabright ssmall-caps">самое обсуждаемое</div>
	</div>
		<div class="box-shad">
			<div class="scroll" style="height:360px;">
				<ul>
					<?php $i = 1; foreach ($this->populars as $popular): ?>
						<li class="<?php echo ($i%2 === 0) ? "" : "anbg" ;?>" >
							<div class="shad left">
								<a href="<?php echo $this->uri($popular) ?>" class="none visited">
								<div class="bording">
									<img src="<?php echo $this->photo($popular, '90x65') ?>"  alt="" />
								</div>
								</a>
							</div>
							<div class="text"><a href="<?php echo $this->uri($popular) ?>" class="none visited"><?php echo Helper::filter($popular->title) ?></a>
								<p><?php echo Helper::filter($popular->description) ?></p>
							</div>
							<div class="viewcom">
								<a class="view"><?php echo Helper::space_digit($popular->views_count) ?></a>
								<?php if ($popular->comments_count > 0): ?><a class="comment"><?php echo Helper::space_digit($popular->comments_count) ?>&nbsp;</a><?php endif; ?>
							</div>
						</li>					
					<?php $i += 1; endforeach; ?>
				</ul>
			</div>
			<p align="right"><br/><a href="<?php echo URL::base(TRUE) ?>cat-all/order-views/" class="none ssmall">все самое популярное</a></p>
		</div>
		<div class="box-shad unvisible">
			<div class="scroll" style="height:360px;">
				<ul>
					<?php $i = 1; foreach ($this->discusses as $discuss): ?>
						<li class="<?php echo ($i%2 === 0) ? "" : "anbg" ;?>" >
							<div class="shad left" >
								<a href="<?php echo $this->uri($discuss) ?>" class="none visited">
								<div class="bording">
									<img src="<?php echo $this->photo($discuss, '90x65') ?>" alt="" />
								</div>
								</a>
							</div>
							<div class="text"><a href="<?php echo $this->uri($discuss) ?>" class="none visited"><?php echo Helper::filter($discuss->title) ?></a>
								<p><?php echo Helper::filter($discuss->description) ?></p>
							</div>
							<div class="viewcom">
								<a class="view"><?php echo Helper::space_digit($discuss->views_count) ?></a>
								<?php if ($discuss->comments_count > 0): ?><a class="comment"><?php echo Helper::space_digit($discuss->comments_count) ?>&nbsp;</a><?php endif; ?>
							</div>
						</li>					
					<?php $i += 1; endforeach; ?>
				</ul>
			</div>
			<p align="right"><br/><a href="<?php echo URL::base(TRUE) ?>cat-all/order-comments/" class="none ssmall">все самое обсуждаемое</a></p>
		</div>		
</div>
<script type="text/javascript">
(function($) {
$(function() {
  $('div.tabs').delegate('div:not(.active)', 'click', function() {
    $(this).addClass('active').siblings().removeClass('active')
      .parents('div.tops').find('div.box-shad').hide().eq($(this).index()).fadeIn(150);
  })
})
})(jQuery)	
</script>
