<?php 
include ('../../config/config.inc.php');

if($_POST['request1'] == 1){
 $search = $_POST['search'];
$customer = pFETCH("SELECT * FROM `stock_product` WHERE objectname like'%".$search."%' AND `id`!=?", '0');
while ($row = $customer->fetch(PDO::FETCH_ASSOC)) 
{
  $response[] = array("value"=>$row['id'],"label"=>$row['objectname']);
 }

 // encoding array to json format
 echo json_encode($response);
 exit;
}


if($_POST['request'] == 1){
 $search = $_POST['search'];
$customer = pFETCH("SELECT * FROM `object` WHERE (objectname like'%".$search."%' OR productcode like'%".$search."%' ) AND `id`!=?", '0');
while ($row = $customer->fetch(PDO::FETCH_ASSOC)) 
{
	$labl=$row['objectname'].' - '.$row['productcode'];
  $response[] = array("value"=>$row['id'],"label"=>$labl);
 }

 // encoding array to json format
 echo json_encode($response);
 exit;
}


if($_REQUEST['stockdata']!='') {
$link22 = FETCH_all("SELECT  * FROM `stock_product` WHERE `id`=? ", $_REQUEST['stockdata']);
echo $link22['price'];
}
if($_REQUEST['data']!='') {
$link22 = FETCH_all("SELECT  * FROM `object` WHERE `id`=? ", $_REQUEST['data']);
echo $link22['price'];
}

if($_REQUEST['purchasebillno']!='') {
$link22 = FETCH_all("SELECT  * FROM `purchase` WHERE `bill_number`=? ", $_REQUEST['purchasebillno']);
if($link22['id']!='') {
echo "<span style='color:red;'>Bill No Already Exist</span>";
}
else
{
echo "";	
}

}

if($_REQUEST['billno']!='') {
$link22 = FETCH_all("SELECT  * FROM `online_order` WHERE `bill_number`=? ", $_REQUEST['billno']);
if($link22['id']!='') {
echo "<span style='color:red;'>Bill No Already Exist</span>";
}
else
{
echo "";	
}

}
?>