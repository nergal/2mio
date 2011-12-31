<?php
/**
 * Шаблон формы редактора пользователей
 *
 * @author sokol, 2011
 * @package btlady-admin
*/

$this->extend('layout') 
?>

<?php $this->block('title') ?>Админка Пользователей<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<script type="text/javascript" src="/js/dhtmlx/dhtmlx.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/connector.js" charset="utf-8"></script> 
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlx.css" charset="utf-8"></link> 
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcalendar.js"></script>
<script type="text/javascript" src="/js/dhtmlx/excells/dhtmlxgrid_excell_dhxcalendar.js"></script>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxcalendar.css" charset="utf-8"></link>



<style type="text/css">
	#user { width: 1240px; padding: 5px }
	#userbox { height: 200px; border: 1px solid #CCCCCC; }
	.hidden { display: none; }
	#editor input, #editor select { position: absolute; left: 275px; }
	#editor input[type=text], #editor select { width: 400px; }
	#editor div { margin-bottom: 5px; }
	#new_title { width: 850px }
	#pagingArea{height:8px;}
	.roles_cont .roles_sub_cont {margin:5px;height:400px;float:left;}
	.roles_cont .roles_sub_cont .roles_grid {width:600px;height:360px;}
	.roles_cont .roles_sub_cont button {margin: 5px;}
</style>

<div>
<div style="background:#B8E3F7;border:1px solid #61C4F2">Раздел: <b>Пользователи</b></div>
	<fieldset id="user">
		<legend class="block-title">Все пользователи</legend>
		<div id="grid">
			<div>
				<div id="userbox"></div>
				<div id="pagingArea"></div>
				<div class="buttons">
					<button id="ext_info">Расширенная инф.</button>
					<?php /* <button id="delete">Удалить</button> */ ?>
					<button id="update">Сохранить</button>
				</div>
			</div>
		</div>
		<div id="editor" class="hidden">
		<br />
			<div id="new">
				<input type="text" id="u_id" value="" />
				<div>
					<label for="new_title">Логин</label>
					<input type="text" id="u_login" />
				</div>
				<?php /*
				<div>
					<label for="new_title">Пароль</label>
					<input type="text" id="u_password" size="20" />
				</div>
				*/ ?>
				<div>
					<label for="new_date_end">Email</label>
					<input type="text" id="u_email" size="20" />
				</div>
				<div>
					<label for="new_date_end">Дата регистрации</label>
					<input type="text" id="u_regdate" size="20" readonly="readonly" />
				</div>
				<div>
					<label for="new_date_end">Дата рождения</label>
					<input type="text" id="u_birthdate" size="20" readonly="readonly" />
				</div>
				<div>
					<label for="new_date_end">Последний вход</label>
					<input type="text" id="u_lastlogin" size="20" readonly="readonly" />
				</div>
				<div>
					<label for="new_category">Откуда</label>
					<input type="text" id="u_wherefrom" size="50" />
				</div>
				<div>
					<label for="new_category">Страницы на Facebook</label>
					<input type="text" id="u_facebook" size="255" />
				</div>				
				<div>
					<label for="new_category">Страницы VKontakte</label>
					<input type="text" id="u_vkontakte" size="255" />
				</div>	
				<div>
					<label for="new_category">О себе</label>
					<textarea id="u_about" cols="55" rows="4" style="margin-left: 214px;"></textarea>
				</div>
				<div>
					<label for="new_category">Подпись</label>
					<textarea id="u_sign" cols="55" rows="4" style="margin-left: 200px;"></textarea>
				</div>
				<div>
					<label for="new_category">Интересы</label>
					<textarea id="u_interests" cols="55" rows="4" style="margin-left: 190px;"></textarea>
				</div>
			</div>
			<label for="save">Изменения: </label>
			<button id="save">Принять</button>
			<button id="hide">Скрыть</button>
		</div>
	</fieldset>
	<fieldset id="user">
		<legend class="block-title">Права пользователей</legend>
		<div class="roles_cont">
			<div class="roles_sub_cont">
				<div class="roles_grid" id="bind_roles"></div>	
				<button id="remove_user_roles">Удалить</button>
				<button id="save_user_roles">Сохранить</button>		
			</div>
			<div class="roles_sub_cont">
				<div class="roles_grid" id="all_roles"></div>			
			</div>
		</div>
	</fieldset>
</div>
<script type="text/javascript" src="/js/admin/common.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/admin/user.js" charset="utf-8"></script>

<?php $this->endblock('content') ?>
