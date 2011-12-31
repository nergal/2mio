<?php $this->extend('layout') ?>

<?php $this->block('meta'); echo '<title>'.$this->user->get_user_title().' | Женский сайт Хочу.ua</title>'; $this->endblock('meta') ?>

<?php $this->block('content') ?>
		<div class="container public-profile">
			<!-- начало -->
			<div class="content" style="padding-left:14px; width:627px;">
				<?php if ($this->user->loaded()): ?>
					<div class="rel">
					    <div class="usermenu" style="height:144px;"><div class="add_bg">

						<div class="left" style="width:113px;">
							<span class="myphoto"><img src="<?php echo $this->user->get_picture() ?>" width="100" style="width:100px;max-height:200px" /></span>
						</div>
						<div class="left" style="padding:5px 10px;width:140px;">
							<?php if ( ! $this->user->has('roles', ORM::factory('role', array('name' => 'social')))): ?>
								<?php if ($this->user->username): ?>
								<h2 class="meedlegray normal"><?php echo $this->user->get_user_title() ?></h2>
								<?php elseif (Auth::instance()->logged_in() AND $this->user->id == Auth::instance()->get_user()->id): ?>
								<a href="/user/edit/">Пожалуйста, заполните Ваш профиль</a>
								<?php endif; ?>
							<?php else: ?>
								<h2 style="font-size:1.1em"><?php echo $this->user->get_user_title() ?></h2>
							<?php endif; ?>
							<div style="height:3px;"></div>
							<span class="small">Последнее посещение</span><br />
							<span class="small strong"><?php
							echo $this->user->user_lastvisit
								 ? Date::formatted_date(date('r', $this->user->user_lastvisit), 'j M Y')
								 : 'ещё не было';
							?></span>
							
							<br />
							<span class="gray">
								<?php
									$reg_date = date('r', $this->user->user_regdate);

									$today = new DateTime('now');
									$user_date = new DateTime($reg_date);
									$days = $today->diff($user_date)->format('%a');
								?>
							    Вместе с &laquo;ХОЧУ&raquo;<br /><strong><?php echo $days.' '.$this->plural($days, 'день', 'дня', 'дней') ?>, </strong> с <strong><?php echo Date::formatted_time($reg_date, 'd.m.Y') ?></strong>
							</span>
						</div>
						<div class="right line" style="width:310px;">
							<?php if ($this->user->show_email): ?>
							<div class="unit size1of2">
								<div class="meedlegray">E-mail:</div>
								<?php echo HTML::mailto($this->user->user_email) ?>
							</div>
							<?php endif ?>

							<?php if ($this->user->user_from OR $this->user->user_birthday): ?>
								<?php
									$date = $this->user->user_birthday;
									$date = preg_replace('/[^-\d\.\/]/', '', $date);
									$data = array(
										Helper::escape($this->user->user_from),
										Date::formatted_time($date, 'd M Y'),
										__(Helper::get_horo($date))
									);
									$data = array_filter($data);

									$data = implode('; ', $data);
								?>
								<?php if ( ! empty($data)): ?>
								<div class="unit size1of2">
									<div class="meedlegray">Личная информация</div>
									<?php echo $data ?>
								</div>
								<?php endif ?>
							<?php endif ?>

							<?php if ($this->user->user_interests): ?>
							<div class="unit size1of2">
								<div class="meedlegray">Интересы:</div>
								<?php echo Text::auto_p(Helper::escape($this->user->user_interests, Helper::BODY)) ?>
							</div>
							<?php endif ?>

							<?php if ($this->user->user_brands): ?>
							<div class="unit size1of2">
								<div class="meedlegray">Бренды:</div>
								<?php echo Text::auto_p(Helper::escape($this->user->user_brands, Helper::BODY)) ?>
							</div>
							<?php endif ?>
						</div>
						<div class="clear"></div>

						<span class="bottom_span"></span>
					    </div></div>
					    <div class="clear"></div>
					</div>
<!--Конец с фоткой-->

<!--1-я с табом-->
					<div class="rel" style="padding-top:15px;">
						<div class="">
							<div class="tableft-white">
								<div class="lft"></div>
								<div class="cntr"><div class="text top3">Темы пользователя</div></div>
								<div class="rght"></div>
							</div>
							<div class="box-shad no-top-left-radius">
								<ul class="blogul">
									<?php if (empty($this->topics)): ?>
										<li>У пользователя еще нет сообщение.</li>
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
						</div>

						<div class="clear"></div>
					</div>
<!--Конец 1-й с табом-->
					<br />
<!--2-я с табом-->
<?php if ($this->user->service_notepad): ?>
					<div id="notepad" class="rel">
						<div>
							<div class="tableft-white">
								<div class="lft"></div>
								<div class="cntr"><div class="text top3">Блокнот пользователя</div></div>
								<div class="rght"></div>
							</div>
							<div class="box-shad no-top-left-radius">
								<div class="scroll" style="max-height:1140px;">
									<?php if ( ! (isset($this->favorites_notepad) AND $this->favorites_notepad->valid())): ?>
										<li>У пользователя нет закладок</li>
									<?php else: ?>
										<ul>
										<?php foreach ($this->favorites_notepad as $favorite): ?>
											<div class="contentnews" id="favorite-<?php echo $favorite->article->id; ?>">
												<div class="shad left" ><div class="bording"><img src="<?php echo $this->photo($favorite->article, '135x100'); ?>" alt="" /></div></div>
												<div class="text" style="top: 4px; padding-right:12px;">
													<a href="<?php echo $this->uri($favorite->article) ?>" class="none"><h2 class="top"><?php echo Helper::escape($favorite->article->title) ?></h2></a>
													<p class="small darkgray"><?php echo Helper::escape($favorite->article->date) ?></p>
													<p><?php echo $favorite->article->description ?></p>

													<div class="viewcom">
														<a class="view"><?php echo intVal($favorite->article->views_count) ?></a>&nbsp;
														<a class="comment"><?php echo intVal($favorite->article->comments_count) ?></a>
													</div>
												</div>
												<div class="clear"></div>
											</div>

										<?php endforeach ?>
										</ul>
									<?php endif; ?>

								</div>
							</div>
						</div>

						<div class="clear"></div>
					</div>
<?php endif ?>
<!--Конец 2-й с табом-->
				<?php else: ?>
					Нет такого пользователя.
				<?php endif; ?>
			</div>
			<!-- конец -->

			<b class="bottom"><b class="bl"></b><b class="br"></b></b>
	</div>
<?php $this->endblock('content') ?>
<br />
