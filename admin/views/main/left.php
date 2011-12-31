<?php $this->extend('layout') ?>

<?php $this->block('title') ?>Меню модулей админки<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<ul class="menu">
  <li><a href="<?=$this->base?>pages/" target="contFrame">Материалы</a></li>
  <li><a href="<?=$this->base?>redirects/" target="contFrame">Редиректы</a></li>
  <li><hr/></li>
  <li><a href="<?=$this->base?>banners/" target="contFrame">Баннеры</a></li>
  <li><a href="<?=$this->base?>informers/" target="contFrame">Информеры RSS</a></li>  
  <li><a href="<?=$this->base?>admin/cache/" target="contFrame">Очистка кэша</a></li>
  <li><hr/></li>
  <li><a href="<?=$this->base?>users/" target="contFrame">Пользователи</a></li>
  <li><hr/></li>
<!--
  <li><a href="<?=$this->base?>blogs/" target="contFrame">Блоги</a></li>
  <li><hr /></li> 
  <li><a href="<?=$this->base?>answers/" target="contFrame">Ответы консультантов</a></li>
  <li><a href="<?= $this->base ?>advisers/" target="contFrame">Консультанты</a></li>
  <li><hr /></li> -->

  <li><a id="maxframe" href="javascript:void(0)">Увеличить фрейм</a></li>
  <li><hr/></li>
  <li><a href="/" target="_top">Перейти на сайт</a></li>
  <li><a href="/logout/" target="_top">Выход</a></li>
</ul>
<ul>
  <li>Авторизован как: <a href="mailto:<?=$this->email?>"><?=$this->user?></a></li>
</ul>

<?php $this->endblock('content') ?>
