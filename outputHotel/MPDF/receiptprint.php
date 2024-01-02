<?php

require_once __DIR__ . '/vendor/autoload.php';
include ('../config/config.inc.php');
date_default_timezone_set('Asia/Kolkata');
function getIndianCurrency($number) {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else
            $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}

$general = FETCH_all("SELECT * FROM `manageprofile` WHERE `pid` = ?", '1');
$img = (getprofile('image', $general['pid']) != '') ? "<img src='" . $sitename . "pages/profile/image/" . getprofile('image', $general['pid']) . "' height='100' />" : "";
$findqry = $db->prepare("SELECT * FROM `loan` WHERE `id`=? ");
$findqry->execute(array($_REQUEST['id']));


$img1 =  "<img src='" . $sitename . "img/customer/" . getcustomer('image',getloan('cusid',$_REQUEST['id'])) . "' height='100' />" ;
$img2 = (getloan('image', $_REQUEST['id']) != '') ? "<img src='" . $sitename . "img/loan/" . getloan('image', $_REQUEST['id']) . "' height='200' width='200' />" : "";
// $img1 = "<img src='" . $sitename . "img/customer/" .getcustomer('image',getloan('cusid',$_REQUEST['id'])) . "' />";

$to = $general['recoveryemail'];
//$subject = "Your Click2buy Order Confirmation " . $am['order_id'];
//$subject1 = "You have new order " . $am['order_id'];
//if ($_SESSION['CART_TOTAL'] != '') {
//print_r($_POST); print_r($_REQUEST); exit;
// $orderid = $_SESSION['last_order_id'];
//$customer = FETCH_all("SELECT * FROM `customer` WHERE `cusid`=?", $_REQUEST['id']);
//echo $customer['id'];
//exit;
$extra = FETCH_all("SELECT * FROM `loan` WHERE `id`=?", $_REQUEST['id']);

//$return = FETCH_all("SELECT * FROM `return` WHERE `customerid`=?", $customer['id']);


$message = '<table style="width:100%; font-family:arial; font-size:20px;" >
    <tr><td style="width:100%;text-align:right;font-size:20px;"> '. date(" h:i a", time()).' </td><td></td></tr>
    </table>
    <table style="width:100%; font-family:arial; font-size:20px;" >

                <tr>
                <td>' . $img . '</td> <td></td>
                    <td style="width:100%; border-bottom:1px solid #CC0063;text-align:center;font-size:20px;"><h2 style="color:#CC0063;" >ANNAMALAIYAR FINANCE</h2><h4>2/99, Thirumogur Rd, Y.Otthakadai, Madurai-625107</h4><h5 style="align:right;">P.B.L.No: 6/2017-2018</h5></td>    
</tr>

                </table>
                <table style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:20px;" >
                <tr>

                    <td width="60%" align="left"><table><tbody>	
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
                         <tr> </tr>
                        <tr>

				<td style="vertical-align:top"><small><strong>Receipt No</strong></small></td>
				<td style="vertical-align:top"><small>:</small></td>
				<td style="vertical-align:top"><small><strong>' . $extra['receipt_no'] . '</strong></small></td>

       
      
        
         <td style="vertical-align:top">'.$img1.'</td>
                                
			</tr>
                           
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
				<td style="vertical-align:top"><small><strong>ID Proof</strong></small></td>
				<td style="vertical-align:top"><small>:</small></td>
				<td style="vertical-align:top"><small><strong>' . $extra['idproof'] . '</strong></small></td>
			</tr>
                       <tr>
		       <td style="vertical-align:top"><small><strong>Address</strong></small></td>
		       <td style="vertical-align:top"><small>:</small></td>
		       <td style="vertical-align:top"><small><strong>' . $extra['address'] . '</strong></small></td>
		       </tr>
                       <tr>
				<td style="vertical-align:top"><small><strong>Value of Items</strong></small></td>
				<td style="vertical-align:top"><small>:</small></td>
				<td style="vertical-align:top"><small><strong>' .$extra['valueofitem'] . '</strong></small></td>
			</tr>
                       <tr>
				<td style="vertical-align:top"><small><strong>Duration of Return</strong></small></td>
				<td style="vertical-align:top"><small>:</small></td>
				<td style="vertical-align:top"><small><strong>' . $extra['pawndays'] . ' 1 Year</strong></small></td>
			</tr>

                       </tbody></table>
                          </td>
                  </tr> </table><br>
                   <td style="align:center">'.$img2.'</td><br><br>
 <br>
                  <table border="1" style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:20px;" >
                          <tr>
                           
                            <th> Entry Date </th> 
                            <th> Items </th>
                            <th> Net Weight </th>
                            <th> Total Quantity </th>
                            <th> Amount </th>
                            
                          </tr>';
$loanqry = $db->prepare("SELECT * FROM `loan` WHERE `id`=? ");
$loanqry->execute(array($_REQUEST['id']));


while ($loan = $loanqry->fetch(PDO::FETCH_ASSOC)) {

    $object_detail = $db->prepare("SELECT * FROM `object_detail` WHERE `object_id`=? ");
    $object_detail->execute(array($loan['id']));

    $c1date = $loan['date'];  
 $date = date("d-m-Y", strtotime($c1date)); 

//       $object_list = getobject('objectname',$object_detaillist['object']);
//    $findqry = $db->prepare("SELECT * FROM `return` WHERE `loanid`=? ");
//    $findqry->execute(array($loan['id']));
//                        echo $find['id'];
//exit;
    $message .= ' <tr>

                              
                                  <td>' .$date . '</td>
                                



            <td>';
    while ($object_detaillist = $object_detail->fetch(PDO::FETCH_ASSOC)) {
//            echo $object_detaillist['id'];
//exit;
        $message .='<p>' . getobject('objectname', $object_detaillist['object']) . -$object_detaillist['quantity'] . '</p>';
    }
    $message .= '</td>
     
  <td>' . $loan['netweight'] . '</td>
                                            <td>' . $loan['totalquantity'] . '</td>
                                                <td>' . $loan['amount'] . '</td>
                                                                        
                          </tr>
                         ';



    $message .= '</table> 
                   
               
             <table style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:13px;" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100%" align="left">
                        <table style="width:100%; font-size:13px;">
                           <tr>
                           <td><strong>Rupees in Words : ' . ucfirst(getIndianCurrency($loan['amount'])) . ' Only/-</strong></td>
                           </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:18px;" cellpadding="0" cellspacing="0">
             <tr><td align="left"> <label>Customer Signature: </label> </td>
                <td align="center"> <label>Agent Signature: </label> </td></tr>
                </table>';
}
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
