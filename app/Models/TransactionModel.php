<?php

namespace App\Models;
use CodeIgniter\Model;

class TransactionModel extends Model {

    protected $table      = 'transactions';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $allowedFields = ['type', 'site','status','name','email','phone','amount','randid','notes','content','timestamp'];

    function verify_qrcode($data) {
        
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT * FROM transactions WHERE randid='".$data['randid']."' AND email='".$data['email']."' LIMIT 1");  
        $content = $query->getResult();
        if($content) {
            return $content;
        } else {
            return false;
        }
        
    }

}