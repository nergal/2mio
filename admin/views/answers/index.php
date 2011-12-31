<?php
/**
 * Шаблон формы "Ответы консультантов"
 *
 * @author sokol, 2011
 * @package btlady-admin
 */

$this->extend('layout') 
?>
<?php $this->block('title') ?>Ответы консультантов<?php $this->endblock('title') ?>
<?php $this->block('content') ?>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlx.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/connector.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxtabbar.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcalendar.js"></script>
<script type="text/javascript" src="/js/forms/jquery.forms.2.84.js"></script>
<link rel="stylesheet" type="text/css" href="/js/dhtmlx/css/dhtmlx.css" />
<link rel="stylesheet" type="text/css" href="/js/dhtmlx/css/dhtmlxcalendar.css" />
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxtabbar.css" charset="utf-8"></link>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxcalendar.css" charset="utf-8"></link>
<style type="text/css">
.block_title {
	background: none #B8E3F7;
	border:1px solid black;
	padding:0px 3px;
}
fieldset {
    font-size: 12px;
	background: none repeat scroll 0 0 #E5F2F8;
	border-radius: 3px 3px 3px 3px;
}
#filter {
	cursor:pointer;
}
#question_grid {
	height:210px;	
}
.button_cont {
	margin: 5px 0;	
}
#tabbar {
	height:350px;
	margin:10px 0 0 0;
	font-size:13px;
}
.qa_text {
	padding:10px;	
}
.qa_text input[type="text"] {
	width:400px;	
}
.qa_text textarea {
	width:400px;
	height:200px;	
}
#tab_add_answer input[type="text"] {
	width:440px;	
}
#tab_send_quest input[type="text"] {
	width: 520px;
	margin: 3px 0;
}
#tab_add_answer table,
#tab_send_quest table {
	margin:5px;	
}
#add_ans_advisers,
#quest_mail_adv {
	width:510px;
	height:235px;
	margin-left: 15px;
}
#tab_add_answer #ans_text {
	width:570px;
	height:200px;	
}
#tab_photo {
	padding:10px;	
}
.load_circle {
	float:right;
}
.preview {
	width:122px;
	height:150px;	
	margin:10px;
	text-align:center;
}
</style>
<div class="block_title">Раздел: <strong>Ответы консультантов</strong></div>
<fieldset>
	<legend id="filter" class="block-title">Фильтр <small>(скрыть/показать)</small></legend>
	<div id="filter_body">
		<table class="filter_table">
			<tbody><tr>
				<td valign="top">
					Дата от:<input type="text" style="width:100px;" readonly="readonly" id="date_from" value="<?= $this->dateFrom; ?>" /> 
					до: <input type="text" style="width:100px;" readonly="readonly" id="date_to" value="<?= $this->dateTo; ?>" />
				</td>
				<td>Специализация:</td>
				<td>
					<select name="speciality" id="speciality">
						<option selected="" value="0">Все</option>
						<?php foreach($this->specialties as $specialty): ?>
						<option value="<?= $specialty['id'] ?>"><?= HTML::entities($specialty['name']); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td>Вопрос:</td>
				<td>
					<select style="width:100px" id="status">
					  <option selected="" value="all">Все</option>
					  <option value="withans">с ответом</option>
					  <option value="noans">без ответа</option>
					</select>
				</td>
				<td><button id="submit_filter">Фильтр</button></td>
			</tr>
		</tbody></table>
	</div>
</fieldset>
<fieldset>
	<legend class="block-title">Вопросы</legend>
	<div id="question_grid"></div>
	<div class="button_cont">
		<button id="save_btn">Сохранить</button>
		<button id="remove_btn">Удалить</button>
		<img class="load_circle" id="load_circle" src="/i/loader_circle.gif">
	</div>
</fieldset>
<div id="tabbar">
	<div id="tab_title_body" class="qa_text">
		<table>
			<tr>
				<td>Название:</td>
				<td><input type="text" id="text_title" /></td>
			</tr>
			<tr>
				<td>Текст:</td>
				<td><textarea id="text_body"></textarea></td>
			</tr>
			<tr>
				<td><button id="btn_edit">Ok</button></td>
				<td></td>
			</tr>
		</table>
	</div>
	<div id="tab_send_quest">
		<table border="0">
			<tr>
				<td>
					Тема: <input type="text" id="quest_mail_title" />
					<div id="quest_mail_text"></div>
				</td>
				<td>
					<div id="quest_mail_adv"></div>
				</td>
			</tr>
			<tr>
				<td><button id="send_mail">Отправить</button></td>
				<td></td>
			</tr>
		</table>
	</div>
	<div id="tab_add_answer">
		<table border="0">
			<tr>
				<td colspan="2">Код вопроса: <span id="a_for_q_codes"></span></td>
			</tr>
			<tr>
				<td>
					От консультанта: <input type="text" readonly="readonly" id="adviser_name" />
					<textarea id="ans_text"></textarea>
				</td>
				<td>
					<div id="add_ans_advisers"></div>
				</td>
			</tr>
			<tr>
				<td><button id="add_answer">Добавить</button></td>
				<td></td>
			</tr>
		</table>
	</div>
	<div id="tab_photo">
		<form id="photo_form" enctype="multipart/form-data">
			Фото: 
			<input type="file" name="photo" />
			<input type="button" value="Загрузить" id="upload_photo" />
		</form>
		<div id="photo_preview"></div>
	</div>
</div>
<script type="text/javascript">
var tree_params = {    
	urlData: 'http://<?=$this->domain?>/admin/answers/get_questions/',
	kidsUrlData: 'http://<?= $this->domain ?>/admin/answers/get_answers/',
	colTitles: '+,ID,Ответ.,Специализация,Автор,Название,Вопрос,Дата,Рейтинг,Ссылка,Показ.,,,,',
	colFilters: ',#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,,,,,,',
	colWidths: '30,120,50,120,160,200,*,115,60,60,50,0,0,0,0',
	colTypes: 'ch,tree,ro,ro,ro,ro,ro,ro,ro,link,ch,ro,ro,ro,ro,ro,ro,ro',
	colAlign: 'center,left,center,left,left,left,left,center,center,center,center,,,,',
	colSorting:",connector,connector,connector,connector,connector,connector,connector,,,,,,,"
};

var advisers_params = {
	urlData: 'http://<?= $this->domain ?>/admin/answers/get_advisers/',
	colTitles: '+,ФИО,email,Специализация',
	colFilters: ',#connector_text_filter,#connector_text_filter,#connector_text_filter',
	colWidths: '30,160,130,*',
	colTypes: 'ch,ro,ro,ro',
	colAlign: 'center,left,left,left',
	colSorting: ',connector,connector,connector'
};
var params = {
	urlMail: 'http://<?= $this->domain ?>/admin/answers/send_letter/',
	urlAddAnswer: 'http://<?= $this->domain ?>/admin/answers/add_answer/',
	urlUploadPhoto: 'http://<?= $this->domain ?>/admin/answers/upload_photo/',
	urlGetPhoto: 'http://<?= $this->domain ?>/admin/answers/get_photo/',
	urlPhotoPreview: 'http://<?= $this->domain ?>/thumbnails/',
	urlRemovePhoto: 'http://<?= $this->domain ?>/admin/answers/remove_photo/'
}
</script>
<script type="text/javascript" src="/js/admin/answers.js?v=001" charset="utf-8"></script>
<?php $this->endblock('content') ?>
