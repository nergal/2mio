<?php

class Model_Forum {
	
	public function get_blog_top_commented()
	{
		$sql = 'SELECT * FROM `phpbb3`.phpbb_blogs
				WHERE `blog_reply_count` > 5
				ORDER BY RAND()
				LIMIT 1;';
				
		$topic = DB::query(Database::SELECT, $sql)->execute()->current();

			$blog_text = preg_replace('/\[[^\]]*\]/', '' , $topic['blog_text']);
			$blog_text = preg_replace('/http[^\s]+/', '' , $blog_text);	
		
		$topic['blog_text'] = strip_tags($blog_text);
		
		return $topic;
	}
	
	public function get_top_commented($limit)
	{
		$limit = intVal($limit);
		$sql = "SELECT * FROM `phpbb3`.phpbb_topics
				WHERE `topic_approved` = 1 
				AND FROM_UNIXTIME(`topic_time`) > DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)
				ORDER BY `topic_replies` DESC 
				LIMIT $limit;";
				
		$topics = DB::query(Database::SELECT, $sql)->execute();
		
		return $topics->as_array();
	}
	
	public function get_new($limit)
	{
		$limit = intVal($limit);
		$sql = "SELECT * FROM `phpbb3`.`phpbb_topics` 
				ORDER BY `topic_time` DESC
				LIMIT $limit;";
		
		$topics = DB::query(Database::SELECT, $sql)->execute();
		
		return $topics->as_array();
	}	
	
	public function get_count_all()
	{
		$sql = "SELECT COUNT(1) AS `topic_cnt` FROM `phpbb3`.phpbb_topics WHERE `topic_approved` = 1";
		
		$topic = DB::query(Database::SELECT, $sql)->execute()->current();
		
		return $topic['topic_cnt'];
	}
	
	public function get_username($user_id)
	{	
		$user_id = intVal($user_id);
		$sql = "SELECT username FROM `phpbb3`.phpbb_users
				WHERE `user_id` = '$user_id'
				LIMIT 1;";
		
		$user = DB::query(Database::SELECT, $sql)->execute()->current();
		
		return $user['username'];
	}
	
	public function lasts_topic_by_user_id($id)
	{
		$sql = "	SELECT `tp`.`user_id`, `t`.* 
					FROM `phpbb3`.`phpbb_topics` AS `t`
					LEFT JOIN `phpbb3`.`phpbb_topics_posted` AS `tp` ON `tp`.`topic_id` = `t`.`topic_id` 
					WHERE `tp`.`user_id` = $id
					ORDER BY `t`.`topic_last_post_time` DESC";
					
		$topics = DB::query(Database::SELECT, $sql)->execute();
		
		return $topics->as_array();
	}
	


}
