<?php $this->extend('layout') ?>

<?php $this->block('meta'); echo '<title>'.$this->user->get_user_title().' | Женский сайт Хочу.ua</title>'; $this->endblock('meta') ?>

<?php $this->block('content') ?>
		<div class="container private-profile">
			<!-- начало -->
			<div class="content" style="padding-left:14px; width:627px;">
				<?php if ($this->user->loaded()): ?>
					<div class="rel">
					    <div class="usermenu" style="height:144px;"><div class="add_bg">

						<div class="left" style="width:113px;">
							<span class="myphoto"><img src="<?php echo $this->user->get_picture() ?>" width="100" style="width:100px;max-height:200px" /></span>
						</div>
						<div class="right" style="width:220px;text-align:right;">
							<?php if ( ! $this->user->has('roles', ORM::factory('role', array('name' => 'social')))): ?>
								<?php if ($this->user->username): ?>
									<span>Привет,</span>
									<h2><?php echo $this->user->get_user_title() ?></h2>
									<span>мы тебя любим!</span>
								<?php elseif (Auth::instance()->logged_in() AND $this->user->id == Auth::instance()->get_user()->id): ?>
									<a href="/user/edit/">Пожалуйста, заполните Ваш профиль</a>
								<?php endif; ?>
							<?php else: ?>
								<span>Привет,</span>
								<h2 style="font-size:1.1em" class="wrapped"><?php echo $this->user->get_user_title() ?></h2>
								<span>мы тебя любим!</span>
							<?php endif; ?>
							<br /><br />
							<span class="gray">
								<?php
									$reg_date = date('r', $this->user->user_regdate);

									$today = new DateTime('now');
									$user_date = new DateTime($reg_date);
									$days = $today->diff($user_date)->format('%a');
								?>
							    вместе с &laquo;ХОЧУ&raquo;<br /><strong><?php echo $days.' '.$this->plural($days, 'день', 'дня', 'дней') ?>, </strong><br />с <strong><?php echo Date::formatted_time($reg_date, 'd.m.Y') ?></strong>
							</span>
							<br />
						</div>
						<div class="left" style="width:125px">
							<?php
								$services = array();
								if ($this->user->service_notepad)   $services['notepad']   = array('title' => 'Мой блокнот');
								if ($this->user->service_cookbook)  $services['cookbook']  = array('title' => 'Мои рецепты');
								if ($this->user->service_beauty)    $services['beauty']    = array('title' => 'Моя косметичка', 'link' => $this->uri(ORM::factory('section', array('sections.name_url' => 'cosmetik-opinions'))));
								if ($this->user->service_blog)      $services['blog']      = array('title' => 'Мой блог', 'link' => 'http://forum.bt-lady.com.ua/blog.php?u='.intVal($this->user->id));
								if ($this->user->service_video)     $services['video']     = array('title' => 'Избранное видео');

								/* Ещё не сделаны
								if ($this->user->service_dresses)   $services['dresses']   = 'Мой гардероб';
								if ($this->user->service_tests)     $services['tests']     = 'Мои тесты';
								if ($this->user->service_colortype) $services['colortype'] = 'Мой цветотип';
								if ($this->user->service_body)      $services['body']      = 'Идеальное тело';
								if ($this->user->service_diet)      $services['diet']      = 'Мои диеты';
								*/
								$_services = $services;
							?>
							<?php if ( ! empty($services)): ?>
								<?php while ( ! empty($_services)): ?>
									<?php $_service = array_splice($_services, 0, 5); ?>
									<ul>
										<?php foreach ($_service as $key => $item): ?>
											<li><a href="<?php echo (isset($item['link'])) ? $item['link'] : ('#'.$key) ?>"><?php echo $item['title'] ?></a></li>
										<?php endforeach ?>
									</ul>
								<?php endwhile ?>
							<?php else: ?>
								<p>Вы можете выбрать сервисы <a href="/user/edit/#services">в настройках</a> профиля.</p>
							<?php endif ?>
						</div>
						<div class="clear"></div>

						<span class="bottom_span"></span>
					    </div></div>
					    <?php echo $this->get_blocks(array('horoscope' => array('user' => $this->user)), 'inner') ?>
					    
					    <div class="usermenu">
						<div class="left" style="width:115px;">
						    <span class="ssmall">Последний визит:</span><br />
						    <span class="ssmall strong"><?php echo Date::fuzzy_span(date('r', $this->user->user_lastvisit)) ?></span>
						</div>
						<?php if ( ! $this->user->has('roles', ORM::factory('role', array('name' => 'social')))): ?>
						<div class="left" style="width:170px;"><a href="/user/avatar/" class="ch_ph small">Изменить фотографию</a></div>
						<?php endif ?>
						<div class="left" style="width:170px;"><a class="edit_pr small" href="/user/edit/">Редактировать профиль</a></div>
						<div class="clear"></div>

						<span class="bottom_span"></span>
					    </div>
					    <div class="clear"></div>
					</div>
<!--Конец с фоткой-->

<!--1-я с табом-->
					<div id="" class="rel" style="padding-top:15px;">
						<div>
							<div class="tableft-white">
								<div class="lft"></div>
								<div class="cntr"><div class="text top3">Темы пользователя</div></div>
								<div class="rght"></div>
							</div>
							<div class="box-shad no-top-left-radius">
								<div class="scroll" style="max-height:200px;">
								<ul class="blogul">
									<?php if (empty($this->topics)): ?>
										<li>Твоих тем еще нет</li>
									<?php else: ?>
										<?php foreach ($this->topics as $topic): ?>
											<li><span class="gray"><?php echo Date::fuzzy_span(date('Y-m-d H:i:s', $topic['topic_time'])) ?></span><div>
												<a href="http://forum.bt-lady.com.ua/viewtopic.php?f=<?php echo intVal($topic['forum_id']) ?>&amp;t=<?php echo intVal($topic['topic_id']) ?>" class="none">
													<?php echo Helper::escape($topic['topic_title']) ?>
												</a> - <?php echo intVal($topic['topic_replies_real']) ?> сообщений</div></li>
										<?php endforeach ?>
									<?php endif ?>
								</ul>
								</div>
								<p align="center"><a href="http://forum.bt-lady.com.ua/" class="blue_sub">добавить тему</a></p>
							</div>
						</div>

						<div class="clear"></div>
					</div>
<!--Конец 1-й с табом-->
<?php foreach($services as $service_name => $item): ?>
<?php
	if (isset($item['link'])) continue;
	$service_title = $item['title'];
	$var_name = 'favorites_'.$service_name;

	if (isset($this->{$var_name})) {
		$pool = $this->{$var_name};
	} else {
		continue;
	}
?>
					<br />
<!--2-я с табом-->
					<div id="<?php echo $service_name ?>" class="rel">
						<div>
							<div class="tableft-white">
								<div class="lft"></div>
								<div class="cntr"><div class="text top3"><?php echo $service_title ?></div></div>
								<div class="rght"></div>
							</div>
							<div class="box-shad no-top-left-radius">
								<div class="scroll" style="max-height:1140px;">
									<?php if ( ! $pool->valid()): ?>
										<p>У меня еще нет закладок</p>
									<?php else: ?>
										<?php foreach ($pool as $favorite): ?>
											<div class="contentnews favorite-<?php echo $favorite->article->id; ?>">
												<div class="shad left" ><div class="bording"><img src="<?php echo $this->photo($favorite->article, '135x100'); ?>" alt="" /></div></div>
												<div class="text" style="top: 4px; padding-right:12px;">
													<a href="<?php echo $this->uri($favorite->article) ?>" class="none"><h2 class="top"><?php echo Helper::escape($favorite->article->title) ?></h2></a>
													<p class="small darkgray"><?php echo Helper::escape($favorite->article->date) ?></p>
													<p><?php echo $favorite->article->description ?></p>

													<div class="viewcom">
														<a class="view"><?php echo intVal($favorite->article->views_count) ?></a>&nbsp;
														<a class="comment"><?php echo intVal($favorite->article->comments_count) ?></a>
														<a class="right notepad fromnotepad" name="<?php echo $favorite->article->id; ?>"><span> - из блокнота</span></a>
													</div>
												</div>
												<div class="clear"></div>
											</div>

										<?php endforeach ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
<!--Конец 2-й с табом-->
<?php endforeach ?>
				<?php else: ?>
					Нет такого пользователя.
				<?php endif; ?>
			</div>
			<!-- конец -->

			<b class="bottom"><b class="bl"></b><b class="br"></b></b>
	</div>
<?php $this->endblock('content') ?>
<br />
