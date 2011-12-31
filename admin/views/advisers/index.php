<?php
/**
 * Шаблон формы Консультантов
 *
 * @author sokol, 2011
 * @package btlady-admin
*/

$this->extend('layout') 
?>

<?php $this->block('title') ?>Админка консультантов<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlx.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/connector.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxtabbar.js"></script>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/Charts/FusionCharts.js"></script>
<script type="text/javascript" src="/js/forms/jquery.forms.2.84.js"></script>
<link rel="stylesheet" type="text/css" href="/js/dhtmlx/css/dhtmlx.css" />
<link rel="stylesheet" type="text/css" href="/js/dhtmlx/css/dhtmlxcalendar.css" />
<link rel="stylesheet" type="text/css" href="/js/dhtmlx/css/dhtmlxcalendar_dhx_skyblue.css" />
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxtabbar.css" charset="utf-8"></link>
<style type="text/css">
.filter #filter {
	cursor:pointer;
}
.filter #filter_block {
	display:none;
}
.consults,
.filter {
	background: none #E5F2F8;
	border-radius:3px;
	-moz-border-radius:3px;
}
.block_title {
	background: none #B8E3F7;
	border:1px solid black;
	padding:0px 3px;
}
.filter_table {
	margin: 0 0 5px 0px;
}
.filter_table td span {
	margin: 0 0 0 10px;
	font-size:12px;
}
.filter_table td button {
	margin: 0 0 0 10px;
}
.consults .consult_gird {
	height:210px;
}
#tabbar {
	height:400px;
	margin:10px 0 0 0;
	font-size:13px;
}
#tabbar #answers {
	height: 400px;
}

#tabbar #tab_consult_activity,
#tabbar #tab_write_letter {
	padding:10px;
	background: none #E5F2F8;
	height:400px;
	font-size:13px;
}
#tabbar #tab_write_letter #letter_title{
	width:350px;
}
#tabbar #tab_write_letter .letter_body_cont{
	margin: 5px 0 0 0;
}
#tabbar #tab_write_letter #send_letter{
	margin: 5px 0 0 0;
}
.info_cont {
	height:350px;
	overflow-y:auto;
	background:none white;
	padding:10px;
}
#tab_adv_info .info_cont td.name {
	text-align:right;
}
#tab_adv_info .info_cont td {
	font-size:13px;
	padding:2px;
}
#tab_adv_info .info_cont td input[type="text"] {
	width: 400px;
}
.bottom_buttons {
	padding: 5px 0;
}
#tab_specialty .info_cont table td {
	padding: 2px 10px 2px 0;
}
#tab_specialty .info_cont label {
	font-weight:normal;
}
.message {
	padding: 5px;
	font-size: 13px;
	font-weight:bold;
	border: 1px solid red;
	display:none;
}
#adv_photo_cont {
	text-align:center;
	width: 100px;
	margin: 5px;
	display:none;
}
#diplm_photo_cont .dipl_cont {
	width:200px;
	height:180px;
	padding:5px;
	text-align:center;
	float:left;
}
</style>

<div class="block_title">Раздел: <strong>Консультанты</strong></div>
<fieldset class="filter">
	<legend class="block-title" id="filter">Фильтр <small>(скрыть/показать)</small></legend>
	<div id="filter_block">
		<table class="filter_table">
			<tbody>
				<tr>
					<td><span>Месяц:</span></td>
					<td>
						<select name="date_month" id="date_month">
							<option value="0">Все</option>
							<option value="1">Январь</option>
							<option value="2">Февраль</option>
							<option value="3">Март</option>
							<option value="4">Апрель</option>
							<option value="5">Май</option>
							<option value="6">Июнь</option>
							<option value="7">Июль</option>
							<option value="8">Август</option>
							<option value="9">Сентябрь</option>
							<option value="10">Октябрь</option>
							<option value="11">Ноябрь</option>
							<option value="12">Декабрь</option>
						</select>
						<span>Год:</span>
						<select name="date_year" id="date_year">
							<option value="0">Все</option>
							<?php for($i = date('Y'); $i > date('Y') - 10; $i --): ?>
							<option value="<?= $i ?>"><?= $i ?></option>
							<?php endfor; ?>
						</select>
					</td>
					<td><span>Специализация:</span></td>
					<td>
						<select name="speciality" id="speciality">
							<option value="0">Все</option>
							<?php foreach($this->specialties as $specialty): ?>
							<option value="<?= $specialty['id'] ?>"><?= $specialty['name'] ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td><span class="status">Ответы:</span></td>
					<td>		
						<select name="status" id="status">
							<option selected="" value="all">Все</option>
							<option value="withans">с ответом</option>
							<option value="noans">без ответа</option>
						</select>
					</td>
					<td><button id="submit_filter_adv">Фильтр</button></td>
				</tr>
			</tbody>
		</table>
	</div>
</fieldset>

<fieldset class="consults">
	<legend class="block-title">Консультанты</legend>
	<div class="consult_gird" id="consult_gird"></div>
</fieldset>

<div id="tabbar">
	<div id="tab_adv_info">
		<div class="info_cont">
			<table>
				<tr>
					<td class="name">Фамилия</td>
					<td><input type="text" id="dsname" /></td>
				</tr>
				<tr>
					<td class="name">Имя</td>
					<td><input type="text" id="dname" /></td>
				</tr>
				<tr>
					<td class="name">Отчество</td>
					<td><input type="text" id="dlname" /></td>
				</tr>
				<tr>
					<td class="name">Страна</td>
					<td>
						<select id="dcountry">
							<option value="0">-- Страна --</option>
							<?php foreach($this->countries as $country): ?>
							<option value="<?= $country['id'] ?>"><?= HTML::entities($country['name']) ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="name">Город</td>
					<td>
						<select id="dcity">
							<option value="0">-- Город --</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="name">Email</td>
					<td><input type="text" id="demail" /></td>
				</tr>
				<tr>
					<td class="name">Телефоны</td>
					<td><input type="text" id="dphone" /></td>
				</tr>
				<tr>
					<td class="name">Диплом</td>
					<td><input type="text" id="ddiploma" /></td>
				</tr>
				<tr>
					<td class="name">Описание</td>
					<td><textarea id="ddescription"></textarea></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="tab_specialty">
		<div class="info_cont">
		<table id="sp_chckbx_cont">
		<?php for($i = 0; $i < count($this->specialties); $i ++): ?>
		<?= (($i % 3 == 0 && $i) ? '</tr><tr>' : (($i % 3 == 0 && !$i) ? '<tr>' : '')) ?>
		<td><label><input type="checkbox" value="<?= $this->specialties[$i]['id'] ?>" /><?= HTML::entities($this->specialties[$i]['name']); ?></label></td>
		<?php endfor; ?>
		</tr>
		</table>
		</div>
	</div>
	<div id="tab_photo">
		<div class="info_cont">
			<div class="message" id="photo_message"></div>
			Фото доктора:
			<div>
				<form id="photo_form" enctype="multipart/form-data">
					<input type="file" id="photo_file" name="photo" />
					<input type="button" id="upload_phote" value="Загрузить" />
				</form>
			</div>
			<div id="adv_photo_cont">
				<img id="adv_photo" src="#" width="100" height="100" /><br />
				<button id="rm_adv_photo">Удалить</button>
			</div>
			<hr />
			<div class="message" id="diploma_message"></div>
			Фото дипломов:
			<div>
				<form id="diploma_form" enctype="multipart/form-data">
					<input type="file" id="diploma_file" name="photo" />
					<input type="button" value="Загрузить" id="upl_diploma_photo" />
				</form>
			</div>
			<div id="diplm_photo_cont"></div>
		</div>
	</div>
	<div id="tab_answers">
		<div id="answers"></div>
	</div>
	<div id="tab_consult_activity">
		<div id="chart_year" align="center"></div>
	</div>
	<div id="tab_write_letter">
		<strong>Тема письма:</strong> <input type="text" id="letter_title" /><br />
		<strong>Коды консультантов:</strong> <span id="consult_codes">не выбрано</span><br />
		<div class="letter_body_cont">
			<textarea id="letter_body"></textarea>
		</div>
		<button id="send_letter">Отправить письмо</button>
	</div>
</div>
<div class="bottom_buttons">
	<button id="btn_save">Сохранить изменения</button>
</div>

<script type="text/javascript">
var domain = '<?= $this->domain ?>';
var advisers_params = {
	urlData: 'http://<?= $this->domain ?>/admin/advisers/advisers/',
	colTitles: '+,ID,Логин,Специализация,ФИО,Дата,Ответ.,Рейтинг,Контакты,Профайл,,,,,,,,,,',
	colFilters: ',#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,,,,,,,,,,,',
	colWidth: '30,80,80,180,*,120,60,60,180,80,0,0,0,0,0,0,0,0,0,0',
	colTypes: 'ch,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro',
	colAlign: 'center,,,,,,,,,center,,,,,,,,,,',
	colSorting: "na,connector,connector,connector,connector,connector,connector,connector,connector,na,na,na,na,na,na,na,na,na,na,na"
};

var answers_params = {
	urlData: 'http://<?= $this->domain ?>/admin/advisers/answers/',
	colTitles: 'ID ответа,ID вопроса,Дата,Ответ',
	colFilters: '#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter',
	colWidth: '100,100,120,*',
	colTypes: 'ro,ro,ro,txt',
	colAlign: '',
	colSorting: 'connector,connector,connector,connector'
};

var sendletter_params = {
	urlData: 'http://<?= $this->domain ?>/admin/advisers/sendletters/'
};
</script>
<script type="text/javascript" src="/js/admin/advisers.js" charset="utf-8"></script>
<?php $this->endblock('content') ?>
