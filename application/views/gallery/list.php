<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Список галерей<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<div class="content-wrapper">
			<div class="breadcrumbs-container-wide">
				<div class="breadcrumbs">
					<div xmlns:v="http://rdf.data-vocabulary.org/#">
					<?php 
						echo '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span> / ';
						echo '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'gallery/">Фотогалерея</a></span> / ';
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
				
					<p align="right">
					<?php if ($this->gallery->votes_active == 1): ?>
				    <span class="gallery-add"><a href="<?php echo $this->uri($this->gallery, array('add' => TRUE)) ?>">Добавить</a></span>
			
					
					<?php endif; ?>
					</p>	
					<div class="description"><?php echo Helper::filter($this->gallery->description, Helper::BODY) ?></div>
					<?php while ( ! empty($this->photos)): ?>
					<?php $photos = array_splice($this->photos, 0, 3); ?>
					<div class="theme-gallery">
						<?php foreach ($photos as $photo): ?>
						<div class="left col3">
							<div class="pic-theme-tab">
								<div class="tab-left"></div>
								<div class="tab-center ssmall-caps"><?php echo $photo->get_user_link() ?></div>
								<div class="tab-right"></div>
							</div>
							<div>
							  <?php echo $this->link($photo, '<img src="'.$this->photo($photo, '170x110').'" class="pic-theme-img" width="170" height="110" />', array('class' => 'pic-theme-link')); ?>
					
							</div>
							<div class="date1"><?php echo Date::formatted_time($photo->date, 'd.m.Y') ?></div>
							<div class="theme-short"><?php echo $this->link($photo, NULL, array('class' => 'none')); ?></div>
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
