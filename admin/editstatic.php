<?php
    include("inc/settings.php");

    if(!isAllowed("rstatic")) {die("� ��� ������������ ���� ��� ��������� ���� ��������");}    
    if (!empty($oper))
    {
    	$err=0;
     	/*if (($oper=='I' or $oper=='U') and (isset($page_url)))
    	{	   	     	
    	 mysql_query("start transaction;");
    	     	 $query="update {$PREFFIX}_page set page_url='$page_url'
    	    	where (static_code=$static_code)";
    	 $result=mysql_query($query) or $err=1; 
    	}*/
    	   
    	if ($oper=='I' and $err==0)
    	{
    		mysql_query("start transaction;");    		
    		$query="insert into {$PREFFIX}_static (static_code, static_title, static_text, static_abstract) values ($static_code,'$static_title','$static_text','$static_abstract')";
    		$result=mysql_query($query) or $err=2;
    		
    		$static_code=mysql_insert_id();
    		
    		if(!$err)
    		{
    			mysql_query("commit;");
    		}
    		else mysql_query("rollback;");
    	}
    	
    	if ($oper=='U' and $err==0)
    	{
    		mysql_query("start transaction;");   		
    		
    		$query="update {$PREFFIX}_static set static_title='".mysql_escape_string($static_title)."', static_text='".mysql_escape_string($static_text)."',static_abstract='".mysql_escape_string($static_abstract)."'		
    		where (static_code=$static_code)";
    		$result=mysql_query($query) or $err=3;//die("�� ���� �������� ��������:<br>$query<br>".mysql_error());
      	
    		if(!$err)
    		{
    			mysql_query("commit;");
    		}
    		else mysql_query("rollback;");
    	}
    	// ��������� �������� �� ����
    	
    	/*if ($_FILES['pdffile'])
    	{
    		// ���� ���� �������� �������, ���������� ���
    		// �� ��������� ���������� � ��������
    		if ($_FILES["pdffile"]["error"]) $err=5;
    			
    		if(is_uploaded_file($_FILES["pdffile"]["tmp_name"]))
    		{	
    		  $dest="../files/".$_FILES["pdffile"]["name"];
    		  move_uploaded_file($_FILES["pdffile"]["tmp_name"], $dest);
	       	  //�������� � ���� URL
    		  mysql_query("start transaction;");
    		  $query="update {$PREFFIX}_static set static_url='$dest'    		  
    		  where (static_code=$static_code)";
    		  $result=mysql_query($query) or $err=4;//die("�� ���� �������� ��������:<br>$query<br>".mysql_error());
    	      if(!$err) { mysql_query("commit;");} 	else mysql_query("rollback;");
    		}
    	} */
    	 
    	 
    }

    include ("inc/head.php");
    $editorname='static_text,static_abstract';
    include("inc/editorbig.php");
    
?>

<script language = JavaScript>
function Upload()
{
document.data.oper.value='F';
document.data.submit();
}

function makeRequest(url) {
	var httpRequest;

	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		httpRequest = new XMLHttpRequest();
		if (httpRequest.overrideMimeType) {
			httpRequest.overrideMimeType('text/xml');
			// See note below about this line
		}
	} 
	else if (window.ActiveXObject) { // IE
		try {
			httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e) {
			try {
				httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} 
			catch (e) {}
		}
	}

	if (!httpRequest) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
	httpRequest.onreadystatechange = function() { alertContents(httpRequest); };
	httpRequest.open('GET', url, true);
	httpRequest.send('');

}

function alertContents(httpRequest) {
	if (httpRequest.readyState == 4) {
		if (httpRequest.status == 200) {
			document.getElementById("txtHint").innerHTML="�������� ������";
		} else {
			alert('There was a problem with the delete pdf request.');
		}
	}
}
</script>
<BODY>
<center>

<div class=mainbody>

<? include("inc/top.php");?>


<table Border=0 CellSpacing=0 CellPadding=0 width=100%>
  <tr><td  class=pageline>
    <div class=wmiddletext><a href="main.php">����������������� �����</a> &#187; <a href="statlist.php">��������� ��������</a> &#187; �������������� ����������� ��������</a></div>
  </td>
   <td width=200 class=pageline> <div class=wmiddletext>
        <?/////////////////////// include("inc/selectlang.php");?>  
    </div>
  </td>
  </tr>
</table>
&nbsp;

<?php 
 //if (!empty($oper)) echo $oper.'<BR>';
  /*$mainquery="SELECT static_code, static_title, static_text,static_abstract
              FROM  {$PREFFIX}_static
              WHERE (static_code=$static_code)";
  echo $mainquery.'<br>';
  $mainres=mysql_query($mainquery) or die(mysql_error());
  $mainnum_rows=mysql_num_rows($mainres); 
  $main_action=($mainnum_rows>0) ? "U" : "I";  
  list($static_code, $static_title, $static_text, $static_abstract)=mysql_fetch_array($mainres);
  $pnq="SELECT static_type,static_title  FROM  {$PREFFIX}_static WHERE (static_code=$static_code)";
  $pnqres=mysql_query($pnq)or die (mysql_error());
  if (mysql_num_rows($pnqres)>0)
 	list($page_type,$page_name,$page_url)=mysql_fetch_array($pnqres);
  if ($static_title=="") $static_title=$page_name;*/
  
  $query = "SELECT static_code, static_title, static_text,static_abstract 
			FROM {$PREFFIX}_static 
			WHERE (static_code=$static_code)";	
  $res = mysql_query ($query) or die ("������ �� ��������, ����� �� ���������.<br> ������ � �������: ".$query);
  $mainnum_rows=mysql_num_rows($res);
  $main_action=($mainnum_rows>0) ? "U" : "I"; 	// ����� ��� �������������� ����������� ��������
  list($static_code, $static_title, $static_text, $static_abstract) = mysql_fetch_array($res);
?>

<table Border=0 CellSpacing=0 CellPadding=0 class=mainbody>
 <tr valign=top>

  <td width=10></td>
  <? include("inc/leftmenu.php"); ?>
  <td width=10></td>

  <td>

<table class=grayhead Border=0 CellSpacing=0 CellPadding=0>
 <tr class=normaltext>
 <!--  <td ><div ><h4><?=$page_name;?></h4></div></td>  -->
  <td ><div ><h4> ���������� �������� </h4></div></td>
  <td align=right>  </td>
 </tr>
</table>

<center>

<table Border=0 CellSpacing=0 CellPadding=0 width=650>
<tr><td>&nbsp;</td></tr>
<?php 
  if ($err==1) print'<tr><td class=smalltext align=center style="color:red;"> ������ ���������� ����� �������������</td></tr>';
  if ($err==2) print'<tr><td class=smalltext align=center style="color:red;"> ������ ���������� ��������</td></tr>';
  if ($err==3) print'<tr><td class=smalltext align=center style="color:red;"> ������ ���������� ��������</td></tr>';
  if ($err==4) print'<tr><td class=smalltext align=center style="color:red;"> ������ ���������� ����� ����� ������������</td></tr>';
  if ($err==5) print'<tr><td class=smalltext align=center style="color:red;"> ������ �������� ����� ������������</td></tr>';
?>
      <!-- <tr><td class=lmenutext align=center><a href="<?//=$_SESSION["pageback"]?>">[ ����� ]</a><br> -->
	  <!--<tr><td class=lmenutext align=center><a href="statlist.php">[ ����� ]</a><br>-->
	  <td class=lmenutext align=center><a href="javascript:history.back();">[ ����� ]</a><br>
</table>


<form name=data action=<?=$PHP_SELF;?> method=POST enctype="multipart/form-data">
     <input type=hidden name=oper value='<?=$main_action?>'>
     <input type=hidden name=static_code value=<?=$static_code;?>>
     <input type=hidden name=page_name value=<?=$page_name;?>>
    <!-- <input type=hidden name=langindex value=<?//=$langindex;?>> -->
     
     <?php
      if ($mainnum_rows>0) echo' <input type=hidden name=static_code value='.$static_code.'>';
     ?>    
     
<table Border=0 CellSpacing=0 CellPadding=0 width=650>
 <tr><td height=10></td></tr>
 <tr>
  <td bgcolor=#f9f9f9 style='padding:5px'>
  <table Border=0 CellSpacing=0 CellPadding=0>
     <tr><td class=lmenutext><a>��������� ��������  [<?=$static_title;?>] :</a><br>
       <input type=text name='static_title' style="width:630px;" value="<?=$static_title; ?>"><p>
     </td></tr>
     <?php  
       if ($page_type!="static")  
	   {
			/*print '<tr><td class=lmenutext>���������  [<?=$langname;?>]:<br>';*/
			print '<tr><td class=lmenutext>���������  ['.$static_title.']:<br>';
			//print '<textarea name="static_abstract" style="width:630px;height:200px;">'. $static_abstract.'</textarea><p>';
			print '<textarea name="static_abstract" style="width:630px;height:200px;">'.$static_abstract.'</textarea><p>';
			print '</td></tr>';
       }
      ?>
     <tr><td class=lmenutext>����� ��������  [<?=$static_title;?>]:<br>
       <textarea name='static_text' style="width:630px;height:400px;"> <?=$static_text;?> </textarea><p>
     </td></tr>
     <?php  
     /*if ($page_type!="static")  {
         print '<tr><td class=lmenutext>';
         $labeltext='������ �� ������� ��������';
         if ($page_type=="catalog") $labeltext='������ �� ���� �������������';
         print $labeltext;
         print '<br> <input type="text" name="page_url" style="width:630px;" value="'.$page_url.'"><p>';
         print '</td></tr>';
     }
     if ($page_type=="catalog")
     {
     	print '<tr><td class=lmenutext > ������ �� �������� (pdf) <span id="txtHint">'.$static_url.'</span>';
     	if ($static_url!="")
     	{
     	print ' &nbsp <input type="button" type=button class=smalltext onClick=makeRequest("deletepdf.php?static_code='.$static_code;
    	print '") value="������� ��������">';}
    	print '<br>';
     	print '<input type=file style="width:300px" name="pdffile">';     	
     }*/
	 	
     ?>
     
  </table>
  </td>
 </tr>
 <tr><td height=10 bgcolor=#f9f9f9 height=0></td></tr>
 <tr><td bgcolor=#f9f9f9 ><center><input type=submit class=smalltext value='��������� ���������' )></td></tr>
 <tr><td height=10 bgcolor=#f9f9f9 height=0></td></tr>
 <tr><td height=10 height=0></td></tr>
</table>
 </form>

<table Border=0 CellSpacing=0 CellPadding=0 width=650>
<!-- <tr><td height=10></td></tr>-->
     <!--<tr><td class=lmenutext align=center><a href="<?//=$_SESSION["pageback"]?>">[ ����� ]</a><br><br>-->
	<!-- <tr><td class=lmenutext align=center><a href="statlist.php">[ ����� ]</a> -->
	<td class=lmenutext align=center><a href="javascript:history.back();">[ ����� ]</a><br>
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
