<?php $this->extend('layout') ?>

<?php $this->block('meta') ?><title><?php echo htmlentities($this->topic->title, ENT_COMPAT, 'UTF-8') ?></title><?php $this->endblock('meta') ?>

<?php $this->block('content') ?>

				<div class="maintext">
					
					<div class="breadcrumbs">
						<?php
							$section = $this->topic->section;
							$breadcrumbs = array('<a href="/">ХОЧУ!ua</a>');
							while ($section->loaded()) {
								$breadcrumbs[] = $this->link($section);
								$section = $section->section;
							}

							echo implode(' / ', $breadcrumbs);
						?>
					</div>
					
<?php if ($this->action == 'view'): ?>

		            <? if ($this->owner === TRUE): ?>
					<div class="rel">
						 <a href="<?= $this->uri($this->topic->section, array('operation' => 'add')) ?>">Добавить запись</a> |
						 <a href="<?= $this->uri($this->topic->section, array('operation' => 'edit')) ?>">Редактировать блог</a> |
						 <a href="<?= $this->uri($this->topic, array('operation' => 'edit')) ?>">Редактировать запись</a> |
						 <a href="<?= $this->uri($this->topic, array('operation' => 'delete')) ?>" onclick="return confirm('Уверенны, что хотите удалить запись?');" >Удалить запись</a> |
					</div>
					<? endif ?>

					<h1 class="top bold"><?php echo $this->topic->title ?></h1>
					<div class="data">
						<?php echo $this->get_blocks(array('like' => array('short' => True)), 'inner'); ?>
						<span class="date"><?php echo Date::formatted_time($this->topic->date, 'd.m.Y') ?></span>
					</div>

					<div class="article-content rel">
						<?php echo $this->topic->body ?>
					</div>

					<div class="data">
						<?php echo $this->get_blocks(array('like'), 'inner'); ?>
					</div>
					<br />
					<p class="data">Автор: <?php echo $this->topic->user->username ?></p>

					<p class="data small">Теги: <a href="#">красота</a>, <a href="#">здоровье</a>, <a href="#">спорт</a></p>

					<?php echo $comments; ?>

<?php elseif ($this->action == 'edit'): ?>

		<div class="form">
			<form method="POST">
					<div class="rel"><h2>Редактировать запись</h2></div>

					<div class="rel">
						Поля, выделенные <strong>жирным</strong> необходимы для заполнения.
					</div>
					
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
					
					<div>
						<label for="body">Заголовок:</label>
						<input type="text" name="title" value="<?php echo $this->topic->title ?>" />
					</div>
					
					<div>
						<label for="body">Сообщение:</label>
						<textarea name="body" id="body"><?php echo $this->topic->body ?></textarea>
					</div>

					<div id="fm-submit" class="fm-req">
						<input type="submit" />
						<a href="<?php echo $this->uri($this->topic) ?>" class="button fm-req">Отмена</a>
					</div>
			</form>
		</div>

<?php endif; ?>

				</div>
					
				<div class="minileft">
<!--sidemenu-->
				<h2 class="top"> &nbsp; <?php echo Helper::filter($this->topic->section->name, Helper::TITLE) ?></h2>
				<div class="sidemenu">
					<ul>
						<?php foreach ($this->sections as $item): ?>
							<?php $class = ($item->id == $this->topic->section->id) ? array('class' => 'active') : NULL; ?>
							<li><?php echo $this->link($item, NULL, $class) ?></li>
						<?php endforeach ?>
					</ul>
					<div class="bott_sm"></div>
				</div>
<!--end sidemenu-->				
			
				</div>
				
<?php $this->endblock('content') ?>
