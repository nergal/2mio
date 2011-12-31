<!--Новый блок с каруселью-->
<?php if ( ! empty($this->data)): ?>
<?php $js_id = 'informer_'.uniqid(); ?>
<div class="box">
	<h2 class="small-caps logo logo2"><?php echo ($this->is_photo ? 'Фото' : 'Видео') ?></h2>
	<div class="slidetabs_s_p_v navi">
		<?php foreach (range(1, count($this->data)) as $i): ?>
		<a href="#" rel="<?php echo intval($i - 1) ?>"<?php ($i !== 1) ?: print(' class="active"') ?>>&nbsp;</a>
		<?php endforeach ?>
	</div>
	<div id="<?php echo $js_id ?>" class="wrapper_s_p_v" >
		<div id="slider_photo_video" class="s_p_v">
			<ul class="list">
			<?php foreach ($this->data as $item): ?>
				<?php $title = Helper::filter($item->title) ?>
				<li class="item">
					<a class="s_p_v-img"  href="<?php echo $this->uri($item); ?>">
						<img src="<?php echo $this->photo($item, '260x155') ?>" width="260" height="155" alt="<?php echo $title ?>" /> 
						<?php if ( ! $this->is_photo): ?>
						<div class="play"></div>
						<?php endif ?>
					</a>
					<p class="text"><a href="<?php echo $this->uri($item); ?>"><?php echo $title ?></a></p>
				</li>
			<?php endforeach ?>
				</ul>
		</div>
		<a class="prev-video">prev</a>
		<a class="next-video">next</a>
	</div>	
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#<?php echo $js_id ?>").scrollable({
        items: ".list",
        keyboard: false,
        circular: true,
        next: 'a.next-video',
        prev: 'a.prev-video'
    }).navigator().autoscroll({
		interval: 7500		
	});
})
</script>
<?php endif ?>
<!--Конец новый блок с каруселью-->
