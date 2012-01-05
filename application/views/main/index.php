<?php $this->extend('layout') ?>

<?php $this->block('content') ?>
<!--Большая каруселька-->
	<?php echo $this->get_blocks('tv', 'inner'); ?>
<!--Конец Большая каруселька-->
<?php $this->endblock('content'); ?>
