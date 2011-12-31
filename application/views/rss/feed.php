<?php echo '<'.'?xml version="1.0" encoding="utf-8"?>' ?>
<rss version="2.0">
    <channel>
        <title>Женский журнал ХОЧУ</title>
        <link><?php echo URL::base(TRUE); ?></link>
        <description>Женский журнал ХОЧУ. Лучший украинский женский сайт.</description>
        <image>
            <url><?php echo URL::base(TRUE) ?>i/logo.png</url>
            <title>Женский журнал ХОЧУ</title>
            <link><?php echo URL::base(TRUE); ?></link>
        </image>
<?php foreach ($this->news as $new): ?>
        <item>
            <title><![CDATA[<?php echo Helper::filter($new->title) ?>]]></title>
            <link><?php echo rtrim(URL::base(TRUE), '/').$this->uri($new); ?></link>
            <description><![CDATA[<?php echo Helper::filter($new->description); ?>]]></description>
            <category><![CDATA[<?php echo $new->section->name ?>]]></category>
            <enclosure url="<?php echo rtrim(URL::base(TRUE), '/').$this->photo($new, '135x100') ?>" type="image/jpeg"/>
            <pubDate><?php echo Kohana_Date::formatted_time($new->date, 'r') ?></pubDate>
        </item>
<?php endforeach; ?>
	</channel>
</rss>
