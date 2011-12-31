<?php echo '<'.'?xml version="1.0" encoding="utf-8"?>' ?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
    <channel>
        <title>Женский журнал ХОЧУ</title>
        <link><?php echo URL::base(TRUE); ?></link>
        <description>Женский журнал ХОЧУ. Лучший украинский женский сайт.</description>
        <image>
            <url><?php echo URL::base(TRUE) ?>i/logo_xochu_rss.gif</url>
            <title>Женский журнал ХОЧУ</title>
            <link><?php echo URL::base(TRUE); ?></link>
        </image>
<?php foreach ($this->news as $new): ?>
        <item>
            <title><![CDATA[<?php echo Helper::filter($new->title, Helper::RSS_TITLE) ?>]]></title>
            <link><?php echo rtrim(URL::base(TRUE), '/').$this->uri($new); ?></link>
            <description><![CDATA[<?php echo Helper::filter($new->description, Helper::RSS_BODY); ?>]]></description>
            <category><![CDATA[<?php echo Helper::filter($new->section->name) ?>]]></category>
            <enclosure url="<?php echo rtrim(URL::base(TRUE), '/').$this->photo($new, '135x100') ?>" type="image/jpeg"/>
            <pubDate><?php echo Kohana_Date::formatted_time($new->date, 'r') ?></pubDate>
			<yandex:full-text>
				<![CDATA[<?php echo Helper::filter($new->body, Helper::RSS_BODY); ?>]]>
			</yandex:full-text>
        </item>
<?php endforeach; ?>
	</channel>
</rss>
