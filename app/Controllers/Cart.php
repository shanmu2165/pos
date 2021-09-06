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
    {   //echo "<pre>"; print_r($_POST); "</pre>"; die;
      
      $data = [];
      //echo "<pre>"; print_r($this->request->getVar()); "</pre>"; die;
      $data['page_title'] = 'POS - Tickets';
      $data['search_url'] = base_url().'/shows/search';
      $date['delete_url'] = base_url().'/delete_cart';
      //$date['coupon_url'] = base_url().'/shows/apply_coupon';
      $data['categories'] = $this->model->get_categories();
      $data['current'] = 'cart';

      if(isset($_SESSION['chart_type']) && $_SESSION['chart_type'] == 'seats') {
        $data['go_back'] = @$_SESSION['quantity_details']['referrer'];
        if(!empty($_SESSION['quantity_details'])) {
          $quantity = array_slice(@$_SESSION['quantity_details'], 15);
        } else {
          $quantity = '';
        }

        if(!empty($_SESSION['cart_details'])){ 
            $cart = array();
            $total = 0;
            $data['pcount'] = @$_SESSION['cart_details']['pcount'];
            $data['priceset'] = $_SESSION['cart_details']['priceset'];
            $data['location'] = ucwords(str_replace("-"," ",@$_SESSION['cart_details']['section']));
            $data['time'] = @$_SESSION['cart_details']['time'];
            $data['date'] = @$_SESSION['cart_details']['date'];
            $data['venue'] = @$_SESSION['cart_details']['venue'];
            $data['total_price'] = @$_SESSION['cart_details']['total_price'];

            $data['content'] = @$_SESSION['cart_details']['content'];
            
            $data['content_detail'] = $this->model->get_show($data['content']); 
            $data['price_dtl'] = $this->model->get_price_data($data['priceset']);
            $data['venue_detail'] = $this->model->get_venue($data['venue']);
            $data['taxinclusive'] = @$_SESSION['quantity_details']['taxinclusive'];
            $data['feesinclusive'] = @$_SESSION['quantity_details']['feesinclusive'];

        
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

        if($quantity) {  
          $cart = array(); $tc = 0;
          for($x = 1; $x<= $data['pcount']; $x++) {
            if($quantity["qty".$x.""] > 0) {
              
            $tc++;
            }
            $cart['item'][$x] =array(
            'name' => $quantity["type".$x."_desc"],
            'qty' => $quantity["qty".$x.""],
            //'tcount' => $tc,
            'price' => floatval($quantity["type".$x."_price"]),
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

          if(!empty($_SESSION['cart_details']['family_seats'])) {
            $cart['family_seats'] = @$_SESSION['cart_details']['family_seats'];
          }

          $cart['content'] = $data["content_detail"];
          $cart['venue'] = $data['venue_detail'][0]->name;
          $cart['itotal'] = $data['total_price'];
          $cart['total'] = $data['total_price'];
          $cart['salestax'] = getenv('salestax');
          $cart['processingfees'] = getenv('processingfees');
          $cart['pcount'] = $data['pcount'];
        
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
          $cart['seats_selected'] = $_SESSION['cart_details']['seat_arr'];
          // if(!empty($_SESSION['cart']['ptotal'])) {
          //   $cart['ptotal'] = @$_SESSION['cart']['ptotal'];
          // }
          if(empty($_SESSION['cart'])) {
            $this->session->set('cart', $cart);
          }
          
        } 
      } else if(isset($_SESSION['chart_type']) && $_SESSION['chart_type'] == 'svg'){
        if(empty($_SESSION['quantity_details'])) {
          $_SESSION['quantity_details'] = $_POST;
        }
        
        //print_r($_SESSION['quantity_details']); 
        $data['go_back'] = @$_SESSION['ccancel_url'];

            $cart = array();
            $total = 0;
            $data['pcount'] = @$_SESSION['quantity_details']['pcount'];
            $data['priceset'] = @$_SESSION['quantity_details']['priceset'];
            $data['location'] = ucwords(str_replace("-"," ",@$_SESSION['quantity_details']['location']));
            $data['time'] = @$_SESSION['quantity_details']['time'];
            $data['date'] = @$_SESSION['quantity_details']['date'];
            $data['venue'] = @$_SESSION['quantity_details']['venue'];
            $data['total_price'] = @$_SESSION['quantity_details']['total_price'];

            $data['content'] = @$_SESSION['quantity_details']['content'];
            //print_r($data['content']); die;
            $data['content_detail'] = $this->model->get_show($data['content']); 
            $data['price_dtl'] = $this->model->get_price_data($data['priceset']);
            $data['venue_detail'] = $this->model->get_venue($data['venue']);
            $data['taxinclusive'] = @$_SESSION['quantity_details']['taxinclusive'];
            $data['feesinclusive'] = $_SESSION['quantity_details']['feesinclusive'];

        
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

            $cart = array(); $tc = 0;
          for($x = 1; $x<= $data['pcount']; $x++) {
            if($_SESSION['quantity_details']["qty".$x.""] > 0) {
              
            $tc++;
            }
            $cart['item'][$x] =array(
            'name' => $_SESSION['quantity_details']["type".$x."_desc"],
            'qty' => $_SESSION['quantity_details']["qty".$x.""],
            //'tcount' => $tc,
            'price' => floatval($_SESSION['quantity_details']["type".$x."_price"]),
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

          if(!empty($_SESSION['quantity_details']['family_seats'])) {
            $cart['family_seats'] = $_SESSION['quantity_details']['family_seats'];
          }

          $cart['content'] = $data["content_detail"];
          $cart['venue'] = $data['venue_detail'][0]->name;
          $cart['itotal'] = $data['total_price'];
          $cart['total'] = $data['total_price'];
          $cart['salestax'] = getenv('salestax');
          $cart['processingfees'] = getenv('processingfees');
          $cart['pcount'] = $data['pcount'];
        
          if($cart['salestax'] > 0) {
            $cart['total'] = $cart['total'] + $cart['salestax'];
          }
          if($cart['processingfees'] > 0) {
            $cart['total'] = $cart['total'] + $cart['processingfees'];
          }
          $cart['tcount'] = $tc;
         
          if(empty($_SESSION['cart'])) {
            $this->session->set('cart', $cart);
          }
          
      }
      //print_r($_SESSION['cart']['ptotal']); die;
      return view('my_cart',$data);
    }

    //Remove Cart Item Functionality
    function remove_cart_item($keyId) { 
        //print_r($keyId); die;
        if(isset($keyId) &&  !empty($keyId)) { //
        $cart = $_SESSION['cart'];
        $rem_qty = $cart['item'][$keyId]['qty'];
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
        for($i =1; $i<=$rem_qty; $i++ ) {
          $rem_val = array_pop($_SESSION['cart']['seats_selected']);
          
        }
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
        
        if(isset($_SESSION['cart']['itotal']) && $_SESSION['cart']['itotal'] > 0) {
              @$data['ccode'] = $this->request->getVar('ccode');
              //print_r($data['ccode']); die;
              $data['validate_ccode'] = $this->model->get_coupon_code($data['ccode']);
              $data['discount'] = @$data['validate_ccode'][0]->discount;
              //print_r($data['validate_ccode']); die;
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
                //$_SESSION['cart']['per_total'] =$ptotal;
                $_SESSION['cart']['total'] = $val;
        
                } else {
                $discount = str_replace('$','',$data['discount']);
                $this->session->set('ccode', $data['discount']);
                $this->session->set('ccodeinfo', $data['validate_ccode'][0]);
                $ptotal =  floatval($_SESSION['cart']['itotal'] - $discount);  
                $ptotal = number_format($ptotal, 3);
                $val= number_format($_SESSION['cart']['total'] - $ptotal,3);
                $_SESSION['cart']['ptotal'] = $ptotal;
                //$_SESSION['cart']['per_total'] =$ptotal;
                $_SESSION['cart']['total'] = $val;
                }
             //print_r($_SESSION['cart']['per_total']); die;
                //$_SESSION['cart']['coupon_type'] = $data['validate_ccode'][0]->type;
                // if($_SESSION['cart']['coupon_type'] == 2) {
                //     $_SESSION['cart']['total'] = 0.00;
                //     $_SESSION['cart']['salestax'] = 0.00;
                //     $_SESSION['cart']['processingfees'] = 0.00;
                //     $this->session->setFlashdata('msg', 'Voucher Applied Successfully!');
                // } else {
                //     $this->session->setFlashdata('msg', 'Coupon Applied Successfully!');
                // }
                
                $this->session->setFlashdata('msg', 'Coupon Applied Successfully!');
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
            // if($_SESSION['cart']['coupon_type'] == 2) {
            //   $overallTotal = $_SESSION['cart']['total'] + $data['cvalue'] + $_SESSION['cart']['salestax'] + $_SESSION['cart']['processingfees'];
            // } else {
              $overallTotal = $_SESSION['cart']['total'] + $data['cvalue'] - 0.01;
            //}
            //
    
            $_SESSION['cart']['total'] = $overallTotal;
            $_SESSION['ccode'] = 0;
            $_SESSION['ccodeinfo'] = '';
            
          }
          
            $this->session->setFlashdata('msg', "Coupon Code removed successfully!");
          
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

    function cancel_transaction() {
      //echo "<pre>"; print_r($_SESSION); "</pre>"; die;
      $cancel = @$_POST['cancel'];
      if(!empty($_SESSION['quantity_details']) && !empty($_SESSION['seats_selected'])) {
      unset($_SESSION['quantity_details']);
      unset($_SESSION['cart_details']);
      unset($_SESSION['cart']);
      unset($_SESSION['seats_selected']);
      unset($_SESSION['tcount']);
      unset($_SESSION['itotal']);
      unset($_SESSION['total']);
      
       echo "success";
      } else if(!empty($_SESSION['cart'])) {
        unset($_SESSION['quantity_details']);
        unset($_SESSION['cart']);
        unset($_SESSION['tcount']);
        unset($_SESSION['itotal']);
        unset($_SESSION['total']);

        echo "success";
      } else {
        echo "failure";
      }
    }
}