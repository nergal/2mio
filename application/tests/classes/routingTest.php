<?php
/**
 * @group custom
 */
class RoutingTest extends PHPUnit_Framework_TestCase
{
    public $regexp = '';

    public function setUp()
    {
	require APPPATH.'routing.php';
	$this->regexp = $articles_url_rule;
    }

    /**
     * @test
     * @dataProvider customRegexp
     */
    public function testPlural($value, $expect)
    {
	$regexp = '#'.$this->regexp.'#ui';
	$if_exists = (boolean) preg_match($regexp, $value, $matches);
	$this->assertTrue($if_exists);
	
	if ($if_exists) {
	    $value = trim($matches[1], '/');
	    $this->assertEquals($expect, $value);
	}
    }
    
    public function customRegexp()
    {
	$arr = array(
	    array('house/page-1', 'house'),
	    array('house/cook/order-views/page-1', 'house/cook'),
	    array('house/cook/order-views', 'house/cook'),
	    array('house/cook/page-1', 'house/cook'),
	    array('beauty-class/video-20607', 'beauty-class'),
	    array('test', 'test'),
	    array('test-on-test', 'test-on-test'),
	    array('test/asdasd', 'test/asdasd'),
	    array('test/asdasd/fffffff', 'test/asdasd/fffffff'),
	    array('test/article-123-asdasdas', 'test'),
	    array('test/asdasd/article-123-asdasdas', 'test/asdasd'),
	    array('test/asdasd/fffffff/article-123-asdasdas', 'test/asdasd/fffffff'),
	    array('specprojects/hochu-svadebnyi-sezon/order-date/page-4', 'specprojects/hochu-svadebnyi-sezon'),
	    array('BL-foto-c-lady-2008', 'BL-foto-c-lady-2008'),
	    array('stars/novosti-shou-biznesa/news-20567-angel-victorias-secret-kendis-sveynpol-vo-vsey-krase-foto', 'stars/novosti-shou-biznesa'),
	    array('stars/novosti-shou-biznesa/news-20567-angel-victorias-secret-kendis-sveynpol-vo-vsey-krase-foto/photo-123', 'stars/novosti-shou-biznesa'),
	    array('wiki/dreambook/order-abc/litera-11', 'wiki/dreambook'),
	    array('wiki/dreambook/order-abc/litera-11/period-all/page-40', 'wiki/dreambook'),
	    array('wiki/dreambook/litera-11/period-all/page-40', 'wiki/dreambook'),
	    //array('BL-foto-c-lady-0311/add', 'BL-foto-c-lady-0311'),
	);
	
	foreach ($arr as $item) {
	    $arr[] = array($item[0].'/', $item[1]);
	}
	
	return $arr;
    }
}
