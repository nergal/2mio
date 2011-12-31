<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_Subscribe extends Blocks_Abstract
{
    public function render()
    {
        $request = Request::initial();
        if (Auth::instance()->logged_in()) {
            $this->template->user = Auth::instance()->get_user();
        }

        if ($request->method() == 'POST') {
            $data = $request->post();

            if (isset($data['email']) AND isset($data['username']) AND isset($data['sections'])) {
                $model = ORM::factory('subscription', array('email' => $data['email']));
                $model->values($data, array('email', 'username', 'sections'));

                $model->hash = md5(microtime(TRUE).uniqid());

                try {
                    $model->save();
                } catch (ORM_Validation_Exception $e) {
                    $this->template->errors = $e->errors('validation');
                }
            }
        }
    }
}
