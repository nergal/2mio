<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Uploader</title>
<style type="text/css">
.preview {
	width: 100px;
	height: 80px;
}
	
.preview.alt {
	border: solid 5px #ddd;
}
.preview.alt:hover {
	border: solid 5px #76D77C;
}

.preview.active {
	border: solid 5px #76D77C;
	width: 250px;
	height: 200px;
}
.resize_msg {
	font-size:11px;
	text-align:left;	
}
</style>
<script type="text/javascript" src="/js/jquery.js"></script>
</head>

<body>
<form action="<?=$this->base . $this->obj ?>/uploadphoto" method="post" enctype="multipart/form-data" name="form_upload" id="form_upload">
<? if(isset($this->error)) { ?><?=$this->error?><? } ?>
 <table height="150">
  <tr valign="top">
   <td>
	<input type="file" name="upfile" id="upfile" />
    <input type="submit" class="but" name="but_upload" id="but_upload" value="Загрузить файл" />
    <input name="MAX_FILE_SIZE" type="hidden" id="MAX_FILE_SIZE" value="1000000" />
    <input name="pageid"  type="hidden" id="pageid"  value="<?=$this->pageid?>" />
    <input name="photoid" type="hidden" id="photoid" value="<?=$this->photoid?>" />
    <? if(isset($this->action) && $this->action != 'delete' && !empty($this->photo)){ ?>
    <br /><br /><input type="submit" class="but" name="but_delete" id="but_delete" value=" Удалить файл " onclick="return confirm('Вы уверенны что хотите удалить файл?')"/>
    <? } ?>
    <? if(isset($this->is_video)) {?>
	<br /><br /><input type="submit" class="but" name="but_regenerate" id="but_regenerate" value="ReMake preview"/>
    <? } ?>
   </td>
   <td width="250" align="right">
    <img id="preview_img" class="preview active" src="<?=(isset($this->photo))?$this->photo.'?'.md5(time()):'/i/default.gif'?>" /><br />
    <?php if(isset($this->resize_msg)): ?>
    <div class="resize_msg"><?= $this->resize_msg ?></div>
    <?php endif; ?>
   </td>
   <td>
	<?php if(isset($this->photo) and isset($this->preview_ok) and ($this->preview_ok === true) and isset($this->is_video)): ?>
		<?php for($j = 0; $j < 10; $j++): ?>
			<input class="preview alt" type="image" name="but_preview" src="<?php echo substr($this->photo, 0, strlen($this->photo)-4) . "_$j.png" ?>" onclick="document.forms['form_upload'].preview_num.value=<?php echo $j ?>">&nbsp;
		<?php endfor;?>
	<?php endif; ?>
		<input type="hidden" name="preview_num" value="-1" />
   </td>
  </tr>
 </table>
 <?php if($this->page_media): ?>
 <input type="hidden" name="page_media" value="1" />
 <?php endif; ?>
</form>
<?=$this->script?>
<?php if(isset($this->isgallery) && $this->isgallery): ?>
<script type="text/javascript">
if(typeof(parent.thumbnail) != 'undefined')
{	
	$(document).ready(function() {
		parent.thumbnail.settings.preview.src = '<?= $this->photo_src ?>';
		parent.thumbnail.settings.wnd.width = 760;
		parent.thumbnail.settings.wnd.height = 530;
		parent.thumbnail.settings.wnd.select_size = '620x400';
		parent.thumbnail.settings.wnd.disable_sizes = true;
		parent.thumbnail.settings.wnd.select_resize_types = 'cropg';
		parent.thumbnail.settings.wnd.disable_resize_types = true;
		parent.thumbnail.settings.jrac.zoom_max = 3000;
		parent.thumbnail.settings.ext_function = function() { 
			$('#preview_img').attr('src', $('#preview_img').attr('src' ) + Math.random());
		};
		
		$('#preview_img').dblclick(function() {
			parent.thumbnail.getEditor();
		});
	});
}
</script>
<?php endif; ?>
</body>
</html>
