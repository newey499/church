<?php /* Smarty version 2.6.26, created on 2009-08-20 19:57:00
         compiled from menucheck.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br>

<p>
	<h2><?php echo $this->_tpl_vars['title']; ?>
</h2>
</p>

<p>
<a href="admin.php">Return to Admin</a>
</p>

<p>

<div class="cdnform">



<?php 
if (isset($this->_tpl_vars['obj']))
{
	$this->_tpl_vars['obj']->exec();
}
 ?>


</div>

</p>


<p>
<a href="admin.php">Return to Admin</a>
</p>

<br />


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

