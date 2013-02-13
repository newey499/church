{include file="header.tpl" title=$title}

<br>

<p>
	<h2>{$title}</h2>
</p>

<p>
<a href="admin.php">Return to Admin</a>
</p>

<p>

<div class="cdnform">

{* Build the table *}
{* $browseObject->exec() *}


{php}
if (isset($this->_tpl_vars['obj']))
{
	$this->_tpl_vars['obj']->exec();
}
{/php}


</div>

</p>


<p>
<a href="admin.php">Return to Admin</a>
</p>

<br />


{include file="footer.tpl"}


