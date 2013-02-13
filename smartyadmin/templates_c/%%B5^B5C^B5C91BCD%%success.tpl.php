<?php /* Smarty version 2.6.26, created on 2009-08-20 17:21:22
         compiled from success.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('title' => 'Last Updated')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br>

<p>

<b><?php echo $this->_tpl_vars['message']; ?>
</b>

</p>


<p>


<?php if (isset ( $this->_tpl_vars['back'] )): ?>
	<a href=<?php echo $this->_tpl_vars['back']; ?>
>Back</a>
<?php else: ?>
	<a href=<?php print($_SERVER['SCRIPT_NAME']); ?>>Back</a>
<?php endif; ?>




&nbsp;
<a href="admin.php">Admin Menu</a>
</p>


<br />


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>