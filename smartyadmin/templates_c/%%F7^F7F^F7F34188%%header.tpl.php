<?php /* Smarty version 2.6.26, created on 2009-08-20 23:25:10
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'header.tpl', 4, false),)), $this); ?>
<html>
<head>

	<title><?php echo ((is_array($_tmp=@$this->_tpl_vars['title'])) ? $this->_run_mod_handler('default', true, $_tmp, 'no title') : smarty_modifier_default($_tmp, 'no title')); ?>
</title>

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />	

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="css/layout.css" />	
  <link rel="stylesheet" type="text/css" href="css/church.css" />	
  <link rel="stylesheet" type="text/css" href="css/adminlayout.css" />	

</head>

<body>

<!-- START TOP BANNER -->

<div style="border-bottom: blue 2px solid; padding-bottom:2px; padding-left:1em;" >
	<a href="http://www.cofe.anglican.org/">
	<img src="../jpgs/cofe_logo030.gif" 
	     onmouseover="this.style.cursor='pointer'" 
   		 alt="Link to Church of England Website" 
			 title="Link to Church of England Website" 
	/> 
	</a>
	
	&nbsp;&nbsp;

	<span class="christchurchtitle">
		Christ Church
	</span>

	<span class="christchurchaddress">
		High Street, Lye, Stourbridge, West Mids, UK. DY9 8LF
	</span>

	<span class="developmentsystem">

		<?php if (file_exists ( "templates/devsystemsemaphore.tpl" )): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "devsystemsemaphore.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>

	</span>		

</div>	

<!-- END TOP BANNER -->
<div id="maincontainer">

