<?php
    include("inc/settings.php");
    if (!intval($object_code)) {header("Location: index.php");die();}

    if ($en==1) 
    {
      $lng="_en";  
      $add="(����)";
    } 
    else 
    {
      $lng="";
      $add="";
    }    
    

if(!isAllowed("rlisten")) {die("� ��� ������������ ���� ��� ��������� ���� ��������");}

    if (!empty($oper))
    {       
    
    
   

        $query="update {$PREFFIX}_service set
        object_text$lng='$object_text', 
        object_embed$lng='$object_embed' 
        where object_code=$object_code";
        
//        echo"$query <br>";

        mysql_query($query) or die(mysql_error());

//           echo htmlspecialchars($query)."<br>";
        if ($en==1) header("Location: $PHP_SELF?en=$en&object_code=$object_code&type_code=$type_code&curr_page=$curr_page");
        else header("Location: $PHP_SELF?object_code=$object_code&type_code=$type_code&curr_page=$curr_page");
    }

 $mainquery="SELECT object_name$lng, object_text$lng, object_embed$lng, spage_code
              FROM {$PREFFIX}_service
              where object_code=$object_code";
//echo $mainquery;
 $res=mysql_query($mainquery);
 list($object_name, $object_text, $object_embed, $spage_code)=mysql_fetch_array($res);
 // $_SESSION['acode']=$spage_code;

 include ("inc/head.php");

 $editorname='object_text,object_embed';
 include("inc/editorbigfull.php");
?>                        

<script language="JavaScript">
function Send(a)
{
document.data.oper.value=a;
document.data.submit();
}
</script>
<BODY>
<center>

<div class=mainbody>

<? include("inc/top.php");?>


<table Border=0 CellSpacing=0 CellPadding=0 width=100%>
  <tr><td class=pageline>
     <div class=wmiddletext><a href="main.php">����������������� �����</a> &#187; <a href="services.php">������</a></a></div>
  </td></tr>
</table>
&nbsp;


<table Border=0 CellSpacing=0 CellPadding=0 class=mainbody>
 <tr valign=top>

  <td width=10></td>
  <? include("inc/leftmenu.php"); ?>
  <td width=10></td>

  <td>

<table class=grayhead Border=0 CellSpacing=0 CellPadding=0>
 <tr class=normaltext>
  <td ><div ><h4><?=$object_name;?></h4></div></td>
  <td align=right>
  </td>
 </tr>
</table>
        
        
        
<script language="JavaScript"><!--
  
function disbledform(e,num){
                      
                      
if (e.value!=0)
{
 for(j = 0; j < document.data.elements.length; j++)
  { 
    if ((j>3)&&(j!=num)&&(j<10))      
     document.data.elements[j].disabled=true;

  }            
}
else   
 for(j = 0; j < document.data.elements.length; j++)
  { 
    if ((j>3)&&(j!=num)&&(j<10))      
     document.data.elements[j].disabled=false;

  }            
}
  

--></script>        
        

<center>
     <form name=data action=<?=$PHP_SELF;?> method=POST>
     <input type=hidden name=oper>
     <input type=hidden name=en value=<?=$en;?>>
     <input type=hidden name=curr_page value=<?=$curr_page;?>>
     <input type=hidden name=object_code value=<?=$object_code;?>>
     <input type=hidden name=type_code value=<?=$type_code;?>>


<table Border=0 CellSpacing=0 CellPadding=0 width=650>
 <tr><td height=10></td></tr>
 <tr>
  <td bgcolor=#f9f9f9 style='padding:5px'>
  <center>
  <table Border=0 CellSpacing=0 CellPadding=0>
     
     
     <tr><td class=lmenutext align=center colspan=5><a href="services.php">[ ����� ]</a><br><br><br></td></tr>
         
     
     <tr><td class=lmenutext colspan=5><a>��������� <?=$add;?>:</a><br>
       <textarea name='object_embed' style="width:630px;height:300px;"><?=$object_embed;?></textarea><p>
     </td></tr>
     
     <tr><td class=lmenutext colspan=5><a>�������� ������� <?=$add;?>:</a><br>
       <textarea name='object_text' style="width:630px;height:400px;"><?=$object_text;?></textarea><p>
     </td></tr>


     
 <tr>
  <td bgcolor=#f9f9f9 height=150>
  <center>
  <table Border=0 CellSpacing=0 CellPadding=0 Width="%" Align="" vAlign="">
     <tr><td height=25 class=lmenutext><a>�����������:</a></td></tr>
     <tr><td>
       <iframe src="spicture.php?code=<?=$spage_code;?>" width="630" height="400" scrolling="auto" frameborder=1></IFRAME>
     </td></tr>
  </table>
  </td>
 </tr>
 

  </table>
  </td>
 </tr>    
 
 

 

 <tr><td height=10 bgcolor=#f9f9f9 height=0></td></tr>
 <tr><td bgcolor=#f9f9f9 ><center><input type=submit class=smalltext value='��������� ���������' onClick=Send('I')></td></tr>
 <tr><td height=10 bgcolor=#f9f9f9 height=0></td></tr>
 <tr><td height=10 height=0></td></tr>
</table>
</form>
 

<table Border=0 CellSpacing=0 CellPadding=0 width=650>
 <tr><td height=10></td></tr>
     <tr><td class=lmenutext align=center><a href="services.php">[ ����� ]</a><br><br>
</table>

</center>

  </td>
    <td width=10></td>
</tr>

</table>
</div>
</center>

</BODY>
</HTML>
