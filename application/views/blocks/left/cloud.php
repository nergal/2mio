<!-- Облако тегов -->
<div id="tags">
    <h1 class="roundback small-caps">облако тегов</h1>
    <ul>
	<?php
	    shuffle($this->tags);
	
	    $max = 5;
	    foreach ($this->tags as $item) {
		$max = ($item['cnt'] > $max) ? $item['cnt'] : $max;
	    }
	    
	    $max = 100 / $max;
	?>
	<?php foreach ($this->tags as $tag): ?>
	    <?php $class = round($max * $tag['cnt'] / 10) ?>
	    <li><a rel="<?php echo $tag['cnt'] ?>" href="<?php echo '/tag/'.urlencode($tag['name']) ?>" class="size<?php echo $class ?>"><?php echo Helper::filter($tag['name']) ?></a></li>
	<?php endforeach ?>
    </ul>
</div>
<!-- Конец Облако тегов -->
