<?php /* Smarty version 2.6.26, created on 2009-08-21 08:31:37
         compiled from rssbld.tpl */ ?>
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
	<a href="admin.php">Admin</a>
</p>

<h4>Running rssbld.php</h4>
<h4>Calling buildNewRssFeed from Running rssbld.php</h4>

<p>
<?php  $this->_tpl_vars['obj']->exec()  ?>
</p>

<h4>Returned From buildNewRssFeed from Running rssbld.php</h4>

<p>
<h4>RSS File Written [rss.xml]</h4>
</p>

<h4>Completed</h4>

<p>
	<a href="admin.php">Admin</a>
</p>

<br />


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

