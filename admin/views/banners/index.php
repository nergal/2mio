<?php
/**
 * Гриды баннерки
 *
 * @author nergal
 * @package btlady
 * @subpackage admin
*/

$this->extend('layout')
?>

<?php $this->block('title') ?>Баннерка<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<script type="text/javascript" src="/js/dhtmlx/dhtmlx.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/dhtmlx/dhtmlxcommon.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/dhtmlx/connector.js" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="/js/dhtmlx/css/dhtmlx.css" charset="utf-8"></link>

<link rel="stylesheet" type="text/css" href="/js/dhtmlx/css/dhtmlxgrid_dhx_skyblue.css" />
<script type="text/javascript" src="/js/admin/base64.js"></script>
<script type="text/javascript" src="/js/dhtmlx/excells/dhtmlxgrid_excell_tree.js"></script>

<style type="text/css">
	#banners { padding: 5px }
	#bannerbox, #placesbox, #joinbox {height: 450px; border: 1px solid #CCCCCC }

	.hidden { display: none }
	#editor input, #editor select { width: 700px }
	#editor div { margin-bottom: 5px; }
	#editor textarea { width: 800px; height: 300px }
	#new_title { width: 1050px }
	#previewPlace { padding: 0px 1em 1em 1em; border: 1px dashed red; background-color: #fafafa; }
	#previewPlace h3 { color: red }
</style>

<div>
<div style="background:#B8E3F7;border:1px solid #61C4F2">Раздел: <b>Баннерка</b></div>
	<fieldset id="banners">
		<legend class="block-title">Баннеры</legend>
		<div id="grid">
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
				<tr>
					<td width="33%">
						<div id="placesbox"></div>
						<button id="add" class="places">Добавить</button>
						<button id="delete" class="places">Удалить</button>
					</td>
					<td width="32%">
						<div id="joinbox"></div>
						<button id="delete" class="joins">Удалить</button>
					</td>
					<td width="33%">
						<div id="bannerbox"></div>
						<button id="add" class="banners">Добавить</button>
						<button id="delete" class="banners">Удалить</button>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="buttons">
							<button id="update">Сохранить</button>
							<button id="reset" disabled="disabled">Сбросить изменения</button>
						</div>
					</td>
				</tr>
			</table>

			<br />
		</div>
		<div id="editor" class="hidden">
			<div style="overflow:hidden;">
				<p><label for="title">Заголовок: </label><input type="text" name="title" id="title" /></p>
				<p><textarea id="banner_code" name="banner_code" cols="50" rows="5"></textarea></p>
			</div>
			<label for="save">Изменения: </label>
			<button id="save" disabled="disabled">Принять</button>
			<button id="cancel" disabled="disabled">Отменить</button>
			<button id="hide">Скрыть</button>
		</div>
	</fieldset>
	<script type="text/javascript" src="/js/admin/common.js?v=001" charset="utf-8"></script>
	<script type="text/javascript" src="/js/admin/banner.js?v=001" charset="utf-8"></script>
</div>
<?php $this->endblock('content') ?>