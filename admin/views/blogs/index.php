<?php
/**
 * Шаблон формы редактора блогов
 *
 * @author sokol, 2011
 * @package btlady-admin
*/

$this->extend('layout') 
?>

<?php $this->block('title') ?>Админка блогов<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlx.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/connector.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcalendar.js"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxtabbar.js"></script>
<script type="text/javascript" src="/js/forms/jquery.forms.js"></script>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxcalendar.css" charset="utf-8"></link>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxtabbar.css" charset="utf-8"></link>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlx.css">
<style type="text/css">
.tag_cont {
	width:350px;
	height:355px;
	float:left;	
	margin:0 15px 10px 0;
}
.tag_cont button {
	margin:5px 10px 0 0;
}
</style>
<table border="0">
  <tr>
    <td>
    <fieldset id="fblogs" style="width:1000px;height:250px; padding:5px;"><legend class="block-title">Блоги</legend>
    <div id="blogridbox"
    style="position:relative;left:0px;top:5px;width:500px;height:200px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px">
    </div>
    <div style="position:absolute;left:530px;top:40px;width:490px;height:257px;">
      <textarea id="blog_content" name="blog_content" cols="55" rows="12"></textarea>
    </div>
    <button id="butblogcreate" class="but" style="margin-left:0px;color:blue;font-weight:bold;">Создать</button>
    <button id="butblogupd" class="but" style="margin-left:0px;color:blue;font-weight:bold;">Сохранить</button>
    <button id="butblogdel" class="but" style="margin-left:0px;color:red;font-weight:bold;">Удалить</button>
    </fieldset>
    </td>
  </tr>
</table>
<div id="tabbar" style="height:680px;width:1020px;">
	<div id="blog_themes">
		<table>
		  <tr>
			<td>
			<fieldset id="fblogitems" style="width:1000px;height:330px; padding:5px;position:relative;"><legend class="block-title">Темы блога</legend>
			<div id="blogitemsgridbox"
			style="position:relative;left:0px;top:5px;width:480px;height:275px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px">
			</div>
			<div style="position:absolute;left:500px;top:0px;width:500px;height:257px;">
			  <textarea id="theme_content" name="theme_content" cols="55" rows="12"></textarea>
			</div>
			<button id="butthemcrt" class="but" style="margin-left:0px;color:blue;font-weight:bold;">Создать</button>
			<button id="buthemeupd" class="but" style="margin-left:0px;color:blue;font-weight:bold;">Сохранить</button>
			<button id="buthemedel" class="but" style="margin-left:0px;color:red;font-weight:bold;">Удалить</button>
			</fieldset>
			</td>
		  </tr>
		  <tr>
			<td valign="top">
			<fieldset id="fblog" style="width:1000px;height:280px;padding:5px;position:relative;"><legend class="block-title">Комментарии</legend>
			 <div id="commentsgridbox"
			  style="position:relative;left:0px;top:5px;width:400px;height:230px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px">
			 </div>
			 <div id="tabcomments">
			  <button id="butcommentupd" class="but" style="margin-left:0px;color:blue;font-weight:bold;">Сохранить</button>
			  <button id="butcommentdel" class="but" style="margin-left:0px;color:red;font-weight:bold;">Удалить</button>
			  <span id="comment_theme_title" class="news_title" style="margin-left: 30px"></span>
			  <div style="height: 3px"></div>
			 </div>
			 <div style="position:absolute;left:420px;top:0px;width:580px;height:300px;margin-bottom:2px;padding-top:2px;padding-bottom:2px;">
			  <textarea id="comment_content" name="comment_content" cols="50" rows="10"></textarea>
			 </div>
			</fieldset>
			</td>
		  </tr>
		</table>
	</div>
	<div id="author">
		<fieldset><legend class="block-title">Автор блога</legend>
			<div id="blog_author" style="float:left;height:300px;width:490px;margin:0 10px 0 0;"></div>
			<div id="all_users" style="width:490px;height:300px;float:left;"></div>
			<button id="saveauthor" style="font-weight:bold;color:blue;margin:3px 0;">Сохранить</button>
		</fieldset>
	</div>
    <div id="tags">
		<fieldset><legend class="block-title">Тэги</legend>
			<fieldset class="tag_cont"><legend class="block-title">Все тэги</legend>
				<div style="width:350px;height:300px;" id="alltagsgrid"></div>
				<button id="add_tag">Добавить</button><button id="remove_tag">Удалить</button>
			</fieldset>
			<fieldset class="tag_cont"><legend class="block-title">Привязаные тэги</legend>
				<div style="width:350px;height:300px;" id="bindtagsgrid"></div>
				<button id="remove_bind_tag">Удалить</button>
			</fieldset>
		</fieldset>
	</div>
</div>
<select style="display:none;" id="blog_types">
	<option value="1">Польз.</option>
	<option value="2">Спец.</option>
</select>

<script type="text/javascript">
  var obj = 'blogs';
  var path_to_ckeditor = '/ckeditor';
  
  var blogsparms = {    
      urlData: 'http://<?=$this->domain?>/admin/' + obj + '/getblogs/',
      colTitles: 'Название,Описание,Модер.,Тип',
      colWidths: '*,200,50,60',
      colTypes: 'ed,ro,ch,co',
      colAlign: 'left,left,center,',
      colSorting: 'connector,na,connector,na',
	  colFilters: '#connector_text_filter,,,',
  };

  var blogitemsparms = {    
	      urlData: 'http://<?=$this->domain?>/admin/' + obj + '/getblogitems/{id}/',
	      colTitles: 'Название,Описание,Дата',
	      colFilters: '#connector_text_filter,#connector_text_filter,#connector_text_filter',
	      colWidths: '*,0,115',
	      colTypes: 'ed,ro,ro',
	      colAlign: 'left,left,left',
	      colSorting: 'connector,na,connector'
	  };

  var commentsparms = {    
	      urlData: 'http://<?=$this->domain?>/admin/' + obj + '/getcomments/{id}/',
	      colTitles: 'Пользователь,Дата,',
	      colFilters: '#connector_text_filter,#connector_text_filter,#connector_text_filter',
	      colWidths: '*,115,0',
	      colTypes: 'ro,ro,ro',
	      colAlign: 'left,left,left',
	      colSorting: 'connector,connector,na'
	  };
	  
  var allusersparams = {    
      urlData: 'http://<?=$this->domain?>/admin/' + obj + '/getallusers/',
      colTitles: 'ID,Логин,Email',
      colWidths: '50,*,*',
      colTypes: 'ro,ro,ro',
      colAlign: 'left,left,left',
      colSorting: 'connector,connector,connector',
	  colFilters: ',#connector_text_filter,#connector_text_filter,#connector_text_filter',
  };
  
  var authorparams = {    
      urlData: 'http://<?=$this->domain?>/admin/' + obj + '/getblogauthor/',
      colTitles: 'ID,Логин,Email',
      colWidths: '50,*,*',
      colTypes: 'ro,ro,ro',
      colAlign: 'left,left,left'
  };
  
  var alltagsparms = {    
      urlData: 'http://<?=$this->domain?>/admin/' + obj + '/getalltags/',
      colTitles: 'Название тэга',
      colFilters: '#connector_text_filter',
      colWidths: '*',
      colTypes: 'ed',
      colAlign: 'left',
      colSorting: 'connector'
  };  
  
  var bindtagsparams = {    
      urlData: 'http://<?=$this->domain?>/admin/' + obj + '/getbindtags/',
      colTitles: 'Название тэга',
      colFilters: '',
      colWidths: '*',
      colTypes: 'ro',
      colAlign: 'left',
      colSorting: 'connector'
  }; 
  
  var cke_image_upl_src = 'http://<?=$this->domain?>/admin/' + obj + '/uploadpostimage/';
</script>
<script type="text/javascript" src="/js/admin/common.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/admin/blogs.js?v=001" charset="utf-8"></script>
<!-- <?=time()?> -->

<?php $this->endblock('content') ?>