<?php $this->extend('layout'); ?>

<?php $this->block('meta') ?><title><?php echo htmlentities($this->video->title, ENT_COMPAT, 'UTF-8') ?></title><?php $this->endblock('meta') ?>

<?php $this->block('content') ?>
	<div class="content-wrapper">
		<div class="breadcrumbs-container-wide">
			<div class="breadcrumbs">
				<?php
					$section = $this->video->section;
					$breadcrumbs = array('<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span>');
					$breadcrumbs[] = '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'videogallery/">Видеогалерея</a></span>';
					while ($section->loaded()) {
						$breadcrumbs[] = $this->link_bread($section);
						$section = $section->section;
					}
					$breadcrumbs[] = '<span typeof="v:Breadcrumb">'.$this->video->title.'</span>';
					echo '<div xmlns:v="http://rdf.data-vocabulary.org/#">';
					echo implode(' / ', $breadcrumbs);
					echo '</div>';
				?>
			</div>
		</div>
		<div class="bread-fade"></div>
		<div class="rel">
			<h1 class="top3"><?php echo $this->video->title ?></h1>
			<div class="line">
				<div class="left date"><?php echo Date::formatted_time($this->video->date, 'd.m.Y') ?>&nbsp;</div>

				<?php if (isset($this->favorite) AND Auth::instance()->logged_in()): ?>
					<div class="viewcom viewcom_top" style="width:150px;height:20px;">
					<?php if ( ! $this->favorite->loaded()): ?>
						<a class="right notepad tonotepad" name="<?php echo $this->video->id; ?>"><span> + сохранить в блокнот</span></a>
					<?php else: ?>
						<a class="right notepad fromnotepad" name="<?php echo $this->video->id; ?>"><span> - из блокнота</span></a>
					<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="viewcom right">
					<?php if (isset($this->video->views_count) AND isset($this->video->comments_count)): ?>
					<span class="view"><span class="gray1">&nbsp;<?php echo intVal($this->video->views_count).' '.$this->plural(intVal($this->video->views_count), 'просмотр', 'просмотра', 'просмотров'); ?></span>&nbsp;</span>
					<a class="comment" href="#">&nbsp;<?php echo intVal($this->video->comments_count).' '.$this->plural(intVal($this->video->comments_count), 'комментарий', 'комментария', 'комментариев'); ?></a>
					<?php endif; ?>
				</div>
			</div>

			<div class="top4"><?php echo $this->video->description ?></div>

			<div class="rel">
			<div class="photo-box video-box">
				<div class="skin-photo">
					<div class="player" href="<?php echo $this->video($this->video) ?>" style="background-image:url(<?php echo $this->photo($this->video, '500x400'); ?>)">
						<img src="/i/play_90x90.png" alt="Play this video" />
						<script type="text/javascript">
							var options = {
								plugins: {
									controls: {
										backgroundColor: '#000',
										backgroundGradient: 'low',
										all: false,
										scrubber: true,
										play: true,
										fullscreen: true,
										volume: true,
										height: 30,
										sliderColor: '#333333',
										progressColor: '#999999',
										bufferColor: '#666666',
										autoHide: true
									}
								}
							};
							flowplayer("div.player", "http://releases.flowplayer.org/swf/flowplayer-3.2.5.swf", options);
						</script>
					</div>

					<img src="<?php echo $this->photo($this->video, '500x400', 'cropr') ?>">
				</div>
			</div>
			</div>

			<div class="data">
				<?php echo $this->get_blocks(array('like' => array('title' => $this->video->title, 'url' => $this->uri($this->video))), 'inner'); ?>
			</div>
			<div><?php echo $rating; ?></div>
			<p class="data">Автор: <?php echo $this->video->get_user_link() ?></p>

			<?php echo $comments; ?>
		</div>
	</div>
<?php $this->endblock('content') ?>
