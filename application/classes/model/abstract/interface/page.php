<?php

interface Model_Abstract_Interface_Page
{
    public function get_page($pagename, $show = FALSE);
    public function get_last($show = TRUE);
    public function get_tree($section, $page = NULL, $limit = 10, $count = FALSE, $order = NULL, $period = NULL);
    public function get_count_tree($section, $period);
    public function valid($title = NULL, $category = NULL);
}
