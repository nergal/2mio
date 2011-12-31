<style>
.link_share .icon {
	width: 20px;
	height: 20px;
	margin-right: 6px;
	background: url(/i/socials_animate.gif) no-repeat 0 0;
	display:block;
	float:left;
	
}

.soc-dropdown {
	width: 120px;
	font-size: 0.8em;	
	background-color: #EBEBEB;
	margin-left: -15px;
	margin-top: 3px;
	padding-left: 7px;
	padding-top: 2px;
	padding-bottom: 2px;

	-moz-box-shadow: 0 0 5px #aaaaaa;
	-webkit-box-shadow: 0 0 5px #aaaaaa;
	-khtml-box-shadow: 0 0 5px #aaaaaa;
	box-shadow: 0 0 5px #aaaaaa;
	border:1px solid #aaaaaa;
		
}
.soc-dropdown-trigger{cursor:pointer;}

.soc-dropdown li {
	padding-top: 5px;
	border-bottom: 1px solid #D7D7D7;
}

.soc-dropdown li:last-child {
	border-bottom: none;
}

.soc-dropdown img {
	margin-right: 12px;	
}

.soc-dropdown-trigger a.link_share {
	text-decoration: none;
	display:block;
}

.soc-dropdown-trigger a.link_share:hover {
	text-decoration: none!important;
}
.soc-dropdown-trigger a.link_share span.link{
	display:block;
	float:left;
	height:20px;
	line-height:20px;
}
.soc-dropdown-trigger a.link_share:hover span.link{
	text-decoration: underline;
}

.soc-dropdown-trigger .btn {
    padding: 3px 15px;
    overflow: hidden;
    padding-right: 25px;
    font-size: 0.8em;
}

.soc-dropdown-trigger .btn span {
    background: url(http://static.praze.me/img/ico/um_friends.png) no-repeat left center;
    padding-left: 20px;
}
.soc-dropdown-trigger .btn span.down {
    position: absolute;
    width: 9px;
    height: 6px;
    padding: 5px;
    background: url(http://www.monacreditunion.com.jm/images/down_arrow.gif) no-repeat center center;
}
</style>

<script type="text/javascript">
	$(document).ready(function() {
		init_dd_like_<?php echo $this->order; ?>();
	})
	
	function init_dd_like_<?php echo $this->order; ?>() {
		$("#soc-dropdown-trigger-<?php echo $this->order; ?>").click(show_hide_<?php echo $this->order; ?>);
	}

	function show_hide_<?php echo $this->order; ?>() {
		$('#soc-dropdown-<?php echo $this->order; ?>').toggle();
	}
</script>


<div class="right">
	<div class="soc-dropdown-trigger" id="soc-dropdown-trigger-<?php echo $this->order; ?>">
		<a class="btn none">
			<span>поделиться</span>
                        <span class="down"></span>
		</a>
	</div>
	<div class="soc-dropdown" id="soc-dropdown-<?php echo $this->order; ?>" style="position:absolute; z-index:400; display: none;">
		<ul>
			<li><a rel="nofollow" target="_blank" href="http://www.livejournal.com/update.bml?subject=<?php echo $this->title; ?>&event=<?php echo $this->title; ?>%20<?php echo $this->url ?>"><img src="/i/ljsmall.png">Live Journal</a></li>
			<li><a rel="nofollow" target="_blank" href="http://www.facebook.com/share.php?u=<?php echo $this->url ?>&t=<?php echo $this->title; ?>"><img src="/i/facebooksmall.png">Facebook</a></li>
			<li><a rel="nofollow" target="_blank" href="http://connect.mail.ru/share?share_url=<?php echo $this->url; ?>"><img src="/i/mailrusmall.png">Мой мир</a></li>
			<li><a rel="nofollow" target="_blank" href="http://vkontakte.ru/share.php?url=<?php echo $this->url; ?>"><img src="/i/vkontaktesmall.png">Вконтакте</a></li>
			<li><a rel="nofollow" target="_blank" href="http://twitter.com/intent/tweet?text=<?php echo $this->title ?>%20<?php echo $this->url ?>"><img src="/i/twittersmall.png">Twitter</a></li>
			<li><a rel="nofollow" target="_blank" href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl=<?php echo $this->url ?>"><img src="/i/odnoklsmall.png">Однокласники</a></li>
		</ul>
	</div>
</div>
<?php if ( ! $this->short): ?>
<div class="left">
    <div class="left" style="margin-right:7px;">
	    <div id="vk_like" style="width: 120px !important"></div>
	    <script type="text/javascript">
	    VK.Widgets.Like("vk_like", {type: "button"});
	    </script>
    </div>

    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) {return;}
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <div class="left" style="padding-left: 6px;"><fb:like send="false" layout="button_count" width="130" show_faces="true" action="recommend"></fb:like></div>
</div>
<?php endif ?>
