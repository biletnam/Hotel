<td class=leftpanel>

<table CellSpacing=0 CellPadding=0>
<tr><td class=bluehead><h4>���������</h4><td></tr>
<tr><td class=blueblock>
<div style="margin-left:20px">

<?php

if( (isAllowed("radmin")) || (isAllowed("rbanner")) )
{
echo"<p><div class=lmenupart><b>�����������������</b></div>";
    if (isAllowed("radmin")) echo"<div class=lmenupart>&#187;&nbsp; <b><a href=\"admin.php\">���������� ��������</a></b></div>";
    if (isAllowed("rstatic")) echo"<div class=lmenupart>&#187;&nbsp; <b><a href=\"statlist.php\">��������� ��������</a></b></div>";
	if (isAllowed("ralbum")) echo"<div class=lmenupart>&#187;&nbsp; <b><a href=\"albumlist.php\">�������</a></b></div>";
}     

?>

</div>
<br>

<td></tr>
</table>
&nbsp;


  <td>

