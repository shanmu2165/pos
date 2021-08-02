<?php
namespace App\Controllers;
// use Endroid\QrCode\Builder\Builder;
// use Endroid\QrCode\Encoding\Encoding;
// use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
// use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
// use Endroid\QrCode\Label\Font\NotoSans;
// use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
// use Endroid\QrCode\Writer\PngWriter;
// use Zxing\QrReader;
// use Twilio\Rest\Client;
// use Mike42\Escpos\PrintConnectors\FilePrintConnector;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
// use Mike42\Escpos\CapabilityProfile;
// use Mike42\Escpos\Printer;


class Shows extends BaseController {
    
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

    //Home Page
    function index() {
        $data = []; 
        $data['page_title'] = 'POS - Showlist';
        $currentDate = '';
        $cal_data = [];
        $time_list = [];
        $val_arr = [];
        $data['my_shows'] = $this->model->get_all_shows($this->user_detail['shows_id']); 
        
        $uid = explode(',',$this->user_detail['shows_id']);
         foreach($uid as $key => $val) { 
        //   $data['detail'][$val] = $this->model->show_other_details_datewise($val);  
           
             
               $data['detail'] = $this->model->show_other_details($val);
              
               foreach ($data['detail'] as $instance){
                $moctr = $instance->start_date;
                
                while($moctr <= $instance->end_date) {
                 
                  $dow = strval(date("w", strtotime($moctr)) + 1);
                  
                  if (strpos($instance->recurrence, $dow) !== false ) {  
                    //Details for Date on Time
                    if ($instance->end_time > $instance->start_time){ 

                      //$cal_data[$val][$moctr][$instance->start_time."-".$instance->end_time] = $instance;
                      $cal_data[$val][$moctr] = $instance;

                      /*if (!isset($time_list[$instance->start_time."|".$instance->end_time])){
                        $time_list[$instance->start_time."|".$instance->end_time] = $ic;
                        $ic++;
                      }*/
                      if (!isset($time_list[$moctr])){
                        $time_list[$moctr] = $instance->start_time."-".$instance->end_time;
                      } else {
                        $time_listpcs = explode("|",$time_list[$moctr]); //send previous to array
                        array_push($time_listpcs, $instance->start_time."-".$instance->end_time); //add new value
                        sort($time_listpcs); //sort
                        $time_list[$moctr] = implode("|", $time_listpcs); //back to string
                      }
                    } else { 
                      //$cal_data[$val][$moctr][$instance->start_time] = $instance;
                      $cal_data[$val][$moctr] = $instance;
                      /*if (!isset($time_list[$instance->start_time])){
                        $time_list[$instance->start_time] = $ic;
                        $ic++;
                      }*/
                      // Time for Date
                      if (!isset($time_list[$moctr])){ 
                        $time_list[$moctr] = $instance->start_time;
                      } else { 
                        $time_listpcs = explode("|",$time_list[$moctr]);
                        array_push($time_listpcs, $instance->start_time);
                        sort($time_listpcs);
                        $time_listpcs = array_unique($time_listpcs);
                        $time_list[$moctr] = implode("|", $time_listpcs);
                      }
                       //
                      //check for Christmas instances
                      if (stripos($instance->type, 'christmas') !== false) {
                        $data['christmasflag'] = TRUE;
                      }
                      //check for Gospel instances
                      if (stripos($instance->type, 'gospel') !== false) {
                        $data['gospelflag'] = TRUE;
                      }
                      //check for Country instances
                      if (stripos($instance->type, 'country') !== false) {
                        $data['countryflag'] = TRUE;
                      }
                    }
                  }
                  //Increment Date + 1 for checking
                  $moctr = date("Y-m-d", strtotime($moctr." +1 day"));
                  
                }
              }

            
         }
         $time_list = array_unique($time_list); //discard duplicates
          
          $time_list = array_values($time_list); //reset keys
          
          $time_list = array_flip($time_list); //flip values and keys [Time list in order]
          ksort($cal_data);
    
    //get start and end of instances for calendar loop
    end($cal_data);

          $data['today'] = date("Y-m-d");
         
         
          //echo "<pre>";  print_r($cal_data); "</pre>"; die;
        $data['cal_data'] =   $cal_data;
        $data['all_shows'] = $this->model->get_all_shows();
        $data['categories'] = $this->model->get_categories();
        $data['buy_url'] = base_url().'/show/';
        $data['search_url'] = base_url().'/shows/search';
        $data['current'] = 'shows';
        //print_r($data['cal_data']); die;
        return view('home',$data);
    }

    //Function for both search and filter shows based category
    function search($filter=NULL) { //
      if(!empty($this->request->getVar('search'))) {
        
        $search_val = $this->request->getVar('search');
        //print_r($search_val); die;
        $data = []; 
        $data['page_title'] = 'POS - Showlist';
        $data['my_shows'] = $this->model->get_searched_show($search_val,'',$this->user_detail['shows_id']);
        $data['all_shows'] = $this->model->get_searched_show($search_val);
        $data['categories'] = $this->model->get_categories();
        $data['buy_url'] = base_url().'/show/';
        $data['search_url'] = base_url().'/shows/search';
        $data['current'] = 'shows';
        $data['today'] = date("Y-m-d");
        
        return view('home',$data);
      } else if(!empty($filter)) {
        $data = []; 
        $data['page_title'] = 'POS - Showlist';
        $data['my_shows'] = $this->model->get_searched_show('',$filter,$this->user_detail['shows_id']);
        $data['all_shows'] = $this->model->get_searched_show('',$filter);
        //print_r($data['my_shows']); die;
        $data['categories'] = $this->model->get_categories();
        $data['buy_url'] = base_url().'/show/';
        $data['search_url'] = base_url().'/shows/search';
        $data['current'] = 'shows';
        $data['today'] = date("Y-m-d");
        
        return view('home',$data);
      } else {
        return redirect()->to('shows');
      }
    }

    //Individual Show Detail Page
    function details($id=NULL,$etime=NULL) {
        
        $data = [];
        $cal_data = [];
        $time_list = [];
        $data['search_url'] = base_url().'/shows/search';
        $data['christmasflag'] = FALSE;
        $data['gospelflag'] = FALSE;
        $data['countryflag'] = FALSE;
        $data['current'] = 'detail';
        $data['go_back'] = $this->request->getUserAgent()->getReferrer();
        $data['page_title'] = 'POS - Show Detail';
        $data['show_details'] = $this->model->get_show($id);
        $data['categories'] = $this->model->get_categories();
        $data['price_detail'] = $this->model->get_price_detail($id);
        $additional_details = $this->model->show_other_details($id);
        $data['etime'] = $etime;
      
        foreach ($additional_details as $instance){
            $moctr = $instance->start_date;
            
            while($moctr <= $instance->end_date) {
             
              $dow = strval(date("w", strtotime($moctr)) + 1);
              
              if (strpos($instance->recurrence, $dow) !== false ) {  
                //Details for Date on Time
                if ($instance->end_time > $instance->start_time){ 
                  $cal_data[$moctr][$instance->start_time."-".$instance->end_time] = $instance;
                  /*if (!isset($time_list[$instance->start_time."|".$instance->end_time])){
                    $time_list[$instance->start_time."|".$instance->end_time] = $ic;
                    $ic++;
                  }*/
                  if (!isset($time_list[$moctr])){
                    $time_list[$moctr] = $instance->start_time."-".$instance->end_time;
                  } else {
                    $time_listpcs = explode("|",$time_list[$moctr]); //send previous to array
                    array_push($time_listpcs, $instance->start_time."-".$instance->end_time); //add new value
                    sort($time_listpcs); //sort
                    $time_list[$moctr] = implode("|", $time_listpcs); //back to string
                  }
                } else { 
                  $cal_data[$moctr][$instance->start_time] = $instance;
                 
                  /*if (!isset($time_list[$instance->start_time])){
                    $time_list[$instance->start_time] = $ic;
                    $ic++;
                  }*/
                  // Time for Date
                  if (!isset($time_list[$moctr])){ 
                    $time_list[$moctr] = $instance->start_time;
                  } else { 
                    $time_listpcs = explode("|",$time_list[$moctr]);
                    array_push($time_listpcs, $instance->start_time);
                    sort($time_listpcs);
                    $time_listpcs = array_unique($time_listpcs);
                    $time_list[$moctr] = implode("|", $time_listpcs);
                  }
                   //
                  //check for Christmas instances
                  if (stripos($instance->type, 'christmas') !== false) {
                    $data['christmasflag'] = TRUE;
                  }
                  //check for Gospel instances
                  if (stripos($instance->type, 'gospel') !== false) {
                    $data['gospelflag'] = TRUE;
                  }
                  //check for Country instances
                  if (stripos($instance->type, 'country') !== false) {
                    $data['countryflag'] = TRUE;
                  }
                }
              }
              //Increment Date + 1 for checking
              $moctr = date("Y-m-d", strtotime($moctr." +1 day"));
              
            }
          }
            
          $time_list = array_unique($time_list); //discard duplicates
          
          $time_list = array_values($time_list); //reset keys
          
          $time_list = array_flip($time_list); //flip values and keys [Time list in order]
          ksort($cal_data);
    
    //get start and end of instances for calendar loop
    end($cal_data);
    $data['lastdate'] = key($cal_data); //last key
    $data['deeppages'] = TRUE;
    //$data['forceurl'] = TRUE;
    $data['time_list'] = $time_list;
    $data['cal_data'] = $cal_data;
    $ld = explode("-", $data['lastdate']);
    $data['endmonth'] = $ld[0]."-".@$ld[1]."-01";
    reset($cal_data);
    $data['firstdate'] = key($cal_data); //first key
    $fd = explode("-", $data['firstdate']);
    $data['cmonth'] = $fd[0]."-".$fd[1]."-01";

    $data['today'] = date("Y-m-d");
    $data['thismonth'] = date("Y-m-01");

    if ($data['thismonth'] > $data['cmonth']){ //filter out past months
        $data['cmonth'] = $data['thismonth'];
    }
    //echo "<pre>"; print_r($data); "</pre>"; die;
       return view('show_detail',$data);
    }

    
    //Function for Theatre Section Page
    function select_section($showid=NULL,$date=NULL) {   
      $data = [];
      //var_dump($showid); die;
      $data['current'] = 'layout';
      $data['page_title'] = 'POS - Select Ticket';
      $data['search_url'] = base_url().'/shows/search';
      $data['go_back'] = $this->request->getUserAgent()->getReferrer();
      $data['categories'] = $this->model->get_categories();
      $data['forsrc'] = ''; 
      if(isset($date) && isset($showid) && $date > "2018-01-01") {
        $data['showid'] =  $showid;
        $data['date'] = $date;
        $data['content'] = $this->model->get_show_data((int)$showid);
        //print_r($data['content']); die;
        $data['page'] = $data['content']->pageinfo;
        $data['form_action'] = base_url().'/section';
        $data['content']->pageinfo->body = "<h1>".$data['page']->title."</h1>";

				//rewrite page title
				$data['page']->title = $data['page']->title . " - ". date("M j, Y", strtotime($date));

        $data['instances'] = $this->model->get_individual_date($showid,$date);
     
        //$data['listingoptions'] = "";
        foreach($data['instances'] as $insta) {
          //print_r($insta); die;
          if (isset($insta->pricesets)){
						//INSTANCE HAS PRICESET
						$thisps = explode(",", $insta->pricesets);
						$ps = $this->model->pricesets(NULL, $insta->content);

						$activepricesets = array();
						foreach ($ps as $key=>$priceset){
							if (in_array($key, $thisps)) {
								$activepricesets[$key] = $priceset;
							}
						}
						$data['pricesets'] = $activepricesets;
					} else {
						//NO PRICESET FOR INSTANCE
						$data['pricesets'] = $this->model->pricesets(NULL, $insta->content);
					}
          
          if($data['pricesets'])
          $data['listingoptions'] = $this->picklistingoptions($insta->content,
          $data['content']->pageinfo->venue,
          $date,
          $insta->start_time,
          $data['pricesets'],
          $this->request->getPost());

          //+
          //echo "<pre>"; var_dump($data['pricesets'][$insta->pricesets]->price5); "</pre>"; die;
          $data['listing_markup'] = $this->model->structured_markup($data['page'],$date,$insta->start_time,$data['pricesets']);
        }
        $data['pcount'] = 0;
        $data['price_seg']= [];
        $data['price_type'] = [];
        //print_r($data['pricesets'][$insta->pricesets]); die;
        if(!empty($data['pricesets'][$insta->pricesets]->price1)) { 
          $data['pcount']++;
          $data['price_seg'][$data['pcount']] = trim($data['pricesets'][$insta->pricesets]->price1, '$');
          $data['price_type'][$data['pcount']] = $data['pricesets'][$insta->pricesets]->type1;
        } 
        if(!empty($data['pricesets'][$insta->pricesets]->price2)) { 
          $data['pcount']++;
          $data['price_seg'][$data['pcount']] = trim($data['pricesets'][$insta->pricesets]->price2, '$');
          $data['price_type'][$data['pcount']] = $data['pricesets'][$insta->pricesets]->type2;
        }   
        if(!empty($data['pricesets'][$insta->pricesets]->price3)) { 
          $data['pcount']++;
          $data['price_seg'][$data['pcount']] = trim($data['pricesets'][$insta->pricesets]->price3, '$');
          $data['price_type'][$data['pcount']] = $data['pricesets'][$insta->pricesets]->type3;
          
        }
        if(!empty($data['pricesets'][$insta->pricesets]->price4) ){ 
          $data['pcount']++;
          $data['price_seg'][$data['pcount']] = trim($data['pricesets'][$insta->pricesets]->price4, '$');
          $data['price_type'][$data['pcount']] = $data['pricesets'][$insta->pricesets]->type4;
          if (strpos($data['pricesets'][$insta->pricesets]->type4, 'Family') !== false) {
            $data['no_of_seats'][$data['pcount']] = 0; 
          }
        }
        if(!empty($data['pricesets'][$insta->pricesets]->price5)) { 
          $data['pcount']++;
          $data['price_seg'][$data['pcount']] = trim($data['pricesets'][$insta->pricesets]->price5, '$');
          $data['price_type'][$data['pcount']] = $data['pricesets'][$insta->pricesets]->type5;
          if (strpos($data['pricesets'][$insta->pricesets]->type5, 'Family') !== false) {
            $data['no_of_seats'][$data['pcount']] = 0; 
          }
        } 
        if(!empty($data['pricesets'][$insta->pricesets]->price6)) { 
          $data['pcount']++;
          $data['price_seg'][$data['pcount']] = trim($data['pricesets'][$insta->pricesets]->price6, '$');
          $data['price_type'][$data['pcount']] = $data['pricesets'][$insta->pricesets]->type6;
        } 
        if(!empty($data['pricesets'][$insta->pricesets]->price7)) { 
          $data['pcount']++;
          $data['price_seg'][$data['pcount']] = trim($data['pricesets'][$insta->pricesets]->price7, '$');
          $data['price_type'][$data['pcount']] = $data['pricesets'][$insta->pricesets]->type7;
        } 
        
      }
       //echo "<pre>"; print_r($data['listingoptions']['opts']); "</pre>"; die;
      return view('product_layout',$data);
    }

    //SUb Function for Theatre Section
    function picklistingoptions($content=NULL, $venue=NULL, $date=NULL, $time=NULL, $pricesets=NULL, $post=NULL){
      $data = [];
      $data['opts'] = $this->model->get_options($content);
      $data['venues'] = $this->model->get_venue($venue);
        foreach($data['venues'] as $value) {
          $venue = $value;
        }
        //GET ANY PRICE OPTIONS THAT MAY BE SOLD OUT FOR THIS DATE
        $data['except'] = $this->model->get_price_exception($content,$date);
        $data['exceptions'] = [];
        foreach ($data['except'] as $value){
          $exceptions[$value->time][$value->priceoption] = $value->text;
        }

        return $data;
    }


    function ticket_booking() {
      $data = [];
      $data['time'] = $this->request->getVar('rtime');
      $data['rdate'] = $this->request->getVar('rdate');
      $data['content'] = $this->request->getVar('content');
      $data['priceset'] = $this->request->getVar('priceset');
      $data['location'] = $this->request->getVar('location');
      $data['showid'] = $this->request->getVar('showid');
      $data['pcount'] = $this->request->getVar('pcount');
      $data['page_title'] = 'POS - Ticket Booking';
      $data['search_url'] = base_url().'/shows/search';
      $data['form_action'] = base_url().'/cart';
      $data['current'] = 'ticket';
      $data['referrer'] = current_url();
      $data['go_back'] = $this->request->getUserAgent()->getReferrer();
      $data['categories'] = $this->model->get_categories();
      $data['get_price_details'] = $this->model->get_price_data($data['priceset']);
      $data['content_detail'] = $this->model->get_show($data['content']);
      
      $data['price_seg']= [];
      $data['price_type'] = [];
      $data['pcount'] = 0;
      
      if(!empty($data['get_price_details'][0]->price1)) { 
        $data['pcount']++;
        $data['price_seg'][$data['pcount']] = $data['get_price_details'][0]->price1;
        $data['price_type'][$data['pcount']] = $data['get_price_details'][0]->type1;

      }
      if(!empty($data['get_price_details'][0]->price2)) { 
        $data['pcount']++;
        $data['price_seg'][$data['pcount']] = $data['get_price_details'][0]->price2;
        $data['price_type'][$data['pcount']] = $data['get_price_details'][0]->type2;

      }
      if(!empty($data['get_price_details'][0]->price3)) { 
        $data['pcount']++;
        $data['price_seg'][$data['pcount']] = $data['get_price_details'][0]->price3;
        $data['price_type'][$data['pcount']] = $data['get_price_details'][0]->type3;
        
        if (strpos($data['get_price_details'][0]->type3, 'Family') !== false) {
          $data['no_of_seats'][$data['pcount']] = 0; 
        }

      }
      if(!empty($data['get_price_details'][0]->price4)) { 
        $data['pcount']++;
        $data['price_seg'][$data['pcount']] = $data['get_price_details'][0]->price4;
        $data['price_type'][$data['pcount']] = $data['get_price_details'][0]->type4;
        if (strpos($data['get_price_details'][0]->type4, 'Family') !== false) {
          $data['no_of_seats'][$data['pcount']] = 0; 
        }
      }
      if(!empty($data['get_price_details'][0]->price5)) { 
        $data['pcount']++;
        $data['price_seg'][$data['pcount']] = $data['get_price_details'][0]->price5;
        $data['price_type'][$data['pcount']] = $data['get_price_details'][0]->type5;
        if (strpos($data['get_price_details'][0]->type5, 'Family') !== false) {
          $data['no_of_seats'][$data['pcount']] = 0; 
        }
      }

      if(!empty($data['get_price_details'][0]->price6)) { 
        $data['pcount']++;
        $data['price_seg'][$data['pcount']] = $data['get_price_details'][0]->price6;
        $data['price_type'][$data['pcount']] = $data['get_price_details'][0]->type6;
        if (strpos($data['get_price_details'][0]->type6, 'Family') !== false) {
          $data['no_of_seats'][$data['pcount']] = 0; 
        }
      }

      if(!empty($data['get_price_details'][0]->price7)) { 
        $data['pcount']++;
        $data['price_seg'][$data['pcount']] = $data['get_price_details'][0]->price7;
        $data['price_type'][$data['pcount']] = $data['get_price_details'][0]->type7;
        if (strpos($data['get_price_details'][0]->type7, 'Family') !== false) {
          $data['no_of_seats'][$data['pcount']] = 0; 
        }
      }
      //echo "<pre>"; print_r($data); "</pre>"; die;
      return view('ticket_booking',$data);
    }
    
    //  function cart()
    //  { 
    //    $data = [];
    //    //echo "<pre>"; print_r($this->request->getVar()); "</pre>"; die;
    //    $data['page_title'] = 'POS - Tickets';
    //    $data['search_url'] = base_url().'/shows/search';
    //    $date['delete_url'] = base_url().'/delete_cart';
    //    //$date['coupon_url'] = base_url().'/shows/apply_coupon';
    //    $data['categories'] = $this->model->get_categories();
    //    $data['current'] = 'cart';
    //    $data['go_back'] = $this->request->getVar('referrer');
    //    if($this->request->getVar()){ 
    //    $cart = array();
		//    $total = 0;
    //    $data['pcount'] = $this->request->getVar('pcount');
    //    $data['priceset'] = $this->request->getVar('priceset');
    //    $data['location'] = ucwords(str_replace("-"," ",$this->request->getVar('location')));
    //    $data['time'] = $this->request->getVar('time');
    //    $data['date'] = $this->request->getVar('date');
    //    $data['venue'] = $this->request->getVar('venue');
    //    $data['total_price'] = $this->request->getVar('total_price');

    //    $data['content'] = $this->request->getVar('content');
       
    //    $data['content_detail'] = $this->model->get_show($data['content']); 
    //    $data['price_dtl'] = $this->model->get_price_data($data['priceset']);
    //    $data['venue_detail'] = $this->model->get_venue($data['venue']);
    //    $data['taxinclusive'] = $this->request->getVar('taxinclusive');
    //    $data['feesinclusive'] = $this->request->getVar('feesinclusive');

       
    //     $contenttax = 0;
    //     $content = '';
    //     $contentLogo = '';
    //     $qty = NULL;
    //     //$loc = ucwords(str_replace("-"," ",$data['item']['location']));
        
    //     //Get Site Tax from settings table
    //     $tax = $this->model->getTax();
    //     if(!empty($tax)) {
    //       $contenttax = $tax[0]->value1;
    //     }
        
    //     foreach($data['content_detail'] as $value) {
    //       $content = $value->title;
    //       $contentLogo = $value->image2;
    //     }
    //   }
    //    if($this->request->getVar()) {  
    //    $cart = array(); $tc = 0;
    //   for($x = 1; $x<= $data['pcount']; $x++) {
    //      if($this->request->getVar("qty".$x."") > 0) {
    //       $tc++;
    //      }
    //      $cart['item'][$x] =array(
    //       'name' => $this->request->getVar("type".$x."_desc"),
    //       'qty' => $this->request->getVar("qty".$x.""),
    //       //'tcount' => $tc,
    //       'price' => floatval($this->request->getVar("type".$x."_price")),
    //       'date'=>$data['date'],
    //       'time'=>$data['time'],
    //       'taxinclusive'=>$data['taxinclusive'],
		// 		  'feesinclusive'=>$data['feesinclusive'],
    //       'location' => $data['location'],
    //       'contentTax' => $contenttax,
    //       'logo'=>$contentLogo,
    //       'venue' => $data['venue_detail'][0]->name
    //     );
    //     }
    //     if(!empty($this->request->getVar('family_seats'))) {
    //       $cart['family_seats'] = @$this->request->getVar('family_seats');
    //     }
    //     $cart['content'] = $data["content_detail"];
    //     $cart['venue'] = $data['venue_detail'][0]->name;
    //     $cart['itotal'] = $data['total_price'];
    //     $cart['total'] = $data['total_price'];
    //     $cart['salestax'] = getenv('salestax');
    //     $cart['processingfees'] = getenv('processingfees');
       
    //     if($cart['salestax'] > 0) {
    //       $cart['total'] = $cart['total'] + $cart['salestax'];
    //     }
    //     if($cart['processingfees'] > 0) {
    //       $cart['total'] = $cart['total'] + $cart['processingfees'];
    //     }
    //     $cart['tcount'] = $tc;
    //     //$cart['tcount'] = $data['pcount'];
    //     //unset($_SESSION['cart']); die;
    //     //$jsoncart = json_encode($cart);
    //     $this->session->set('cart', $cart);
    //   } 
        
    //    //echo "<pre>"; print_r($_SESSION['cart']['item']); "</pre>"; die;
    //    return view('my_cart',$data);
    // }
  // function remove_cart_item($keyId) { 
    
  //     if(isset($keyId) &&  !empty($keyId)) { //
  //     $cart = $_SESSION['cart'];
  //     foreach($cart['item'] as $key => $val) { 
  //       if ($key== $keyId) {
  //         if($cart['item'][$key]['qty'] > 1) {
  //           $cart['total'] = $cart['total'] - ($cart['item'][$key]['qty'] * $val['price']);
  //           $cart['itotal'] = $cart['itotal'] - ($cart['item'][$key]['qty'] * $val['price']);
  //         }  else {
  //           $cart['total'] = $cart['total'] - $val['price'];
  //           $cart['itotal'] = $cart['itotal'] - $val['price'];
  //         }
          
  //         $cart['item'][$key]['qty'] = 0;
  //         //print_r($cart['itotal']); die;
          
  //         //$this->apply_coupon($cart['itotal']);
          
  //         $cart['tcount'] = $cart['tcount'] - 1;
  //         $cart['item'][$key]['price'] = 0;

  //       }
  //     }    
  //     $this->session->set('cart', $cart);
  //     //echo "<pre>"; print_r($_SESSION['cart']); "</pre>"; die;
  //     if(!empty($_SESSION['ccode'])) {
  //       $this->remove_coupon();
  //     }
      
  //     if($_SESSION['cart']['tcount'] <= 0) {
  //       $_SESSION['cart']['total'] = 0;
  //       $_SESSION['cart']['salestax'] = 0;
  //       $_SESSION['cart']['processingfees'] = 0;
  //     }
  //     return redirect()->to('/cart');
  //     } else {
  //       echo "Item Not Removed";
  //     }
  // }

  // function apply_coupon() {
  //   $data = [];
  //   $ptotal = 0; $discount = 0;
  //   //print_r($_SESSION['cart']); die;
  //   //echo "<pre>"; print_r($_SESSION['ccode']); "</pre>"; die;
  //   if(isset($_SESSION['cart']['itotal']) && $_SESSION['cart']['itotal'] > 0) {
  //     if($this->request->getVar('ccode')) {
  //       $data['ccode'] = $this->request->getVar('ccode');
  //       //print_r($data['ccode']); die;
  //       $data['validate_ccode'] = $this->model->get_coupon_code($data['ccode']);
  //       $data['discount'] = $data['validate_ccode'][0]->discount;
        
  //       //$type = str_contains($data['discount'],'%');
  //       $type = strpos($data['discount'], '%');
  //       if(!empty($type)) {
  //         $discount = str_replace('%','',$data['discount']);
  //         $this->session->set('ccode', $data['discount']);
  //         $this->session->set('ccodeinfo', $data['validate_ccode'][0]);
  //         //print_r($discount); die;
  //           $ptotal =  floatval(($_SESSION['cart']['itotal'] * $discount )/100);
          
  //         $ptotal = number_format($ptotal, 3);
  //         $val= number_format($_SESSION['cart']['total'] - $ptotal,3);
  //         $_SESSION['cart']['ptotal'] = $ptotal;
  //         $_SESSION['cart']['total'] = $val;

  //       } else {
  //         $discount = str_replace('$','',$data['discount']);
  //         $this->session->set('ccode', $data['discount']);
  //         $this->session->set('ccodeinfo', $data['validate_ccode'][0]);
  //         $ptotal =  floatval($_SESSION['cart']['itotal'] - $discount);  
  //         $ptotal = number_format($ptotal, 3);
  //         $val= number_format($_SESSION['cart']['total'] - $ptotal,3);
  //         $_SESSION['cart']['ptotal'] = $ptotal;
  //         $_SESSION['cart']['total'] = $val;
  //       }
  //         $_SESSION['cart']['coupon_type'] = $data['validate_ccode'][0]->type;
  //         if($_SESSION['cart']['coupon_type'] == 2) {
  //           $_SESSION['cart']['total'] = 0.00;
  //           $_SESSION['cart']['salestax'] = 0.00;
  //           $_SESSION['cart']['processingfees'] = 0.00;
  //           $this->session->setFlashdata('msg', 'Voucher Applied Successfully!');
  //         } else {
  //           $this->session->setFlashdata('msg', 'Coupon Applied Successfully!');
  //         }
        
  //       return redirect()->to('/cart');
        
  //     }  else {
  //       $this->session->setFlashdata('msg', 'Invalid Coupon Code!!!');
  //       return redirect()->to('/cart');
  //     }
  //   } else {
  //       $this->session->setFlashdata('msg', 'Please Select Tickets to apply Coupon/Voucher!');
  //       return redirect()->to('/cart');
  //   }
  // }

  // function remove_coupon(){ 
  //   $data = [];
  //   $overallTotal = 0;
  //   //print_r($this->request->getVar()); die;
    
  //   if($this->request->getVar('ccode')) {
  //     $data['ccode'] = $this->request->getVar('ccode');
  //     $data['cvalue'] = $this->request->getVar('coupon_val');

  //     if(isset($_SESSION['cart']) && $_SESSION['ccode']) {
  //       $_SESSION['cart']['ptotal'] = 0;
  //       $_SESSION['cart']['salestax'] = getenv('salestax');
  //       $_SESSION['cart']['processingfees'] = getenv('processingfees');
  //       if($_SESSION['cart']['coupon_type'] == 2) {
  //         $overallTotal = $_SESSION['cart']['total'] + $data['cvalue'] + $_SESSION['cart']['salestax'] + $_SESSION['cart']['processingfees'];
  //       } else {
  //         $overallTotal = $_SESSION['cart']['total'] + $data['cvalue'];
  //       }
  //       //

  //       $_SESSION['cart']['total'] = $overallTotal;
  //       $_SESSION['ccode'] = 0;
  //       $_SESSION['ccodeinfo'] = '';
        
  //     }
  //     if($_SESSION['cart']['coupon_type'] == 2) {
  //       $this->session->setFlashdata('msg', "Voucher removed successfully!");
  //     } else {
  //       $this->session->setFlashdata('msg', "Coupon Code removed successfully!");
  //     }
  //     return redirect()->to('/cart');
  //   } else if(!empty($_SESSION['ccode']) && !empty($_SESSION['cart']['ptotal'])) {
  //     $ccode = $_SESSION['ccode'];
  //     $ptotal = $_SESSION['cart']['ptotal'];
  //     $overallTotal = $_SESSION['cart']['total'] + $ptotal;
  //     $_SESSION['cart']['total'] = $overallTotal;
  //     $_SESSION['ccode'] = 0;
  //     $_SESSION['ccodeinfo'] = '';

  //   } else {  //echo "13"; die;
  //     $this->session->setFlashdata('msg', "Can't able to remove coupon applied!");
  //     return redirect()->to('/cart');
  //   }
  // }

 
//Need Seperate Controller for payment
// function pay()
// { //print_r($this->request->getVar()); die;
// $data = [];
// $data['page_title'] = 'POS - Select Payment Option';
// $data['search_url'] = base_url().'/shows/search';
// $date['delete_url'] = base_url().'/delete_cart';

// $data['categories'] = $this->model->get_categories();
// $data['current'] = 'pay_option';

//     if($this->request->getVar()) {
//         $data['item']['date'] = $this->request->getVar('date');
//         $data['item']['time'] = $this->request->getVar('time');
//         $data['item']['ticket_venue'] = $this->request->getVar('ticket_venue');
//         $data['item']['ticket_location'] = $this->request->getVar('ticket_location');
//         $data['item']['ticket_title'] = $this->request->getVar('ticket_title');
//         $data['item']['tot_qty'] = $this->request->getVar('tot_qty');
//         $data['item']['tot_amount'] = $this->request->getVar('tot_amount');
//         $data['item']['pcount'] = $this->request->getVar('pcount');
//         for($i =1; $i<=$data['item']['pcount']; $i++) { 
//           $data['item']["ticket_type".$i]=$this->request->getVar("ticket_type".$i);
//           $data['item']["qty".$i] = $this->request->getVar("qty".$i);
//           $data['item']["price".$i] = $this->request->getVar("price".$i);
//         }
//         if(!empty($_SESSION['cart']['ptotal']) && $_SESSION['cart']['ptotal'] > 0) {
//           $data['item']['discount_amt'] = $_SESSION['cart']['ptotal'];
//           $data['item']['ccode'] = $_SESSION['ccode'];
//           $data['item']['code_name'] = $_SESSION['ccodeinfo']->code;
//         }
//     }
    
//     $arr = json_encode($data['item']);
//     $data1 = [];
//     $data1['type'] = "Stripe";
//     $data1['site'] = 1;
//     $data1['status'] = 1;
//     $data1['name'] = "Shanmuga Sundaram M";
//     $data1['email'] = "letmetest95@gmail.com";
//     $data1['phone'] = "7012405595";
//     $data1['amount'] = $this->request->getVar('tot_amount');
    
//     $data1['notes'] = $arr;

//     $random = mt_rand(100000000, 999999999);
//     $data1['randid'] = "BSTD".$random;
//     $data1['timestamp'] = date('Y-m-d h:i:a');
    
//     //$success = $this->transaction_model->insert($data1);
    
    

//            return view('payment_options',$data);
//     }

    //Success 
    function pay_success() { 
      $data = [];
      $data['page_title'] = 'POS - Payment Status';
      $data['search_url'] = base_url().'/shows/search';
      $date['delete_url'] = base_url().'/delete_cart';

      $data['categories'] = $this->model->get_categories();
      $data['current'] = 'pay_status';

    //  if($this->request->getVar('pay') == "Submit") {
    //   //$data['coupon_']
    //  }
    }

//     function pay_old() //Original pay
// {
// $data = [];
// $data['page_title'] = 'POS - Select Payment Option';
// $data['search_url'] = base_url().'/shows/search';
// $date['delete_url'] = base_url().'/delete_cart';

// $data['categories'] = $this->model->get_categories();
// $data['current'] = 'pay_option';
// //echo "<pre>"; print_r($_SESSION); "</pre>"; die;
// if($this->request->getVar()) {
//     $data['item']['date'] = $this->request->getVar('date');
//     $data['item']['time'] = $this->request->getVar('time');
//     $data['item']['ticket_venue'] = $this->request->getVar('ticket_venue');
//     $data['item']['ticket_location'] = $this->request->getVar('ticket_location');
//     $data['item']['ticket_title'] = $this->request->getVar('ticket_title');
//     $data['item']['tot_qty'] = $this->request->getVar('tot_qty');
//     $data['item']['tot_amount'] = $this->request->getVar('tot_amount');
//     $data['item']['pcount'] = $this->request->getVar('pcount');

//     for($i =1; $i<=$data['item']['pcount']; $i++) { 
//       $data['item']["ticket_type".$i]=$this->request->getVar("ticket_type".$i);
//       $data['item']["qty".$i] = $this->request->getVar("qty".$i);
//       $data['item']["price".$i] = $this->request->getVar("price".$i);
//     }

//     if(!empty($_SESSION['cart']['ptotal']) && $_SESSION['cart']['ptotal'] > 0) {
//       $data['item']['discount_amt'] = $_SESSION['cart']['ptotal'];
//       $data['item']['ccode'] = $_SESSION['ccode'];
//       $data['item']['code_name'] = $_SESSION['ccodeinfo']->code;
//     }
//     if(!empty($_SESSION['cart']['family_seats'])) {
//       $data['item']['family_seats'] = @$_SESSION['cart']['family_seats'];
//     }
// }
//     //var_dump($data['item']); die;
//     $arr = json_encode($data['item']);
//     $data1 = [];
//     $data1['type'] = "Stripe";
//     $data1['site'] = 1;
//     $data1['status'] = 1;
//     $data1['name'] = "Shanmuga Sundaram M";
//     $data1['email'] = "letmetest95@gmail.com";
//     $data1['phone'] = "7012405595";
//     $data1['amount'] = $this->request->getVar('tot_amount');
//     $data1['cc_type'] = @$_SESSION['ccodeinfo']->type;
//     $data1['cc_total'] =  @$_SESSION['cart']['ptotal'];
//     $data1['cc_code'] = @$_SESSION['ccodeinfo']->code;
//     $data1['cc_discount'] = @$_SESSION['ccodeinfo']->discount;
//     // $data1['service_tax'] = @$_SESSION['cart']['salestax'];
//     // $data1['processing_fees'] = @$_SESSION['cart']['processingfees'];
//     //print_r($data1); die;
//     //$data1['content'] = json_encode($data['item']);
//     $data1['notes'] = $arr;

//     $random = mt_rand(100000000, 999999999);
//     $data1['randid'] = "BSTD".$random;
//     $data1['timestamp'] = date('Y-m-d h:i:a');
//     //echo "<pre>"; print_r($data); "</pre>"; die;
//     $success = $this->transaction_model->insert($data1);
//     //required settings
    
//     if($success) {
//     $result = Builder::create()
//     ->writer(new PngWriter())
//     ->writerOptions([])
//     ->data($data1['randid'])
//     ->encoding(new Encoding('UTF-8'))
//     ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
//     ->size(300)
//     ->margin(10)
//     ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
//     //->logoPath(__DIR__ . '/qr4.png')
//     ->labelText('')
//     ->labelFont(new NotoSans(20))
//     ->labelAlignment(new LabelAlignmentCenter())
//     ->build();
//     ///echo "32"; die;
//     // Directly output the QR code
//     //
//     //
//     if(!empty($_SESSION['cart'])) {
//     unset($_SESSION['cart']);
//     unset($_SESSION['ccodeinfo']);
//     unset($_SESSION['ccode']);
//     }
//     //echo "<pre>"; print_r($_SESSION['_ci_previous_url']); "</pre>"; die;
//     //header('Content-Type: '.$result->getMimeType());
//     //echo $result->getString(); die;
//     //echo "32"; die;
//     // Save it to a file
//     //echo "yes"; die;
//     $filepath = $_SERVER['DOCUMENT_ROOT'].'/pos/public/images/qrcode/';
//     //print_r($filepath); die;
//     $filename = "qrcode_".$random.".png";
//     $result->saveToFile($filepath.$filename);
    
    

//     //$file = __DIR__."/qrcode_".$random.".png";

//     $this->session->setFlashdata('msg', "Ticket Booked Successfully");

//     //Send Email For transaction

//     $msg = new \stdClass();
	
//           //required settings
//           $msg->subject = "My message subject"; //SUBJECT
//           //$msg->textbody = "My text-only message"; //TEXT MSG, NULL IF sending HTML
//           //$msg->htmlbody = NULL; //HTML MSG, NULL if sending TEXT
//           $body = "<!DOCTYPE html>";
//           $body .= "<head><meta charset='utf-8'>";
//           $body .= "<meta name='viewport' content='width=device-width'>";
//           $body .="<link rel='stylesheet' id='font-awesome-css'  href='https://use.fontawesome.com/releases/v5.8.1/css/all.css?ver=5.3.2' media='all' />";
//           $body .="<link href='https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap' rel='stylesheet'></head>";
//           $body .=" <body width='100%' style='margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #fff;'>
//           <center style='width: 100%; background-color: #ccc;'> ";
//           //$body .=" <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: 'Source Sans Pro', sans-serif;'> (Optional) This text will appear in the inbox preview, but not the email body. It can be used to supplement the email subject line or even summarize the email's contents. Extended text preheaders (~490 characters) seems like a better UX for anyone using a screenreader or voice-command apps like Siri to dictate the contents of an email. If this text is not included, email clients will automatically populate it using the text (including image alt text) at the start of the email's body. </div>";
//           //$body .= "<div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: 'Source Sans Pro', sans-serif;'> &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp; </div>";
//           $body .=" <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='650' style='margin: auto;' class='email-container' style='background: #fff;'>";
//           $body .= "<tr><td style='background-color: #f5f5f5;'>";
//           $body .="<table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>";
//           $body .= "<tr><td style='padding:15px; font-family: 'Source Sans Pro', sans-serif; line-height: 25px; color: #555555;' class='thank'> ";
//           $body .= "<h2 style='margin: 0 0; word-break: break-all; margin-bottom:0px;font-size: 35px; text-align:center; font-weight:500;padding:0;
//           color: #000;'>Your Receipt</h2></td> ";
//           $body .= "</tr></table></td></tr>";
//           $body .= "<tr><td style='padding:20px 0;background-color: #fff;'>
//                     <img src='".base_url().'/images/qrcode/'.$filename."' width='600' height='' alt='alt_text' border='0' style='width: 225px;
// max-width: 600px;margin:0 auto;display:block;' class='g-img'>
// </td>
// </tr> ";
// $body .= "<tr>
//     <td style='padding: 15px;background-color: #fff;'>
//         <table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'> ";
//             $body .= "<tr>
//                 <th valign='top' width='100%' class='stack-column-center'> ";
//                     $body .="<table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>";
//                         $body .="<tr>
//                             <td style='font-family: ' Source Sans Pro', sans-serif;' class='center-on-narrow'>
//                                 <h2
//                                     style='font-size: 25px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:0 0 5px;color: #000;'>
//                                     Transaction #".$random." Details
//                                 </h2>";
//                                 $body .="<h4
//                                     style='font-size: 20px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:0 0 5px;color: #000;'>
//                                     Customer Info
//                                 </h4>";
//                                 $body .="<table style='width:100%;'>
//                                     <tr>";
//                                         $body .="<td style='text-align:left; padding:5px;'>
//                                             <p
//                                             style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
//                                                 <i style='display:block;font-weight:normal; font-style:normal;'>Name:</i>
//                                                 <b>".$data1['name']."</b>
//                                             </p>
//                                         </td>";
//                                         $body .="<td style='text-align:left; padding:5px;'>
//                                             <p
//                                             style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
//                                                 <i style='display:block;font-weight:normal; font-style:normal;'>Email:</i>
//                                                 <b>".$data1['email']."</b>
//                                             </p>
//                                         </td>";
                                        
//                                         $body .="</tr><tr>";
//                                         $body .="<td style='text-align:left; padding:5px;'>
//                                             <p
//                                             style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
//                                                 <i style='display:block;font-weight:normal; font-style:normal;'>Phone:</i>
//                                                 <b>".$data1['phone']."</b>
//                                             </p>
//                                         </td>
//                                         <td style='text-align:left; padding:5px;'>
//                                             <p
//                                             style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
//                                                 <i style='display:block;font-weight:normal; font-style:normal;'>Ticket Status:</i>
//                                                 <b>Issued</b>
//                                             </p>
//                                         </td></tr>
//                                 </table>";
//                                 $body .="<h4
//                                     style='font-size: 20px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:0 0 5px;color: #000;'>
//                                     Item Purchased
//                                 </h4>";
//                                 $body .=" <table style='width:100%; border:1px solid #ccc;border-collapse: collapse;'>
//                                     <tr>
//                                         <th style='text-align:left; padding:10px 5px;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                 <b>Item</b>
//                                             </p>
//                                         </th>
//                                         <th style='text-align:left; padding:10px 5px;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                 <b>Qty</b>
//                                             </p>
//                                         </th>
//                                         <th style='text-align:left; padding:10px 5px;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                 <b>Price</b>
//                                             </p>
//                                         </th>
//                                     </tr>";
//                                     //$body .= "";
//                                     for($i =1; $i<=$data['item']['pcount']; $i++) {
//                                               if($data['item']["qty".$i] > 0) {
//                                     $body .= "<tr><td
//                                             style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 ".strtoupper($data['item']["ticket_title"])." - ".$data['item']["date"].", ".$data['item']["time"]."";
//                                          if(strpos($data['item']["ticket_type".$i], 'Family') !== false) {
//                                           $body .= "  <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data['item']["ticket_type".$i]." [".$data['item']['family_seats']."] - Preferred </p>
//                                             </p>";
//                                          } else {
//                                           $body .= "  <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>".$data['item']["ticket_type".$i]." - Preferred </p>
//                                             </p>";
//                                          }
//                                         $body .= "</td>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 ".$data['item']["qty".$i]."
//                                             </p>
//                                         </td>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #eee;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 $".$data['item']["price".$i]."
//                                             </p>
//                                         </td></tr> ";
//                                     } }
//                                         //$body .="";
//                                     if($data1['cc_type'] != 2) {    
//                                     $body .="<tr>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 <i>Tax</i>
//                                             </p>
//                                         </td>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 &nbsp
//                                             </p>
//                                         </td>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 <i>$".getenv('salestax')."</i>
//                                             </p>
//                                         </td>
//                                     </tr> ";
//                                     $body .= " <tr>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 <i>Processing Fees</i>
//                                             </p>
//                                         </td>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                 &nbsp
//                                             </p>
//                                         </td>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 <i>$".getenv('processingfees')."</i>
//                                             </p>
//                                         </td>
//                                     </tr>";
//                                     }
//                                     $body .= " <tr>";
//                                     if($data1['cc_type'] == 2) {
//                                     $body .= "<td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 <i>Voucher Applied - ".$data1['cc_code']."(".$data1['cc_discount'].")</i>
//                                             </p>
//                                             </td>";
//                                     $body .= "<td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                 &nbsp
//                                             </p>
//                                         </td>
//                                         <td
//                                             style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                 <i>$".number_format(@$data1['cc_total'],2)."</i>
//                                             </p>
//                                         </td>";        

//                                     } else if(!empty($data1['cc_code']) && $data1['cc_type'] == 1) {
//                                       $body .= "<td style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                                     <p
//                                                         style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                         <i>Coupon Applied - ".$data1['cc_code']."(".$data1['cc_discount'].")</i>
//                                                     </p>
//                                                 </td>";
//                                       $body .= "<td
//                                                 style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                                 <p
//                                                     style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                     &nbsp
//                                                 </p>
//                                             </td>
//                                             <td
//                                                 style='text-align:left; padding:10px;background-color: #fff;border-bottom:1px solid #ccc;'>
//                                                 <p
//                                                     style='color: #333; font-size:16px !important; line-height:18px; margin:0;font-weight:normal;'>
//                                                     <i>$".number_format(@$data1['cc_total'],2)."</i>
//                                                 </p>
//                                             </td>";          
//                                     } else {
                                      
//                                     }
                                        
//                                     $body .= " </tr>";
//                                     $body .= " <tr>
//                                         <td style='text-align:left; padding:10px;background-color: #fff;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                 <b>Total</b>
//                                             </p>
//                                         </td>
//                                         <td style='text-align:left; padding:10px;background-color: #fff;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                 &nbsp
//                                             </p>
//                                         </td>
//                                         <td style='text-align:left; padding:10px;background-color: #fff;'>
//                                             <p
//                                                 style='color: #333; font-size:16px !important; line-height:18px; margin:0;'>
//                                                 <b>$".$data['item']["tot_amount"]."</b>
//                                             </p>
//                                         </td>
//                                     </tr>";
//                                     $body .= "
//                                 </table>
//                             </td>
//                         </tr>
//                     </table>
//                 </th>
//             </tr>
//         </table>";
//         $body .=" <h4
//             style='font-size: 20px;font-style: normal;font-weight: normal;line-height: 35px;letter-spacing: normal;text-align: left; margin:10px 0 5px;color: #000;'>
//             Payment Details
//         </h4>";
//         $body .= " <table role='presentation' cellspacing='0' cellpadding='0' border='0'
//             style='margin: auto;width: 100%;'>
//             <tr>
//                 <td width='50%' class='stack-column-center'>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Amount:</p>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>$".$data['item']["tot_amount"]."</b>
//                     </p>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Status:</p>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>
//                         <b>authorized</b></p>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Timestamp:</p>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>".date('Y-m-d h:i:s')."</b></p>
//                 </td>
//                 <td width='50%' class='stack-column-center'>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Method:</p>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>amex
//                             **3002</b></p>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'>Message:</p>
//                     <p style='font-size: 16px;line-height: 25px;text-align: left; margin:0;color: #000;'><b>Payment
//                             complete.</b></p>
//                 </td>
//             </tr>
//         </table>";

//         $body .="
//     </td>
// </tr>";
// $body .= " <footer style='background:#fff;'>

//     <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='650'
//         style='margin: auto;' class='email-container' style='background:#fff;'>
//         <tr>
//             <td width='100%' style='border-top:1px solid #eee;background:#fff; text-align:center; padding:10px 0 15px;'>
//                 <p
//                     style='margin:0;color:#0D2030; padding:5px 0; font-weight:normal;font-size: 14px; text-transform:uppercase;'>
//                     Copyright © 2021 POS</p>
//             </td>
//         </tr>
//     </table>

// </footer> ";
// $body .="</center>
// </body>

// </html>";
//         echo $body; die;
//         $msg->htmlbody = $body;
//         $msg->to = array('shanmugamizme2165@gmail.com','Shanmugam'); //TO
//         $msg->from = array(getenv('fromaddress'),getenv('fromname')); //FROM

//         //optional settings
//         //$msg->reply_to = array('address@site.com','XYZ Company'); //REPLY TO
//         //$msg->cc = array('address2@site.com','Someone'); //CC
//         //$msg->bcc = array('address3@site.com','Somebody Else'); //BCC
//         $msg->track_clicks = TRUE; //TRACK CLICKS, TRUE by default
//         $msg->track_opens = TRUE; //TRACK OPENS, TRUE by default
//         $msg->client_reference = NULL; //CLIENT ID (string)
//         $msg->mime_headers = NULL; //ADDITIONAL MIME HEADERS (array)
//         $msg->attachments = NULL; //ATTACHMENTS (array)
//         $msg->inline_images = NULL; //INLINE IMAGES (array)

//         //instantiate library and pass info
//         $tmail = new \Transmail\TransmailClient($msg,getenv('transmailkey'),
//         getenv('transbounceaddr'), TRUE);

//         //send the message
//         $response = $tmail->send();

//         if ($response)
//         {
//           $this->session->setFlashdata('msg', "Mail Sended Successfully");
//           return redirect()->to('/shows');
//         }
//         else
//         {
//           $this->session->setFlashdata('msg', "Can't Send Mail!!!");
//           return redirect()->to('/shows');
//         }

//          return redirect()->to('/shows');
//     } else {
//           $this->session->setFlashdata('msg', "Transaction Failed!");
//     }

//            return view('payment_options',$data);
//     }

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

  
}