<?php /* Smarty version 2.6.26, created on 2009-08-18 21:26:10
         compiled from validate.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'validate', 'validate.tpl', 7, false),array('function', 'html_select_date', 'validate.tpl', 14, false),)), $this); ?>


<form method="POST" action="validate.php">

<p>
Full Name: <input type="text" name="FullName" value="<?php echo $this->_tpl_vars['FullName']; ?>
" >
<?php echo smarty_function_validate(array('id' => 'fname','message' => 'Full Name cannot be empty'), $this);?>

</p>

 
<p>

<?php echo smarty_function_html_select_date(array('prefix' => 'fdate','time' => $this->_tpl_vars['time'],'end_year' => '+1','display_days' => true,'field_order' => 'DMY'), $this);?>

</p>






<?php echo smarty_function_validate(array('id' => 'fdatevalid','message' => 'Date is not valid'), $this);?>

</p>

<input type="submit">
</form>