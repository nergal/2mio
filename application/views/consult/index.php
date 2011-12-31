<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Это тестовый заголовок<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
		    <div id="rules" class="mod simple">
			<b class="top"><b class="tl"></b><b class="tr"></b></b>
			<div class="inner">
				<div class="hd">
					<h2>Направдения консультаций</h2>
				</div>
				<div class="bd">
					<ul>
						<?php foreach ($this->specialities as $page): ?>
							<li style="width:50%;float:left;">
								<div class="item" style="min-height:100px;border:1px solid #eee;margin-bottom:1em">
									<h3><?php echo $this->link($page); ?></h3>
									<ul class="simpleList">
										<li>Консультантов: <?php echo $page->consultants->count_all() ?></li>
										<li>Вопросов: <?php echo $page->questions->count_all() ?></li>
									</ul>
									<p><?php echo $this->link($page, 'Читать дальше'); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<b class="bottom"><b class="bl"></b><b class="br"></b></b>
		    </div>
<?php $this->endblock('content') ?>
<br />