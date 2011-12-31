<style>
.unvisible {
  display: none;
 }
.forum_list {
	min-height:200px;
}
</style>
					<div class="forum_tops left col2">
						<div class="freshmat fresh3"></div>
						<div class="forum_tabs">
							<div class="tableft active ssmall-caps">самое обсуждаемое</div>
							<div class="tabright ssmall-caps">новые темы</div>
						</div>
						<div class="box-shad forum_list">
							<ul class="blogul">
								<?php foreach ($this->topics_commented as $topic_commented): ?>
									<li><a href="http://forum.bt-lady.com.ua/viewtopic.php?f=<?php echo $topic_commented['forum_id'] ?>&t=<?php echo $topic_commented['topic_id'] ?>" class="none"><?php echo $topic_commented['topic_title'] ?></a> - <?php echo $topic_commented['topic_replies'] ?> сообщений</li>
								<?php endforeach; ?>
								<li class="bl_bott">Всего <?php echo $this->topic_cnt ?> темы  <a href="http://forum.bt-lady.com.ua/" class="none">посмотреть все</a></li>
							</ul>
						</div>
						<div class="box-shad unvisible forum_list">
							<ul class="blogul">
								<?php foreach ($this->topics_new as $topic_new): ?>
									<li><a href="http://forum.bt-lady.com.ua/viewtopic.php?f=<?php echo $topic_new['forum_id'] ?>&t=<?php echo $topic_new['topic_id'] ?>" class="none"><?php echo $topic_new['topic_title'] ?></a> - <?php echo $topic_new['topic_replies_real'] ?> сообщений</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>					
<script type="text/javascript">
(function($) {
$(function() {
  $('div.forum_tabs').delegate('div:not(.active)', 'click', function() {
    $(this).addClass('active').siblings().removeClass('active')
      .parents('div.forum_tops').find('div.box-shad').hide().eq($(this).index()).fadeIn(150);
  })
})
})(jQuery)	
</script>
