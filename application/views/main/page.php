<?php $this->extend('layout') ?>

<?php $this->block('content') ?>
<!--основной текст-->

	<div class="breadcrumbs-container">
		<div class="breadcrumbs">
			<div xmlns:v="http://rdf.data-vocabulary.org/#">
			<?php 
				echo '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span> / ';
				echo '<span typeof="v:Breadcrumb">'.Helper::filter($this->page->title).'</span>';
			?>
			</div>
		</div>
	</div>
	<div class="bread-fade"></div>
	
    <h1 class="top bold"><?php echo Helper::filter($this->page->title); ?></h1>
    <div class="article-content rel">
	<?php echo Helper::filter($this->page->body, Helper::BODY) ?>
    </div>
    <div class="data rel">
	<?php echo $this->get_blocks(array('like'), 'inner'); ?>
    </div>
<!--Конец основного текста-->
<?php $this->endblock('content') ?>
