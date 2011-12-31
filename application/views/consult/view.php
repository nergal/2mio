<?php $this->extend('layout') ?>

<?php $this->block('meta') ?><title><?php echo htmlentities($this->question->title, ENT_COMPAT, 'UTF-8') ?></title><?php $this->endblock('meta') ?>

<?php $this->block('content') ?>
		    <div id="rules" class="mod simple">
			<b class="top"><b class="tl"></b><b class="tr"></b></b>
			<div class="inner">
				<div class="hd">
					<h2><?php echo $this->question->title ?></h2>
				</div>
				<div class="bd">
					<div class="content">
						<?php echo Text::auto_p($this->question->body) ?>
					</div>

					<ul class="answers">
						<?php foreach ($this->question->answers->find_all() as $answer): ?>
							<li>
								<div class="item" style="border:1px solid #eee;margin:0 0 1em 3em;">
									<p><?php echo ($answer->consultant->user->loaded() ? $this->link($answer->consultant->user) : ('<i>'.$answer->author.'</i>')); ?>, <?php echo $answer->date ?></p>
									<div class="text"><?php echo Text::auto_p($answer->body) ?></div>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php if ($user = Auth::instance()->get_user()): ?>
						<?php if ($this->question->allow_answer($user)): ?>
							<form class="form" method="post" action="">
								<div>
									<label for="answer">Ответ<span>Ваш ответ</span></label>
									<textarea name="answer" id="answer"></textarea>
								</div>

								<div>
									<input class="button" type="submit" value="Ответить" />
								</div>
							</form>
						<?php endif ?>
					<?php endif ?>
				</div>
			</div>
			<b class="bottom"><b class="bl"></b><b class="br"></b></b>
		    </div>
<?php $this->endblock('content') ?>
<br />