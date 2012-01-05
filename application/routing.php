<?php defined('SYSPATH') or die('No direct script access.');

// Стандартные правила для валидации адресов
$url_rule = '[-_a-zA-Z0-9]+';
$articles_url_rule = '[\d]+';
$category_url_rule = $articles_url_rule;
$id_rule = '\d+';

$list = array(
    'category'       => $category_url_rule,
    'page'           => $id_rule,
);

$view = array(
    'category'       => $articles_url_rule,
    'id'             => $id_rule,
    'title'          => $url_rule,
);

$gallery_view = array(
    'category'       => $category_url_rule,
    'id'             => $id_rule,
    'comments_page>' => $id_rule,
);

$artcle_view = array(
    'category'       => $articles_url_rule,
    'id'             => $id_rule,
    'title'          => $url_rule,
    'photo_id'       => $id_rule,
    'comment_page'   => $id_rule,
);

$item = array(
    'id'             => $id_rule,
);

$page = array(
    'page'           => $id_rule,
);

Route::set('page-view', 'page-<pagename>(/comments-<comments_page>)', array('name_url' => $url_rule, 'comments_page' => '\d+'))
    ->defaults(array('controller' => 'main', 'action' => 'pages', 'comments_page' => 1));

// -- Статьи
Route::set('category', 'articles(/order-<order>(/period-<period>(/page-<page>)))',
    array_merge($list, array('order' => '(abc|date|views|comments|title)'), array('period' => '(week|month|all)')))
    ->defaults(array('controller' => 'articles', 'action' => 'index', 'page' => 1, 'order' => 'date', 'period' => 'all'));

// -- Галереи
Route::set('gallery', 'gallery')
    ->defaults(array('controller' => 'gallery', 'action' => 'index'));

// NB: это правило должно идти ДО обявления gallery-list
// NB: из-за некорретной обработки регулярки $category_url_rule
Route::set('photo-add', 'gallery/<category>/add', array('category' => $category_url_rule,))
    ->defaults(array('controller' => 'gallery', 'action' => 'addphoto'));

Route::set('gallery-list', 'gallery/<category>(/page-<page>)', $list)
    ->defaults(array('controller' => 'gallery', 'action' => 'list', 'page' => 1));

Route::set('gallery-view', 'gallery/<category>/photo-<id>(/comments-<comments_page>)', $gallery_view)
    ->defaults(array('controller' => 'gallery', 'action' => 'view', 'comments_page' => 1));


// -- Пользователь
Route::set('profile', 'user-<id>', $item)
    ->defaults(array('controller' => 'user', 'action' => 'view'));

Route::set('reset', 'reset(/<token>)', array('token' => '[0-9a-f]{32}'))
    ->defaults(array('controller' => 'user', 'action' => 'reset'));

Route::set('login', 'login')
    ->defaults(array('controller' => 'user', 'action' => 'login'));

Route::set('logout', 'logout')
    ->defaults(array('controller' => 'user', 'action' => 'logout'));

Route::set('register', 'register')
    ->defaults(array('controller' => 'user', 'action' => 'register'));

// -- Default action
Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array('controller' => 'main', 'action' => 'index'));
