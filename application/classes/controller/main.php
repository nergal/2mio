<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Основной контроллер
 *
 * @author nergal
 * @package main
 */
class Controller_Main extends Controller_Abstract
{

    /**
     * Главная страница
     *
     * @meta main
     * @uses main/index
     */
    public function action_index()
    {
        $this->template = View::factory('main/index');
        $this->template->index = true;
    }
}
