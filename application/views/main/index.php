<?php $this->extend('layout') ?>

<?php $this->block('content') ?>
<!--Большая каруселька-->
					<?php echo $this->get_blocks('tv', 'inner'); ?>
<!--Конец Большая каруселька-->

<!--Здоровье, фитнес, отдых, шоппинг-->
					<?php echo $this->get_blocks('starsfashion', 'double'); ?>
<!--Конец Здоровье, фитнес, отдых, шоппинг-->
<!--Видео-->
					<?php echo $this->get_blocks('video', 'double'); ?>
<!--Конец Видео-->
<!--Секс, отношения, стиль, мода -->
					<?php echo $this->get_blocks('beautysex', 'double'); ?>
<!--Конец Секс, отношения, стиль, мода-->
					<?php echo $this->get_blocks('cosmetic', 'double'); ?>
<!--Форум и блоги-->
				<div class="rel">
					<?php echo $this->get_blocks('forum', 'left'); ?>
					<?php echo $this->get_blocks('blog', 'inner'); ?>
				</div>					
<!--Конец Форум и блоги-->
<?php $this->endblock('content'); ?>
