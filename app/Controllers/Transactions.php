<?php
namespace App\Controllers;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Zxing\QrReader;
use Twilio\Rest\Client;

class Transactions extends BaseController {

    protected $model;
    protected $user;
    protected $transaction_model;
    protected $session;
    protected $user_detail;

    function __construct() {
        $this->model = new \App\Models\ShowsModel();
        $this->user = new \App\Models\UserModel();
        $this->transaction_model = new \App\Models\TransactionModel();
        $this->session = session();
        if(isset($_SESSION['user_id'])) { 
          $this->user_detail = $this->user->find($_SESSION['user_id']);
        }
    }

    function index() { 
        //print_r($this->request->getVar()); die;
        $data = [];
        $data['page_title'] = 'POS - Select Payment Option';
        $data['search_url'] = base_url().'/shows/search';
        $date['delete_url'] = base_url().'/delete_cart';

        $data['categories'] = $this->model->get_categories();
        $data['current'] = 'pay_option';

        if($this->request->getVar()) {
            $data['item']['date'] = $this->request->getVar('date');
            $data['item']['time'] = $this->request->getVar('time');
            $data['item']['ticket_venue'] = $this->request->getVar('ticket_venue');
            $data['item']['ticket_location'] = $this->request->getVar('ticket_location');
            $data['item']['ticket_title'] = $this->request->getVar('ticket_title');
            $data['item']['tot_qty'] = $this->request->getVar('tot_qty');
            $data['item']['tot_amount'] = $this->request->getVar('tot_amount');
            $data['item']['pcount'] = $this->request->getVar('pcount');

            for($i =1; $i<=$data['item']['pcount']; $i++) { 
                $data['item']["ticket_type".$i]=$this->request->getVar("ticket_type".$i);
                $data['item']["qty".$i] = $this->request->getVar("qty".$i);
                $data['item']["price".$i] = $this->request->getVar("price".$i);
            }
            if(!empty($_SESSION['cart']['ptotal']) && $_SESSION['cart']['ptotal'] > 0) {
                $data['item']['discount_amt'] = $_SESSION['cart']['ptotal'];
                $data['item']['ccode'] = $_SESSION['ccode'];
                $data['item']['code_name'] = $_SESSION['ccodeinfo']->code;
            }
        }
        
        $arr = json_encode($data['item']);
        $data1 = [];
        $data1['type'] = "Stripe";
        $data1['site'] = 1;
        $data1['status'] = 1;
        $data1['name'] = "Shanmuga Sundaram M";
        $data1['email'] = "letmetest95@gmail.com";
        $data1['phone'] = "7012405595";
        $data1['amount'] = $this->request->getVar('tot_amount');
        
        $data1['notes'] = $arr;

        $random = mt_rand(100000000, 999999999);
        $data1['randid'] = "BSTD".$random;
        $data1['timestamp'] = date('Y-m-d h:i:a');
        
        //$success = $this->transaction_model->insert($data1);
        return view('payment_options',$data);
    }
    
    function pay_old() //Original pay
    {
            $data = [];
            $data['page_title'] = 'POS - Select Payment Option';
            $data['search_url'] = base_url().'/shows/search';
            $date['delete_url'] = base_url().'/delete_cart';
            
            $data['categories'] = $this->model->get_categories();
            $data['current'] = 'pay_option';
            //echo "<pre>"; print_r($_SESSION); "</pre>"; die;
            if($this->request->getVar()) {
                $data['item']['date'] = $this->request->getVar('date');
                $data['item']['time'] = $this->request->getVar('time');
                $data['item']['ticket_venue'] = $this->request->getVar('ticket_venue');
                $data['item']['ticket_location'] = $this->request->getVar('ticket_location');
                $data['item']['ticket_title'] = $this->request->getVar('ticket_title');
                $data['item']['tot_qty'] = $this->request->getVar('tot_qty');
                $data['item']['tot_amount'] = $this->request->getVar('tot_amount');
                $data['item']['pcount'] = $this->request->getVar('pcount');
            
                for($i =1; $i<=$data['item']['pcount']; $i++) { 
                $data['item']["ticket_type".$i]=$this->request->getVar("ticket_type".$i);
                $data['item']["qty".$i] = $this->request->getVar("qty".$i);
                $data['item']["price".$i] = $this->request->getVar("price".$i);
                }
            
                if(!empty($_SESSION['cart']['ptotal']) && $_SESSION['cart']['ptotal'] > 0) {
                $data['item']['discount_amt'] = $_SESSION['cart']['ptotal'];
                $data['item']['ccode'] = $_SESSION['ccode'];
                $data['item']['code_name'] = $_SESSION['ccodeinfo']->code;
                }
                if(!empty($_SESSION['cart']['family_seats'])) {
                $data['item']['family_seats'] = @$_SESSION['cart']['family_seats'];
                }
            }
        //var_dump($data['item']); die;
        $arr = json_encode($data['item']);
        $data1 = [];
        $data1['type'] = "Stripe";
        $data1['site'] = 1;
        $data1['status'] = 1;
        $data1['name'] = "Shanmuga Sundaram M";
        $data1['email'] = "letmetest95@gmail.com";
        $data1['phone'] = "7012405595";
        $data1['amount'] = $this->request->getVar('tot_amount');
        $data1['cc_type'] = @$_SESSION['ccodeinfo']->type;
        $data1['cc_total'] =  @$_SESSION['cart']['ptotal'];
        $data1['cc_code'] = @$_SESSION['ccodeinfo']->code;
        $data1['cc_discount'] = @$_SESSION['ccodeinfo']->discount;
        // $data1['service_tax'] = @$_SESSION['cart']['salestax'];
        // $data1['processing_fees'] = @$_SESSION['cart']['processingfees'];
        //print_r($data1); die;
        //$data1['content'] = json_encode($data['item']);
        $data1['notes'] = $arr;
    
        $random = mt_rand(100000000, 999999999);
        $data1['randid'] = "BSTD".$random;
        $data1['timestamp'] = date('Y-m-d h:i:a');
        //echo "<pre>"; print_r($data); "</pre>"; die;
        $success = $this->transaction_model->insert($data1);
        //required settings
        $filepath = $_SERVER['DOCUMENT_ROOT'].'/pos/public/images/qrcode/';
                //print_r($filepath); die;
                $filename = "qrcode_".$random.".png";
        
        if($success) {
                $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($data1['randid'])
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(300)
                ->margin(10)
                ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                //->logoPath($_SERVER['DOCUMENT_ROOT'].'/pos/vendor/endroid/qr-code/tests/assets/symphony.png')
                ->labelText('Scan This')
                ->labelFont(new NotoSans(20))
                ->labelAlignment(new LabelAlignmentCenter())
                ->build();
                //echo "32"; die;
                // Directly output the QR code
                //
                //
                if(!empty($_SESSION['cart'])) {
                unset($_SESSION['cart']);
                unset($_SESSION['ccodeinfo']);
                unset($_SESSION['ccode']);
                }
                //echo "<pre>"; print_r($_SESSION['_ci_previous_url']); "</pre>"; die;
                //header('Content-Type: '.$result->getMimeType());
                //echo $result->getString(); die;
                //echo "32"; die;
                // Save it to a file
                //echo "yes"; die;
                
                $result->saveToFile($filepath.$filename);
                
                
            
                //$file = __DIR__."/qrcode_".$random.".png";
            
                $this->session->setFlashdata('msg', "Ticket Booked Successfully");
            
                //Send Email For transaction
            
                $msg = new \stdClass();
        
              //required settings
              $msg->subject = "My message subject"; //SUBJECT
              //$msg->textbody = "My text-only message"; //TEXT MSG, NULL IF sending HTML
              //$msg->htmlbody = NULL; //HTML MSG, NULL if sending TEXT
              $body = "<!DOCTYPE html>";
              $body .= "<head><meta charset='utf-8'>";
              $body .= "<meta name='viewport' content='width=device-width'>";
              $body .="<link rel='stylesheet' id='font-awesome-css'  href='https://use.fontawesome.com/releases/v5.8.1/css/all.css?ver=5.3.2' media='all' />";
              $body .="<link href='https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap' rel='stylesheet'></head>";
              $body .=" <body width='100%' style='margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #fff;'>
              <center style='width: 100%; background-color: #ccc;'> ";
              //$body .=" <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: 'Source Sans Pro', sans-serif;'> (Optional) This text will appear in the inbox preview, but not the email body. It can be used to supplement the email subject line or even summarize the email's contents. Extended text preheaders (~490 characters) seems like a better UX for anyone using a screenreader or voice-command apps like Siri to dictate the contents of an email. If this text is not included, email clients will automatically populate it using the text (including image alt text) at the start of the email's body. </div>";
              //$body .= "<div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: 'Source Sans Pro', sans-serif;'> &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp; </div>";
              $body .=" <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='650' style='margin: auto;' class='email-container' style='background: #fff;'>";
              $body .= "<tr><td style='background-color: #f5f5f5;'>";
              $body .="<table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>";
              $body .= "<tr><td style='padding:15px; font-family: 'Source Sans Pro', sans-serif; line-height: 25px; color: #555555;' class='thank'> ";
              $body .= "<h2 style='margin: 0 0; word-break: break-all; margin-bottom:0px;font-size: 35px; text-align:center; font-weight:500;padding:0;
              color: #000;'>Your Receipt</h2></td> ";
              $body .= "</tr></table></td></tr>";
              $body .= "<tr><td style='padding:20px 0;background-color: #fff;'>
                        <img src='".base_url().'/images/qrcode/'.$filename."' width='600' height='' alt='alt_text' border='0' style='width: 225px;
    max-width: 600px;margin:0 auto;display:block;' class='g-img'>
    </td>
    </tr> ";
    $body .= "<tr>
        <td style='padding: 15px;background-color: #fff;'>
            <table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'> ";
                $body .= "<tr>
                    <th valign='top' width='100%' class='stack-column-center'> ";
                        $body .="<table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>";
                            $body .="<tr>
                                <td style='font-family: ' Source Sans Pro', sans-serif;' class='center-on-narrow'>
                                    <h2
                                        style='font-size: 25px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:0 0 5px;color: #000;'>
                                        Transaction #BSTD".$random." Details
                                    </h2>";
                                    $body .="<h4
                                        style='font-size: 20px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:0 0 5px;color: #000;'>
                                        Customer Info
                                    </h4>";
                                    $body .="<table style='width:100%;'>
                                        <tr>";
                                            $body .="<td style='text-align:left; padding:5px;'>
                                                <p
                                                style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
                                                    <i style='display:block;font-weight:normal; font-style:normal;'>Name:</i>
                                                    <b>".$data1['name']."</b>
                                                </p>
                                            </td>";
                                            $body .="<td style='text-align:left; padding:5px;'>
                                                <p
                                                style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
                                                    <i style='display:block;font-weight:normal; font-style:normal;'>Email:</i>
                                                    <b>".$data1['email']."</b>
                                                </p>
                                            </td>";
                                            
                                            $body .="</tr><tr>";
                                            $body .="<td style='text-align:left; padding:5px;'>
                                                <p
                                                style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
                                                    <i style='display:block;font-weight:normal; font-style:normal;'>Phone:</i>
                                                    <b>".$data1['phone']."</b>
                                                </p>
                                            </td>
                                            <td style='text-align:left; padding:5px;'>
                                                <p
                                                style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
                                                    <i style='display:block;font-weight:normal; font-style:normal;'>Ticket Status:</i>
                                                    <b>Issued</b>
                                                </p>
                                            </td></tr>
                                    </table>";
                                    $body .="<h4
                                        style='font-size: 20px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:0 0 5px;color: #000;'>
                                        Item Purchased
                                    </h4>";
                                    $body .=" <table style='width:100%; border:1px solid #ccc;border-collapse: collapse;'>
                                        <tr>
                                            <th style='text-align:left; padding:10px 5px;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                    <b>Item</b>
                                                </p>
                                            </th>
                                            <th style='text-align:left; padding:10px 5px;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                    <b>Qty</b>
                                                </p>
                                            </th>
                                            <th style='text-align:left; padding:10px 5px;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                    <b>Price</b>
                                                </p>
                                            </th>
                                        </tr>";
                                        //$body .= "";
                                        for($i =1; $i<=$data['item']['pcount']; $i++) {
                                                  if($data['item']["qty".$i] > 0) {
                                        $body .= "<tr><td
                                                style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    ".strtoupper($data['item']["ticket_title"])." - ".$data['item']["date"].", ".$data['item']["time"]."";
                                             if(strpos($data['item']["ticket_type".$i], 'Family') !== false) {
                                              $body .= "  <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data['item']["ticket_type".$i]." [".$data['item']['family_seats']."] - Preferred </p>
                                                </p>";
                                             } else {
                                              $body .= "  <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data['item']["ticket_type".$i]." - Preferred </p>
                                                </p>";
                                             }
                                            $body .= "</td>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    ".$data['item']["qty".$i]."
                                                </p>
                                            </td>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    $".$data['item']["price".$i]."
                                                </p>
                                            </td></tr> ";
                                        } }
                                            //$body .="";
                                        if($data1['cc_type'] != 2) {    
                                        $body .="<tr>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    <i>Tax</i>
                                                </p>
                                            </td>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    &nbsp
                                                </p>
                                            </td>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    <i>$".getenv('salestax')."</i>
                                                </p>
                                            </td>
                                        </tr> ";
                                        $body .= " <tr>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    <i>Processing Fees</i>
                                                </p>
                                            </td>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                    &nbsp
                                                </p>
                                            </td>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    <i>$".getenv('processingfees')."</i>
                                                </p>
                                            </td>
                                        </tr>";
                                        }
                                        $body .= " <tr>";
                                        if($data1['cc_type'] == 2) {
                                        $body .= "<td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    <i>Voucher Applied - ".$data1['cc_code']."(".$data1['cc_discount'].")</i>
                                                </p>
                                                </td>";
                                        $body .= "<td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                    &nbsp
                                                </p>
                                            </td>
                                            <td
                                                style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                    <i>$".number_format(@$data1['cc_total'],2)."</i>
                                                </p>
                                            </td>";        
    
                                        } else if(!empty($data1['cc_code']) && $data1['cc_type'] == 1) {
                                          $body .= "<td style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                        <p
                                                            style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                            <i>Coupon Applied - ".$data1['cc_code']."(".$data1['cc_discount'].")</i>
                                                        </p>
                                                    </td>";
                                          $body .= "<td
                                                    style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                    <p
                                                        style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                        &nbsp
                                                    </p>
                                                </td>
                                                <td
                                                    style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                    <p
                                                        style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                        <i>$".number_format(@$data1['cc_total'],2)."</i>
                                                    </p>
                                                </td>";          
                                        } else {
                                          
                                        }
                                            
                                        $body .= " </tr>";
                                        $body .= " <tr>
                                            <td style='text-align:left; padding:10px;background-color: #fff;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                    <b>Total</b>
                                                </p>
                                            </td>
                                            <td style='text-align:left; padding:10px;background-color: #fff;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                    &nbsp
                                                </p>
                                            </td>
                                            <td style='text-align:left; padding:10px;background-color: #fff;'>
                                                <p
                                                    style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
                                                    <b>$".$data['item']["tot_amount"]."</b>
                                                </p>
                                            </td>
                                        </tr>";
                                        $body .= "
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </table>";
            $body .=" <h4
                style='font-size: 20px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:10px 0 5px;color: #000;'>
                Payment Details
            </h4>";
            $body .= " <table role='presentation' cellspacing='0' cellpadding='0' border='0'
                style='margin: auto;width: 100%;'>
                <tr>
                    <td width='50%' class='stack-column-center'>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Amount:</p>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>$".$data['item']["tot_amount"]."</b>
                        </p>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Status:</p>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
                            <b>authorized</b></p>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Timestamp:</p>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>".date('Y-m-d h:i:s')."</b></p>
                    </td>
                    <td width='50%' class='stack-column-center'>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Method:</p>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>amex
                                **3002</b></p>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Message:</p>
                        <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>Payment
                                complete.</b></p>
                    </td>
                </tr>
            </table>";
    
            $body .="
        </td>
    </tr>";
    $body .= " <footer style='background:#fff;'>
    
        <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='650'
            style='margin: auto;' class='email-container' style='background:#fff;'>
            <tr>
                <td width='100%' style='border-top:1px solid #eee;background:#fff; text-align:center; padding:10px 0 15px;'>
                    <p
                        style='margin:0;color:#0D2030; padding:5px 0; font-weight:normal;font-size: 14px; text-transform:uppercase;'>
                        Copyright © 2021 POS</p>
                </td>
            </tr>
        </table>
    
    </footer> ";
    $body .="</center>
    </body>
    
    </html>";
            //echo $body; die;
            $msg->htmlbody = $body;
            $msg->to = array('shan@zaigoinfotech.com','Shanmugam'); //TO
            $msg->from = array(getenv('fromaddress'),getenv('fromname')); //FROM
    
            //optional settings
            //$msg->reply_to = array('address@site.com','XYZ Company'); //REPLY TO
            //$msg->cc = array('address2@site.com','Someone'); //CC
            //$msg->bcc = array('address3@site.com','Somebody Else'); //BCC
            $msg->track_clicks = TRUE; //TRACK CLICKS, TRUE by default
            $msg->track_opens = TRUE; //TRACK OPENS, TRUE by default
            $msg->client_reference = NULL; //CLIENT ID (string)
            $msg->mime_headers = NULL; //ADDITIONAL MIME HEADERS (array)
            $msg->attachments = NULL; //ATTACHMENTS (array)
            $msg->inline_images = NULL; //INLINE IMAGES (array)
    
            //instantiate library and pass info
            $tmail = new \Transmail\TransmailClient($msg,getenv('transmailkey'),
            getenv('transbounceaddr'), TRUE);
    
            //send the message
            $response = $tmail->send();
    
            if ($response)
            {
              $this->session->setFlashdata('msg', "Ticket Booked Successfully! you will receive a mail shortly.");
              return redirect()->to('/shows');
            }
            else
            {
              $this->session->setFlashdata('msg', "Can't able to book ticket!!! Please check again.");
              return redirect()->to('/shows');
            }
    
             return redirect()->to('/shows');
        } else {
              $this->session->setFlashdata('msg', "Oops Transaction Failed!");
        }
    
               return view('payment_options',$data);
    }

    //Functions for lookup transactions
    function lookup_transaction() {
        $data = [];
  
        $data['page_title'] = 'POS - Lookup Transaction';
        $data['search_url'] = base_url().'/shows/search';
        $date['delete_url'] = base_url().'/delete_cart';
  
        $data['categories'] = $this->model->get_categories();
        $data['current'] = 'lookup';
        if(!empty($this->request->getVar())) {
          $val_data = [
          'randid' => $this->request->getVar('trans_id'),
          'email' => $this->request->getVar('trans_email'),
          ];
  
          $validate_qr = $this->transaction_model->verify_qrcode($val_data);
  
          if($validate_qr) {
          $data['details'] = $validate_qr;
          $data['json_details'] = json_decode($validate_qr[0]->notes, true);
          $data['pcount'] = $data['json_details']['pcount'];
  
          //echo "<pre>"; print_r($data); "</pre>"; die;
          return view('lookup_transaction',$data);
          } else {
          $this->session->setFlashdata('msg', "Invalid Transaction Id or Email!");
          return redirect()->to('/shows');
          }
        } else {
        $this->session->setFlashdata('msg', "Please Enter Transaction Id & Email");
        return redirect()->to('/shows');
        }
    }


    //Twilio Sms Function 
    function send_sms($no = '+917012405595',$body= "This is a text message.") {
        $account_sid = getenv('twilioaccountid');
        $auth_token = getenv('twilioauthtoken');
  
        // A Twilio number you own with SMS capabilities
          $twilio_number = getenv('twiliono');
  
          $client = new Client($account_sid, $auth_token);
          $client->messages->create(
              // Where to send a text message (your cell phone?)
              $no,
              array(
                  'from' => $twilio_number,
                  'body' => $body
              )
          );
          
    }
}