<?php
if (isset($_REQUEST['lid'])) {
    $thispageeditid = 10;
} else {
    $thispageaddid = 10;
}
$menu = "4,4,111";
include ('../../config/config.inc.php');
$dynamic = '1';
include ('../../require/header.php');
if (isset($_REQUEST['createaccount'])) {
	global $db;
    @extract($_REQUEST);
		
	$status=1;
$resa = $db->prepare("INSERT INTO `supplier` (`shopname`,`suppliername`, `area`,`mobileno`,`status`,`date`,`ip`) VALUES(?,?,?,?,?,?,?)");
            $resa->execute(array($shopname, $suppliername , $area, $mobileno,$status,date('Y-m-d'),$ip));
			
 $insid = $db->lastInsertId();    
$url="addpurchase.php?pid=".$insid; 
  echo "<script>window.location.assign('".$url."')</script>";  

}


if (isset($_REQUEST['submit'])) {
    @extract($_REQUEST);
    $getid = $_REQUEST['lid'];
    $ip = $_SERVER['REMOTE_ADDR'];

 $oid1 = implode(',', $oid);
    $object1 = implode(',', $objectval);
    $quantity1 = implode(',', $quantity);
    $rate1 = implode(',', $rate);
    $tamount1 = implode(',', $total);
   

    $customerid1 = FETCH_all("SELECT  * FROM `customer` WHERE `id`=?", $cusid);

    $customerid = $customerid1['cusid'];
// $object11 = ltrim($object1, ',');
// $quantity11 = ltrim($quantity1, ',');
$msg = addpurchase($oid1,$bill_no,$supplierid, $date, $netweight, $amount, $status,$object1, $quantity1,$rate1,$tamount1, $sub_total,$discount,$tot_amt,$payment_type,$advance_amount,$balance_amount,$getid);
}

if (isset($_REQUEST['lid']) && ($_REQUEST['lid'] != '')) {
    $get1 = $db->prepare("SELECT * FROM `return` WHERE `id`=?");
    $get1->execute(array($_REQUEST['id']));
    $showrecords = $get1->fetch(PDO::FETCH_ASSOC);
}
if (isset($_REQUEST['delid1']) && $_REQUEST['delid1'] != '') {
    $up = $db->prepare("DELETE FROM `purchase_object_detail` WHERE `id`=?");
    $up->execute(array($_REQUEST['delid1']));
    $a = $_REQUEST['lid'];
	if($_REQUEST['lid']!='') { 
    echo '<script>window.location.href="' . $sitename . 'process/' + $_REQUEST['lid'] + '/editpurchase.htm"</script>';
	}else{
	   echo '<script>window.location.href="' . $sitename . 'process/addpurchase.htm"</script>';	
	}
	
//    echo '<script>window.location.href="' . $sitename . 'process/' + $_REQUEST['lid'] + 'editpurchase.htm"</script>';
}
?>

<style>
.content-wrapper, .right-side {
    min-height: 100%;
    background-color: #fff;
    z-index: 800;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 28px;
    font-size: 13px;
    font-weight: bold;
}
    .form-control{
        font-size:13px;
        font-weight:bold;
    }
    label{
        font-size:13px;
    }
    input{
        font-style:bold;
    }
</style>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Purchase
            <small><?php
                if ($_REQUEST['lid'] != '') {
                    echo 'Edit';
                } else {
                    echo 'Add New';
                }
                ?> Purchase</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $sitename; ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="#"><i class="fa fa-asterisk"></i> Master(s)</a></li>            
            <li><a href="<?php echo $sitename; ?>pages/process/purchase.php"><i class="fa fa-circle-o"></i> Purchase</a></li>
            <li class="active"><?php
                if ($_REQUEST['lid'] != '') {
                    echo 'Edit';
                } else {
                    echo 'Add New';
                }
                ?> Purchase</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <form name="department" id="department"  method="post" enctype="multipart/form-data">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php
                        if ($_REQUEST['lid'] != '') {
                            echo 'Edit';
                        } else {
                            echo 'Add New';
                        }
                        ?> Purchase</h3>
                    <span style="float:right; font-size:13px; color: #333333; text-align: right;"><span style="color:#FF0000;">*</span> Marked Fields are Mandatory</span>
                </div>
                <div class="box-body">
                    <?php echo $msg; ?>
                    <div class="row">
                        <div class="col-md-4">
                           <input type="hidden" id="lid" value="<?php echo $_REQUEST['lid']; ?>">
						   <table width="100%">
						<tr>
						<td valign="top">
						<label>Supplier Name<span style="color:#FF0000;">*</span></label>
                            <?php //$purid = get_bill_settings('prefix', '2') . str_pad(get_bill_settings('current_value', '2'), get_bill_settings('format', '1'), '0', STR_PAD_LEFT);
                            ?>
							 <select name="supplierid" id="supplierid" class="form-control select2" required="required" style="font-size: 19px;font-weight: bold;">
							 <option value="">Select</option>
                             <?php
$customer = pFETCH("SELECT * FROM `supplier` WHERE `status`=?", '1');
while ($customerfetch = $customer->fetch(PDO::FETCH_ASSOC)) 
{
?>
 <option value="<?php echo $customerfetch['id']; ?>" <?php if(getpurchase("supplierid", $_REQUEST['lid'])==$customerfetch['id'] || $customerfetch['id']==$_REQUEST['pid']) { ?> selected="selected" <?php } ?>><?php echo $customerfetch['suppliername']; ?></option>
<?php } ?>							
							 </select>
							 
                   
						</td>
						<td style="vertical-align:bottom;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Add Supplier</button></td>
						</tr>
						</table>
				
				
                        </div>

                        <div class="col-md-4" style="margin-top: 10px;">
                            <label>Date<span style="color:#FF0000;">*</span></label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right usedatepicker" name="date" id="date" required="required"  value="<?php
                                if (isset($_REQUEST['lid']) && (date('d-m-Y', strtotime(getpurchase('date', $_REQUEST['lid']))) != '01-01-1970')) {
                                    echo date('d-m-Y', strtotime(getpurchase('date', $_REQUEST['lid'])));
                                } else {
                                    echo date('d-m-Y');
                                }
                                ?>" >
                            </div>  
                        </div>
						 
                            <div class="col-md-4" style="margin-top: 10px;">
                            <label>Reference Bill Number<span style="color:#FF0000;">*</span></label>
                            <!--  Manual Bill:<input type="checkbox" name="" id="chkRead"> -->
                            
                                <input type="text" class="form-control"name="bill_no" id="bill_no" required="required" value="<?php echo getpurchase('bill_number',$_REQUEST['lid']); ?>">                                
                          
                        </div>
                  
				  
</div>             
                    <!-- <div class="clearfix"><br /></div> -->
                   
				   
                            <div class="row">   
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table width="80%" class="table table-bordered" id="task_table" cellpadding="0"  cellspacing="0">
                                            <thead>
                                                <tr style="font-size:13px;">
                                                    <th width="5%">S.no</th>
                                                    <th width="20%">Product</th>
                                                    <th width="10%">Quantity</th>
                                                    <th width="10%">Rate</th>
                                                    <th width="10%">Total</th>
                                                    <!--<th width="55%">Image</th>-->
                                                    <th width="5%">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $b = 1;
                                                $object1 = $db->prepare("SELECT * FROM `purchase_object_detail` WHERE `object_id`= ?");
                                                $object1->execute(array($_REQUEST['lid']));
                                                $scount = $object1->rowcount();
                                                //echo $_REQUEST['lid'];
                                                if ($scount != '0') {
                                            while ($object1list = $object1->fetch(PDO::FETCH_ASSOC)) 
                                            {

                                                        ?>
                                                        <tr>
                                                            <td><?php echo $b; ?></td>
                                                            <td>
															  <input type="text" name="objectval[]" id="objectval" class="form-control objectchange" value="<?php echo $object1list['object']; ?>">
															  
</td>
                                                           
        <td style="border: 1px solid #f4f4f4;"><input type="text" style="text-align: right;" name="quantity[]" id="quantity[]" pattern="[0-9]+" class="form-control quantity" value="<?php echo $object1list['pquantity']; ?>" onkeyup="quantitycalculation(this.value)"/><input type="hidden" name="qtyhidden" id="qtyhidden" value="" /></td>
        <td style="border: 1px solid #f4f4f4;"><input type="text" style="text-align: right;" name="rate[]" id="rate[]" class="form-control rate" value="<?php echo $object1list['rate']; ?>" onkeyup="quantitycalculation(this.value)"/><input type="hidden" name="ratehidden" id="ratehidden" value="" /></td>
        <td style="border: 1px solid #f4f4f4;"><input type="text" style="text-align: right;" name="total[]" id="total[]" class="form-control total" value="<?php echo $object1list['amount']; ?>" onkeyup="quantitycalculation(this.value)"/>
		<input type="hidden" name="oid[]" value="<?php echo $object1list['id']; ?>">
		<input type="hidden" name="qtyhidden" id="qtyhidden" value="" /></td>

                                                            <td onclick="delrec1($(this), '<?php echo $object1list['id']; ?>')" style="border: 1px solid #f4f4f4;"><i class="fa fa-trash fa-2x" style="color:#F00;cursor:pointer;"></i></td>
                                                        </tr>
                                                        <?php
                                                        $b++;
                                                    }
                                                }
                                                ?>
												
                                                <tr id="firsttasktr" style="display:none;">
                                                    <td>1</td>
                                                    <td>
													<input type="hidden" name="oid[]">
                                                      <input type="text" name="objectval[]" id="objectval" class="form-control objectchange">
                                                    </td>
<td style="border: 1px solid #f4f4f4;"><input type="text" style="text-align: right;" name="quantity[]" id="quantity[]" class="form-control quantity" value="" /><input type="hidden" name="qtyhidden" id="qtyhidden" pattern="[0-9]+" onkeyup="quantitycalculation(this.value)"/></td>

                                                    
<td style="border: 1px solid #f4f4f4;"><input type="number" style="text-align: right;" name="rate[]" id="rate[]" class="form-control rate" value="" /><input type="hidden" name="ratehidden" id="ratehidden" value="" onkeyup="quantitycalculation(this.value)"/></td>

<td style="border: 1px solid #f4f4f4;"><input type="number" style="text-align: right;" name="total[]" id="total" class="form-control total"/><input type="hidden" name="totalhidden" id="totalhidden" value="" onkeyup="quantitycalculation(this.value)"/></td>

                                                    <!--<td style="border: 1px solid #f4f4f4;"><div class="row"><div class="col-md-6"><input class="form-control spinner" <?php if (getobjectdetail('object_image', $_REQUEST['lid']) == '') { ?>  <?php } ?> name="object_image[]" type="file"></div></div> </td>-->
                                                </tr>
												
                                                <tr id="firsttasktr1">
                                                    <td><?php echo $b; ?></td>
                                                    <td>
													<input type="hidden" name="oid[]">
                                                      <input type="text" name="objectval[]" id="objectval" class="form-control objectchange">
                                                    </td>
<td style="border: 1px solid #f4f4f4;"><input type="text" style="text-align: right;" name="quantity[]" id="quantity[]" class="form-control quantity" value="" /><input type="hidden" name="qtyhidden" id="qtyhidden" pattern="[0-9]+" onkeyup="quantitycalculation(this.value)"/></td>

                                                    
<td style="border: 1px solid #f4f4f4;"><input type="number" style="text-align: right;" name="rate[]" id="rate[]" class="form-control rate" value="" /><input type="hidden" name="ratehidden" id="ratehidden" value="" onkeyup="quantitycalculation(this.value)"/></td>

<td style="border: 1px solid #f4f4f4;"><input type="number" style="text-align: right;" name="total[]" id="total" class="form-control total"/><input type="hidden" name="totalhidden" id="totalhidden" value="" onkeyup="quantitycalculation(this.value)"/></td>

                                                    <!--<td style="border: 1px solid #f4f4f4;"><div class="row"><div class="col-md-6"><input class="form-control spinner" <?php if (getobjectdetail('object_image', $_REQUEST['lid']) == '') { ?>  <?php } ?> name="object_image[]" type="file"></div></div> </td>-->
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr><td colspan="6"></td></tr>
                                                <tr>
                                                    <td colspan="1" style="border:0;"><button type="button" class="btn btn-info" style="background-color: #00a65a;border-color: #008d4c;" id="add_task">Add Item</button></td>
                                                    <!--<td style="text-align:right;"> <label>Total Quantity </label> </td>-->
                                                    <!--<td><input type="number" style="text-align: right;font-size: 19px;" name="totalquantity" id="totalquantity" value="<?php echo getpurchase('totalquantity', $_REQUEST['lid']); ?>" /></td>-->
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>                                   
                                </div>
                            </div>
                            <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 6px solid #ffff;
  text-align: left;
  padding: 10px;
}

tr:nth-child(even) {
  /*background-color: #dddddd;*/
}
</style>



<div class="row">
<div class="col-md-12">
<table border="1" cellpadding="10" style="width:500px;font-size:13px; font-weight:bold;" align="right">

  
  <tr>
    <td>Sub Total:</td>
    <td><input type="text" name="sub_total" id="sub_total" readonly placeholder="subtotal" value="<?php echo stripslashes(getsales("sub_tot", $_REQUEST['lid'])); ?>"></td>
  </tr>
  <tr>
   <td>Discount:</td>
    <td><input type="text" name="discount" id="discount" value="<?php echo stripslashes(getsales("discount", $_REQUEST['lid'])); ?>"></td>
    
  </tr>
  
   <tr>
 
 
    <td>Grand Total:</td>
    <td><input type="text" name="tot_amt" id="tot_amt" placeholder="Total Amount" value="<?php echo stripslashes(getsales("total_amnt", $_REQUEST['lid'])); ?>"></td>
    
  </tr>
  
  
</table>
  </div>    </div>                     
                      
					  
                   <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 6px solid #ffff;
  text-align: left;
  padding: 10px;
}

tr:nth-child(even) {
  /*background-color: #dddddd;*/
}
</style>




                    <div class="row">
                        <div class="col-md-4">
                            <label>Payment Type <span style="color:#FF0000;">*</span></label>
                            <select name="payment_type" id="payment_type" class="form-control" required="required">
							<option value="">select</option>
                                <option value="1" <?php
                                if (stripslashes(getpurchase('payment_type', $_REQUEST['lid'])) == '1') {
                                    echo 'selected';
                                }
                                ?>>Cash Bill</option>
                                <option value="0" <?php
                                if (stripslashes(getpurchase('payment_type', $_REQUEST['lid']) == '0')) {
                                    echo 'selected';
                                }
                                ?>>Credit Bill</option>

                            </select>
                           <!--  <input type="text" name="" value="<?php echo getpurchase("payment_type", $_REQUEST['lid']) ?>"> -->
                        </div>
                        <div class="col-md-4" <?php if(getpurchase("payment_type", $_REQUEST['lid'])=='0') { ?> style="display:block" <?php } else { ?> style="display:none" <?php } ?> id="advance">
                            <label>Advance Amount <span style="color:#FF0000;">*</span></label>
                            <input type="text" name="advance_amount" id="advance_amount" placeholder="Enter Amount" class="form-control" value="<?php echo stripslashes(getpurchase("advance_amount", $_REQUEST['lid'])); ?>" maxlength="10"/>
                        </div>
						<div class="col-md-4" <?php if(getpurchase("payment_type", $_REQUEST['lid'])=='0') { ?> style="display:block" <?php } else { ?> style="display:none" <?php } ?> id="balance">
                            <label>Balance Amount <span style="color:#FF0000;">*</span></label>
                            <input type="text" name="balance_amount" id="balance_amount" placeholder="Enter Amount" class="form-control" value="<?php echo stripslashes(getpurchase("balance_amount", $_REQUEST['lid'])); ?>" maxlength="10"/>
                        </div>
                        <!--  <div class="col-md-4">
                             <label>Interest Percent<span style="color:#FF0000;"></span></label>
                             <input type="text" id="interestpercent"  name="interestpercent" placeholder="Enter Interest Percent" class="form-control" onchange="interest_calculation()" value="<?php echo stripslashes(getpurchase("interestpercent", $_REQUEST['lid'])); ?>" />
                         </div> -->

                    </div> <br>
                    <!-- <div class="panel panel-info">
                   <div class="panel-heading">ID Proof Details</div>
                   <div class="panel-body">
                       <div class="row">   
                           <div class="col-md-12">
                               <div class="table-responsive">
                                   <table width="80%" class="table table-bordered" id="proof_table" cellpadding="0"  cellspacing="0">
                                       <thead>
                                           <tr>
                                               <th width="5%">S.no</th>
                                               <th width="10%">Proof Name</th>
                                               <th width="10%">Proof</th>
                                               <th width="5%">Delete</th>
                                           </tr>
                                       </thead>
                                       <tbody>

                               <tr id="firstprooftr" style="display:none;">
                                               <td>1</td>

                                              <td>
                                                  <input type="text" name="proofname[]" id="proofname[]" class="form-control">
                                                 
                                             </td>
                                              
                                             <td><input type="file" name="proof[]" id="proof[]"  class="form-control"></td>
                                             
                                           </tr>


                                       </tbody>
                                       <tfoot>
                                           
                                           <tr><td colspan="2"></td></tr>
                                           <tr>
                                               <td colspan="2" style="border:0;"><button type="button" class="btn btn-info" id="add_proof">Add Proof</button></td>
                                               
                                           </tr>
                                       </tfoot>
                                   </table>

                               </div>                                   
                           </div>
                       </div>
                   </div>

               </div> -->
                    <!-- <div class="clearfix"><br /></div> -->
                    <!-- <div class="row">
                          <div class="col-md-4">
                            <label>Net Weight (in gms) <span style="color:#FF0000;">*</span></label>
                            <input type="text"  required="required" name="netweight" id="netweight" placeholder="Enter Net Weight" class="form-control" value="<?php echo stripslashes(getpurchase('netweight', $_REQUEST['lid'])); ?>" maxlength="10"/>
                        </div>
                        <div class="col-md-4">
                            <label>Amount <span style="color:#FF0000;">*</span></label>
                            <input type="text" required="required" name="amount" id="amount" placeholder="Enter Amount" class="form-control" value="<?php echo stripslashes(getpurchase('amount', $_REQUEST['lid'])); ?>" maxlength="10"/>
                        </div>
                         <div class="col-md-4">
                            <label>Interest Percent<span style="color:#FF0000;">*</span></label>
                            <input type="text" required="required" id="interestpercent"  name="interestpercent" placeholder="Enter Interest Percent" class="form-control" value="<?php echo stripslashes(getpurchase('interestpercent', $_REQUEST['lid'])); ?>" />
                        </div>
                       
                    </div> 
                     
                     <br> -->
                    <div class="row">
                        <!-- <div class="col-md-4">
                            <label>Interest <span style="color:#FF0000;">*</span></label>
                            <input type="text" required="required" id="interest"  name="interest" placeholder="Enter Interest Amount" class="form-control" value="<?php echo stripslashes(getpurchase('interest', $_REQUEST['lid'])); ?>" />
                        </div> -->
                        <div class="col-md-4">

                            <label>Status <span style="color:#FF0000;">*</span></label>                                  
                            <select name="status" class="form-control">
                                <option value="1" <?php
                                if (stripslashes(getpurchase('status', $_REQUEST['lid'])) == '1') {
                                    echo 'selected';
                                }
                                ?>>Active</option>
                                <option value="0" <?php
                                if (stripslashes(getpurchase('status', $_REQUEST['lid']) == '0')) {
                                    echo 'selected';
                                }
                                ?>>Canceled</option>

                            </select>
                        </div>

                    </div> 
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($_REQUEST['lid'] != '') {
                                ?>
                                    <!--<a id="printbutton" target="_blank" class="btn btn-info fa fa-print" href="<?php echo $fsitename . 'MPDF/receiptprint.php?id=' . $_REQUEST['lid']; ?>"></a>-->
                            <?php } ?>
                        </div>
                    </div>

                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?php echo $sitename; ?>pages/process/purchase.php" style="font-size:19px;">Back to Listings page</a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="submit" id="submit" class="btn btn-success" style="float:right;"><?php
                                if ($_REQUEST['lid'] != '') {
                                    echo 'UPDATE';
                                } else {
                                    echo 'SUBMIT';
                                }
                                ?></button>
                        </div>
                    </div>
                </div>
            </div><!-- /.box -->
        </form>
    </section><!-- /.content -->
	  
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add New Supplier</strong></h4>
        </div>
		<form name="mform" method="post">
       <div class="row" style="padding:10px;">
                        
                        
                        <div class="col-md-6">
                            <label>Supplier Name <span style="color:#FF0000;">*</span></label>
                            <input type="text"  required="required" name="suppliername" id="suppliername" placeholder="Enter Supplier Name" class="form-control"/>
                        </div>
                         <div class="col-md-6">
                            <label>Contact Person Name <span style="color:#FF0000;">*</span></label>
                            <input type="text"  required="required" name="shopname" id="mobileno" placeholder="Enter Contact Person Name" class="form-control"/>
                        </div>
						</div>
						<br>
						<div class="row"  style="padding:10px;">
						<div class="col-md-6">
                            <label>Mobile No <span style="color:#FF0000;">*</span></label>
                             <input type="text"  required="required" name="mobileno" id="area" placeholder="Enter Mobile No" class="form-control" />
                        </div>
						
						<div class="col-md-6">
                            <label>Area <span style="color:#FF0000;">*</span></label>
                             <input type="text"  required="required" name="city" id="area" placeholder="Enter Area" class="form-control"/>
                        </div>
                       
					   
                         <!-- <div class="col-md-4">
                             <label>Receipt Number <span style="color:#FF0000;">*</span></label>
                         <?php //$purid = get_bill_settings('prefix', '2') . str_pad(get_bill_settings('current_value', '2'), get_bill_settings('format', '1'), '0', STR_PAD_LEFT);
                                    ?>
                                    <input type="text" name="receipt_no" id="receipt_no" placeholder="Enter the Receipt Number"  class="form-control" value="<?php echo (getcustomer('receipt_no',$_REQUEST['cid'])); ?>" />
                        </div>  -->
                    </div>
        
		<div class="modal-footer">
          <button type="submit" name="createaccount" class="btn btn-default" name="newcustomer">Save</button> &nbsp; &nbsp;&nbsp; <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
       </form>
      </div>
      
    </div>
  </div>
 
 
</div><!-- /.content-wrapper -->
<?php include ('../../require/footer.php'); ?>

<link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
<script type="text/javascript">

    function show_contacts(id) {
        $.ajax({
            url: "<?php echo $sitename; ?>getpassup.php",
            data: {get_contacts_of_customer: id}
        }).done(function (data) {
            $('#choose_contacts_grid_table tbody').html(data);
        });
    }


    function delrec(elem, id) {
        if (confirm("Are you sure want to delete this Object?")) {
            $(elem).parent().remove();
            window.location.href = "<?php echo $sitename; ?>master/<?php echo getpurchase('id', $_REQUEST['lid']); ?>/editprovider.htm?delid=" + id;
        }
    }

$(document).on('change', '#payment_type', function (e){
var paytype = $(this).val();
if(paytype == 0){
$("#advance").css("display", "block");	
$("#advance_amount").prop('required',true);
$("#balance").css("display", "block");
$("#balance_amount").prop('required',true);
}
else{
$("#advance").css("display", "none");	
$("#advance_amount").prop('required',false);
$("#balance").css("display", "none");	
$("#balance_amount").prop('required',false);
}

});


$(document).on('keyup', '.objectchange', function (e){
	var product = $(this).val();
	 $this = $(this);
	 
	 // Initialize jQuery UI autocomplete
  $('.objectchange').autocomplete({
   source: function( request, response ) {
    $.ajax({
     url: "<?php echo $sitename.'pages/process/'; ?>proprice.php",
     type: 'post',
     dataType: "json",
     data: {
      search: request.term,request1:1
     },
     success: function( data ) {
      response( data );
     }
    });
   },
   select: function (event, ui) {
    $(this).val(ui.item.label); // display the selected text
    var userid = ui.item.value; // selected value
$.ajax({
				url:"<?php echo $sitename.'pages/process/'; ?>proprice.php",
				data:{stockdata:userid}
			}).done(function(result)
			{
				var rateval=result;
				  $this.parent().parent().find('.rate').val(rateval);
			})

    return false;
   }
  });
  
	
			
});
   

  $(document).ready(function (e) {

$(document).on('keyup', '#given_amt', function (e){
 final_total = parseFloat($('#tot_amt').val()) - parseFloat($(this).val());
	 $('#balance_amt').val(Math.abs(final_total));	
});
$(document).on('keyup', '#discount', function (e){
	if($(this).val()!=='') {
	  final_total = parseFloat($('#sub_total').val()) - parseFloat($(this).val());
	 $('#tot_amt').val(Math.abs(final_total));
	}
	else
	{
	 $('#tot_amt').val($('#sub_total').val());	
	}
	 
 });
 
 
 $(document).on('keyup', '#advance_amount', function (e){
	  final_total = parseFloat($('#tot_amt').val()) - parseFloat($(this).val());
	 $('#balance_amount').val(Math.abs(final_total));
	  });
	  
 $(document).on('keyup', '#packing_charge', function (e){
	 if($(this).val()!=='') {
	 final_total = (parseFloat($('#sub_total').val()) - parseFloat($('#discount').val())) + Number($(this).val());
	 $('#tot_amt').val(final_total);
	 }
	 else
	{
	 final_total = parseFloat($('#sub_total').val()) - parseFloat($('#discount').val());
	 $('#tot_amt').val(Math.abs(final_total));
	}
	 
 });

 $(document).on('keyup', '#delivery_charge', function (e){
	  if($(this).val()!=='') {
		   final_total = (parseFloat($('#sub_total').val()) - parseFloat($('#discount').val())) + Number($(this).val()) + Number($('#packing_charge').val());
	 $('#tot_amt').val(final_total);
	
	  }
	  else{
		
	 final_total = (parseFloat($('#sub_total').val()) - parseFloat($('#discount').val())) + parseFloat($('#packing_charge').val());
	 $('#tot_amt').val(final_total);

	  }
	  
	 
 });

$(document).on('keyup', '#bill_no', function (e){
	  var billno = this.value;
	  
	  
	$.ajax({
				url:"<?php echo $sitename.'pages/process/'; ?>proprice.php",
				data:{purchasebillno:billno}
			}).done(function(result)
			{
				if(result!='') {
					$("#bilresult11").css("display", "block");
				$('#bilresult11').html(result);
				$('#bill_no').val('');
				}
				else
				{
				$('#bilresult11').html('');	
		
				}
				
			})
	 });
	
        $(document).on('keyup', '.total', function (e){
            var rate =  $(this).parent().siblings('td').find('.rate').val();
    var quantity = $(this).val();

    var total = rate * quantity;

$(this).parent().siblings('td').find('.total').val(total);

var sum=0;
 
        $('.total').each(function(){
        sum+=Number($(this).val());
		 
        });

        $('#sub_total').val(sum);
		final_total=sum;
		if($('#lid').val()!=='') {
			discount=$('#discount').val();
			if( parseFloat(discount)!='') {
			final_total = (parseFloat(sum) - parseFloat(discount));
			}
			else{
			final_total = parseFloat(sum);	
			}
			
		$('#tot_amt').val(final_total);
		advance_amount=$('#advance_amount').val();
		balance_amount=$('#balance_amount').val();
			if(advance_amount!=='') {
			gfinal_total = (parseFloat(final_total) - parseFloat(advance_amount));
			$('#balance_amount').val(gfinal_total);
			}
			
		}
		else{
			$('#tot_amt').val(sum);
		}
		
var data = $('#firsttasktr').clone();
            var rem_td = $('<td />').html('<i class="fa fa-trash fa-2x" style="color:#F00;cursor:pointer;"></i>').click(function () {
                if (confirm("Do you want to delete the Details?")) {
                    $(this).parent().remove();
                    re_assing_serial();

                }
            });
            $(data).attr('id', '').show().append(rem_td);

            data = $(data);
            $('#task_table tbody').append(data);
            $('.usedatepicker').datepicker({
                autoclose: true
            });


            re_assing_serial();
        });


	 
        $(document).on('keyup', '.rate', function (e){
            var quantity =  $(this).parent().siblings('td').find('.quantity').val();
    var rate = $(this).val();

    // alert(quantity);
    // alert(rate);
    // exit;

    var total = rate * quantity;

$(this).parent().siblings('td').find('.total').val(total);


var sum=0;
 
        $('.total').each(function(){
        sum+=Number($(this).val());
        });

        $('#sub_total').val(sum);
      final_total=sum;
		if($('#lid').val()!=='') {
			discount=$('#discount').val();
			if( parseFloat(discount)!='') {
			final_total = (parseFloat(sum) - parseFloat(discount));
			}
			else{
			final_total = parseFloat(sum);	
			}
			
		$('#tot_amt').val(final_total);
		advance_amount=$('#advance_amount').val();
		balance_amount=$('#balance_amount').val();
			if(advance_amount!=='') {
			gfinal_total = (parseFloat(final_total) - parseFloat(advance_amount));
			$('#balance_amount').val(gfinal_total);
			}
			
		}
		else{
			$('#tot_amt').val(sum);
		}

        });







        $("input").click(function () {
            $(this).next().show();
            $(this).next().hide();
        });



        $('#add_task').click(function () {


            var data = $('#firsttasktr').clone();
            var rem_td = $('<td />').html('<i class="fa fa-trash fa-2x" style="color:#F00;cursor:pointer;"></i>').click(function () {
                if (confirm("Do you want to delete the Details?")) {
                    $(this).parent().remove();
                    re_assing_serial();

                }
            });
            $(data).attr('id', '').show().append(rem_td);

            data = $(data);
            $('#task_table tbody').append(data);
            $('.usedatepicker').datepicker({
                autoclose: true
            });


            re_assing_serial();

        });

        $('#add_proof').click(function () {


            var data = $('#firstprooftr').clone();
            var rem_td = $('<td />').html('<i class="fa fa-trash fa-2x" style="color:#F00;cursor:pointer;"></i>').click(function () {
                if (confirm("Do you want to delete the Proof?")) {
                    $(this).parent().remove();
                    re_assing_serial();

                }
            });
            $(data).attr('id', '').show().append(rem_td);

            data = $(data);
            $('#proof_table tbody').append(data);
            $('.usedatepicker').datepicker({
                autoclose: true
            });


            re_assing_serial();

        });



    });

    function del_addi(elem) {
        if (confirm("Are you sure want to remove this?")) {
            elem.parent().parent().remove();
            additionalprice();
        }
    }





    function re_assing_serial() {
        $("#task_table tbody tr").not('#firsttasktr').each(function (i, e) {
            $(this).find('td').eq(0).html(i + 1);
        });
        $("#proof_table tbody tr").not('#firstprooftr').each(function (i, e) {
            $(this).find('td').eq(0).html(i + 1);
        });
    }

    function delrec1(elem, id) {
        if (confirm("Are you sure want to delete this Details?")) {
            $(elem).parent().remove();

             <?php if($_REQUEST['lid']!='') { ?>
            window.location.href = "<?php echo $sitename; ?>process/<?php echo $_REQUEST['lid']; ?>/editpurchase.htm?delid1=" + id;
			<?php } else { ?>
			  window.location.href = "<?php echo $sitename; ?>process/addpurchase.htm?delid1=" + id;
			<?php } ?>
			
        }
    }

    function interest_calculation() {
        var interest_amount = $('#amount').val();
        var interest_percent = $('#interestpercent').val();
        var a = (interest_percent / 100);
        // alert(a);
        var interest_total = interest_amount - (interest_amount * a);
        // alert(interest_total);
        document.getElementById('interest').value = interest_total;
        // $('#interest').html(interest_total);
    }

    function customer(a) {

        $.ajax({
            url: "<?php echo $sitename; ?>pages/master/ajax_page.php",
            data: {customerid: a},
            success: function (data) {
                $("#customerdetail").html(data);
                $("#customerid").val(a);

            }
        });
    }

    function quantitycalculation(a) {
//            document.getElementById('qtyhidden').value = a;
//            var total = 0;
        var hidden1 = $('#qtyhidden').val();
        var c = +a + +hidden1;
        document.getElementById('qtyhidden').value = c;
        document.getElementById('totalquantity').value = c;
    }



</script>

<script type="text/javascript">
    $(document).ready(function () 
{

            $("#chkRead").change(function () {

                if ($(this).is(":checked")) 
                {
                    $('#bill_no').removeAttr("readonly")
                }
                else 
                {
                    $('#bill_no').attr('readonly', true);
                }
            });
        });
    $(document).ready(function()
    {
  $("#manual").click(function()
  {
    var a = $("#bill_no").val();
    var b = $("#manual").text();
    
   if(a!="")
   {
    if(b=="Manual Bill")
    {
    //$("#bill_no").val();
    $("#bill_no").removeAttr("readonly");
    $("#manual").text("Auto BIll");
    }

   }
   if(b!="Manual Bill")
    {
    //$("#bill_no").val();
    $("#bill_no").addAttr("readonly");
    $("#manual").text("Manual BIll");
    }
  });
});

    $("#discount_type").focusout(function()
    {
 var name = $('#discount_type').val();
        //var lastChar = name.slice(-1);
        var value = name.substr(0, 2);;
        
        $("#discount").val(value);
});
    ///////////
    $("#delivery_charge").focusout(function()
    {
 var name = $('#discount_type').val();
 var d_charge=parseInt($("#delivery_charge").val());
 var p_charge=parseInt($("#packing_charge").val());
 
 var s_tot = parseInt($('#sub_total').val());
 var lastChar = name.slice(-1);
 var value = name.substr(0, 2);
 var tot = d_charge + p_charge + s_tot;
 //var discountPrice = $("#discount").val();

 
 var percentage = $('#discount').val();
 var price = tot;
 var calcPrice = price - ( (price/100) * percentage );
 var discountPrice = calcPrice.toFixed(2);
 $("#tot_amt").val(discountPrice);
});
</script>

