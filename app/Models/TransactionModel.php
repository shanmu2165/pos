<?php

namespace App\Models;
use CodeIgniter\Model;

class TransactionModel extends Model {

    protected $table      = 'transactions';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $allowedFields = ['type', 'site','status','seat_status','name','email','phone','amount','randid','notes','booked_data','content','timestamp'];

    function verify_qrcode($id,$email='') {
   
        $db      = \Config\Database::connect();
        if(!empty($id) && !empty($email)) {
            $query = $db->query("SELECT * FROM transactions WHERE randid='".$id."' AND email='".$email."' LIMIT 1");  
        } else {
            $query = $db->query("SELECT * FROM transactions WHERE randid='".$id."' LIMIT 1");  
        }
        
        $content = $query->getResult();
        if($content) {
            return $content;
        } else {
            return false;
        }
        
    }

    function get_transaction_details($id) {
        $db      = \Config\Database::connect(); 
        $query = $db->query("SELECT * FROM transactions WHERE id='".$id."' LIMIT 1");  
        $content = $query->getResult();
        return $content;
    }

    function update_seat_status($id) {
        $date = date("Y-m-d h:i:s");
        $db      = \Config\Database::connect(); 
        $query = $db->query("UPDATE transactions SET seat_status='2', check_in_date='".$date."' WHERE id='".$id."'");  
        //$content = $query->getResult();

        $query1 = $db->query("SELECT * FROM transactions WHERE id='".$id."' LIMIT 1");  
        $content = $query1->getResult();
        //print_r($content); die;
        $seat_data = json_decode($content[0]->booked_data,true);
        $seats = 0;
        
        $seats = implode(',',$seat_data['seats_selected']);
        $name = $content[0]->name;
        
        $msg1 = new \stdClass();
            //required settings
          $msg1->subject = "Checked-in"; //SUBJECT
          //$mpdf = new \Mpdf\Mpdf();
          $html_data = "Hi " .ucfirst($name).", <br/>"."<p style='margin-left:20px;'>Checked In! Proceed to your seats ".$seats.". Enjoy the show!</p><br/><br/>"."Thanks,<br/>"."Admin";
          // echo $html_data; die;
          //$mpdf->WriteHTML($html);
          $msg1->htmlbody = $html_data;
          $msg1->to = $content[0]->email; //TO
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
        return $query;
    }

}