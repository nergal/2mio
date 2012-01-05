<?php defined('SYSPATH') or die('No direct script access.');

class Exception_Handler
{
    public static function handle(Exception $e)
    {
        switch (get_class($e)) {
            case 'HTTP_Exception_404': {
                $response = new Response;
                $response->status(404);

                $view = new View('errors/error404');
                Controller_Abstract::add_static();
                if (Kohana::$environment == Kohana::DEVELOPMENT) {
                    $view->message = $e->getMessage();
                }
                echo $response
                    ->body($view)
                    ->send_headers()
                    ->body();

                return TRUE;
                break;
            }
            case 'HTTP_Exception_410': {
                $response = new Response;
                $response->status(410);

                $view = new View('errors/error410');
                Controller_Abstract::add_static();

                echo $response
                    ->body($view)
                    ->send_headers()
                    ->body();

                return TRUE;
                break;
            }
            default: {
                header('C-Data: '.uniqid().str_replace('=', '', base64_encode($e->getMessage())));
                return Kohana_Exception::handler($e);
                break;
            }
        }
    }
}