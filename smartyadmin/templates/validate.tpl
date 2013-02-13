

<form method="POST" action="validate.php">

<p>
Full Name: <input type="text" name="FullName" value="{ $FullName  }" >
{validate id="fname" message="Full Name cannot be empty"}
</p>

 
<p>

{* start and end year can be relative to current year *}
{html_select_date prefix='fdate' time=$time 
   end_year='+1' display_days=true field_order="DMY" }
</p>



{* generate fdate value from fdateyear, fdatemonth, fdateday *}
{ validate id="time" }


{* Date: <input type="text" name="Date" value="{ $Date }"> *}
{* Date: <input type="text" name="fdate" value="{ $fdate }"> *}
{* Date: <input type="text" name="fdate" value="{$fdate|date_format:"%Y%m%d"}"> *}

{ validate id="timevalid" message="Date is not valid" }
</p>

<input type="submit">
</form>
