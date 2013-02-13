{include file="header.tpl" title=$title}

<br>

<p>
	<h2>{$title}</h2>
</p>

<p>
{$msg}
</p>

<p>

<div class="cdnform">

{php}
if (isset($this->_tpl_vars['obj']))
{
	$this->_tpl_vars['obj']->exec();
}
{/php}

</div>

</p>

<p>
<a href="admin.php">Admin</a>
&nbsp;
{* opcode=W&subcode=U *}
{ if ($_GET.opcode eq 'W') && ($_GET.subcode eq 'U') }
<a href="editsysconf.php">System configuration</a>
{/if}
</p>


<br />


{include file="footer.tpl"}


