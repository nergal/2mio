		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<script type="text/javascript">window.jQuery || document.write("<script type='type/javascript' src='/js/jquery-1.6.1.min.js'>\x3C/script>")</script>
		<script src="http://cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js?foo"></script>

		<?php echo Asset::render(); ?>
<title></title>

<style>
	body, html{
		background:none;
	}
	.rightcol {
		padding: 0;
}
</style>

<div class="rightcol">
	<?php echo $this->get_blocks(array('adverts', 'links', 'social', 'horoscope', 'services', 'top'), 'right'); ?>
</div>

