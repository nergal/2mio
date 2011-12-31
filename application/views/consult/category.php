<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Это тестовый заголовок<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
		    <div id="rules" class="mod simple">
			<b class="top"><b class="tl"></b><b class="tr"></b></b>
			<div class="inner">
				<div class="hd">
					<h2><?php echo $this->category->name; ?></h2>
				</div>
				<div class="bd">
					<?php
						echo Request::factory('consult/addform')
								->query('id', $this->category->id)
								->execute()
								->body();
					?>

					<p>
						<a href="#">Все</a> | <a href="#answered">С ответом</a> | <a href="#not-answered">Без ответа</a>
					</p>

					<ul>
						<?php foreach ($this->listing as $page): ?>
							<li>
								<div class="item" style="border:1px solid #eee;margin-bottom:1em">
									<h3><?php echo $page->speciality->name.' &raquo; '.$this->link($page).' ('.($page->answers->count_all()).' ответов)' ?></h3>
									<div class="description"><?php echo Text::auto_p($page->body); ?></div>
									<p><?php echo $this->link($page, 'Читать дальше'); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php echo $this->pager ?>
				</div>
			</div>
			<b class="bottom"><b class="bl"></b><b class="br"></b></b>
		    </div>
<?php $this->endblock('content') ?>
<br />