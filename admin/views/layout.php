<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php $this->block('title') ?>::ADMIN::<?php $this->endblock('title') ?></title>

		<?= Asset::render() ?>
    </head>
    <body>

		<div class="main">
			<?php $this->block('content') ?>
			<?php $this->endblock('content') ?>
		</div>
    </body>
</html>
		