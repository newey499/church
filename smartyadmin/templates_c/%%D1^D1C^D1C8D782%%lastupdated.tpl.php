<?php /* Smarty version 2.6.26, created on 2009-08-19 18:45:09
         compiled from lastupdated.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_select_date', 'lastupdated.tpl', 12, false),array('function', 'html_select_time', 'lastupdated.tpl', 20, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('title' => 'Last Updated')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br>

<p>
	<h2>Website Last updated</h2>
</p>

<form name="setLastUpdated" action="lastupdated.php" method="post">

				<?php echo smarty_function_html_select_date(array('prefix' => 'luDate','time' => $this->_tpl_vars['luDate'],'start_year' => '-1','end_year' => '+1','display_days' => true,'field_order' => 'DMY'), $this);?>



		<?php echo smarty_function_html_select_time(array('use_24_hours' => true,'display_seconds' => false,'time' => $this->_tpl_vars['luTime']), $this);?>


  <br />
  <br />

  <input type="submit" value="Update"> 

	&nbsp;


</form>

	<form name="touchLastUpdated" action="lastupdated.php" method="post">

			<input type=hidden name="touchLastUpdated" value="touchLastUpdated">
	    <input type="submit" value="Set Last Updated to now"> 

	</form>



<p> 
Return to <a href="index.php">Admin Menu</a> 
</p>


<br />


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

