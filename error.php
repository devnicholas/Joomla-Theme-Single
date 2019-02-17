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
$params = JFactory::getApplication()->getTemplate(true)->params;

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

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add current user information
$user = JFactory::getUser();


// Logo file
if ($params->get('logoFile'))
{
	$logo = JUri::root() . $params->get('logoFile');
}
else
{
	$logo = $this->baseurl . "/templates/" . $this->template . "/images/logo.png";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->title; ?> <?php echo htmlspecialchars($this->error->getMessage()); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/error.css" type="text/css" />
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" type="text/css" />

	<?php
		$debug = JFactory::getConfig()->get('debug_lang');
		if ((defined('JDEBUG') && JDEBUG) || $debug)
		{
	?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/cms/css/debug.css" type="text/css" />
	<?php
		}
	?>
	<?php
	// If Right-to-Left
	if ($this->direction == 'rtl')
	{
	?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-rtl.css" type="text/css" />
	<?php
	}
	?>
	<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<?php
	// Template color
	if ($params->get('templateColor'))
	{
	?>
	<style type="text/css">
		body.site
		{
			background-color: <?php echo $params->get('templateColor');?>;
      color: <?php echo $params->get('templateBackgroundColor');?>;
		}
		a
		{
			color: <?php echo $params->get('templateBackgroundColor');?>;
		}
    smile {
			background-color: <?php echo $params->get('templateBackgroundColor');?>;
      color: <?php echo $params->get('templateColor');?>;
    }
    hr{
      border-top: 1px solid <?php echo $params->get('templateBackgroundColor');?>;
    }
	</style>
	<?php
	}
	?>
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
?>">

	<!-- Body -->
	<div class="body">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<!-- Header -->
			<div class="header">
				<div class="header-inner clearfix">
					<a class="brand pull-left" href="<?php echo $this->baseurl; ?>">
						<img src="<?php echo $logo;?>" alt="<?php echo $sitename; ?>" />
					</a>
					<div class="header-search pull-right">
						<?php
						// Display position-0 modules
						echo $doc->getBuffer('modules', 'position-0', array('style' => 'none'));
						?>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div id="content" class="span12">
					<!-- Begin Content -->
          <hr class="left">
          <br><br>
					<div class="well">
						<div class="row-fluid">
							<div class="span6">
					       <h1 class="page-header">Opss... A página solicitada não pôde ser encontrada</h1>              
							</div>
							<div class="span6">
									<smile>:(</smile>
							</div>
						</div>
						
						<blockquote>
							<span class="label label-inverse"><?php echo $this->error->getCode(); ?></span> <?php echo $this->error->getMessage();?>
						</blockquote>
					</div>
          <br><br>
          <hr class="right">
					<!-- End Content -->
				</div>
			</div>
		</div>
	</div>
	<?php echo $doc->getBuffer('modules', 'debug', array('style' => 'none')); ?>
</body>
</html>
