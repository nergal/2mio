<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Это тестовый заголовок<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<div class="content-wrapper">
	
	<div class="breadcrumbs-container-wide">
		<div class="breadcrumbs">
			<div xmlns:v="http://rdf.data-vocabulary.org/#">
			<?php 
				echo '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span> / ';
				echo '<span typeof="v:Breadcrumb">Видеогалерея</span>';
			?>
			</div>
		</div>
	</div>
	<div class="bread-fade"></div>

	<div class="rel">
	<?php foreach ($this->galleries as $title => $galleries): ?>
	<!-- gallery -->
		<div class="content-gallery">
			<h1 class="top1"><?php echo $title; ?></h1>
			<?php foreach ($galleries as $first_page): ?>
			<div class="left col3">
				<div class="album">
					<?php echo $this->link($first_page->section, '<img src="'.$this->photo($first_page, '400x300').'" width="196" height="147" class="border" />', array('class' => 'none')); ?>
				</div>
				<div class="album-text"><?php echo $this->link($first_page->section, NULL, array('class' => 'none')); ?></div>
				<?php if (isset($first_page->views_count) AND isset($first_page->comments_count)): ?>
				<div class="viewcom">
					<a class="view"><?php echo intVal($first_page->views_count) ?></a>&nbsp;
					<a class="comment"><?php echo intVal($first_page->comments_count).' '.$this->plural(intVal($first_page->comments_count), 'комментарий', 'комментария', 'комментариев'); ?></a>
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
			<div class="clear"></div>
		</div>
	<?php endforeach; ?>
	</div>
</div>
<?php $this->endblock('content') ?>
