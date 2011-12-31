<?php $this->extend('layout') ?>

<?php $this->block('content') ?>
				<div class="maintext">
					<div class="breadcrumbs-container">
						<div class="breadcrumbs">
							<div xmlns:v="http://rdf.data-vocabulary.org/#">
							<?php 
								echo '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span> / ';
								echo '<span typeof="v:Breadcrumb">Гороскопы</span>';
							?>
							</div>
						</div>
					</div>
					<div class="bread-fade"></div>
					<h1 class="top1">Все гороскопы на сегодня &mdash; <?php echo Date::formatted_time('now', 'd M') ?></h1>

					<!--Новости-->
					<div class="rel horoscope">
						<?php $links = array(); ?>
						<?php foreach ($this->signs as $alias => $page): ?>
						<?php $links[$alias] = mb_convert_case($page['title'], MB_CASE_TITLE); ?>
						<div id="<?php echo $alias ?>" class="contentnews">
							<div class="left image"></div>
							<div class="text">
								<h2 class="top"><?php echo Helper::filter($page['title']) ?></h2>
								<p class="small darkgray"><?php echo $page['date_start'] ?> / <?php echo $page['date_end'] ?></p>
								<?php echo Text::auto_p($page['text']); ?>
							</div>
							<div class="clear"></div>
						</div>
						<?php endforeach ?>
					</div>
					<!--Конец новостям-->
				</div>
<!--Конец основного текста-->
<?php $this->block('sidebar') ?>
<!--Маленькая левая колонка-->
				<div class="minileft">
<!--sidemenu-->
				<h2 class="top"> &nbsp; Гороскоп</h2>
				<div class="sidemenu">
					<ul>
						<?php foreach ($links as $id => $name): ?>
							<li><a href="#<?php echo $id ?>"><?php echo $name ?></a></li>
						<?php endforeach ?>
					</ul>
					<div class="bott_sm"></div>
				</div>
<!--end sidemenu-->

<!--Здоровье, фитнес, отдых, шоппинг-->
				<div class="rel">
					<?php // echo $this->get_blocks(array('section' => array('id' => '106'), 'section' => array('id' => '106')), 'left') ?>
					<div class="clear"></div>
				</div>
<!--Конец Здоровье, фитнес, отдых, шоппинг-->

<!--Конец отзывы о косметике-->
				<?php echo $this->get_blocks(array('cosmetic'), 'left'); ?>

				<div class="clear"></div>
			</div>
<?php $this->endblock('sidebar') ?>
<?php $this->endblock('content') ?>
