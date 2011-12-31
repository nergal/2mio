<?php
/**
 * Шаблон формы редактора редиректов
 *
 * @author kolex, 2011
 * @package btlady-admin
*/

$this->extend('layout') 
?>

<?php $this->block('title') ?>Админка редиректов<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<script type="text/javascript" src="/js/dhtmlx/dhtmlx.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/connector.js" charset="utf-8"></script> 
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js"></script>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlx.css">
<table border="0">
  <tr>
    <td>
    <fieldset id="fblogs" style="width:805px;height:600px; padding:5px;"><legend class="block-title">Редиректы</legend>
    <div id="redirectsgridbox"
    style="position:relative;left:0px;top:5px;width:800px;height:550px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px">
    </div>
    <button id="butredirectadd" class="but" style="margin-left:0px;color:blue;font-weight:bold;">Добавить</button>
    <button id="butredirectupd" class="but" style="margin-left:0px;color:blue;font-weight:bold;">Сохранить</button>
    <button id="butredirectdel" class="but" style="margin-left:0px;color:red;font-weight:bold;">Удалить</button>
    </fieldset>
    </td>
  </tr>
</table>

<script type="text/javascript">
  var obj = 'redirects';
  var path_to_ckeditor = '/ckeditor';
  
  var redirectsparms = {    
      urlData: 'http://<?=$this->domain?>/admin/' + obj + '/getredirects/',
      colTitles: 'Старая ссылка,Новая ссылка',
      colWidths: '400,*',
      colTypes: 'ed,ed',
      colAlign: 'left,left',
      colSorting: 'connector,na,connector,na',
	  colFilters: '#connector_text_filter,#connector_text_filter'
  };

</script>
<script type="text/javascript" src="/js/admin/common.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/admin/redirects.js?v=<?=md5(date('Ymd'))?>" charset="utf-8"></script>

<?php $this->endblock('content') ?>