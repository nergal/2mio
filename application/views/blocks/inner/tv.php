<?php if ($this->is_ajax !== true): ?>

<!-- tv container -->
<div class="rel">
					<a href="/cat-all/" class="none"><div class="freshmat fresh1"></div></a>
					<div class="box adv">

						<!-- "previous slide" button --> 
						<a href="javascript:void(0)" class="backward prev">prev</a>							

						
						<!-- container for the slides --> 
						<div id="mycarousel1" class="skin-adv images">
						
							<!-- news scroller -->
							<div id="news_scroller">
							
								<!-- news_line -->
								<div class="news_line">
<?php endif; ?>

<!-- news_scroll rel -->
<div class="news_scroll rel">
	<?php $article = $this->articles[0]; ?>
					<!-- div left col2 -->
					<div class="left col2" > 
						<div class="bd"> 
							<a href="<?php echo $this->uri($article) ?>" class="none"><img src="<?php echo $this->photo($article, '400x230') ?>" alt="" class="shad" /></a>
							<a href="<?php echo $this->uri($article) ?>" class="none visited"><h2 class="top"><?php echo Helper::filter($article->title) ?></h2></a>
<?php 
	$title_len = mb_strlen(Helper::filter($article->title));
	$desc_len  = ($title_len > 64) ? 51 : 98;
	$desc_len  = ($title_len < 32) ? 152 : 98;
?>
							<div class="rel"><?php echo text::limit_chars(Helper::filter($article->description), $desc_len) ?></div>
							<div class="viewall right">
								<div class="left"></div>
								<div class="center"><a href="/category/all/" class="none small">все материалы</a></div>
								<div class="right"></div>
							</div>
						</div>
					</div>
</div>
<!-- // news_scroll rel -->

<?php if ($this->is_ajax !== true): ?>
						
								</div>
								<!-- // news_line -->
								
							</div>
							<!-- // news scroller -->
							
							<!-- right column -->
							<div class="col2">

<?php
	$i = 1;
	foreach ($this->articles as $key => $article): ?>
	<?php if ($i != 1): ?>

						<div class="sd">
							<a href="<?php echo $this->uri($article) ?>" class="none"><img src="<?php echo $this->photo($article, '90x85') ?>" alt="" class="shad" /></a>
							<div class="text">
								<div class="valign">
									<a href="<?php echo $this->uri($article) ?>" class="none visited"><span class="top"><?php echo text::limit_chars($article->title, 48); ?></span></a>
								</div>
							</div>
							<div class="clear"></div>
						</div>
	<?php endif; ?>
<?php $i = $i+1; endforeach; ?>


							</div>							
							<!-- // right column -->
							
						</div>
						<!-- // container for the slides --> 

					<!-- "next slide" button --> 					
					<a href="javascript:void(0)" class="forward next">next</a>
					
				</div>
</div>
<!--/ tv container -->

				
<script type="text/javascript">
$(document).ready(function(){
        $("#news_scroller").scrollable({
                items: ".news_line",
                onBeforeSeek: function(event, tabIndex) {
                        var api = $("#news_scroller").data("scrollable");
                        if (tabIndex == api.getSize()) {
                                $.ajax({
                                        type: 'POST',
                                        url: '/articles/getforblock/',
                                        data: {'page': tabIndex},
                                        success: function(data) {
                                                if (data != 'NONE') {
                                                        api.addItem(data).end();
                                                } else {
                                                        api.begin();
                                                }
                                        }
                                });
                                return false;
                        };
                }
        });
})
</script>
<?php endif; ?>
 
