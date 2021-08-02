<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginFilter implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
      $session = session();
      //print_r($session); die;
      if(!$session->has('user_logged_in')) {
        return redirect()->to(base_url().'/');
      }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        
    }
}