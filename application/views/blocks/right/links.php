<!--Популярные ссылки-->
				<div class="linkbox">
					<h2 class="top bold">Полезные закладки</h2>
					<?php if (count($links) > 1): ?>
						<?php 
							$first_clmn_cnt = round(count($links)/2);
						?>
						<div class="left" style="width:136px;">
						<ul>
							<?php $i = 0; foreach ($links as $link): ?>
								<?php if ($i == $first_clmn_cnt): ?>
									</ul>	
									</div>
									<div class="right" style="width:136px;">
									<ul>								
								<?php endif; ?>
								
								<li><a href="/goto/<?php echo str_replace('http://', '', $link->url) ?>" target="_blank"><?php echo Helper::filter($link->title) ?></a></li>
								<?php if ($link->img_counter != ''): ?>
									<img src="<?php echo $link->img_counter; ?>" border=0 width=1 height=1>
								<?php endif; ?>
								
							<?php $i = $i + 1; endforeach; ?>
						</ul>
						</div>
					<?php else: ?>
						<ul>
							<li><a href="<?php echo str_replace('http://', '', $links[0]->url) ?>" target="_blank"><?php echo Helper::filter($links[0]->title) ?></a></li>
								<?php if ($link->img_counter != ''): ?>
									<img src="$link->img_counter" border=0 width=1 height=1>
								<?php endif; ?>							
						</ul>					
					<?php endif; ?>
					<div class="clear"></div>
				</div>
<!--Конец Популярные ссылки-->
