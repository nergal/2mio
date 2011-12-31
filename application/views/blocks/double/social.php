<?php
	if(isset($this->wide) AND $this->wide === TRUE) {
		$vk_width = 395;
		$facebook_width = 375;
	 } else {
		$vk_width = 214;
		$facebook_width = 237;
	}
?>
<br />
<div class="rel">
	<div class="left">
		<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?31"></script>
		<div id="vk_groups_2" style="width: 214px; background-image: none; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: initial; height: 218px; background-position: initial initial; background-repeat: initial initial; "></div>
		<script type="text/javascript">
			VK.Widgets.Group("vk_groups_2", {mode: 0, width: "<?php echo $vk_width; ?>", height: "224"}, 22275376);
		</script>
	</div>
	
	<div class="right">
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
		<fb:like-box href="http://www.facebook.com/portal.hochu" width="<?php echo $facebook_width; ?>" height="226" show_faces="true" stream="false" header="true" class="  fb_iframe_widget"></fb:like-box>	
	</div>
</div>
<div class="clear"></div>
