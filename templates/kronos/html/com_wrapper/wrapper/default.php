<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_wrapper
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="contentpane<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
        <h1>
            <?php if ($this->escape($this->params->get('page_heading'))) :?>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            <?php else : ?>
                <?php echo $this->escape($this->params->get('page_title')); ?>
            <?php endif; ?>
        </h1>
    <?php endif; ?>
    
    <iframe <?php echo $this->wrapper->load; ?> class="pageWrap <?php echo $this->pageclass_sfx; ?>" name="investorframe" id="investorframe" src="<?php echo $this->escape($this->wrapper->url); ?>" allowTransparency="true" width="495px" height="400px" scrolling="no" frameborder="0" align="right" hspace="0" vspace="0"></iframe>
</div>

<script type="text/javascript">
	if(navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
	window.addEventListener("message", function () {
	if (event.origin !== "http://phx.corporate-ir.net") return;
	updateIFrame(event.data)
	}, false);
	}
	
	function updateIFrame(height) {
	var iframe = document.getElementById('investorframe');
	iframe.setAttribute('height',height);
	}
</script>
