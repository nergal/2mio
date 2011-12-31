<?php $this->extend('layout'); ?>

<?php $this->block('meta') ?>
    <title><?php echo htmlentities($this->photo->title, ENT_COMPAT, 'UTF-8') ?></title>
    <meta property="og:title" content="<?php echo htmlentities($this->photo->title, ENT_COMPAT, 'UTF-8') ?>"/>
    <meta property="og:type" content="album" />
    <meta property="og:image" content="<?php echo rtrim(URL::base(TRUE), '/').$this->photo($this->photo, '620x400', 'cropg') ?>" />
    <meta property="og:url" content="<?php echo rtrim(URL::base(TRUE), '/').$this->uri($this->photo) ?>" />
    <meta property="fb:app_id" content="<?php echo Kohana::config('oauth.facebook.key') ?>" />
    <meta name="Description" content="<?php echo Helper::filter($this->photo->section->name) ?>" />
<?php $this->endblock('meta') ?>

<?php $this->block('content') ?>

	<div class="content-wrapper">
		<div class="breadcrumbs-container-wide">
			<div class="breadcrumbs">
					<?php
						$section = $this->photo->section;
						$breadcrumbs = array('<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span>');
						$breadcrumbs[] = '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'gallery/">Фотогалерея</a></span>';
						while ($section->loaded()) {
							$breadcrumbs[] = $this->link_bread($section);
							$section = $section->section;
						}
						$breadcrumbs[] = '<span typeof="v:Breadcrumb">'.$this->photo->title.'</span>';
						echo '<div xmlns:v="http://rdf.data-vocabulary.org/#">';
						echo implode(' / ', $breadcrumbs);
						echo '</div>';
					?>
			</div>
		</div>
		<div class="bread-fade"></div>
		<div class="rel">
			<h1 class="top3"><?php echo $this->photo->title ?></h1>
			<div class="line">
				<div class="left date"><?php echo Date::formatted_time($this->photo->date, 'd.m.Y') ?>&nbsp;</div>

				<div class="viewcom right">
					<?php if (isset($this->photo->views_count) AND isset($this->photo->comments_count)): ?>
					<span class="view"><span class="gray1">&nbsp;<?php echo intVal($this->photo->views_count).' '.$this->plural(intVal($this->photo->views_count), 'просмотр', 'просмотра', 'просмотров'); ?></span>&nbsp;</span>
					<a class="comment" href="#">&nbsp;<?php echo intVal($this->photo->comments_count).' '.$this->plural(intVal($this->photo->comments_count), 'комментарий', 'комментария', 'комментариев'); ?></a>
					<?php endif; ?>
				</div>
			</div>

			<div class="top4"><p><?php echo $this->photo->description ?></p></div>

			<div class="photo-box rel">
				<?php list($prev_link, $next_link) = $this->photo->get_neighbors() ?>

				<?php if ($prev_link->loaded()): ?>
				<a href="<?php echo $this->uri($prev_link); ?>" class="backward prev">prev</a>
				<?php endif; ?>

				<?php if ($next_link->loaded()): ?>
				<a href="<?php echo $this->uri($next_link); ?>" class="forward next">next</a>
				<?php endif ?>

				<div class="skin-photo">
					<img src="<?php echo $this->photo($this->photo, '620x400', 'cropg') ?>" alt="<?php echo Helper::filter($this->photo->description); ?>" />
				</div>
			</div>

			<div class="data">
				<?php echo $this->get_blocks(array('like' => array('title' => $this->photo->title, 'url' => $this->uri($this->photo))), 'inner'); ?>
			</div>
			<div><?php echo $rating; ?></div>
			<p class="data">Автор: <?php echo $this->photo->get_user_link() ?></p>
			<?php /*
			<div>
				<?php if ($this->photo->age !== NULL): ?>
				<p class="data">Возраст: <?php echo $this->photo->age ?></p>
				<?php endif ?>

				<?php if ($this->photo->social_state !== NULL): ?>
				<p class="data">Социальный статус: <?php
					$state = array(NULL,'топ-менеджмер','квалифицированный специалист','офисный работник','рабочий/тех. рабочий','другое');
					$state_text = 'другое';
					if (isset($state[$this->photo->social_state])) {
						$state_text = $state[$this->photo->social_state];
					}

					echo $state_text;
				?></p>
				<?php endif ?>

				<?php if ($this->photo->subscribe_seldiko !== NULL): ?>
				<p class="data">Подписка на Seldiko: <?php echo ($this->photo->subscribe_seldiko ? 'да' : 'нет') ?></p>
				<?php endif ?>
			</div>
			*/ ?>

			<?php echo $comments; ?>
		</div>
	</div>
<?php $this->endblock('content') ?>
