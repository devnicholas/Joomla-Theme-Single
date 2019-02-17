<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Getting params from template
$theme = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add current user information
$user = JFactory::getUser();


$popup='<div class="single-popup">';
  if($theme->get('linkPopup')) :
    $popup.='<a href="'.$theme->get('linkPopup').'" ';
    if($theme->get('target')) :
      $popup.='target="_blank"';
    endif;  
    $popup.='>';
  endif;
        
  $popup.='<img src="'.$this->baseurl.'/'.$theme->get('popupImage').'">';
        
  if($theme->get('linkPopup')) : 
    $popup.='</a>';
  endif;
$popup.='</div>';
$popup = str_replace("
","",$popup);
?>
<script>
  jQuery(document).ready(function() {
    jQuery.fancybox.open('<?= $popup; ?>');
  });
</script>