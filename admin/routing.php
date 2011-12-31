<?php defined('SYSPATH') or die('No direct script access.');

Route::set('main', '')
    ->defaults(array('controller' => 'admin', 'action' => 'index'));

Route::set('left', 'left')
    ->defaults(array('controller' => 'admin', 'action' => 'left'));

Route::set('content', 'content')
    ->defaults(array('controller' => 'admin', 'action' => 'content'));
    
Route::set('informers', 'informers(/<action>(/id/<id>))')
	->defaults(array('controller' => 'informers', 'action' => 'index'));

Route::set('banner-joins', 'banners/getalljoins(/id/<id>)', array('id' => '\d+'))
	->defaults(array('controller' => 'banners', 'action' => 'getalljoins', 'id' => 1));

Route::set('photoform', 'pages/photoform/id/<id>/photoid/<photoid>(/gallery/<isg>)', array('id' => '\d+', 'photoid' => '\d+', 'isg' => '1'))
    ->defaults(array('controller' => 'pages', 'action' => 'photoform'));

Route::set('videoform', 'pages/videoform/id/<id>/photoid/<photoid>', array('id' => '\d+', 'photoid' => '\d+'))
    ->defaults(array('controller' => 'pages', 'action' => 'videoform'));

Route::set('videoform', 'pages/getsimilarsbytags/id/<id>/single/<single_id>', array('id' => '\d+', 'single_id' => '\d+'))
    ->defaults(array('controller' => 'pages', 'action' => 'getsimilarsbytags'));

Route::set('pages', 'pages(/<action>(/id/<id>))')
    ->defaults(array('controller' => 'pages', 'action' => 'index'));

Route::set('answers', 'answers/get_questions/df/<df>/dt/<dt>/sp/<sp>/st/<st>', array('sp' => '\d+', 'st' => 'all|withans|noans', 'df' => '[0-9\-]+', 'dt' => '[0-9\-]+'))
    ->defaults(array('controller' => 'answers', 'action' => 'get_questions'));
	
Route::set('advisers_chart', 'advisers/yearchart/year/<year>/sp_id/<sp_id>/adv_id/<adv_id>', array('year' => '\d+', 'sp_id' => '\d+'))
	->defaults(array('controller' => 'advisers', 'action' => 'yearchart'));
	
Route::set('advisers', 'advisers/advisers/year/<year>/month/<month>/sp_id/<sp_id>/ans/<ans>', array('year' => '\d+', 'month' => '\d+', 'sp_id' => '\d+', 'ans' => 'all|withans|noans'))
	->defaults(array('controller' => 'advisers', 'action' => 'advisers'));

Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array('controller' => 'admin', 'action' => 'index'));
