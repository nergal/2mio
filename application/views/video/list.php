<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Это тестовый заголовок<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<div class="content-wrapper">
		<div class="breadcrumbs-container-wide">
			<div class="breadcrumbs">
				<div xmlns:v="http://rdf.data-vocabulary.org/#">
				<?php 
					echo '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span> / ';
					echo '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'videogallery/">Видеогалерея</a></span> / ';
					echo '<span typeof="v:Breadcrumb">'.$this->gallery->name.'</span>';
				?>
				</div>
			</div>
		</div>
		<div class="bread-fade"></div>
		
		<div class="rel">
		<!-- gallery theme -->
			<div class="theme-gallery-container" style="width:640px;">
				<div class="tableft-white">
					<div class="lft"></div>
					<div class="cntr"><div class="text top3"><?php echo Helper::filter($this->gallery->name) ?></div></div>
					<div class="rght"></div>
				</div>
				<div class="box-shad no-top-left-radius">
					<div style="height:10px;">&nbsp;</div>
					<?php while ( ! empty($this->videos)): ?>
					<?php $videos = array_splice($this->videos, 0, 3); ?>
					<div class="theme-gallery">
						<?php foreach ($videos as $video): ?>
						<div class="left col3">
							<div>
								<?php echo $this->link($video, '<img src="'.$this->photo($video, '170x110').'" class="pic-theme-img" width="170" height="110" />', array('class' => 'none')); ?>
							</div>
							    
							<div class="date1"><?php echo Date::formatted_time($video->date, 'd.m.Y') ?> <span class="author"><?php echo $video->get_user_link() ?></span></div>
							<div class="theme-short"><?php echo $this->link($video, NULL, array('class' => 'none')); ?></div>
						</div>
						<?php endforeach; ?>
						<div class="clear"></div>
					</div>
					<?php endwhile; ?>

					<?php echo $this->pager ?>
				</div>
			</div>
		<!-- end:gallery theme -->
		</div>
</div>
<?php $this->endblock('content') ?>
