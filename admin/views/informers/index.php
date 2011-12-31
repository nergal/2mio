<?php
/**
 * Гриды Информеров RSS
 *
 * @author tretyak
 * @package btlady
 * @subpackage admin
*/

$this->extend('layout')
?>

<?php $this->block('title') ?>Информеры RSS<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<script type="text/javascript" src="/js/dhtmlx/dhtmlx.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxtabbar.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/dhtmlx/connector.js" charset="utf-8"></script>

<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlx.css" charset="utf-8"></link>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxtabbar.css" charset="utf-8"></link>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlxgrid_dhx_skyblue.css" />

<script type="text/javascript" src="/js/admin/base64.js"></script>
<script type="text/javascript" src="/js/dhtmlx/excells/dhtmlxgrid_excell_tree.js"></script>

<div>
	<div style="background:#B8E3F7;border:1px solid #61C4F2">Раздел: <b>Информеры RSS</b></div>

	<table border="0">
	  <tr>
	    <td>
	    <fieldset id="fsecs" style="width:800px;height:328px"><legend class="block-title">Разделы</legend>
		    <div id="sectiongridbox"
	    	style="position:relative;width:340px;height:303px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px"></div>
		    <div id="pagesgridbox"
	    	style="position:absolute;left:360px;top:51px;width:450px;height:303px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px">
	    </div>
	    </fieldset>
	    </td>
	  </tr>
	  <tr>
	    <td>
		    <div id="tabbar" style="width:800px;height:205px;margin-left:5px;">
		    	<div id="rsstab1">
				    <div id="rssbox1"
	    			style="width:100%;height:200px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px"></div>
	    		</div>
		    	<div id="rsstab2">
				    <div id="rssbox2"
	    			style="width:100%;height:200px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px"></div>
				</div>	    		
	    	</div>
	    </td>
	  </tr>
	  <tr>
	    <td>
			<div align="center">
				<button id="butsaverss">Сохранить</button>
	    		<button id="butdeleterss">Удалить</button>
	    	</div>
	    </td>
	  </tr>
	</table>
	
	<div id="chgsecgridbox" style="width:380px;height:350px;background-color:#ССCCCC; overflow:hidden; border:solid 1px;border-color:#CCCCCC;margin-bottom:5px; display:none;"></div>

	<script type="text/javascript" src="/js/admin/common.js?v=001" charset="utf-8"></script>
	<script type="text/javascript" src="/js/admin/informer.js?v=006" charset="utf-8"></script>
</div>

<?php $this->endblock('content') ?>
