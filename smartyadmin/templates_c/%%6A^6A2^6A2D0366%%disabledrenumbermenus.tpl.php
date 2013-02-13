<?php /* Smarty version 2.6.26, created on 2009-08-21 00:08:47
         compiled from disabledrenumbermenus.tpl */ ?>
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
This routine renumbers menu groups and items within groups.
<br />
It is disabled because the web site now uses internal links that should use
the primary key as the identifier of the page to load.
<br />
Need to check to make sure none of these internal links are relying upon
group and item numbers.
</p>

<p>
<a href="admin.php">Admin</a>
</p>

<br />


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

