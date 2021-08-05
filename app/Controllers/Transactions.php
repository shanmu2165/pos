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
        
        $data = [];
        $data['page_title'] = 'POS - Select Payment Option';
        $data['search_url'] = base_url().'/shows/search';
        $date['delete_url'] = base_url().'/delete_cart';

        $data['categories'] = $this->model->get_categories();
        $data['current'] = 'pay_option';

        
        //echo "<pre>"; print_r($_SESSION); "</pre>"; die;
        return view('payment_options',$data);
    }
    
   

    //Function for payment capture
    function pay_success() {
        //echo "<pre>"; print_r($_SESSION); "</pre>"; die; 
        $data = [];
        $pos_data = [];
        $data1 = [];
        //
        $data['page_title'] = 'POS - Select Payment Option';
        $data['search_url'] = base_url().'/shows/search';
        $date['delete_url'] = base_url().'/delete_cart';
        
        $data['categories'] = $this->model->get_categories();
        $data['current'] = 'payment_success';
        if($this->request->getVar()) {
            $pos_data['name'] = $this->request->getVar('fname'). " ".@$this->request->getVar('lname');
            $pos_data['email'] = $this->request->getVar('email');
            $pos_data['phone'] = $this->request->getVar('phone');
            //$pos_data['content'] = $this->request->getVar('capture_pay');
            $cap_pay = @$this->request->getVar('capture_pay');
            $pos_data['type'] =  $this->request->getVar('payment_type');
        }
        //
        //echo "<pre>"; print_r($_SESSION['cart']); "</pre>"; die;
        $pos_data['notes'] = json_encode($_SESSION['cart']);
        $data1 = $_SESSION['cart'];
        //echo "<pre>"; print_r($data1['ptotal']); "</pre>"; die;
        $data1['pcount'] = count($data1['item']);
        //
        $random = mt_rand(100000000, 999999999);
        $pos_data['randid'] = "BSTD".$random;
        $pos_data['timestamp'] = date('Y-m-d h:i:a');
        //echo "<pre>"; print_r($data); "</pre>"; die;
        $success = $this->transaction_model->insert($pos_data);

         //Qrcode Path
         $filepath = $_SERVER['DOCUMENT_ROOT'].'/pos/public/images/qrcode/';
         //Qrcode Image name
         $filename = "qrcode_".$random.".png";
         
        if($success) 
        {   
            //Qrcode Creation for Ticket transaction
            $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($pos_data['randid'])
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

            //Save Qrcode path
            $result->saveToFile($filepath.$filename);
                
            //Send Email For transaction
            $msg = new \stdClass();
            //required settings
            $msg->subject = "Tickets Booked"; //SUBJECT
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
                                                  <b>".$pos_data['name']."</b>
                                              </p>
                                          </td>";
                                          $body .="<td style='text-align:left; padding:5px;'>
                                              <p
                                              style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
                                                  <i style='display:block;font-weight:normal; font-style:normal;'>Email:</i>
                                                  <b>".$pos_data['email']."</b>
                                              </p>
                                          </td>";
                                          
                                          $body .="</tr><tr>";
                                          $body .="<td style='text-align:left; padding:5px;'>
                                              <p
                                              style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
                                                  <i style='display:block;font-weight:normal; font-style:normal;'>Phone:</i>
                                                  <b>".$pos_data['phone']."</b>
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
                                      
                                      for($i =1; $i<=$data1['pcount']; $i++) {
                                                if($data1['item'][$i]['qty'] > 0) {
                                      $body .= "<tr><td
                                              style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
                                              <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                  ".strtoupper(@$data1['content'][0]->title)." - ".date('M d, Y',strtotime($data1['item'][$i]['date'])).", ".date('h:i a',strtotime($data1['item'][$i]['time']))."";
                                           if(strpos($data1['item'][$i]['name'], 'Family') !== false) {
                                            $body .= "  <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data1['item'][$i]['name']." [".@$data1['family_seats']."] - Preferred </p>
                                              </p>";
                                           } else {
                                            $body .= "  <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data1['item'][$i]['name']." - Preferred </p>
                                              </p>";
                                           }
                                          $body .= "</td>
                                          <td
                                              style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
                                              <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                  ".$data1['item'][$i]['qty']."
                                              </p>
                                          </td>
                                          <td
                                              style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
                                              <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                  $".number_format($data1['item'][$i]['price'],2)."
                                              </p>
                                          </td></tr> ";
                                      } } //}
                                          //$body .="";
                                      if(@$data1['cc_type'] != 2) {    
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
                                      if(@$_SESSION['ccodeinfo']->type == 2) {
                                      $body .= "<td
                                              style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                              <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                  <i>Voucher Applied - ".$_SESSION['ccodeinfo']->code."(".$_SESSION['ccodeinfo']->discount.")</i>
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
                                                  <i>$".number_format(@$data1['ptotal'],2)."</i>
                                              </p>
                                          </td>";        
  
                                      } else if(!empty($_SESSION['ccode']) && $_SESSION['ccodeinfo']->type == 1) {
                                        $body .= "<td style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
                                                      <p
                                                          style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
                                                          <i>Coupon Applied - ".$_SESSION['ccodeinfo']->code."(".$_SESSION['ccodeinfo']->discount.")</i>
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
                                                      <i>$".number_format(@$data1['ptotal'],2)."</i>
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
                                                  <b>$".number_format($data1['total'],2)."</b>
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
          if($pos_data['type'] == 'Stripe') {
          $body .=" <h4
              style='font-size: 20px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:10px 0 5px;color: #000;'>
              Payment Details
          </h4>";
          $body .= " <table role='presentation' cellspacing='0' cellpadding='0' border='0'
              style='margin: auto;width: 100%;'>
              <tr>
                  <td width='50%' class='stack-column-center'>
                      <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Amount:</p>
                      <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>$".number_format($data1['total'],2)."</b>
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
          }
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
           //Unset Cart Items
           if(!empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
            unset($_SESSION['ccodeinfo']);
            unset($_SESSION['ccode']);
            }
          $msg->htmlbody = $body;
          $msg->to = array($pos_data['email'],$pos_data['name']); //TO
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
    
            $this->session->setFlashdata('msg', "Ticket Booked Successfully! you will receive a mail shortly.");
            return redirect()->to('/shows');
        }
        else
        {
        $this->session->setFlashdata('msg', "Can't able to book ticket!!! Please check again.");
        return redirect()->to('/shows');
        }
        
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