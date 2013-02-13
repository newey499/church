{include file="header.tpl" title=$title}

<br>

<p>
	<h2>{$title}</h2>
</p>

<p>
	<a href="admin.php">Admin</a>
</p>

<h4>Running rssbld.php</h4>
<h4>Calling buildNewRssFeed from Running rssbld.php</h4>

<p>
{* Build the RSS File *}
{php} $this->_tpl_vars['obj']->exec() {/php}
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


{include file="footer.tpl"}


