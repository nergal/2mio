<?php $url = $this->uri($this->page); ?>
<script type="text/javascript">$(function() {$('#hello_bots').val('<?php echo $this->hello_bots; ?>');});</script>		
<div id="comments">
        <div class="middle_line">
		<a href="#comments-form" id="add-comment2"><img src="/i/add_comment.png" alt="Комментировать" /></a>
		<h3>комментарии</h3>
		<span class="q_com">&mdash; <?php echo intVal($this->count); ?> <?php echo Helper::plural($this->count, 'сообщение', 'сообщения', 'сообщений') ?></span>

	</div>

	<div class="comments-list">
            <div id="pager">
                <?php echo $this->pager ?>
            </div>

            <ul>
		<?php 
			$total_comments = count($this->comments);
			$i = 1;
			foreach ($this->comments as $key => $comment): ?>
			
			<?php if ($i == $total_comments): ?>
				<a name="last_comment"></a>
			<?php endif; $i=$i+1; ?>			
		
                        <li<?php ($key % 2) ?: print(' class="anbg"') ?> id="<?php echo $comment->id ?>">
				<?php if (Auth::instance()->logged_in('admin')): ?>
					<form class="admin" action="<?php echo $url ?>" method="post">
						<input type="hidden" name="id" value="<?php echo $comment->id ?>" />
						<input type="hidden" name="action" value="delete" />
						<input type="submit" value="" class="admin-btn delete" />
					</form>
				<?php endif ?>
				<div class="avatar left">
					<a><img src="<?php echo $comment->user->get_picture($comment->email) ?>" width="38" height="38" /></a>
				</div>
				<div class="text left">
                                    <p>
                                        <a href="#reply" class="reply-button right"><img src="/i/reply_button.png" alt="Ответить" /></a>
                                        <?php echo Helper::filter($comment->get_user_link(), Helper::COMMENT) ?> &mdash;
                                        <span class="comment_time small gray"><?php echo Date::formatted_time($comment->date, 'd/m/Y, H:i') ?></span>
                                        <div class="clear"></div>
                                    </p>

                                    <div class="comment-body">
                                    <?php echo Text::auto_p(Helper::filter($comment->body, Helper::COMMENT)) ?>
                                    </div>
				</div>
                <div class="clear"></div>
			</li>
		<?php endforeach; ?>
            </ul>

            <div id="pager">
                <?php echo $this->pager ?>
            </div>
	</div>

    <!--Блок Добавить комментарий-->
        <?php if ( ! empty($this->errors)): ?>
            <div class="message error txtC">
                    <h4>Возникли следующие ошибки при заполнении формы:</h4>
                    <ul>
                    <?php foreach ( (array) $this->errors as $error): ?>
                            <li><?php echo $error ?></li>
                    <?php endforeach ?>
                    </ul>
            </div>
        <?php endif ?>
        <script>
            $(function() {
                $('#comment-submit').click(function() {
                    $(this).parent('form').submit();
                    return false;
                });
            });
        </script>

        <a name="comments-form"></a>
	<div class="get_mess form" id="comment_form">
		<h3>добавить комментарий</h3>
		<div class="clear"></div>
		<br />
		<form action="<?php echo $url ?>#last_comment" method="post">
			<div class="clear">
				<label for="user_name">Ваше имя <span>Пустое поле<br /> для анонимного ответа</span></label>
				<input type="text" value="<?php (!Auth::instance()->logged_in()) ?: print($this->author) ?>" name="author" id="user_firstname" class="fancy">
			</div>
			<div class="clear">
				<label for="user_body">Текст</label>
				<textarea rows="4" name="body" id="body" class="fancy"><?php echo $this->body ?></textarea>
                                <input type="hidden" name="hello_bots" id="hello_bots" value="<?php echo md5(microtime(TRUE).uniqid()) ?>" />
				<div class="clear"></div>
			</div>
			<div class="left hidden">
				<span class="ch">
					<input type="checkbox" style="margin: 0pt; padding: 0pt; width: auto;" name="show_birthday" id="user_show_birthday">
				</span>
				<label for="user_show_birthday" class="auto_label"><span style="text-align: left;width:auto;">Подписаться на комментарии</span></label>
			</div>
			<a class="right" href="#" id="comment-submit"><img src="/i/add_comment.png" alt="Добавить комментарий"></a>
			<div class="clear"></div>
		</form>
	</div>
    <!--Конец Блок Добавить комментарий-->
</div>
<!-- /end comments -->
