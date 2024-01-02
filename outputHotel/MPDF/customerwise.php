<?php

require_once __DIR__ . '/vendor/autoload.php';
include ('../config/config.inc.php');

$general = FETCH_all("SELECT * FROM `manageprofile` WHERE `pid` = ?", '1');
$img = (getprofile('image', $general['pid']) != '') ? "<img src='" . $sitename . "pages/profile/image/" . getprofile('image', $general['pid']) . "' height='100' />" : "";
$findqry = $db->prepare("SELECT * FROM `return` WHERE `customeridname`=? ");
$findqry->execute(array($_REQUEST['id']));
//$objectlist = FETCH_all("SELECT * FROM `object_detail` WHERE `status`=? AND `object_id` =?", '1', $extra['id']);
//    $objectlist->execute(array('1',$find['loanid']));
//$am = FETCH_all("SELECT * FROM `norder` WHERE `oid`=?", $_REQUEST['id']);
//$general = FETCH_all("SELECT * FROM `manageprofile` WHERE `pid` = ?", '1');

$to = $general['recoveryemail'];
//$subject = "Your Click2buy Order Confirmation " . $am['order_id'];
//$subject1 = "You have new order " . $am['order_id'];
//if ($_SESSION['CART_TOTAL'] != '') {
//print_r($_POST); print_r($_REQUEST); exit;
// $orderid = $_SESSION['last_order_id'];
$customer = FETCH_all("SELECT * FROM `customer` WHERE `cusid`=?", $_REQUEST['id']);
$extra = FETCH_all("SELECT * FROM `loan` WHERE `cusid`=?", $customer['id']);
//$return = FETCH_all("SELECT * FROM `return` WHERE `customerid`=?", $customer['id']);


$message = ' <table style="width:100%; font-family:arial; font-size:20px;" >
                <tr>
                <td>' . $img . '</td> <td></td>
                    <td style="width:100%; border-bottom:1px solid #CC0063;text-align:center;"><h2 style="color:#CC0063;" >Customer Details</h2></td>
                </tr>
                </table>
                <table style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:20px;" >
                <tr>

                    <td width="60%" align="left"><table><tbody>	
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
			<tr>
				<td style="vertical-align:top"><small><strong>Customer ID</strong></small></td>
				<td style="vertical-align:top"><small>:</small></td>
				<td style="vertical-align:top"><small><strong>' . $extra['customerid'] . '</strong></small></td>
			</tr>
			<tr>
				<td style="vertical-align:top"><small><strong>Customer Name</strong></small></td>
				<td style="vertical-align:top"><small>:</small></td>
				<td style="vertical-align:top"><small><strong>' . $extra['name'] . '</strong></small></td>
			</tr>	
                        <tr>
		       <td style="vertical-align:top"><small><strong>Mobile Number</strong></small></td>
		       <td style="vertical-align:top"><small>:</small></td>
		       <td style="vertical-align:top"><small><strong>' . $extra['mobileno'] . '</strong></small></td>
		       </tr>
                       <tr>
		       <td style="vertical-align:top"><small><strong>Address</strong></small></td>
		       <td style="vertical-align:top"><small>:</small></td>
		       <td style="vertical-align:top"><small><strong>' . $extra['address'] . '</strong></small></td>
		       </tr>
                       </tbody></table>
                          </td>
                  </tr> </table>
 <br>
                  <table border="1" style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:20px;" >
                          <tr>
                            <th> Receipt No </th>
                            <th> Entry Date </th>
                            <th> Return Date </th>
                            <th> Items </th>
                            <th> Net Weight </th>
                            <th> Amount </th>
                            <th> Interest </th>
                            <th> Status </th>
                          </tr>';
$loanqry = $db->prepare("SELECT * FROM `loan` WHERE `cusid`=? ");
$loanqry->execute(array($customer['id']));


while ($loan = $loanqry->fetch(PDO::FETCH_ASSOC)) {

    $object_detail = $db->prepare("SELECT * FROM `object_detail` WHERE `object_id`=? ");
    $object_detail->execute(array($loan['id']));



//       $object_list = getobject('objectname',$object_detaillist['object']);
    $findqry = $db->prepare("SELECT * FROM `return` WHERE `loanid`=? ");
    $findqry->execute(array($loan['id']));

    while ($find = $findqry->fetch(PDO::FETCH_ASSOC)) {
//                        echo $find['id'];
//exit;
        $message .= ' <tr>
                              <td>' . $find['receipt_no'] . '</td>
                                  <td>' . $find['date'] . '</td>
                                      <td>' . $find['currentdate'] . '</td>
            <td>';
        while ($object_detaillist = $object_detail->fetch(PDO::FETCH_ASSOC)) {
            $message .='<p>'. getobject('objectname', $object_detaillist['object']) . - $object_detaillist['quantity'] .'</p>';
        }
        $message .= '</td>
     
  <td>' . $find['netweight'] . '</td>
                                            <td>' . $find['amount'] . '</td>
                                                <td>' . $find['interestpercent'] . '</td>
                                                    <td>';
        if ($find['status'] == '0') {
            $message .='Returned';
        } else {
            $message .='Pawned';
        }
        $message .='</td>                       
                          </tr>';
    }
}


$message .= '</table> 
                   
               
             <table style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:13px;" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100%" align="left">
                        <table style="width:100%; font-size:13px;">
                            
                        </table>
                    </td>
                </tr>
            </table>
            <table style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:18px;" cellpadding="0" cellspacing="0">
             <tr><td align="left"> <label>Customer Signature: </label> </td>
                <td align="center"> <label>Agent Signature: </label> </td></tr>
                </table>';

// echo $message;

$mpdf = new mPDF();
$mpdf->SetDisplayMode('default');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
$filename = "test.txt";

$file = fopen($filename, "w");
fwrite($file, $message);
$mpdf->SetTitle('Customer Report');
$mpdf->keep_table_proportions = false;
$mpdf->shrink_this_table_to_fit = 0;
$mpdf->SetAutoPageBreak(true, 10);
$mpdf->WriteHTML(file_get_contents($filename));
$mpdf->setAutoBottomMargin = 'stretch';
//$mpdf->setHTMLFooter('<div style="text-align:center;">THANK YOU FOR YOUR ENQUIRY</div><div style="border-top: 0.1mm solid #000000;text-align:center;padding:5px 0 5px 0;"><small>'.$address.'</samll></div>');
$mpdf->Output('yourFileName.pdf', 'I');
?>
