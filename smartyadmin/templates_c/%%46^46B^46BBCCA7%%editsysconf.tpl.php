<?php /* Smarty version 2.6.26, created on 2009-08-20 21:45:05
         compiled from editsysconf.tpl */ ?>
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
<?php echo $this->_tpl_vars['msg']; ?>

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
<a href="admin.php">Admin</a>
&nbsp;
<?php if (( $this->_tpl_vars['_GET']['opcode'] == 'W' ) && ( $this->_tpl_vars['_GET']['subcode'] == 'U' )): ?>
<a href="editsysconf.php">System configuration</a>
<?php endif; ?>
</p>


<br />


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

