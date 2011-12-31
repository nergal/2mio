<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Это тестовый заголовок<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
		    <div id="rules" class="mod simple">
			<b class="top"><b class="tl"></b><b class="tr"></b></b>
			<div class="inner">
				<div class="hd">
					<h2><?php echo Helper::filter($this->section->name) ?></h2>
				</div>
				<div class="bd">
					<ul>
						<?php foreach ($this->pages as $page): ?>
							<li>
								<div class="item">
									<h3><?php echo $this->link($page); ?></h3>
									<div>
										<img src="<?php echo $this->photo($page, '100x70') ?>" width="100" height="70" />
										<div class="description"><?php echo Text::auto_p($page->description); ?></div>
									</div>
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
