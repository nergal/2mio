				<div class="rel">
					<div class="box-shad left col2" >
						<div class="stylehead var1">
							<h2 class="small-caps">здоровье и фитнес</h2>
							<a href="<?php echo $this->uri($health_sec) ?>">все материалы</a>
						</div>
						<?php foreach ($this->health as $health_item): ?>						
							<div class="col2 left" style="height:236px; overflow:hidden; ">
								<a href="<?php echo $this->uri($health_item) ?>" class="none"><img src="<?php echo $this->photo($health_item, '130x95') ?>" alt="" class="shad" /></a>
									<div style="padding-right: 8px;">
											<a href="<?php echo $this->uri($health_item) ?>" class="none"><h2 class="top"><?php echo Helper::filter($health_item->title) ?></h2></a>
											<p><?php echo Helper::filter($health_item->description) ?></p>
									</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="box-shad right col2">
						<div class="stylehead var2">
							<h2 class="small-caps">отдых и шопинг</h2>
							<a href="<?php echo $this->uri($shoping_sec) ?>">все материалы</a>
						</div>
						<?php foreach ($this->shoping as $shoping_item): ?>
							<div class="col2 left" style="height:236px; overflow:hidden; ">
								<a href="<?php echo $this->uri($shoping_item) ?>" class="none"><img src="<?php echo $this->photo($shoping_item, '130x95') ?>" alt="" class="shad" /></a>
									<div style="padding-right: 8px;">
											<a href="<?php echo $this->uri($shoping_item) ?>" class="none"><h2 class="top"><?php echo Helper::filter($shoping_item->title) ?></h2></a>
											<p><?php echo Helper::filter($shoping_item->description) ?></p>
									</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="clear"></div>
				</div>
