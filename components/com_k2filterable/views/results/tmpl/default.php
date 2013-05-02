<?php echo $this->filters; ?>

<div id="results">
  
<?php if($this->resultsIntro): ?>

<p id="resultsIntro"><?php echo $this->resultsIntro; ?></p>

<?php endif; ?>

<?php if($this->results): ?>

<?php foreach($this->results['db'] as $result): ?>

<div class="result">

<h3>
   <?php echo $this->getLink($result, $result->title) ?>
</h3>

<?php echo $result->introtext ?>
<div class="k2filter-details">
<?php echo $this->getLink($result, JText::_('K2FILTERABLE_DETAILS')); ?>
</div>

</div>

<?php
endforeach;
endif;
?>

<div class="centerButton">

<?php if($this->results['offset']-$this->results['limit'] >= 0): ?>

<a href="javascript:prev()" class="filterButton"><?php echo JText::_('K2FILTERABLE_PREVIOUS_RESULTS');?></a>

<?php

endif;

if($this->results['offset']+$this->results['limit']+1 <= $this->results['num']):

?>

<a href="javascript:next()" class="filterButton"><?php echo JText::_('K2FILTERABLE_MORE_RESULTS');?></a>

<?php endif; ?>

</div>

</div>

