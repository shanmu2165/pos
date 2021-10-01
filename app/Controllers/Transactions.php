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
        //print_r($_SESSION['cart_details']); die;
        $data = [];
        $data['page_title'] = 'POS - Select Payment Option';
        $data['search_url'] = base_url().'/shows/search';
        $data['delete_url'] = base_url().'/delete_cart';
        $data['cart_url'] = $this->request->getUserAgent()->getReferrer();
        $data['categories'] = $this->model->get_categories();
        $data['current'] = 'pay_option';
        //echo "<pre>"; print_r($_SESSION);"</pre>"; die; 
        return view('payment_options',$data);
    }
    
   

    //Function for payment capture
    function pay_success() {
        
        
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
            
            if(!empty($this->request->getVar('capture_pay'))) {
                $cap_pay = @$this->request->getVar('capture_pay');
                $pay_det = json_decode(@$this->request->getVar('capture_pay'));
                $card_data = [
                    'method' => $pay_det->charges->data[0]->payment_method_details->card_present->brand."**".$pay_det->charges->data[0]->payment_method_details->card_present->last4,
                    'status' => $pay_det->charges->data[0]->outcome->type,
                    'message' =>$pay_det->charges->data[0]->outcome->seller_message
                ];
                $pos_data['content'] = $cap_pay;
            }

            
            if(!empty($this->request->getVar('payment_type'))) {
                $pos_data['type'] =  @$this->request->getVar('payment_type');
            } else {
                $pos_data['type'] = "Voucher";  
            }
            
        }
       
        
        if($this->request->getVar('payment_type') == 'Free'){
            $_SESSION['cart']['itotal'] = number_format(0,2);
            $_SESSION['cart']['total'] = number_format(0,2);
            $_SESSION['cart']['salestax'] = number_format(0,2);
            $_SESSION['cart']['processingfees'] = number_format(0,2);
        }
        $pos_data['booked_data'] = json_encode($_SESSION['cart']);
        $data1 = $_SESSION['cart'];
        //echo "<pre>"; print_r($data1); "</pre>"; die;
        $data1['pcount'] = count($data1['item']);
        $random = mt_rand(100000000, 999999999);
        $pos_data['randid'] = "BSTD".$random;
        if($this->request->getVar('payment_type') == 'Free'){
            $pos_data['amount'] = number_format(0,2);
        }else{
            $pos_data['amount'] = number_format($data1['total'],2);
        }
        $pos_data['timestamp'] = date('Y-m-d h:i:a');
        $pos_data['seat_status'] = 1;
       //echo "<pre>"; print_r($pos_data); "</pre>"; die;
        $success = $this->transaction_model->insert($pos_data);
        //print_r($success); die;
        if(!empty($_SESSION['cart_details']['seat_arr'])) {
            $update_seats = $this->model->update_individual_seats_booked($_SESSION['cart_details']['content'],$_SESSION['cart_details']['venue'],$_SESSION['cart_details']['date'],$_SESSION['cart_details']['time'],$_SESSION['cart_details']['section'],$_SESSION['cart_details']['seat_arr'],$success);
        }
        
        //print_r($success); die;
         //Qrcode Path For Server
         //$filepath = $_SERVER['DOCUMENT_ROOT'].'/public/images/qrcode/';

         //Qrcode Path For Local
          $filepath = $_SERVER['DOCUMENT_ROOT'].'/pos/public/images/qrcode/';
         //Qrcode Image name
         $filename = "qrcode_".$random.".png";
         //echo $filepath; die;
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
            ->labelText('')
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
                                      Transaction Id #BSTD".$random." Details
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
                                                  $body .= "  <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data1['item'][$i]['venue']." -  ".$data1['item'][$i]['location']."</p>
                                              </p>";
                                           if(strpos($data1['item'][$i]['name'], 'Family') !== false) {
                                            $body .= "  <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data1['item'][$i]['name']." [".@$data1['family_seats']."] - Preferred </p>
                                              </p>";
                                           } else {
                                            $body .= "  <p
                                                  style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data1['item'][$i]['name']." - Preferred </p>
                                              </p>";
                                           }
                                           if($this->request->getVar('payment_type') != 'Free'){
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
                                           }else{
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
                                                    $".number_format(0,2)."
                                                </p>
                                            </td></tr> ";
                                           }
                                      } } //}
                                        if(!empty($_SESSION['cart']['seats_selected'])) {
                                            $seat_no = implode(",",@$_SESSION['cart']['seats_selected']); 
                                            $body .="<tr><td
                                            style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc; font-size:16px;'>Seats Selected:- ".$seat_no."</td><td style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'></td><td style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'></td></tr>";
                                        }
                                      //if(@$_SESSION['ccodeinfo']->type != 2) {    
                                        if($this->request->getVar('payment_type') != 'Free'){
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
                                      //}
                                      if(isset($_SESSION['ccodeinfo']) && !empty($_SESSION['ccodeinfo'])) {
                                      $body .= " <tr>";
                                         
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
                                      
                                      $body .= " </tr>";
                                       }
                                    }
                                    if($this->request->getVar('payment_type') != 'Free'){
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
                                    }else{
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
                                                <b>$".number_format(0,2)."</b>
                                            </p>
                                        </td>
                                    </tr>";
                                    }
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
                          <b>".$card_data['status']."</b></p>
                      <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Timestamp:</p>
                      <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>".date('Y-m-d h:i:s')."</b></p>
                  </td>
                  <td width='50%' class='stack-column-center'>
                      <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Method:</p>
                      <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>".$card_data['method']."</b></p>
                      <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Message:</p>
                      <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>".$card_data['message']."</b></p>
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
          if(!empty($success)) {
          $trans_data = $this->transaction_model->get_transaction_details($success);
          }
          //For Admin Mail 
          $msg1 = new \stdClass();
            //required settings
          $msg1->subject = "Tickets Booked"; //SUBJECT
          //$mpdf = new \Mpdf\Mpdf();
          $html_data = view('pdf_view',['trans_data' => $trans_data]);
          //echo $html_data; die;
          //$mpdf->WriteHTML($html);
          $msg1->htmlbody = $html_data;
          $msg1->to = array('letmetest95@gmail.com','Tester'); //TO
          $msg1->from = array(getenv('fromaddress'),getenv('fromname')); //FROM
          $msg1->track_clicks = TRUE; //TRACK CLICKS, TRUE by default
          $msg1->track_opens = TRUE; //TRACK OPENS, TRUE by default
          $msg1->client_reference = NULL; //CLIENT ID (string)
          $msg1->mime_headers = NULL; //ADDITIONAL MIME HEADERS (array)
          $msg1->attachments = NULL; //ATTACHMENTS (array)
          $msg1->inline_images = NULL; //INLINE IMAGES (array)
          $tmail1 = new \Transmail\TransmailClient($msg1,getenv('transmailkey'),
          getenv('transbounceaddr'), TRUE);
  
          //send the message
          $response1 = $tmail1->send();
          //Admin Mail END

          if(!empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
            unset($_SESSION['cart_details']); 
            unset($_SESSION['quantity_details']); 
            unset($_SESSION['ccodeinfo']);
            unset($_SESSION['ccode']);
            }
            //print_r($success); die;
            $url = base_url().'/transactions/update_transaction/'.$success;
            $this->session->setFlashdata('msg', "Ticket Booked Successfully! you will receive a mail shortly. <a href='".$url."'>Check-in</a>");
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
        //print_r($_POST); die;
        $data = [];
        
        $data['page_title'] = 'POS - Lookup Transaction';
        $data['search_url'] = base_url().'/shows/search';
        $date['delete_url'] = base_url().'/delete_cart';
  
        $data['categories'] = $this->model->get_categories();
        $data['current'] = 'lookup';
        if(!empty($this->request->getVar('trans_email'))) { 
  
          $validate_qr = $this->transaction_model->verify_qrcode($this->request->getVar('trans_id'),$this->request->getVar('trans_email'));
  
          if($validate_qr) {
          $data['details'] = $validate_qr;
          $data['json_details'] = json_decode($validate_qr[0]->booked_data, true);
          $data['pcount'] = $data['json_details']['pcount'];
  
          
          return view('lookup_transaction',$data);
          } else { 
          //$this->session->setFlashdata('msg', "Invalid Transaction Id or Email!");
          //return redirect()->to('/shows');
         echo "invalid";
          }
        } else if(!empty($_POST['randid'])) {   
            $valid = 0;
            $validate_qr = $this->transaction_model->verify_qrcode($_POST['randid']);
            
            $notes = json_decode($validate_qr[0]->booked_data, true);
            $date_data = date('d-m-Y',strtotime($notes['item'][1]['date']));
            $data1 = [
                'id' => $validate_qr[0]->id,
                'name' => $validate_qr[0]->name,
                'date' => $date_data,
                'time' => $notes['item'][1]['time'],
                'seat_status'=> $validate_qr[0]->seat_status,
                'show_name' => $notes['content'][0]['title']
            ];
            //print_r($data1); die;
            if(!empty($data1)) {
                $valid = json_encode($data1);
            } else {
                $valid = 'failure';
            }
            return $valid;
        }else {  
        //$this->session->setFlashdata('msg', "Please Enter Transaction Id & Email");

        //return redirect()->to('/shows');
        }
    }

    function update_transaction($id,$page='') {
        //echo $id; die;
        $db      = \Config\Database::connect(); 
        $seat_status = $db->query("SELECT seat_status FROM transactions WHERE id='".$id."'");
        $content = $seat_status->getResult();
        if($content[0]->seat_status==1){
            if(!empty($id)) {
                $update = $this->transaction_model->update_seat_status($id);
             }
             $this->session->setFlashdata('msg', "Checked-in Successfully!");
             return redirect()->to('/shows');
        }else{
             $this->session->setFlashdata('msg', "These seats have already been checked in.");
             if(!empty($page)) {
                return redirect()->to('/shows');   
             } else {
                return redirect()->to('/qrcode_reader');
             }
             
        }
        
    }

    // function lookup_transaction() {
    //         //echo "123"; die;
    //     //if ($this->request->isAJAX()) {
    //             $data = [];
    //             //echo "123345"; die;
    //             $data['page_title'] = 'POS - Lookup Transaction';
    //             $data['search_url'] = base_url().'/shows/search';
    //             $date['delete_url'] = base_url().'/delete_cart';
          
    //             $data['categories'] = $this->model->get_categories();
    //             $data['current'] = 'lookup'; 

    //             $id = service('request')->getVar('trans_id');
    //             $email = service('request')->getVar('trans_email');
                
    //             if(!empty($id) && !empty($email)) {
    //                 $validate_qr = $this->transaction_model->verify_qrcode($this->request->getVar('trans_id'),$this->request->getVar('trans_email'));
  
    //                 if($validate_qr) {
    //                     $data['details'] = $validate_qr;
    //                     $data['json_details'] = json_decode($validate_qr[0]->notes, true);
    //                     $data['pcount'] = $data['json_details']['pcount'];
                
    //                     return view('lookup_transaction',$data);
    //                 } else { 
                 
    //                     return json_encode(['success'=> 'Invalid Transaction Id or Email!']);
    //                     die;
    //                 }   
    //             }

    //     //}
    // }


    //Twilio Sms Function 
    function send_sms($no = '+917012405595',$body= "This is a text message.") {
        $account_sid = getenv('twilioaccountid');
        $auth_token = getenv('twilioauthtoken');
  
        // A Twilio number you own with SMS capabilities
          $twilio_number = getenv('twiliono');
  
          $client = new Client($account_sid, $auth_token);
            try {
                $client->messages->create(
                    // Where to send a text message (your cell phone?)
                    $no,
                    array(
                        'from' => $twilio_number,
                        'body' => $body
                    )
                );
                // $client->messages
                //   ->create($no, // to
                //            ["body" => "Hi there", "from" => $twilio_number]
                // );
                $this->session->setFlashdata('msg', "SMS send successfully");
                return redirect()->to('/shows');
            } catch (TwilioException $e) {
                Log::error(
                    'Could not send SMS notification.' .
                    ' Twilio replied with: ' . $e
                );
            }
          
    }
}