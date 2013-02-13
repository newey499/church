{include file="header.tpl" title=$title}

<br>

<p>
	<h2>{$title}</h2>
</p>

<p>
{* Renumber the Menus *}
{php} $this->_tpl_vars['obj']->exec() {/php}
</p>

<h4>Completed</h4>

<p>
	<a href="admin.php">Admin</a>
</p>

<br />


{include file="footer.tpl"}


