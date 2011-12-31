<?php $url = $this->uri($this->page); ?>
<!-- begin rating for <?php echo $url; ?> -->
<div id="rating-<?php echo $page->id ?>" class="rating-stars">
	<form method="post">
		<?php foreach (range(1, 5) as $i): ?>
		<input<?php echo (round(floatVal($this->value)) == $i) ? ' checked="checked"' : ''; ?> class="stars" type="radio" name="rating" value="<?php echo $i ?>"<?php echo ($this->enabled ? '' : ' disabled="disabled"'); ?> />
		<?php endforeach; ?>
	</form>
	<div class="counts">&nbsp;<span class="avg"><?php echo round($this->counts, 1) ?></span> <?php echo $this->plural($this->counts, 'голос', 'голоса', 'голосов') ?>, <span class="avg"><?php echo round($this->sum, 1) ?></span> <?php echo $this->plural($this->sum, 'балл', 'балла', 'баллов') ?></div>
</div>
<!-- /end rating -->