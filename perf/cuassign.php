<?php
        session_start();
        if($_SESSION["Username"] == "")
        {
                header("location:logout.php");
                exit();
        }

        // set timeout period in seconds
        $inactive = 1800;

        // check to see if $_SESSION['timeout'] is set
        if(isset($_SESSION['timeout']) ) {
                $session_life = time() - $_SESSION['timeout'];
                if($session_life > $inactive)
                        { session_destroy(); header("Location: logout.php"); }
        }
        $_SESSION['timeout'] = time();
include("db.php");
//$objConnect = mysqli_init();
//mysqli_real_connect($objConnect,"luna.g-able.ga",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",3036,NULL,MYSQLI_CLIENT_SSL) or die("Error Connect to Database");
//$objConnect = mysqli_connect("localhost",$_SESSION["MUSER"],$_SESSION["MPASS"],"perf",NULL,"/cloudsql/smiling-gasket-170108:us-central1:csypart") or die("Error Connect to Database");
   //$objDB = mysqli_select_db("perf");
   mysqli_query($objConnect,"SET NAMES UTF8");
?>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8"> 
 <script type="text/javascript" src="scripts/ajax.js"></script>
</head>
<body>
<?php
  if ($_GET["RESULT"]) {
   switch ($_GET["RESULT"]) {
    case 3:
        echo "<h2><font color=green>".$_GET["INFO"]." add success</font></h2>";
        break;
    case 4:
        echo "<h2><font color=red>Add Failed</font></h2>";
        break;
    case 5:
        echo "<h2><font color=red>".$_GET["INFO"]." is existing </font></h2>";
        break;
   }
  echo "<hr>";
  }
        if($_POST["hdnCmd"] == "Update")
          {
            $strSQL = "UPDATE customer SET ";
            $strSQL .="customer = '".trim($_POST["txtEditcustomer"])."' ";
            $strSQL .=",detail = '".$_POST["txtEditdetail"]."' ";
            $strSQL .=",address = '".$_POST["txtEditaddress"]."' ";
            $strSQL .="WHERE customer = '".$_POST["hdnEditcustomer"]."' ";
            $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
            if(!$objQuery)
                {
                echo "Error Update [".mysqli_error()."]";
                }
          }
	if($_GET["Action"] == "Del"){

	  $strSQL = "DELETE  FROM customer ";
          $strSQL .="WHERE customer='".$_GET["CUST"]."'";
          $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
        if($objQuery){
	  unlink("./images/customer/".$_GET["CUST"].".jpg");
	  echo "<font color=blue size=+1> Customer <b>".$_GET["CUST"]."</b> has been deleteted</font><br>";
        }else{
	  echo "<font color=red size=+1><b> Cannot delete customer ".$_GET["CUST"]."</b></font><br>";
        }

	}
?>
Pleae select Customer
<select id="selcust" name="selcust" >
<option value="">Select Customer</option>
<?php
   $strSQL = "SELECT customer FROM customer";
   $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
   while($objResult = mysqli_fetch_array($objQuery))
     {
       echo "<option value=".$objResult["customer"].">".$objResult["customer"]."</option>";
     }
?>
</select>

<button type="button" name="btnlist" id="btnlist" onClick="JavaScript:doCallAjax('myuser','cuassign1.php','selcust');">Assigned User
</button>
<span id="myuser"/>
<hr/>

<h1>Add customer</h1>
<form name="frmcustomer" method="post" enctype="multipart/form-data" action="customeradd.php" >
<table width=100%>
<tr>
  <td width=100> customer logo<font size=-1 color=red>*</font></td>
  <td> <input name="fileUpload" type="file" accept="*.jpg"> <font size=-1 color=red>Recommended size 75x75 pixel</font></td>
</tr><tr>
  <td width=100> customer name<font size=-1 color=red>*</font></td>
  <td> <input type="text" name="txtcustname" id="txtcustname" size="20" maxlength="20"> </td>
</tr><tr>
  <td> detail<font size=-1 color=red>*</font></td>
  <td> <input type="text" name="txtdet" id="txtdet" size="75" maxlength="200"></td>
</tr><tr>
  <td> address<font size=-1 color=red>*</font></td>
  <td><input type="text" name="txtaddress" id="txtaddress" size="100" maxlength="500">
  <input name="btnadd" type="submit" id="btnadd" value="Add customer">
  </td>
</tr>
</table>
</form>

<hr/>

<?php
   $strSQL = "SELECT * FROM customer ORDER BY customer";
?>
<form name="frmMain" method="post" action="cuassign.php">
<input type="hidden" name="hdnCmd" value="">
<table width="80%" border=0 cellspacing=0 cellpadding=0 bgcolor=black><tr><td>
<table width="100%" border=0 width=100% cellspacing=1 cellpadding=1>
<tr bgcolor=LimeGreen>
<th width=20% align=left> logo </th>
<th width=20% align=left> customer name </th>
<th width=30% align=left> detail </th>
<th width=45% align=left> address </th>
<th  align=center> edit </th>
</tr>

<?php
   $objQuery = mysqli_query($objConnect,$strSQL) or die ("Error Query [".$strSQL."]");
   $i=1;
   while($objResult = mysqli_fetch_array($objQuery)) 
     { 
      if (($i%2) == 0) {
        echo "<tr bgcolor=lavender>";
      }else{
        echo "<tr bgcolor=LavenderBlush>";
      }
      $i+=1;
        if($objResult["customer"] == $_GET["CUST"] and $_GET["Action"] == "Edit")
                {
?>
<td></td>
<td><div align="center">
       <input type="text" name="txtEditcustomer" size="10" value="<?=$objResult["customer"];?>">
       <input type="hidden" name="hdnEditcustomer" size="5" value="<?=$objResult["customer"];?>">
</div></td>
    <td><input type="text" name="txtEditdetail" size="50" value="<?=$objResult["detail"];?>"></td>
    <td><input type="text" name="txtEditaddress" size="50" value="<?=$objResult["address"];?>"></td>
    <td align="right"><div align="center">
<button name="btnAdd" type="button" id="btnUpdate" OnClick="frmMain.hdnCmd.value='Update';frmMain.submit();">
<img src="./images/save.bmp" alt="save" title="save" width="20" height="20" align="absmiddle" >
</button>
<button name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
<img src="./images/cancel.jpg" alt="cancel" title="cancel" width="20" height="20" align="absmiddle">
</button>
    </div></td>
  </tr>

<?php }else{ 

      echo "<td><img ";
	if (file_exists("./images/customer/".$objResult["customer"].".jpg")){
	  echo "src=./images/customer/".$objResult["customer"].".jpg";
	}else{
	  echo "src=./images/customer/blank.jpg";	
	}
      echo " alt=".$objResult["customer"]." align=center ></td>";
      echo "<td> ".$objResult["customer"]."</td>";
      echo "<td>".$objResult["detail"]."</td>";
      echo "<td>".$objResult["address"]."</td>";
      echo "<td align=center>";
      echo "<button name='btnEdit' type='button' id='btnEdit' OnClick=\"window.location='cuassign.php?Action=Edit&CUST=".$objResult[
"customer"]."';\">";
      echo "<img src='./images/edit.bmp' alt='edit' title='edit'  align='absmiddle' width='20' height='20'>";
      echo "</button>";
      echo "<button name=btnDel type=button id=btnDel OnClick=\"JavaScript:if(confirm('Delete customer ".$objResult["customer"]." ?')==true){window.location='cuassign.php?Action=Del&CUST=".$objResult["customer"]."';}\">";
      echo "<img src=./images/del.jpg alt=delete title=delete  align=absmiddle width=16 heigh=16>";
      echo "</button>";
      echo "</td></tr>";

   } // if
     } // while
   mysqli_close($objConnect); 
?> 
</form>
</table>
</td></tr></table>

</body>
</html>
