<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Это тестовый заголовок<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
	<div style="padding-left:15px;">
			<div class="breadcrumbs-container">
				<div class="breadcrumbs">
					<div xmlns:v="http://rdf.data-vocabulary.org/#">
					<?php 
						echo '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span> / ';
						echo '<span typeof="v:Breadcrumb">Список всех разделов</span>';
					?>
					</div>
				</div>
			</div>
			<div class="bread-fade"></div>
		<div class="rel">
			<?php foreach ($this->categories as $section): ?>
			<?php
				$selected = ORM::factory('article')->get_tree($section->id, 1, 8);
				if (empty ($section->picture))
				{
				    $picture = 'http://static.flickr.com/75/199481072_b4a0d09597_s.jpg';
				} else {
				    $picture = '/i/categories/'.$section->picture;
				}
			?>
			<div class="contentnews allnews">
				<div class="shad left"><img src="<?php echo $picture;?>" alt="" /></div>
				<div class="text">
					<a href="<?php echo $this->uri($section) ?>" class="none"><h1 class="top1 darkred"><?php echo $section->name ?></h1></a>
					<ul class="news">
						<?php foreach ($selected as $item): ?>
						<li>
							<a href="<?php echo $this->uri($item) ?>" class="none visited" ><?php echo $item->title; ?></a>
						</li>
						<?php endforeach ?>
					</ul>

					<div class="right"><a href="<?php echo $this->uri($section) ?>" class="small none"><span class="darkred">все статьи раздела &laquo;<?php echo $section->name ?>&raquo;</span></a></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php $this->endblock('content') ?>
