<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<?php
			$this->block('meta');
			echo Meta::render_meta();
			$this->endblock('meta');
		?>
		
		<?php echo Asset::render(); ?>
	</head>
	<body>
		<div class="wrapper" id="wrapper">
			<div class="limiter">
				<div class="header">
					<div class="logosection">
						<div class="logo"><a href="<?php echo URL::base(TRUE) ?>"><h1>HEAD TITLE</h1></a></div>
					</div>
					<?php echo $this->get_blocks(array('menu'), 'full'); ?>
				</div>
				<div class="container">
					<?php $this->block('content') ?>
					<?php $this->endblock('content') ?>
				</div>
			</div>
		</div>
	</body>
</html>
