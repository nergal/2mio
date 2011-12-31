<!--отзывы о косметике-->
<script type="text/javascript">
$(function() {

    var cosmeticscroller = $("#mycarousel").scrollable({
        items: ".slide-wrapper",
        next: '.forward',
        prev: '.backward',
        keyboard: false //отключаем управление с клавиатуры (жалобы что скроллится вся страница по KEY_UP & KEY_DOWN)
    });
});
</script>

<?php if ($this->type == 'small'): ?>

<!--<style type="text/css">
.skin-tango {
	margin-left: -8px;
}
.skin-tango .slide-wrapper .slide {
	width: 130px;
}
</style>-->
<?php endif; ?>

<div>
    <h1 class="roundback small-caps">отзывы о косметике</h1>
    <div class="adv" >
	<!-- container for the slides -->
		<!-- "previous slide" button -->
		<a class="backward">prev</a>
		<div id="mycarousel" class="skin-tango images">
			<div class="slide-wrapper">
			 	<?php while ( ! empty($this->images)): ?>
				<div class="rel slide">
					<?php 
						$limit = ($this->type == 'small') ? 1 : 4;
						$_images = array_splice($this->images, 0, $limit); ?>
					<?php foreach ($_images as $key => $item): ?>
					<div class="left<?php echo ($key == (count($_images) - 1)) ? ' last' : ''; ?>">
						<a href="<?php echo $this->uri($item->cosmetic) ?>" class="none"><img src="<?php echo $this->photo($item->cosmetic, '122x122') ?>" alt="" /></a>
						<a href="<?php echo $this->uri($item->cosmetic) ?>" class="none visited"><?php echo text::limit_chars(Helper::filter($item->title), 30) ?></a>
					</div>
					<?php endforeach ?>
				</div>
				<?php endwhile; ?>
			</div>
	  	</div>
	  	<!-- "next slide" button -->
		<a class="forward">next</a>
	</div>
</div>
<!-- Конец отзывы о косметике-->
