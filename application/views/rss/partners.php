<?php echo '<'.'?xml version="1.0" encoding="utf-8"?>' ?>
<root>
<?php foreach ($this->news as $new): ?>
	<item>
		<id><?php echo $new->id; ?></id>
		<img><?php echo rtrim(URL::base(TRUE), '/').$this->photo($new, '170x110') ?></img>
		<name><?php echo Helper::filter($new->title) ?></name>
		<link><?php echo rtrim(URL::base(TRUE), '/').$this->uri($new); ?></link>
	</item>
<?php endforeach; ?>
</root>
