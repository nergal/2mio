				<div class="rel">
					<div class="box-shad left col2">
						<div class="stylehead var3">
							<h2 class="small-caps">секс и отношения</h2>
							<a href="<?php echo $this->uri($this->sex_sec) ?>">все материалы</a>
						</div>
						<?php foreach ($this->sex as $sex_item): ?>						
							<div class="col2 left" style="height:236px; overflow: hidden;">
								<a href="<?php echo $this->uri($sex_item) ?>" class="none"><img src="<?php echo $this->photo($sex_item, '130x95') ?>" alt="" class="shad" /></a>
									<div style="padding-right: 8px;">								
											<a href="<?php echo $this->uri($sex_item) ?>" class="none"><h2 class="top"><?php echo Helper::filter($sex_item->title) ?></h2></a>
											<p><?php echo Helper::filter($sex_item->description) ?></p>
									</div>
							</div>
						<?php endforeach; ?>
						<div class="clear"></div>
					</div>
					<div class="box-shad right col2">
						<div class="stylehead var4">
							<h2 class="small-caps">стиль и мода</h2>
							<a href="<?php echo $this->uri($this->fashion_sec) ?>">все материалы</a>
						</div>
						<?php foreach ($this->fashion as $fashion_item): ?>
							<div class="col2 left" style="height:236px; overflow: hidden;">
								<a href="<?php echo $this->uri($fashion_item) ?>" class="none"><img src="<?php echo $this->photo($fashion_item, '130x95') ?>" alt="" class="shad" /></a>
									<div style="padding-right: 8px;">
											<a href="<?php echo $this->uri($fashion_item) ?>" class="none"><h2 class="top"><?php echo Helper::filter($fashion_item->title) ?></h2></a>
											<p><?php echo Helper::filter($fashion_item->description) ?></p>
									</div>
							</div>
						<?php endforeach; ?>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
