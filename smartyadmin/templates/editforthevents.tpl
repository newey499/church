{include file="header.tpl" title=$title}

<br>

<p>
	<h2>{$title}</h2>
</p>

<p>

{$debug}
</p>

<p>

<div class="cdnform">

{* Build the table *}
{* $browseObject->exec() *} 		

{php} $this->_tpl_vars['obj']->exec($opcode) {/php}


</div>

</p>



<br />


{include file="footer.tpl"}


