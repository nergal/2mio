<?php defined('SYSPATH') or die('No direct script access.');

// Стандартные правила для валидации адресов
$url_rule = '[-_a-zA-Z0-9]+';
$articles_url_rule = '((?!.+(/[\w]+\-(\d+|views|comments|date|title|abc)(\-[^/]+)?)).+|(?:.+?)(?=(/([\w]+\-(\d+|views|comments|date|title|abc)(\-[^/]+)?))))';
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

$topic_operation = array(
    'category'       => $url_rule,
    'id'             => $id_rule,
    'operation'      => '(view|edit|delete)',
);

$item = array(
	'id'             => $id_rule,
);

$page = array(
	'page'           => $id_rule,
);

$litera = array(
	'litera'         => $id_rule,
);

Route::set('page-view', 'page-<pagename>(/comments-<comments_page>)', array('name_url' => $url_rule, 'comments_page' => '\d+'))
    ->defaults(array('controller' => 'main', 'action' => 'pages', 'comments_page' => 1));

// -- Гороскопы
Route::set('horo-index', 'horoscope')
    ->defaults(array('controller' => 'main', 'action' => 'horoscope'));

// -- Консультации
Route::set('consult-index', 'consultation')
    ->defaults(array('controller' => 'consult', 'action' => 'index'));

Route::set('consult-list', 'consult-<category>(/<answer>)(/page-<page>)', array_merge($list, array('answer' => '(answered|not-answered)')))
    ->defaults(array('controller' => 'consult', 'action' => 'list', 'answer' => 'all', 'page' => 1 ));

Route::set('consult-view', 'consult-<category>/question-<id>-<title>', $view)
    ->defaults(array('controller' => 'consult', 'action' => 'view' ));

Route::set('consult-admin', 'consultation/admin')
    ->defaults(array('controller' => 'consult', 'action' => 'admin' ));

Route::set('consult-add', 'consultation/add')
    ->defaults(array('controller' => 'consult', 'action' => 'addform' ));
    
// -- Статьи
Route::set('category', 'cat-<category>(/order-<order>(/period-<period>(/page-<page>)))',
	array_merge($list, array('order' => '(abc|date|views|comments|title)'), array('period' => '(week|month|all)')))
    ->defaults(array('controller' => 'articles', 'action' => 'index', 'page' => 1, 'order' => 'date', 'period' => 'all'));

// -- Wiki
Route::set('wiki', 'cat-<category>(/order-<order>(/litera-<litera>)(/period-<period>)(/page-<page>))',
	array_merge($list, array('order' => '(abc|date|views|comments|title)'), array('period' => '(week|month|all)')))
    ->defaults(array('controller' => 'articles', 'action' => 'index', 'page' => 1, 'order' => 'date', 'period' => 'all', 'litera' => 0));
  
Route::set('category-all', 'category/all')
    ->defaults(array('controller' => 'articles', 'action' => 'all'));

Route::set('tags-view', 'tag/<tag>', array('tag' => '[^/]+'))
    ->defaults(array('controller' => 'articles', 'action' => 'tag'));

Route::set('article-view', 'cat-<category>/article-<id>-<title>(/photo-<photo_id>)(/comment-<comment_page>)', $artcle_view)
    ->defaults(array('controller' => 'articles', 'action' => 'view', 'photo_id' => 1));

Route::set('news-view', 'cat-<category>/news-<id>-<title>(/photo-<photo_id>)(/comment-<comment_page>)', $artcle_view)
    ->defaults(array('controller' => 'articles', 'action' => 'view', 'photo_id' => 1));

Route::set('wiki-view', 'cat-<category>/wiki-<id>-<title>(/photo-<photo_id>)(/comment-<comment_page>)', $artcle_view)
    ->defaults(array('controller' => 'articles', 'action' => 'view', 'photo_id' => 1));

Route::set('set-favorites', 'articles/set-favorites')
    ->defaults(array('controller' => 'articles', 'action' => 'setfavorite'));

// -- Блоги
Route::set('blog', 'blog(/page-<page>)', $page)
    ->defaults(array('controller' => 'blog', 'action' => 'index', 'page' => 1));

Route::set('blog-index', 'blog(/page-<page>)', $page)
    ->defaults(array('controller' => 'blog', 'action' => 'index', 'page' => 1));

Route::set('blog-list', 'blog-<category>(/page-<page>)', $list)
    ->defaults(array('controller' => 'blog', 'action' => 'list', 'page' => 1));

Route::set('blog-operation', 'blog-<category>/<operation>', $topic_operation)
    ->defaults(array('controller' => 'blog', 'action' => 'topic_operation'));

Route::set('topic-view', 'blog-<category>/topic-<id>-<title>', $view)
    ->defaults(array('controller' => 'blog', 'action' => 'topic'));

Route::set('topic-operation', 'blog-<category>/topic-<id>/<operation>', $topic_operation)
    ->defaults(array('controller' => 'blog', 'action' => 'topic_operation'));

// -- Галереи
Route::set('gallery', 'gallery')
    ->defaults(array('controller' => 'gallery', 'action' => 'index'));

// NB: это правило должно идти ДО обявления gallery-list
// NB: из-за некорретной обработки регулярки $category_url_rule
Route::set('photo-add', 'gallery-<category>/add', array('category' => $category_url_rule,))
    ->defaults(array('controller' => 'gallery', 'action' => 'addphoto'));

Route::set('gallery-list', 'gallery-<category>(/page-<page>)', $list)
    ->defaults(array('controller' => 'gallery', 'action' => 'list', 'page' => 1));

Route::set('gallery-view', 'gallery-<category>/photo-<id>(/comments-<comments_page>)', $gallery_view)
    ->defaults(array('controller' => 'gallery', 'action' => 'view', 'comments_page' => 1));



// -- Видео-галереи
Route::set('video-index', 'videogallery(/<page>)', $page)
    ->defaults(array('controller' => 'video', 'action' => 'index', 'page' => 1));

Route::set('video', 'videogallery-<category>(/page-<page>)', $list)
    ->defaults(array('controller' => 'video', 'action' => 'list', 'page' => 1));

Route::set('video-view', 'videogallery-<category>/video-<id>(/comments-<comments_page>)', $gallery_view)
    ->defaults(array('controller' => 'video', 'action' => 'view', 'comments_page' => 1));

// -- Пользователь
Route::set('token', 'user/token.<type>', array('type' => '(js|json)'))
    ->defaults(array('controller' => 'user', 'action' => 'token'));

Route::set('profile', 'user/<id>', $item)
    ->defaults(array('controller' => 'user', 'action' => 'view'));

Route::set('reset', 'reset(/<token>)', array('token' => '[0-9a-f]{32}'))
    ->defaults(array('controller' => 'user', 'action' => 'reset'));

Route::set('login', 'login')
    ->defaults(array('controller' => 'user', 'action' => 'login'));

Route::set('logout', 'logout')
    ->defaults(array('controller' => 'user', 'action' => 'logout'));

Route::set('register', 'register')
    ->defaults(array('controller' => 'user', 'action' => 'register'));

// -- Dumb action
Route::set('index', 'index.php')
    ->defaults(array('controller' => 'main', 'action' => 'index'));

Route::set('temp-consult', 'consult')
    ->defaults(array('controller' => 'main', 'action' => 'consult'));

// -- Default action
Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array('controller' => 'main', 'action' => 'index'));
