<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Это тестовый заголовок<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
			<div class="rel">
				
				<h1>Свежачок в блогах:</h1>

		<?php foreach ($topics as $topic) 
			{
			// TODO: real picture
			$img = '/img/nouserpic.png';
			$url = $this->uri($topic);
		?>
			<div>
				<a href="<?php echo $url ?>"><img width="90" height="90" src="<?php echo $img ?>" /></a>
				<a href="<?php echo $url ?>" class="title"><?php echo text::limit_chars($topic->title, 130) ?></a>
				<p><?php echo text::limit_chars($topic->description, 200) ?></p>
				<a href="<?php echo $url ?>" class="read_blog">Читать блог</a><p>&nbsp;</p>
			</div>
		<?php
			}				
		?>
				
			</div>
			
<?php $this->endblock('content') ?>
