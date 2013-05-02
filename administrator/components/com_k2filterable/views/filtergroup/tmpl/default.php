<?php 
defined('_JEXEC') or die; 

?>

<form name="adminForm" method="POST" class="form-validate" action="index.php">

    <fieldset class="adminform">

<?php foreach ($this->form->getFieldset('main') as $field):  ?>

<dl>

<?php
    if ($field->hidden): 
        echo $field->input;
    else:
 ?>

    <dt>
        <?php echo $field->label; ?>
    </dt>
    <dd<?php echo ($field->type == 'Editor' || $field->type == 'Textarea') ? ' style="clear: both; margin: 0;"' : ''?>>
        <?php echo $field->input ?>
    </dd>

<?php endif; ?>

</dl>

<?php endforeach; ?>

</fieldset>

<input type="hidden" name="option" value="com_k2filterable" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->item->id;?>" /> 
<?php echo JHTML::_('form.token') ?>

</form>


