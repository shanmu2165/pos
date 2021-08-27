<?php
namespace App\Controllers;

class Cart extends BaseController {

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

    //Function for Cart Page
    function index($data = '')
    {  echo "<pre>"; print_r($_SESSION['cart_details']); "</pre>"; die;
      $data = [];
      //echo "<pre>"; print_r($this->request->getVar()); "</pre>"; die;
      $data['page_title'] = 'POS - Tickets';
      $data['search_url'] = base_url().'/shows/search';
      $date['delete_url'] = base_url().'/delete_cart';
      //$date['coupon_url'] = base_url().'/shows/apply_coupon';
      $data['categories'] = $this->model->get_categories();
      $data['current'] = 'cart';
      $data['go_back'] = $this->request->getVar('referrer');
      if($this->request->getVar()){ 
      $cart = array();
          $total = 0;
      $data['pcount'] = $this->request->getVar('pcount');
      $data['priceset'] = $this->request->getVar('priceset');
      $data['location'] = ucwords(str_replace("-"," ",$this->request->getVar('location')));
      $data['time'] = $this->request->getVar('time');
      $data['date'] = $this->request->getVar('date');
      $data['venue'] = $this->request->getVar('venue');
      $data['total_price'] = $this->request->getVar('total_price');

      $data['content'] = $this->request->getVar('content');
      
      $data['content_detail'] = $this->model->get_show($data['content']); 
      $data['price_dtl'] = $this->model->get_price_data($data['priceset']);
      $data['venue_detail'] = $this->model->get_venue($data['venue']);
      $data['taxinclusive'] = $this->request->getVar('taxinclusive');
      $data['feesinclusive'] = $this->request->getVar('feesinclusive');

      
       $contenttax = 0;
       $content = '';
       $contentLogo = '';
       $qty = NULL;
       //$loc = ucwords(str_replace("-"," ",$data['item']['location']));
       
       //Get Site Tax from settings table
       $tax = $this->model->getTax();
       if(!empty($tax)) {
         $contenttax = $tax[0]->value1;
       }
       
       foreach($data['content_detail'] as $value) {
         $content = $value->title;
         $contentLogo = $value->image2;
       }
     }
      if($this->request->getVar()) {  
      $cart = array(); $tc = 0;
     for($x = 1; $x<= $data['pcount']; $x++) {
        if($this->request->getVar("qty".$x."") > 0) {
         $tc++;
        }
        $cart['item'][$x] =array(
         'name' => $this->request->getVar("type".$x."_desc"),
         'qty' => $this->request->getVar("qty".$x.""),
         //'tcount' => $tc,
         'price' => floatval($this->request->getVar("type".$x."_price")),
         'date'=>$data['date'],
         'time'=>$data['time'],
         'taxinclusive'=>$data['taxinclusive'],
                 'feesinclusive'=>$data['feesinclusive'],
         'location' => $data['location'],
         'contentTax' => $contenttax,
         'logo'=>$contentLogo,
         'venue' => $data['venue_detail'][0]->name
       );
       }
       if(!empty($this->request->getVar('family_seats'))) {
         $cart['family_seats'] = @$this->request->getVar('family_seats');
       }
       $cart['content'] = $data["content_detail"];
       $cart['venue'] = $data['venue_detail'][0]->name;
       $cart['itotal'] = $data['total_price'];
       $cart['total'] = $data['total_price'];
       $cart['salestax'] = getenv('salestax');
       $cart['processingfees'] = getenv('processingfees');
      
       if($cart['salestax'] > 0) {
         $cart['total'] = $cart['total'] + $cart['salestax'];
       }
       if($cart['processingfees'] > 0) {
         $cart['total'] = $cart['total'] + $cart['processingfees'];
       }
       $cart['tcount'] = $tc;
       //$cart['tcount'] = $data['pcount'];
       //unset($_SESSION['cart']); die;
       //$jsoncart = json_encode($cart);
       $this->session->set('cart', $cart);
     } 
       
      //echo "<pre>"; print_r($_SESSION['cart']['item']); "</pre>"; die;
      return view('my_cart',$data);
    }

    //Remove Cart Item Functionality
    function remove_cart_item($keyId) { 
    
        if(isset($keyId) &&  !empty($keyId)) { //
        $cart = $_SESSION['cart'];
        foreach($cart['item'] as $key => $val) { 
          if ($key== $keyId) {
            if($cart['item'][$key]['qty'] > 1) {
              $cart['total'] = $cart['total'] - ($cart['item'][$key]['qty'] * $val['price']);
              $cart['itotal'] = $cart['itotal'] - ($cart['item'][$key]['qty'] * $val['price']);
            }  else {
              $cart['total'] = $cart['total'] - $val['price'];
              $cart['itotal'] = $cart['itotal'] - $val['price'];
            }
            
            $cart['item'][$key]['qty'] = 0;
            //print_r($cart['itotal']); die;
            
            //$this->apply_coupon($cart['itotal']);
            
            $cart['tcount'] = $cart['tcount'] - 1;
            $cart['item'][$key]['price'] = 0;
  
          }
        }    
        $this->session->set('cart', $cart);
        //echo "<pre>"; print_r($_SESSION['cart']); "</pre>"; die;
        if(!empty($_SESSION['ccode'])) {
          $this->remove_coupon();
        }
        
        if($_SESSION['cart']['tcount'] <= 0) {
          $_SESSION['cart']['total'] = 0;
          $_SESSION['cart']['salestax'] = 0;
          $_SESSION['cart']['processingfees'] = 0;
        }
        return redirect()->to('/cart');
        } else {
          echo "Item Not Removed";
        }
    }

    //Apply Coupon Functionality
    function apply_coupon() {
        $data = [];
        $ptotal = 0; $discount = 0;
        //print_r($_SESSION['cart']); die;
        //echo "<pre>"; print_r($_SESSION['ccode']); "</pre>"; die;
        if(isset($_SESSION['cart']['itotal']) && $_SESSION['cart']['itotal'] > 0) {
              @$data['ccode'] = $this->request->getVar('ccode');
              //print_r($data['ccode']); die;
              $data['validate_ccode'] = $this->model->get_coupon_code($data['ccode']);
              $data['discount'] = @$data['validate_ccode'][0]->discount;

            if($this->request->getVar('ccode') && !empty($data['validate_ccode'])) {
                
                
                //$type = str_contains($data['discount'],'%');
                $type = strpos($data['discount'], '%');
                if(!empty($type)) {
                $discount = str_replace('%','',$data['discount']);
                $this->session->set('ccode', $data['discount']);
                $this->session->set('ccodeinfo', $data['validate_ccode'][0]);
                //print_r($discount); die;
                    $ptotal =  floatval(($_SESSION['cart']['itotal'] * $discount )/100);
                
                $ptotal = number_format($ptotal, 3);
                $val= number_format($_SESSION['cart']['total'] - $ptotal,3);
                $_SESSION['cart']['ptotal'] = $ptotal;
                $_SESSION['cart']['total'] = $val;
        
                } else {
                $discount = str_replace('$','',$data['discount']);
                $this->session->set('ccode', $data['discount']);
                $this->session->set('ccodeinfo', $data['validate_ccode'][0]);
                $ptotal =  floatval($_SESSION['cart']['itotal'] - $discount);  
                $ptotal = number_format($ptotal, 3);
                $val= number_format($_SESSION['cart']['total'] - $ptotal,3);
                $_SESSION['cart']['ptotal'] = $ptotal;
                $_SESSION['cart']['total'] = $val;
                }
                $_SESSION['cart']['coupon_type'] = $data['validate_ccode'][0]->type;
                if($_SESSION['cart']['coupon_type'] == 2) {
                    $_SESSION['cart']['total'] = 0.00;
                    $_SESSION['cart']['salestax'] = 0.00;
                    $_SESSION['cart']['processingfees'] = 0.00;
                    $this->session->setFlashdata('msg', 'Voucher Applied Successfully!');
                } else {
                    $this->session->setFlashdata('msg', 'Coupon Applied Successfully!');
                }
                
                return redirect()->to('/cart');
                
            }  else {
                $this->session->setFlashdata('msg', 'Invalid Coupon Code!!!');
                return redirect()->to('/cart');
            }
        } else {
            $this->session->setFlashdata('msg', 'Please Select Tickets to apply Coupon/Voucher!');
            return redirect()->to('/cart');
        }
    }

    //Remove Coupon Functionality
    function remove_coupon(){ 
        $data = [];
        $overallTotal = 0;
        //print_r($this->request->getVar()); die;
        
        if($this->request->getVar('ccode')) {
          $data['ccode'] = $this->request->getVar('ccode');
          $data['cvalue'] = $this->request->getVar('coupon_val');
    
          if(isset($_SESSION['cart']) && $_SESSION['ccode']) {
            $_SESSION['cart']['ptotal'] = 0;
            $_SESSION['cart']['salestax'] = getenv('salestax');
            $_SESSION['cart']['processingfees'] = getenv('processingfees');
            if($_SESSION['cart']['coupon_type'] == 2) {
              $overallTotal = $_SESSION['cart']['total'] + $data['cvalue'] + $_SESSION['cart']['salestax'] + $_SESSION['cart']['processingfees'];
            } else {
              $overallTotal = $_SESSION['cart']['total'] + $data['cvalue'];
            }
            //
    
            $_SESSION['cart']['total'] = $overallTotal;
            $_SESSION['ccode'] = 0;
            $_SESSION['ccodeinfo'] = '';
            
          }
          if($_SESSION['cart']['coupon_type'] == 2) {
            $this->session->setFlashdata('msg', "Voucher removed successfully!");
          } else {
            $this->session->setFlashdata('msg', "Coupon Code removed successfully!");
          }
          return redirect()->to('/cart');
        } else if(!empty($_SESSION['ccode']) && !empty($_SESSION['cart']['ptotal'])) {
          $ccode = $_SESSION['ccode'];
          $ptotal = $_SESSION['cart']['ptotal'];
          $overallTotal = $_SESSION['cart']['total'] + $ptotal;
          $_SESSION['cart']['total'] = $overallTotal;
          $_SESSION['ccode'] = 0;
          $_SESSION['ccodeinfo'] = '';
    
        } else {  //echo "13"; die;
          $this->session->setFlashdata('msg', "Can't able to remove coupon applied!");
          return redirect()->to('/cart');
        }
    }
}