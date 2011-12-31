<?php
/**
 * Шаблон формы редактора материалов
 *
 * @author kolex, 2011
 * @package btlady-admin
*/

$this->extend('layout') 
?>

<?php $this->block('title') ?>Админка материалов<?php $this->endblock('title') ?>

<?php $this->block('content') ?>

<!-- begin js controls -->
<script type="text/javascript" src="/js/dhtmlx/dhtmlx.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/connector.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcalendar.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxtabbar.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxwindows.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcontainer.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcalendar.js"></script>
<script type="text/javascript" src="/js/dhtmlx/excells/dhtmlxgrid_excell_dhxcalendar.js"></script>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="/js/jqWindowsEngine/jquery.windows-engine.js"></script>
<script type="text/javascript" src="/js/jrac/jrac/jquery.jrac.js"></script>
<script type="text/javascript" src="/js/jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>
<!-- end js controls -->
<!-- begin css controls -->
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlx.css" charset="utf-8"></link> 
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxtabbar.css" charset="utf-8"></link>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxcalendar.css" charset="utf-8"></link>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxwindows.css">
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxwindows_dhx_skyblue.css">
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxcalendar.css">
<link type="text/css" rel="stylesheet" href="/js/jqWindowsEngine/jquery.windows-engine.css">
<link type="text/css" rel="stylesheet" href="/js/jrac/jrac/style.jrac.css">
<link type="text/css" rel="stylesheet" href="/js/jquery-ui/css/smoothness/jquery-ui-1.8.16.custom.css">
<!-- end css controls -->

<!-- begin controls layout -->
<style type="text/css">
.but, input[type="text"], label {
	font-size:9pt;
}
#tabaddtion button {
	margin:5px 5px 0 0;
}
</style>
<table border="0">
  <tr>
    <td>
    <fieldset id="fsecs" style="width:800px;height:327px"><legend class="block-title">Разделы</legend>
    <div id="secgridbox"
    style="position:relative;width:340px;top:1px;height:253px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px"></div>
    <div id="pagesgridbox"
    style="position:absolute;left:360px;top:33px;width:450px;height:253px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px">
    </div>
 
	<div style="width:345px;float:left;">
		<span id="sec_animation" class="ajax-animation"><img src="/i/loader_circle.gif"></span>
		<label>Назв.:</label><input type="text" id="sec_title" style="width:100px;" />
		<label>урл:</label><input type="text" id="sec_url" style="width:100px;" />
		<span id="sec_votes_container" style="display: none;"><label>Голос:</label><input type="checkbox" id="sec_votes_active" /></span><br />
		<input type="hidden" id="sec_url_old" />
		<button id="butsecadd" class="but" style="padding-left:1px;">Добавить</button> 
		<button id="butsecupd" class="but" style="padding-left:1px;">Сохранить</button>
		<button id="butsecdel" class="but">Удалить</button>
		<label>Показ.:</label><input type="checkbox" id="sec_show" />
		<label>Поряд.:</label><input type="text" id="sec_order" />
	</div>
	<div style="float:right;width:453px;">
		<button id="butpageadd" class="but" style="margin-left:0px;color:#006633;font-weight:bold;">Добавить</button> 
		<button id="butpageupd" class="but" style="margin-left:0px;color:#0000FF;font-weight:bold;">Сохранить</button>
		<button id="butpagedel" class="but" style="margin-left:0px;color:red;font-weight:bold;">Удалить</button>
		<button id="butchgsec" class="but" style="margin-left:0px;font-weight:bold;">Раздел</button>
		<button id="butshowall" class="but" style="margin-left:0px;font-weight:bold;">Все</button>
		<button id="butshowfix" class="but" style="margin-left:0px;font-weight:bold;">Фикс.</button>
		<span id="pages_animation" class="ajax-animation"><img src="/i/loader_circle.gif"></span>
	</div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td valign="top">
    <div id="tabbar" style="width:810px; height:465px;margin-left:1px;">
		<div id="tabpages">
			<fieldset id="fspage" style="width:800px;height:650px;padding-top:0px;"><legend class="block-title">Новость</legend>&nbsp;
			<label>Название: </label><input id="page_title" type="text" size="60" maxlength="255" value="" />  
			&nbsp;<label>Дата: </label><input id="page_date" type="text" size="10" maxlength="10" value="" class="data" />
			<input id="page_time" type="text" size="8" maxlength="8" value="02:00:00" class="time" /><br />
			&nbsp;<label>ID раздела: </label><span id="sec_id" style="width:30px"></span>
			<label>ID страницы: </label><span id="page_id" style="width:30px"></span>
			<label>Показывать:</label><input id="page_show" type="checkbox" value="1" />| 
			<label>Заморозить:</label><input id="page_fix" type="checkbox" value="1" />|
			<label>Анонсировать:<input id="page_announcing" value="1" type="checkbox" /></label>|
			<label>Ya RSS:</label><input id="page_rss" type="checkbox" value="1" />|
			<label>Partners RSS:</label><input id="partners_rss" type="checkbox" value="0" /><br />
			<label>В фото-информер:</label><input id="page_photo_informer" type="checkbox" value="0" />|
			<label>В видео-информер:</label><input id="page_video_informer" type="checkbox" value="0" />|
			<label>Рекоменд:</label><input id="page_recommended" type="checkbox" value="1" />|			
			<label style="margin-left:2px;">Ссылка: </label><span id="page_furl"></span>|
			<input id="butsimilar" type="button" value="Похожая" />
			<input id="butsimilars" type="button" value="Похожие" />
			<div style="position:relative;width:800px;height:580px;margin-bottom:2px;padding-top:2px;padding-bottom:2px;">
			<textarea id="page_content" name="page_content" cols="50" rows="10"></textarea>
			</div>
			</fieldset>
		</div>
		
    <div id="tabdescription">
    <div style="position:relative;">
       <div style="position:relative;width:800px;margin-top:5px;margin-left:5px;">
         <textarea id="page_desc" name="page_descr" cols="25" rows="5"></textarea>
       </div> 
       <div style="margin-left:5px;top:5px;width:230px">
         <label id="media_label">Фото по-умолчанию</label>
         <div>
            <iframe src="" id="if_page_photo" style="width:798px;height:200px;border:1px solid grey;vertical-align: middle;"></iframe>
         </div>
      </div>
     </div>
    </div>
    </div>
	<div id="tabcomments">
		<div id="commentsgridbox" 
		style="width:795px;height:190px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:3px"></div>
		<table border="0" cellpadding="0" cellspacing="0"><tr>
		<td width="20"><span id="comments_animation" class="ajax-animation"><img src="/i/loader_circle.gif"></span></td>
		<td align="left"><button id="butcommentupd">Сохранить</button>
		<button id="butcommentdel">Удалить</button>
		</td></tr></table>
		
		<span id="comment_page_title" class="page_title" style="margin-left: 30px"></span>
		<div style="width:800px;height:100px">
			<textarea id="comment_body" name="comment_body" cols="50" rows="3"></textarea>
		</div>
	</div>
	<div id="tabtags">
	  <fieldset style="position:absolute;width:380px;height:400px;"><legend class="block-title">Все тэги</legend>
		<div id="alltagsgridbox" 
			style="width:370px;height:375px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px"></div>
			<table border="0" cellpadding="0" cellspacing="0"><tr>
			<td width="20"><span id="tags_animation" class="ajax-animation"><img src="/i/loader_circle.gif"></span></td>
			<td align="left"><button id="butagadd">Добавить</button>
			<button id="butagdel">Удалить</button>
			</td></tr></table>
		  </fieldset>
		 
		<fieldset style="position:absolute;left:400px;width:380px;height:400px;"><legend class="block-title">Тэги по новости/статье</legend>
		<div id="pagetagsgridbox" 
			style="width:370px;height:375px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px"></div>
			<table border="0" cellpadding="0" cellspacing="0"><tr>
			<td align="right"><span id="pagetags_animation" class="ajax-animation"><img src="/i/loader_circle.gif"></span></td>
			<td width="120"><button id="butpagetagdel" style="margin-left:280px">Отвязать</button>
			</td></tr></table>
	  	</fieldset>
		</div>
      <div id="tabphotos">
        <div id="photosgridbox" 
           style="width:800px;height:400px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:3px"></div>
           <button id="butphotoadd" style="margin-left: 10px;">Добавить</button> 
           <button id="butphotodel">Удалить</button>
      </div>
	  <div id="tabaddoption" style="padding:10px;">
		<div id="optionsgrid" style="height:390px;width:785px;"></div>
		<button id="save_option">Сохранить</button>
		<select id="opt_type_values" style="display:none;">
			<?php foreach($this->option_type_values as $type): ?>
			<option value="<?= $type['alias'] ?>"><?= $type['name'] ?></option>
			<?php endforeach; ?>
		</select>
	  </div>
    </div>
    </td>
  </tr>
</table>
<div id="chgsecgridbox" 
 style="width:380px;height:350px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px; display:none;"></div>
 
<div id="similarbox" style="display:none;"> 
	<div id="similargridbox" 
	 style="width:525px;height:175px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:1px;"></div>
 	<button id="butsetsimilar">Закрыть</button>
</div>
<div id="similarsbox" style="display:none;"> 
	<div id="similarsgridbox" 
	 style="width:525px;height:175px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:1px;"></div>
 	<button id="butsetsimilars">Закрыть</button>
</div>
<!-- end controls layout -->

<!-- begin grid parameters -->
<script type="text/javascript">
  var obj = '<?=$this->obj?>';
  var typedoc = '<?=$this->typedoc?>';
  var path_to_ckeditor = '/ckeditor';
  var pictureFolder = '/<?=$this->pictureFolder?>';
  
  var secparms = {    
      urlData: '<?=$this->base?>'+obj+'/sections/',
      colTitles: 'Название раздела,,,,,',
      colFilters: '#connector_text_filter,,,,,',
      colWidths: '*,0,0,0,0,0',
      colTypes: 'tree,ro,ro,ro,ro,ro',
      colAlign: 'left,,,,,',
      colSorting: 'connector,na,na,na,na,na'
  };
  
  var pagesparms = {    
      urlData: '<?=$this->base?>' + obj + '/list/',
      urlContent: '<?=$this->base?>' + obj + '/getcontent/',
      urlSetContent: '<?=$this->base?>' + obj + '/setcontent/',
      urlUploadPhoto: '<?=$this->base?>' + obj + '/photoform/id/{id}/',
      urlDeletePhoto: '<?=$this->base?>' + obj + '/deletephoto/id/{id}/',
      urlGetFurl: '<?=$this->base?>' + obj + '/pageurl/',
      colTitles: 'Название,Дата,,,,,,,,,,,,',
      colFilters: '#connector_text_filter,#connector_text_filter,,,,,,,,,,,,',
      colWidths: '*,80,0,0,0,0,0,0,0,0,0,0,0,0',
      colTypes: 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro',
      colAlign: 'left,left,left,left,left,,,,,,,,,',
      colSorting: 'connector,connector,na,na,na,na,na,na,na,na,na,na,na,na'
  };
  
  var alltagsparms = {    
      urlData: '<?=$this->base?>' + obj + '/alltags/',
      colTitles: 'Название тэга',
      colFilters: '#connector_text_filter',
      colWidths: '*',
      colTypes: 'ed',
      colAlign: 'left',
      colSorting: 'connector'
  };
  
  var pagetagsparms = {    
      urlData: '<?=$this->base?>' + obj + '/pagetags/id/{id}/',
      colTitles: 'Название тэга,,',
      colFilters: '#connector_text_filter,,',
      colWidths: '*,0,0',
      colTypes: 'ro,ro,ro',
      colAlign: 'left,right,right',
      colSorting: 'connector,na,na'
  };

  var commentsparms = {
      urlData: '<?=$this->base?>' + obj + '/comments/',
      colTitles: 'Коммент,Дата,Пользователь,',
      colFilters: '#connector_text_filter,,,',
      colWidths: '*,120,110,0',
      colTypes: 'ro,ro,ro,ro',
      colAlign: 'left,left,left,left',
      colSorting: 'connector,connector,connector,na'
  };

  var photosparms = {
	      urlData: '<?=$this->base?>' + obj + '/getphotos/id/{id}/',
	      colTitles: 'Фото,По-умолч.,Описание,,,Порядок',
	      colFilters: '#connector_text_filter,,,,,',
	      colWidths: '80,80,*,0,0,80',
	      colTypes: 'img,ch,ed,ro,ro,ed',
	      colAlign: 'center,center,left,left,left,left',
	      colSorting: 'connector,na,connector,na,na,na'
  };
	  
	var optionsparams = {
		urlData: '<?= $this->base ?>' + obj + '/getoptions/',
		colTitles: ',Название,Значение,Тип,',
		colWidths: '0,*,150,200,0',
		colTypes: 'ed,ed,ed,ro,ro',
		colAlign: 'left,left,left,left,left',
		colSorting: 'na,na,na,na,na',
		types: {
			<?php foreach($this->option_type_values as $key => $type): ?>
			<?= $type['alias'] ?>: '<?= $type['dhtmlx'] ?>'<?= ($key == count($this->option_type_values) - 1 ? '' : ",\n\t") ?>
			<?php endforeach; ?>
		}
	};
	
  var similarparms = {    
      urlData: '<?=$this->base?>'+obj+'/getsimilarbytags/id/{id}/',
      colTitles: ',Раздел,Подраздел,Название материала',
      colFilters: ',#connector_text_filter,,',
      colWidths: '20,120,130,*',
      colTypes: 'ra,ro,ro,ro',
      colAlign: 'center,left,,',
      colSorting: 'na,connector,na,na'
  };

  var similarsparms = {    
      urlData: '<?=$this->base?>'+obj+'/getsimilarsbytags/id/{id}/single/{single}/',
      colTitles: ',Раздел,Подраздел,Название материала',
      colFilters: ',#connector_text_filter,,',
      colWidths: '20,120,130,*',
      colTypes: 'ch,ro,ro,ro',
      colAlign: 'center,left,,',
      colSorting: 'na,connector,na,na'
  };

	var secid_to_pageid = new Array();
	secid_to_pageid[1] = 2;
	secid_to_pageid[2] = 5;
	secid_to_pageid[4] = 3;
	

 $(document).ready(function() {
	<?php foreach($this->preview_sizes as $size): ?>
	thumbnail.settings.preview.sizes.push({'label': '<?= implode('x', $size) ?>', 'value': '<?= implode('x', $size) ?>'});
	<?php endforeach; ?>
	<?php foreach($this->resize_types as $name => $type): ?>
	thumbnail.settings.preview.resize_types.push({'label': '<?= $name ?>', 'value': '<?= $type ?>'});
	<?php endforeach; ?>
	//thumbnail.settings.preview.src = 'http://www.sokol.xo4y.ua/images/articles/22275_0.gif';
	thumbnail.settings.url = '<?=$this->base?>' + obj;
 });

</script>
<!-- end grids parameters -->

<script type="text/javascript" src="/js/admin/common.js?v=<?=md5(date())?>" charset="utf-8"></script>
<script type="text/javascript" src="/js/admin/pages.js?v=<?=md5(date())?>" charset="utf-8"></script>
<script type="text/javascript" src="/js/admin/additional_options.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/admin/thumbnail.js" charset="utf-8"></script>

<?php $this->endblock('content') ?>
