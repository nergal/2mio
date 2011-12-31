				<div class="rel">
					<a href="/video/" class="none"><div class="freshmat fresh2"></div></a>
					<div class="box-shad bdvideo">

	<?php $i = 1; ?>
	<?php foreach ($this->videos as $video): ?>
		<?php if ($i == 1): ?>
					<!-- div left col2 -->
						<div class="col2 left ">
							<div class="pic_shad" style="width:252px; margin-bottom:8px;">
								<div class="video bording">
									<a href="<?php echo $this->uri($video) ?>" class="none"><img src="<?php echo $this->photo($video, '250x150') ?>" ></a>
									<a href="<?php echo $this->uri($video) ?>" class="play"></a>
								</div>
							</div>
							<div class="text" style="min-height: 36px; max-height: 54px; overflow:hidden;">
								<a href="<?php echo $this->uri($video) ?>" class="none"><h2 style="font-size:1.17em; font-weight: normal; color: #0d4e8b;"><?php echo Helper::filter($video->title) ?></h2></a>
							</div>
							<div class="viewcombig">
									<?php if ($video->views_count > 0): ?><a class="view"><?php echo Helper::space_digit($video->views_count) ?></a>&nbsp;<?php endif; ?>
									<?php if ($video->comments_count > 0): ?><a class="comment"><?php echo Helper::space_digit($video->comments_count) ?> </a><?php endif; ?><br />
									<div style="margin-top:6px;"><a href="/video/" class="ssmall light_blue none">все материалы раздела &laquo;видео&raquo;</a></div>
							</div>
						</div>
					<!-- /div left col2 -->
		<?php else: ?>
			<?php if ($i == 2): ?>
					<!-- div right col2 -->
						<div class="col2 right">
							<div class="scroll" style="height:260px;">
								<ul>
			<?php endif; ?>
									<li class="<?php echo ($i%2 === 0) ? "" : "anbg" ;?>" >
										<div class="pic_shad left" style="margin: 6px 12px 6px 6px;">
											<div class="video bording">
												<a href="<?php echo $this->uri($video) ?>" class="none"><img src="<?php echo $this->photo($video, '90x65') ?>" ></a>
												<a href="<?php echo $this->uri($video) ?>" class="play"></a>
											</div>
										</div>
										<div>
											<h2 style="text-transform: uppercase; font-size:0.86em; font-weight:normal; color:#353535;"><?php echo $video->section->name ?></h2>
											<a href="<?php echo $this->uri($video) ?>" class="none"><h2 class="top5"><?php echo Helper::filter($video->title) ?></h2></a>
										</div>
										<div class="viewcom">
											<?php if ($video->views_count > 0): ?><a class="view"><?php echo Helper::space_digit($video->views_count) ?></a>&nbsp;<?php endif; ?>
											<?php if ($video->comments_count > 0): ?><a class="comment"><?php echo Helper::space_digit($video->comments_count) ?> </a><?php endif; ?>
										</div>
									</li>
		<?php endif; ?>
	<?php $i += 1; endforeach; ?>
								</ul>
							</div>
						</div>
						<div class="clear"></div>
					</div>

				</div>
