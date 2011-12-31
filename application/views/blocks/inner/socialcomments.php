<div id="comments">
	<h3>Комментарии</h3>
		<div class="left">
			<fb:comments href="<?php echo rtrim(URL::base(TRUE), '/').$this->uri($this->page); ?>" num_posts="10" width="375"></fb:comments>
		</div>
		<div class="right">
			<div id="vk_comments"></div>
			<script type="text/javascript">
			VK.Widgets.Comments("vk_comments", {limit: 10, width: "345", attach: "*"});
			</script>
		</div>
</div>
<div class="clear"></div>
<br />
<!-- /end comments -->
