{include file="header.tpl" title="Last Updated"}

<br>

<p>

<b>{$message}</b>

</p>


<p>


{if isset($back)}
	<a href={$back}>Back</a>
{else}
	<a href={php}print($_SERVER['SCRIPT_NAME']);{/php}>Back</a>
{/if}




&nbsp;
<a href="admin.php">Admin Menu</a>
</p>


<br />


{include file="footer.tpl"}
