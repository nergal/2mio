<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Это тестовый заголовок<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
				<div class="maintext">
					<?php if ( ! isset($this->tag)): ?>
					<div class="breadcrumbs-container">
						<div class="breadcrumbs">
							<?php
								$section = $this->section;
								$breadcrumbs = array('<span typeof="v:Breadcrumb">'.$section->name.'</span>');
								while ($section->parent_id !== NULL) {
									$section = $section->parent;
									$breadcrumbs[] = $this->link_bread($section);
								}
	
								$breadcrumbs[] = '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span>';
								$breadcrumbs = array_reverse($breadcrumbs);
	
								echo '<div xmlns:v="http://rdf.data-vocabulary.org/#">';
								echo implode(' / ', $breadcrumbs);
								echo '</div>';
							?>
						</div>
					</div>
					<div class="bread-fade"></div>
					<?php endif ?>

				<?php if ( ! isset($this->tag)): ?>
					<?php if ($this->section->name_url != 'all'): ?>
						<h1 class="top1">Все статьи раздела &laquo;<?php echo Helper::filter($this->section->name) ?>&raquo;</h1>
					<?php else: ?>
						<h1 class="top1">Все статьи</h1>
					<?php endif; ?>
				
					<?php if ($this->wiki and $this->order == 'abc'): ?>
						<?php echo $abc; ?>
					<?php elseif ( ! $this->wiki): ?>
						<p class="darkgray small">
							Отсортированы по: &nbsp;
							<?php foreach ($orders as $order => $name): ?>
								<?php $url = URL::site(Route::get('category')->uri(array('category' => $this->section->name_url, 'order' => $order, 'period' => $this->period))); ?>
								<a href="<?php echo $url ?>"<?php echo (($order == $this->order) ? ' class="black none strong"' : ' class="none"'); ?>><?php echo $name ?></a> &nbsp;
							<?php endforeach; ?>
						</p>
						<p class="darkgray small">
							<?php foreach ($periods as $period => $name): ?>
								<?php $url = URL::site(Route::get('category')->uri(array('category' => $this->section->name_url, 'order' => $this->order, 'period' => $period))); ?>
								<a href="<?php echo $url ?>"<?php echo (($period == $this->period) ? ' class="black none strong"' : ' class="none"'); ?>><?php echo $name ?></a>&nbsp;
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				<?php else: ?>
				
					<h1 class="top1">Все статьи по тегу &laquo;<?php echo Helper::filter($this->tag) ?>&raquo;</h1>
					
				<?php endif ?>
					<!--Новости-->
					<div class="rel">
						<?php foreach ($this->pages as $page): ?>
						<div class="contentnews">
							<div class="shad left"><a href="<?php echo $this->uri($page) ?>#img"><img src="<?php echo $this->photo($page, '135x100').'?'.md5(date('YmdH')) ?>" alt="" width="135" height="100" /></a></div>
							<div class="text">
								<a href="<?php echo $this->uri($page); ?>" class="none visited"><h2 class="top"><?php echo Helper::filter($page->title) ?></h2></a>
								<p class="small darkgray"><?php echo Date::fuzzy_span($page->date) ?> / <?php echo $this->link($page->section, NULL, array('class' => 'none')) ?></p>
								<?php echo Helper::filter($page->description); ?>

								<div class="viewcom">
									<?php if (isset($page->views_count) AND isset($page->comments_count)): ?>
									<?php if ($page->views_count > 0): ?><a class="view"><?php echo Helper::space_digit($page->views_count); ?></a>&nbsp;<?php endif; ?>
									<?php if ($page->comments_count > 0): ?><a class="comment"><?php echo Helper::space_digit($page->comments_count); ?></a><?php endif; ?>
									<?php endif ?>

									<?php if (Auth::instance()->logged_in('login') AND isset($this->favorite)): ?>
										<?php if ( ! in_array($page->id, $this->favorite)): ?>
											<a class="right notepad tonotepad" name="<?php echo $page->id; ?>"><span> + сохранить в блокнот</span></a>
										<?php else: ?>
											<a class="right notepad fromnotepad" name="<?php echo $page->id; ?>"><span> - из блокнота</span></a>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<?php endforeach ?>

						<?php
							if (isset($this->pager)) {
								echo $this->pager;
							}
						?>
					</div>
					<!--Конец новостям-->

					<!--Здоровье и фитнес-->
<!--					<div class="rel">
						<div class="box-shad col3 left">
							<div class="stylehead var1">
								<h2 class="small-caps">здоровье и фитнес</h2>
								<a href="/link">все материалы</a>
							</div>
							<div class="col2 left">
								<img src="http://static.flickr.com/77/199481108_4359e6b971_s.jpg" alt="" class="shad" />
								<a href="/test.html" class="none"><h2 class="top">Заголовок большой новости </h2></a>
								<p>Текст новости, о том, как космические корабли бороздят просторы большого театра</p>

							</div>
							<div class="col2 left">
								<img src="http://static.flickr.com/77/199481108_4359e6b971_s.jpg" alt="" class="shad" />
								<a href="/test.html" class="none"><h2 class="top">Заголовок большой новости </h2></a>
								<p>Текст новости, о том, как космические корабли бороздят просторы большого театра</p>
							</div>
							<div class="col2 left">
								<img src="http://static.flickr.com/77/199481108_4359e6b971_s.jpg" alt="" class="shad" />
								<a href="/test.html" class="none"><h2 class="top">Заголовок большой новости </h2></a>
								<p>Текст новости, о том, как космические корабли бороздят просторы большого театра</p>
							</div>
						</div>
						<div class="clear"></div>
					</div>
-->
					<!--Конец здоровью и фитнесу-->

<?php if(isset($this->zpixel) && $this->zpixel != ''): ?>	
<?php echo $this->zpixel; ?>
<?php endif; ?>					
					
				</div>
<!--Конец основного текста-->
<?php $this->block('sidebar') ?>
<!--Маленькая левая колонка-->
				<div class="minileft">
<?php if ( ! isset($this->tag)): ?>
<!--sidemenu-->
				<?php
					$section = $this->section;
					$data = array();
					while ( ! $data) {
						$data = $section->section->where('sections.showhide', '=', 1)->order_by('order')->find_all()->as_array();

						if ( ! $section->parent_id) {
							break;
						}
						if ( ! $data) {
							$section = $section->parent;
						}
					}
				?>
				<h2 class="top"> &nbsp; <?php echo Helper::filter($section->name) ?></h2>
				<div class="sidemenu">
					<ul>
						<?php foreach ($data as $item): ?>
							<?php $class = ($item->id == $this->section->id) ? array('class' => 'active') : NULL; ?>
							<li><?php echo $this->link($item, NULL, $class) ?></li>
						<?php endforeach ?>
					</ul>
					<div class="bott_sm"></div>
				</div>
<!--end sidemenu-->
<?php endif ?>

<!--Здоровье, фитнес, отдых, шоппинг-->
				<div class="rel">
					<?php // echo $this->get_blocks(array('section' => array('id' => '106'), 'section' => array('id' => '106')), 'left') ?>
					<div class="clear"></div>
				</div>
<!--Конец Здоровье, фитнес, отдых, шоппинг-->

<!--Конец отзывы о косметике-->
				<?php echo $this->get_blocks(array('cosmetics', 'cloud'), 'left'); ?>

				<div class="clear"></div>
			</div>
<?php $this->endblock('sidebar') ?>
<?php $this->endblock('content') ?>
