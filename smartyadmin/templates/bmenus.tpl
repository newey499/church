{include file="header.tpl" title=$title}

<br>

<p>
	<h2>{$title}</h2>
</p>

<p>
{php} $this->_tpl_vars['menuFilterObject']->exec() {/php}
</p>

<p>
{$touchClearMsg}
</p>

<p>

<div class="cdnform">

{* Build the table *}
{* $browseObject->exec() *}

{php} $this->_tpl_vars['browseObject']->exec() {/php}



</div>

</p>


<br />


{include file="footer.tpl"}


