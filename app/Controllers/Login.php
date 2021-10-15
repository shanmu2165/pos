<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Login extends BaseController {

    protected $session;
    protected $db;
    function __construct() {
        $this->session = session();
        $this->db = \Config\Database::connect();
    }

    function index() { 
        
        $data = [];
       
        $data['page_title'] = 'POS - Login';
        $data['form_action'] = base_url()."/login/verify_login";
        
        if($this->session->has('user_logged_in')) {
            return redirect()->to(base_url().'/shows');
        } else {
           
            return view('login', ['data' => $data]);
        }
           
    }

    function verify_login() {
        //print_r($_POST); die;
        helper(['form', 'url']);
        $isValidated = $this->validate([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[5]'
        ]);
        $ldata = [];
       
        $ldata['page_title'] = 'POS - Login';
        $ldata['form_action'] = base_url()."/login/verify_login";
        $model = new \App\Models\UserModel();

        if(!$isValidated) { 
            echo view('login', [
                'validation' => $this->validator,
                'data' => $ldata
            ]);
        } else { 
            $email = $this->request->getVar('email', FILTER_SANITIZE_EMAIL);
            $pass = $this->request->getVar('password');
            $data = $model->where('email', $email)->first();
            $model1 = $this->db->table('users');

            $password = @$data['password'];
            $verifyPass = password_verify($pass,$password); 
            if(!empty($verifyPass)) {
                
                $session_data = [
                 'user_id' => $data['id'],
                 'user_name' => $data['name'],
                 'user_email' => $data['email'],
                 'user_logged_in' => TRUE,
                 'user_permission' => $data['perms'],
                ];
               $data1 = array('lastlogin' => strtotime(date('Y-m-d H:i:s')));
               
               $this->session->set($session_data);
               $model1->where('id',$data['id']);
               $model1->update($data1);
               return redirect()->to(base_url().'/shows');
            } else {
                $this->session->setFlashdata('msg','User Not Found!');
                return redirect()->to('/login');
            }
        }
    }

    function logout() { 
        
        $this->session->remove('user_id');
        $this->session->remove('user_name');
        $this->session->remove('user_email');
        $this->session->remove('user_logged_in');
        $this->session->remove('user_permission');
        $this->session->remove('ccode');
        
        session_destroy();
        return redirect()->to('/login');

    }

}