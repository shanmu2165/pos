<?php

namespace App\Models;
use CodeIgniter\Model;

class TransactionModel extends Model {

    protected $table      = 'transactions';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $allowedFields = ['type', 'site','status','name','email','phone','amount','randid','notes','content','timestamp'];

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

}