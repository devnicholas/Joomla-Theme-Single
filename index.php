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
$db = & JFactory::getDBO();
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
$menu = &JSite::getMenu();
$active = $menu->getItem($itemid);
$params = $menu->getParams( $active->id );
$pageclass = $params->get( 'pageclass_sfx' );

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScript('templates/' .$this->template. '/js/jquery.plugins.js');

// Add Stylesheets
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/template_psl.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/all.min.css');

// Add optional frameWorks
//Fancybox
if($theme->get('fancybox')) :
  $doc->addScript('templates/' .$this->template. '/js/jquery.fancybox.min.js');
  $doc->addStyleSheet('templates/'.$this->template.'/css/jquery.fancybox.min.css');  
endif;

//SlickNav
if($theme->get('slicknav')) :
  $doc->addScript('templates/' .$this->template. '/js/jquery.slicknav.js');
  $doc->addStyleSheet('templates/'.$this->template.'/css/slicknav.css');  
endif;

//Bootstrap
if($theme->get('bootstrap')) :
  $doc->addScript('templates/' .$this->template. '/js/bootstrap.js');
  $doc->addStyleSheet('templates/'.$this->template.'/css/bootstrap.css');  
endif;

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Add current user information
$user = JFactory::getUser();

// Logo file or site title param
if ($this->params->get('logoFile')) {
	$logo = '<img src="'. JUri::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';
} elseif ($this->params->get('sitetitle')) {
	$logo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span>';
} else {
	$logo = '<span class="site-title" title="'. $sitename .'">'. $sitename .'</span>';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
  <?php if($theme->get('slicknav')) : ?>
    <script>
      jQuery(function(){
        jQuery('nav .menu').slicknav();
      });
    </script>
  <?php endif; ?>
  <?php if($theme->get('googleFont')) : ?>
    <link href="https://fonts.googleapis.com/css?family=<?= $theme->get('googleFontName'); ?>" rel="stylesheet">
  <?php endif; ?>
</head>

<body>

	<!-- Body -->
	<div class="body">
    
		<!-- Header -->
		<header class="header" role="banner">
			<div class="header-inner clearfix">
				<a class="brand pull-left" href="<?php echo $this->baseurl; ?>">
					<?php echo $logo;?>
				</a>
                
        <?php if ($this->countModules('busca')) : ?>
  				<div class="header-search pull-right">
  					<jdoc:include type="modules" name="busca" style="none" />
  				</div>
        <?php endif; ?>
                
				<?php if ($this->countModules('nav')) : ?>
          <nav class="navigation" role="navigation">
            <jdoc:include type="modules" name="nav" style="none" />
          </nav>
        <?php endif; ?>
                
			</div>
            
      <?php if ($this->countModules('banner')) : ?>
			   <jdoc:include type="modules" name="banner" style="none" />
      <?php endif; ?>
            
		</header>
    
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
            
			<div class="row-fluid">
				<main id="content" role="main" class="<?php echo $span;?>">
					<!-- Begin Content -->
                    <?php if ($this->countModules('breadcrumbs')) : ?>
					<jdoc:include type="modules" name="breadcrumbs" style="none" />
                    <?php endif; ?>
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<!-- End Content -->
				</main>
			</div>
            
		</div>
	</div>
	<!-- Footer -->
	<footer class="footer" role="contentinfo">
		<div class="mx-auto container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<hr />
			<jdoc:include type="modules" name="footer" style="none" />
			<div class="row">
        <div class="col-md-12">
          <p>&copy; <?php echo $sitename; ?> - <?php echo date('Y');?>. Todos os direitos reservados.</p>
        </div>
      </div>
		</div>
	</footer>
  
  <?php 
  if($theme->get('popup') and $_SESSION['popup']!=true) :
     require_once('popup.php');
     $_SESSION['popup']=true; 
  endif; 
  ?>
  
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
