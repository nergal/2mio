<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_Comments extends Blocks_Abstract
{
	/**
	 * Комментариев на страницу
	 * @var integer
	 */
	protected $_per_page = 10;

    public function render()
    {
		$this->template->errors = FALSE;
		$this->template->body = FALSE;
		$this->template->author = FALSE;
		$this->template->email = FALSE;
		$this->template->limited = FALSE;
                $this->template->user = Auth::instance()->get_user();

		$page = $this->request->query('model');
		$this->template->page = $page;

		$request = Request::initial();

    	if ($request->method() == 'POST') {
    		if ($request->post('action') == 'delete') {

    			if (Auth::instance()->logged_in('admin')) {
    				$id = $request->post('id');

					$comment = ORM::factory('comment', $id);
					$comment->delete();

					$page->decrement_comments_count();
    			} else {
    				throw new HTTP_Exception_403('You are not allowed to proceed this action');
    			}
    		} elseif ($request->post('action') == 'edit') {
    		    if (Auth::instance()->logged_in('admin')) {
    		    	$id = $request->post('id');
    				$body = strip_tags($request->post('value'));

					$comment = ORM::factory('comment', $id);
					$comment->body = $body;
					$comment->update();

					$this->template = NULL;
					$body = Text::auto_p($body);

					// FIX: злобный хак, нужно как-нибудь исправить
					echo $body;
					die();
    			} else {
    				throw new HTTP_Exception_403('You are not allowed to proceed this action');
    			}
    		} elseif ($request->post('hello_bots') != '') {
	    		$comment = ORM::factory('comment');
	    		
	    		$hash = $request->post('hello_bots');
	    		if (Security::check($hash)) {
				if ($user = Auth::instance()->get_user()) {
					$comment->user = $user;
					$comment->email = $user->email;
				} else {
					$comment->email = $request->post('email');
				}

				$comment->body = $request->post('body');
				$comment->author = $request->post('author');
                                $comment->topic = $request->post('comment_subscribe');

				$comment->ip = ip2long(Request::$client_ip);
				$comment->page = $page;

				try {
					$comment->save();

					$page->comments_count = $page->comments_count+1;
					$page->update();
                                        $subscribers = $page->comments->subscribers($page->id);
                                        foreach ($subscribers as $subscriber)
                                        {
                                            $data = array('sub_email'=>$subscriber->email,
                                                'sub_name'=>$subscriber->author,
                                                'page_url'=>substr(URL::base('http'), 0, -1).$this->template->uri($page),
                                                'page_title'=>$page->title,
                                                'com_body'=>$comment->body,
                                                'com_name'=>$comment->author);
                                            Queue::instance()->add('comment', $data);
                                        }
				} catch (ORM_Validation_Exception $exception) {
					$this->template->errors = $exception->errors('validation');

					$this->template->body = $comment->body;
					$this->template->author = $comment->author;
					$this->template->email = $comment->email;
				}
			}
    		    }
		    $this->template->hello_bots = Security::token(TRUE);
		} else {
		    $this->template->hello_bots = Security::token(FALSE);
		}

		$per_page = $this->_per_page;

		$comment_page = $request->param('comment_page', 1);

		list($count, $comments) = $page->comments->fetch($comment_page, $comment_page, $per_page);
		$this->template->count = $count;
		$this->template->comments = $comments;
                
		$pagination_config = array(
			'current_page' => array('source' => 'route', 'key' => 'comment_page'),
			'items_per_page' => $per_page,
			'total_items' => $count,
			'view' => 'floating'
                );

                $_request = clone Request::$current;
		Request::$current = clone Request::$initial;

		$this->template->pager = Pagination::factory($pagination_config)->render();

		Request::$current = clone $_request;
		unset($_request);
    }
}
