{include file="header.tpl" title="template.tpl - Change This"}


<br>
<h2>template.tpl</h2>

<p>

To use the template - 

</p>

<p>
From docroot of website
<br />
<br />
cp template.php newfile.php
<br />
cp templates/template.tpl templates/newfile.tpl
</p>


<p>
Change the line at the top of newfile.tpl to give the title of the new page
</p>

<p>

<div class="code" >
<tt>
{literal}
{include file="header.tpl" title="template.tpl - Change This Page Title"}
{/literal}
</tt>
</div>

</p>




<p>
Change the line at the bottom of newfile.php to point to the new template file Eg. newfile.tpl
</p>

<p>

<div class="code" >
<tt>
// display the associated template file
<br />
// Change this to the name of the new Template File Eg. newfile.tpl
$smarty->display('template.tpl'); 
</tt>
</div>

</p>

<br />

{include file="footer.tpl"}


