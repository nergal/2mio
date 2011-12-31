		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<script type="text/javascript">window.jQuery || document.write("<script type='type/javascript' src='/js/jquery-1.6.1.min.js'>\x3C/script>")</script>

		<script src="http://cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js?foo"></script>

		<?php echo Asset::render(); ?>
		
<style>
	body, html{
		background:none;
	}
	.limiter-forum{
		width:96%;
		margin:0 auto;
	}
	.wrapper_forum{
		width: 100%;
		min-width: 1020px;
		width: expression((documentElement.clientWidth||document.body.clientWidth)<1015?'1020px':'');
		margin: 0 auto;
		font-size: 1em;
		color:#252525;
	}
	.header{
		margin:0;
		background:none;
		margin-bottom:15px;	
		background-color:white;
		-webkit-box-shadow: 0px 0px 15px #999;
		-moz-box-shadow: 0px 0px 15px #999;
		box-shadow: 0px 0px 15px #999;		
	}
</style>

<div class="wrapper_forum">
	<div class="limiter-forum">
		<div class="header">
			<div class="logosection">

				<div class="logo"><a href="/"><img src="/i/logo.png" alt="hochu.ua"></a></div>
				<div class="banner728x90"><?php echo $this->banner('728x90'); ?></div>

			</div>
			<div class="clear"></div>
			
			<?php echo $this->get_blocks('menu', 'full'); ?>
			
			</div>
		</div>
		
	</div>
</div>	
