<?php /* Smarty version 2.6.26, created on 2009-08-21 00:17:17
         compiled from bmenus.tpl */ ?>
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
<?php  $this->_tpl_vars['menuFilterObject']->exec()  ?>
</p>

<p>
<?php echo $this->_tpl_vars['touchClearMsg']; ?>

</p>

<p>

<div class="cdnform">


<?php  $this->_tpl_vars['browseObject']->exec()  ?>



</div>

</p>


<br />


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

