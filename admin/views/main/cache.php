<?php
/**
 * Сброс кэша
 *
 * @author nergal
 * @package btlady
 * @subpackage admin
*/

$this->extend('layout')
?>

<?php $this->block('title') ?>Сброс кэша<?php $this->endblock('title') ?>

<?php $this->block('content') ?>
<div>
<div style="background:#B8E3F7;border:1px solid #61C4F2">Раздел: <b>Сброс кэша</b></div>
    <form method="post">
	<?php if (isset($this->success) AND !empty($this->success)): ?>
	    <div>Успешно сброшены ключи кэша: <b><?php echo implode(', ', $this->success) ?></b></div>
	<?php endif ?>

	<?php if (isset($this->errors) AND !empty($this->errors)): ?>
	    <div>Ошибки при сбросе ключей: <b><?php echo implode(', ', $this->errors) ?></b></div>
	<?php endif ?>
    
	<div>
	<label for="id">Выберите ключи для сброса:</label>
	</div>
	<select multiple="multiple" required="required" name="id[]" style="width:250px;height:400px">
	    <?php foreach ($this->tables as $table): ?>
		<option value="<?php echo $table ?>"><?php echo $table ?></option>
	    <?php endforeach ?>
	</select>
	
	<div>
	<input type="reset" />
	<input type="submit" />
	</div>
    </form>
</div>
<?php $this->endblock('content') ?>