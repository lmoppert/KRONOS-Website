<?php 
/*-----------------------------------------
  License: GPL v 3.0 or later
-----------------------------------------*/

defined('_JEXEC') or die; 

?>

<div id="mailinvote">
  
  <div class="notice">

    <h3><?php echo JText::_('WELCOME') ?></h3>

    <p><?php echo JText::_('MAIL_OPTIONS_FIRST') ?></p>

    <p><a href="index.php?option=com_config&view=component&component=com_mailinvote&path=&tmpl=component" 
       class="modal" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}">
<?php echo JText::_('SET_MAIL_OPTIONS')?>
    </a></p>

    <p><?php echo JText::_('NO_BALLOTS') ?></p>

    <p><a href="index.php?option=com_mailinvote&task=ballot.add"><?php echo JText::_('CREATE_BALLOT')?></a></p>

  </div>

</div><!-- End #mailinvote -->

<form name="adminForm">
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="view" value="ballot" />
  <input type="hidden" name="option" value="com_mailinvote" />
</form>