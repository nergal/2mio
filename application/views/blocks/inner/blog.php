					<div class="right col2">
						<div class="blogs citata">
							<h3 class="top"> &nbsp; Популярное в блогах:</h3>
							<div class="citata">
								<a href="http://forum.bt-lady.com.ua/blog.php?u=<?php echo $this->topic['user_id'] ?>&b=<?php echo $this->topic['blog_id'] ?><?php // echo $this->uri($this->topic) ?>" class="none gray"><?php echo text::limit_chars($this->topic['blog_text'], 255) // echo text::limit_chars($this->topic->description, 255) ?></a>
							</div>
						</div>
						<p align="right"><a href="http://forum.bt-lady.com.ua/memberlist.php?mode=viewprofile&u=<?php echo $this->topic['user_id'] ?><?php // echo $this->uri($this->topic->section) ?>" class="none">Авторская колонка: <?php echo $this->user_name; ?><?php // echo $this->topic->user->username; ?></a></p>
					</div>
					<div class="clear"></div>
