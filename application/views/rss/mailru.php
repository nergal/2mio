<?php echo '<'.'?xml version="1.0" encoding="utf-8"?>' ?>
<rss version="2.0" xmlns:mailru="http://news.mail.ru/">
    <channel>
        <title>Женский журнал ХОЧУ</title>
        <link><?php echo URL::base(TRUE); ?></link>
        <description>Женский журнал ХОЧУ. Лучший украинский женский сайт.</description>
        <language>ru</language>
        <pubDate><?php echo date('r'); ?></pubDate>
        
		<lastBuildDate><?php echo date('r'); ?></lastBuildDate>
		<generator>kohana rss generator</generator>
		<managingEditor>lady@bt-lady.com.ua</managingEditor>
		<webMaster>lady@bt-lady.com.ua</webMaster>
        
<?php foreach ($this->news as $new): ?>
        <item>
            <category><![CDATA[<?php echo Helper::filter($new->section->name) ?>]]></category>
            <title><![CDATA[<?php echo Helper::filter($new->title, Helper::RSS_TITLE) ?>]]></title>
            <guid><?php echo rtrim(URL::base(TRUE), '/').$this->uri($new); ?></guid>
            <description><![CDATA[<?php echo Helper::filter($new->description, Helper::RSS_BODY); ?>]]></description>
            <link><?php echo rtrim(URL::base(TRUE), '/').$this->uri($new); ?></link>
            <enclosure url="<?php echo rtrim(URL::base(TRUE), '/').$this->photo($new, '135x100') ?>" type="image/jpeg" />
            <pubDate><?php echo Kohana_Date::formatted_time($new->date, 'r') ?></pubDate>
            <mailru:full-text><![CDATA[<?php echo Helper::filter($new->body, Helper::RSS_BODY); ?>]]></mailru:full-text>
        </item>
<?php endforeach; ?>
	</channel>
</rss>
