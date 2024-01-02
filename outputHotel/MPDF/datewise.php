<?php

require_once __DIR__ . '/vendor/autoload.php';
include ('../config/config.inc.php');

//if ($_SESSION['CART_TOTAL'] != '') {
//print_r($_POST); print_r($_REQUEST); exit;
// $orderid = $_SESSION['last_order_id'];
//echo $_REQUEST['id'];
$extra = FETCH_all("SELECT * FROM `loan` WHERE `customerid`=?", $_REQUEST['id']);

$general = FETCH_all("SELECT * FROM `manageprofile` WHERE `pid` = ?", '1');
$img = (getprofile('image',$general['pid'])!='') ? "<img src='" . $sitename . "pages/profile/image/" . getprofile('image',$general['pid']) . "' height='100' />" : "";
$yearqry = $db->prepare("SELECT * FROM `return` WHERE `currentdate`=? ");
    $yearqry->execute(array($_REQUEST['id']));

//$am = FETCH_all("SELECT * FROM `norder` WHERE `oid`=?", $_REQUEST['id']);
//$general = FETCH_all("SELECT * FROM `manageprofile` WHERE `pid` = ?", '1');

$to = $general['recoveryemail'];
//$subject = "Your Click2buy Order Confirmation " . $am['order_id'];
//$subject1 = "You have new order " . $am['order_id'];



$message = ' <table style="width:100%; font-family:arial; font-size:20px;" >
                <tr>
                <td>'. $img .'</td> <td></td>
                    <td style="width:100%; border-bottom:1px solid #CC0063;text-align:center;"><h2 style="color:#CC0063;" >Return Details</h2></td>
                    <td style=" border-bottom:1px solid #CC0063;text-align:right;"><h5 style="color:#CC0063;" >'.date('d-m-Y').'</h5></td>
                </tr>
                </table>
                <br><h3> Date Wise Report </h3>
                  <table border="1" style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:20px;" >
                          <tr>
                          <th> Customer ID </th>
                          <th> Customer Name </th>
                            <th> Receipt No </th>
                            <th> Entry Date </th>
                            <th> Return Date </th>
                            <th> Net Weight </th>
                            <th> Amount </th>
                            <th> Interest </th>
                            <th> Status </th>
                          </tr>';
 while ($year = $yearqry->fetch(PDO::FETCH_ASSOC)) {
    $message .= ' <tr>
        <td>' . $year['customeridname'] . '</td>
            <td>' . $year['name'] . '</td>
                              <td>' . $year['receipt_no'] . '</td>
                                  <td>' . date('d-m-Y',strtotime($year['date'])) . '</td>
                                      <td>' . $year['currentdate'] . '</td>
                                        <td>' . $year['netweight'] . '</td>
                                            <td>' . $year['amount'] . '</td>
                                                <td>' . $year['interestpercent'] . '</td>
                                                    <td>';
    if($year['status'] == '1'){  
        $message .='Pawned';
    }else{
        $message .='Returned';
    }
    $message .='</td>                       
                          </tr>';
}



$message .= '</table> 
                   
               
             <table style="width:100%; border-bottom:1px solid #CC0063; padding:2%; font-size:13px;" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100%" align="right">
                        <table style="width:100%; font-size:13px;">
                            
                        </table>
                    </td>
                </tr>
            </table>';

// echo $message;

$mpdf = new mPDF();
$mpdf->SetDisplayMode('default');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
$filename = "test.txt";

$file = fopen($filename, "w");
fwrite($file, $message);
$mpdf->SetTitle('Return Datewise Report');
$mpdf->keep_table_proportions = false;
$mpdf->shrink_this_table_to_fit = 0;
$mpdf->SetAutoPageBreak(true, 10);
$mpdf->WriteHTML(file_get_contents($filename));
$mpdf->setAutoBottomMargin = 'stretch';
//$mpdf->setHTMLFooter('<div style="text-align:center;">THANK YOU FOR YOUR ENQUIRY</div><div style="border-top: 0.1mm solid #000000;text-align:center;padding:5px 0 5px 0;"><small>'.$address.'</samll></div>');
$mpdf->Output('yourFileName.pdf', 'I');
?>
