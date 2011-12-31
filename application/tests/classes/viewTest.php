<?php
/**
 * @group custom
 */
class ViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider validPhoto
     */
    public function testPhoto($model, $path, $size, $type, $is_loaded, $expect)
    {
	$model = $this->getMock($model, array('loaded', '__get'), array(), '', FALSE);
	
	$model
	    ->expects($this->once())
	    ->method('loaded')
	    ->will($this->returnValue($is_loaded));
	
	if ($is_loaded) {
	    $model
		->expects($this->any())
		->method('__get')
		->with($this->equalTo('photo'))
		->will($this->returnValue($path));
	}
	
	$test = View::factory()->photo($model, $size, $type);
	$this->assertEquals($test, $expect);
    }
    
    public function validPhoto()
    {
	return array(
	    array('Model_Photo', NULL, NULL, NULL, FALSE, '/i/placeholder.gif'),
	    array('Model_Photo', '123456789012345678901234567890af.gif', '100x100', 'crop', TRUE, '/thumbnails/12/3456/crop_100x100/123456789012345678901234567890af.gif'),
	    array('Model_Photo', '123456789012345678901234567890af.gif', NULL, NULL, TRUE, '/uploads/12/3456/123456789012345678901234567890af.gif'),
	    array('Model_Photo', '123456789012345678901234567890az.gif', '100x100', 'crop', TRUE, '/thumbnails/articles/crop_100x100/123456789012345678901234567890az.gif'),
	    array('Model_Photo', '123456789012345678901234567890az.gif', NULL, NULL, TRUE, '/images/articles/123456789012345678901234567890az.gif'),
	    array('Model_Test', 'test.gif', '100x100', 'crop', TRUE, '/thumbnails/articles/crop_100x100/test.gif'),
	    array('Model_Test', 'photos/test.gif', '100x100', 'crop', TRUE, '/thumbnails/articles/photos/crop_100x100/test.gif'),
	    array('Model_Test', '/photos/test.gif', '100x100', 'crop', TRUE, '/thumbnails/articles/photos/crop_100x100/test.gif'),
	    array('Model_Photo', 'photos/test.gif', '100x100', 'crop', TRUE, '/thumbnails/articles/photos/crop_100x100/test.gif'),
	);
    }
}

class Model_Test extends ORM
{ }

class Model_Failed
{ }