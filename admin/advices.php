    <?php
include("inc/settings.php");
$per_page=30;
$oper=$_POST['oper'];

if(!isAllowed("rpremium")) {die("� ��� ������������ ���� ��� ��������� ���� ��������");}

if(!($sortby)) $sortby='type_pos'; else {$sortby=explode(" ",$sortby);$sortby=$sortby[0];}

if(!isset($sortdir)) {$sortdir="";$realsortdir="ASC"; }
else if(!intval($sortdir)) {$sortdir="";$realsortdir="ASC";} else {$sortdir="1";$realsortdir="DESC";}


$mainquery="select {$PREFFIX}_doctypes.type_code, type_name, type_name_en, type_pos, type_active
            from {$PREFFIX}_doctypes
            order by $sortby $realsortdir";
//echo $mainquery;
if (!empty($oper))
{
if ($oper=='I')
{
 $err=0;
mysql_query("start transaction;");
$query="insert into {$PREFFIX}_doctypes values(0,'$type_name','$type_name_en',$type_pos,0)";
$result=mysql_query($query) or $err=1;//die("�� ���� �������� �����:".mysql_error());

if(!$err)
  {
      mysql_query("commit;");
    //��� �������������-------------------------------------------------------------
     $result=mysql_query($mainquery) or die("Incorrect Currpage Query") ;
     $num_compl=mysql_num_rows($result);
     for($i=0;$i<$num_compl;$i++)
            if (mysql_result($result,$i,0)==$newunitcode) break;
     $curr_page=ceil($i/$per_page)-1;
     if ($curr_page==-1) $curr_page=0;
    //------------------------------------------------------------------------------
  }
  else mysql_query("rollback;");

RenumeratePos("{$PREFFIX}_doctypes","type_code","type_pos");
  

}

if ($oper=="D")
{
  while (list($key,$value)=each($_POST))
  {
      unset($arr);
      $arr=explode('#',$key);
      $varName=$arr[0];
      $varCode=$arr[1];
      $varPos=$arr[2];
//      echo"varCode=$varCode<br>varName=$varName<br>varPos=$varPos<br>";
      if ( ($varName=="C")&&($value=="on"))
      {
         $err=0;

         $query = "delete from {$PREFFIX}_faq where type_code=$varCode";
         $resultdel = mysql_query($query) or $err+=20;
         
         $query = "delete from {$PREFFIX}_doctypes where type_code=$varCode";
         $resultdel = mysql_query($query) or $err+=10;
      }
  }
RenumeratePos("{$PREFFIX}_doctypes","type_code","type_pos");
}//oper=D

if (!strcasecmp($oper,"U"))
{
  $err=0;
  while (list($key,$value)=each($_POST))
  {
      $arr=explode('#',$key);
      $varName=$arr[0];
      $varCode=$arr[1];
      $varField=$arr[2];
      $varType=$arr[3];

    if ($_POST["C#$varCode"]=="on")
    {
      $flag=0;
      if ($varName=="F")
      {
         if ($varType=="string") $tmp="'"; else $tmp="";
         $sqlupd = "update {$PREFFIX}_doctypes set $varField=$tmp".$value."$tmp  where type_code=$varCode";
//         echo"$sqlupd";
            $resultupd = MYSQL_QUERY($sqlupd) or $err-=10;
         if ($varField=="type_name") $flag=1;
         
         if ($varField=="type_pos")
         {
           $newpos=$value;
           $oldpos=$varPos;
           //������� ����
           if ($oldpos<$newpos) mysql_query("update {$PREFFIX}_doctypes set type_pos=type_pos-1 where type_pos<=$newpos and main_pos>$oldpos");
           //������� �����
           if ($oldpos>$newpos) mysql_query("update {$PREFFIX}_doctypes set type_pos=type_pos+1 where type_pos>=$newpos");
           mysql_query("update {$PREFFIX}_artmain set artmain_pos=$newpos where artmain_code=$varCode");
           RenumeratePos("{$PREFFIX}_doctypes","type_code","type_pos");
         }

      }//if ($varName=="F")
    }
  }//while
}//if  (UPD)
   if($sortby) $tmp="&sortby=$sortby&sortdir=$sortdir"; else $tmp="";
   header("Location: $PHP_SELF?err=$err$tmp");
}//empty
?>



<? include ("inc/head.php"); ?>

<script language = JavaScript>
function Send(a)
{
document.data.oper.value=a;
document.data.submit();
}

function change_line(Check,Unit)
{
Elem=document.getElementById("C#"+Check);
Elem.checked=true;
Unit=document.getElementById(Unit);
while ((Unit.id==null)||(Unit.id.indexOf('#')==0)) Unit=Unit.parentElement;
var name=Unit.id;
var value=Unit.innerHTML.replace("'","&#39;");
if (value.indexOf('<input')>=0) return ;
res = "<textarea style='width:250px;' rows=5 class=smalltext name='"+name+"'>" + value+"'</textarea>";
Unit.innerHTML = res;
}

function change_yes_no(Check,Unit){
Elem=document.getElementById("C#"+Check);
Elem.checked=true;
Unit=document.getElementById(Unit);
while ((Unit.id==null)||(Unit.id.indexOf('#')==0)) Unit=Unit.parentElement;
var name=Unit.id;
var value=Unit.innerHTML.replace("'","&#39;");
if (value.indexOf('<SELECT')>=0) return ;
res = "<SELECT style='width:100%' class=smalltext name='"+name+"'><OPTION VALUE=0>���</OPTION><OPTION VALUE=1>��</OPTION></SELECT>";
Unit.innerHTML = res;
}

function ConfirmSend(a)
{
if (confirm('�� �������, ��� ������ ������� �����?'))
 {
    document.data.oper.value=a;
    document.data.submit();
 }
}

</script>

<BODY>
<center>

<div class=mainbody>

<? include("inc/top.php");?>


<table Border=0 CellSpacing=0 CellPadding=0 width=100%>
  <tr><td class=pageline>
     <div class=wmiddletext><a href="main.php">����������������� �����</a> &#187; <a>������</a></div>
  </td></tr>
</table>
&nbsp;

<table Border=0 CellSpacing=0 CellPadding=0 class=mainbody>
 <tr valign=top>

  <td width=10></td>
  <? include("inc/leftmenu.php"); ?>
  <td width=10></td>

  <td>

<?php
echo"<form name='data' action=$PHP_SELF method=POST>";
echo"<input type=hidden name='oper' value=''>";
echo"<input type=hidden name=curr_page value=\"$curr_page\">";
echo"<input type=hidden name=sortby value=\"$sortby\">";
echo"<input type=hidden name=sortdir value=\"$sortdir\">";
?>

<table class=grayhead Border=0 CellSpacing=0 CellPadding=0 >
 <tr class=normaltext>
  <td ><div ><h4>������</h4></div></td>
  <td align=right class=wmiddletext><a class=submenu onclick="displayform(this,'�������� �����')">�������� �����</a></td>
 </tr>
</table>

<div id=addform>
<table Border=0 CellSpacing=0 class=pagebluetable CellPadding=0>
 <tr><td colspan=3 bgcolor=#ffffff height=10></td></tr>
 <tr><td colspan=10 class=blueheadcolor><center><div class=normaltext>�������� �����</div></center></td></tr>
 <tr><td colspan=3 height=1 bgcolor=#ffffff></td></tr>
 <tr><td>
 <center>
 <table cellpadding=2  cellspacing=0>
 <tr height=30 >
    <td class=lmenutext>�������� �������:</td>
    <td width=5></td>
    <td><textarea name='type_name' type=text style="width:250px" rows=3 class=smalltext></textarea></td>
 </tr>
 <tr height=30 >
    <td class=lmenutext>�������� ������� (����):</td>
    <td width=5></td>
    <td><textarea name='type_name_en' type=text style="width:250px" rows=3 class=smalltext></textarea></td>
 </tr>
 <tr height=30 >
    <td class=lmenutext>�������:</td>
    <td width=5></td>                     
    
<?
$presu=mysql_query("select max(type_pos) from {$PREFFIX}_doctypes");
$newpos=mysql_result($presu,0,0)+1;
?>    

    <td><input name='type_pos' type=text style="width:250px" class=smalltext value='<?=$newpos;?>'></td>
 </tr>
 </table>
 </td>

 <td class=helpzone>

     <table Border=0 CellSpacing=1 CellPadding=0 bgcolor=#999999 class=helptable>
       <tr>
        <td class=helptd>

             <div class=ssmalltext>
                ��������� ���� ����� � ������� ������ "�������� �����". ����� ���������� ������ ����������� ��������������� �����.</a>
             </div>

        </td>
       </tr>
     </table>

 </td>


 </tr>
 <tr><td colspan=3 height=1 bgcolor=#ffffff></td></tr>
 <tr><td colspan=10 id=blueheadcolor><center><input type=button onClick=Send('I') value='�������� �����'  class=smalltext></td></tr>
</table>
</div>


<table Border=0 CellSpacing=0 class=pagebluetable CellPadding=0 >
 <tr><td class=lmenutext height=30 bgcolor=#ffffff align="center">������</td></tr>
</table>

<?php
 if(intval($err)<=-10) echo"<div class=smalltext align=center style='color:red;'>������ ��� ��������� </div>";
 if(intval($err)>=10) echo"<div class=smalltext align=center style='color:red;'>������ ��� �������� </div>";
 if(intval($err)==1) echo"<div class=smalltext align=center style='color:red;'>������ ��� ����������</div>";

$res=mysql_query ($mainquery) or die ("�� ���� ������� �������. ������ � �������.");
  $num_rows=mysql_num_rows($res);
  if($num_rows)
  {
      $page_quant=ceil($num_rows / $per_page); //����� �������
  }

if ($page_quant>1)
{
echo"<div align=left class=smalltext><b>��������:</b>  ";
for ($i=1;$i<$page_quant+1;$i++)
 {
   $y=$i-1;
   if ($curr_page==$y) {$t1="<b>";$t2="</b>";} else {$t1="";$t2="";}
   echo"<a class=blue href=$PHP_SELF?curr_page=$y&sortby=$sortby&sortdir=$sortdir>$t1 $i $t2|</a>&nbsp";
 }
echo"</div><br>";
}


  

?>


<table Border=0 CellSpacing=0 class=pagebluetable CellPadding=0>
 <tr><td>
 <center>
 <table>
 <tr height=30 >
    <td><input type=button onClick=Send('U') value='�������� ����������' class=smalltext></td>
    <td width=5></td>
    <td><input type=button onClick=ConfirmSend('D') value='������� ����������'  class=smalltext></td>
 </tr>
 </table>
 </td></tr>
</table>


<table Border=0 CellSpacing=1 class=bluetable CellPadding=4 width=100%>
  <tr class=lmenutext height=20 align=center bgcolor=#ffffff>
    <td width=20>&nbsp;</td>
    <td><?=SortTitle("���������","type_name",$sortby,$sortdir);?></td>
    <td><?=SortTitle("��������� (����)","type_name",$sortby,$sortdir);?></td>
    <td width="70"><?=SortTitle("�������","type_pos",$sortby,$sortdir);?></td>
    <td width="90"><?=SortTitle("����������","type_active",$sortby,$sortdir);?></td>
  </tr>

<?php
  if($num_rows)
  {
      $page_quant=ceil($num_rows / $per_page); //����� �������
    $start_pos=$curr_page*$per_page;
    if ($start_pos+$per_page<$num_rows) $end_pos=$start_pos+$per_page;
       else $end_pos=$num_rows;
    mysql_data_seek($res,$start_pos);
    for ($x=$start_pos; $x<$end_pos; $x++)
    {
    list($type_code,$type_name,$type_name_en,$type_pos,$type_active)=mysql_fetch_array($res);
    if($type_active) {$type_active="��";$bg="#FFFFFF";} else {$type_active="���";$bg="#EEEEEE";}

    $checkname=$type_code;
    echo"<tr class=edittabletext height=24 bgcolor=$bg>";
    echo"<TD width=20 align=center ><input type='checkbox' name=\"C#$checkname\" id=\"C#$checkname\"></TD>";
    echo"<TD class=smalltext ondblclick='change_line(\"$checkname\",\"F#$checkname#type_name#string\");' id=\"F#$checkname#type_name#string\">".Show($type_name)."</TD>\n";
    echo"<TD class=smalltext ondblclick='change_line(\"$checkname\",\"F#$checkname#type_name_en#string\");' id=\"F#$checkname#type_name_en#string\">".Show($type_name_en)."</TD>\n";
    echo"<TD class=smalltext align=center  ondblclick='change_line(\"$checkname\",\"F#$checkname#type_pos#inc\");' id=\"F#$checkname#type_pos#inc\">".Show($type_pos)."</TD>\n";
    echo"<TD class=smalltext align=center  ondblclick='change_yes_no(\"$checkname\",\"F#$checkname#type_active#int\");' id=\"F#$checkname#type_active#int\">".Show($type_active)."</TD>\n";
    echo"</TR>\n";
    } //end for x
  } // if num_rows
?>

</table>

<table Border=0 CellSpacing=0 class=pagebluetable CellPadding=0 width=95%>
 <tr><td>
 <center>
 <table>
 <tr height=30 >
    <td><input type=button onClick=Send('U') value='�������� ����������' class=smalltext></td>
    <td width=5></td>
    <td><input type=button onClick=ConfirmSend('D') value='������� ����������'  class=smalltext></td>
 </tr>
 </table>
 </td></tr>
</table>

<br>
<?php
{
echo"<div align=left class=smalltext><b>��������:</b>  ";
for ($i=1;$i<$page_quant+1;$i++)
 {
   $y=$i-1;
   if ($curr_page==$y) {$t1="<b>";$t2="</b>";} else {$t1="";$t2="";}
   echo"<a class=blue href=$PHP_SELF?curr_page=$y&sortby=$sortby&sortdir=$sortdir>$t1 $i $t2|</a>&nbsp";
 }
echo"</div>";
}
?>

</form>
  </td>
    <td width=10></td>
</tr>

</table>
</div>
</center>

</BODY>
</HTML>
