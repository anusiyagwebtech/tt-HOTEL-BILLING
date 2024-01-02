<?php
include ('../config/config.inc.php');
$supplier = FETCH_all("SELECT * FROM `online_order` WHERE `id`=?", $_REQUEST['id']);
if ($supplier['order_method'] == '1') {
if ($supplier['order_type'] == '1') { 
$ordtype='Vendor Order';
}
else
{
	$ordtype='Phone Order';
}
	
}
if ($supplier['order_method'] == '2') {
if ($supplier['order_type'] == '1') { 
$ordtype='Dinnig Order';
}
else
{
	$ordtype='Take Away';
}
	
}

?>
  <div id="invoice-POS">
    
    <center id="top">
      <div class="logo"></div>
      <div class="info"> 
      <!--  <img src="http://localhost:8080/westernfinance/pages/profile/image/1616745309.png" height="50" width="50"><br>-->
		
		<h2>Microwebz Billing<br><h2>
		
		<!--<h4>Contact No : +91 8248592800<br>No, 248/2 (Opp.to SP Office) Near River City Vegetable Shop<br>Alagar Kovil Main Road, Surveyor Colony, Madurai - 625 007</h4>-->
        <!-- <h2>Madurai Scraps</h2> -->
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    <hr>
    <div id="mid">
      <div id="table">
        <table width="100%">
		<tr class="tabletitle" style="font-size:16px;">
		<td align="left"><strong>Bill No :</strong> #PET<?php echo $supplier['bill_number']; ?><br><br>
		<strong> <?php echo $supplier['payment_mode']; ?></strong>
		</td>
		<td align="right" valign="top"><?php echo date('d-m-Y, h:i a',strtotime($supplier['cudate'])); ?><br><br>
		<strong><?php echo $ordtype; ?></strong>
		</td>
		</tr>
		</table>
      </div>
    </div><!--End Invoice Mid-->
    <hr>
    <div id="bot">

          <div id="table">
            <table border="1" cellpadding="4" cellspacing="0">
              <tr class="tabletitle">
                <td class="item"><h4>Sno</h4></td>
                <td class="Hours"><h4>Description</h4></td>
                <td class="Rate"><h4>Qty</h4></td>
                <td class="Rate"><h4>Rate</h4></td>
				 <td class="Rate"><h4>Amt</h4></td>
              </tr>


<?php
 $sno =1;
    $tot_amnt=0;
    $object_detail = $db->prepare("SELECT * FROM `online_order_deatils` WHERE `object_id`= ?");
    $object_detail->execute(array($_REQUEST['id']));


    while ($object_detaillist = $object_detail->fetch(PDO::FETCH_ASSOC)) 
    {
      ?>
              <tr class="service">
			   <td class="tableitem"><p class="itemtext"><?php echo $sno; ?></p></td>
                <td class="tableitem"><p class="itemtext"><?php echo $object_detaillist['product_name']; ?></p></td>
                <td class="tableitem"><p class="itemtext"><?php echo $object_detaillist['qty']; ?></p></td>
                <td class="tableitem"><p class="itemtext">Rs. <?php echo $object_detaillist['rate']; ?></p></td>
                <td class="tableitem"><p class="itemtext">Rs. <?php echo $object_detaillist['total']; ?></p></td>
              </tr>
<?php
$tot_amnt += $object_detaillist['rate'];
$sno++;
}
?>
              


              



            </table>
          </div><!--End Table-->

<br>
   <div id="mid">
      <div id="table" align="right">
        <table width="100%">
		<tr class="tabletitle" style="font-size:16px; margin-bottom:5px;">
		 <td align="right" valign="top"><strong>Sub Total </strong></td>
		 <td align="right" valign="top"><strong> : Rs. </strong></td>
		 <td align="right" valign="top"><?php echo number_format($supplier['sub_tot'],2); ?></td>
		</tr>
		
		<?php if($supplier['discount']!='') { ?>
		<tr class="tabletitle" style="font-size:16px;margin-bottom:5px;">
		 <td align="right" valign="top"><strong>Discount </strong></td>
		 <td align="right" valign="top"><strong> : Rs. </strong></td>
		 <td align="right" valign="top"><?php echo number_format($supplier['discount'],2); ?></td>
		</tr>
		<?php }  if($supplier['packing_charges']!='') { ?>
		<tr class="tabletitle" style="font-size:16px;margin-bottom:5px;">
		 <td align="right" valign="top"><strong>Packing Charges </strong></td>
		 <td align="right" valign="top"><strong> : Rs. </strong></td>
		 <td align="right" valign="top"><?php echo number_format($supplier['packing_charges'],2); ?></td>
		</tr>
		<?php }  if($supplier['delivery_charges']!='') { ?>
		<tr class="tabletitle" style="font-size:16px;margin-bottom:5px;">
		 <td align="right" valign="top"><strong>Delivery Charges </strong></td>
		 <td align="right" valign="top"><strong> : Rs. </strong></td>
		 <td align="right" valign="top"><?php echo number_format($supplier['delivery_charges'],2); ?></td>
		</tr>
		<?php }  ?>
		<tr class="tabletitle" style="font-size:16px;margin-bottom:5px;">
		 <td align="right" valign="top"><strong>Grand Total </strong></td>
		 <td align="right" valign="top"><strong> : Rs. </strong></td>
		 <td align="right" valign="top"><?php echo number_format($supplier['total_amnt'],2); ?></td>
		</tr>
		</table>
      </div>
    </div><!--End Invoice Mid-->
    <hr>
          <div id="legalcopy">
            <center><strong>Thanks and Visit again..!</strong></center>   
            </p>
          </div>

        </div><!--End InvoiceBot-->
  </div><!--End Invoice-->
<style type="text/css">
@media print {
     html, body {
        border: 1px solid white;
        height: 99%;
        page-break-after: avoid;
        page-break-before: avoid;
     }
}
  #invoice-POS{
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  margin: 0 auto;
  width: 72mm;
  height: auto;
  background: #FFF;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top .logo{
  //float: left;
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
  background-size: 60px 60px;
}
.clientlogo{
  float: left;
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
  background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  //padding: 5px 0 5px 15px;
  //border: 1px solid #EEE
}
.tabletitle{
  //padding: 5px;
  font-size: 14px;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext
{
 font-size: 1px;
}

#legalcopy{
  margin-top: 5mm;
}


  
  
}


.service .tableitem p.itemtext {
    font-size: 12px!important;
}
</style>
<script>
window.onload = function () {
    window.print();
}
</script>