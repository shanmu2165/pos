<?php
namespace App\Controllers;

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
        //print_r($data); die;
        if($this->session->has('user_logged_in')) {
            return redirect()->to(base_url().'/shows');
        } else {
            return view('login',$data);
        }
           
    }

    function verify_login() {
        //print_r($_POST); die;
        $model = new \App\Models\UserModel();
        $email = $this->request->getVar('email');
        $pass = $this->request->getVar('password');
        $data = $model->where('email', $email)->first();
        $model1 = $this->db->table('users');
        if($data) { 
            $password = $data['password'];
            $verifyPass = password_verify($pass,$password);
            if($verifyPass) {
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
                $this->session->setFlashdata('msg', 'Wrong Password!');
                return redirect()->to('/login');
            }
        } else {
            $this->session->setFlashdata('msg','User Not Found!');
            return redirect()->to('/login');
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