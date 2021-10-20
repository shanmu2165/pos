<?php
namespace App\Controllers;

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
      $this->db = \Config\Database::connect();
      if(isset($_SESSION['user_id'])) { 
        $this->user_detail = $this->user->find($_SESSION['user_id']);
      }
    }

    //Home Page
    function index() {
      //echo "<pre>"; print_r($_SESSION);"</pre>"; die; 
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
        //print_r($data['all_shows']); die;
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
       //echo "<pre>"; print_r($data); "</pre>"; die;
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

    //Function for seatings
    function seating() {
      //echo "<pre>"; print_r($_POST); "</pre>"; die;

      $data = [];
      $this->session->set('quantity_details',$_POST);
      
      $data['time'] = $this->request->getVar('time');
      $data['rdate'] = $this->request->getVar('date');
      $data['content'] = $this->request->getVar('content');
      $data['priceset'] = $this->request->getVar('priceset');
      $data['location'] = $this->request->getVar('location');
      $data['showid'] = $this->request->getVar('showid');
      $data['pcount'] = $this->request->getVar('pcount');
      $data['venueid'] = $this->request->getVar('venue');
      $data['total_price'] = $this->request->getVar('total_price');
      $data['family_seats'] = $this->request->getVar('family_seats');
      $data['total_seats_sel'] = $this->request->getVar('tot_qty');
      
      $data['page_title'] = 'POS - Ticket Booking';
      $data['search_url'] = base_url().'/shows/search';
      $data['form_action'] = base_url().'/cart';
      $data['current'] = 'seats';
      $data['referrer'] = current_url();
      $data['go_back'] = $this->request->getUserAgent()->getReferrer();
     
      $data['categories'] = $this->model->get_categories();
      $unlock_seats = $this->model->unlock_free_seats();
      
      $data['venue_sec'] = $this->model->get_venue_section($data['venueid'],$data['location']); 
      //print_r($data['venue_sec'][0]->total_rows); die;
      if(!empty($data['venue_sec'])) {
        $data['seats'] = $this->model->get_seats($data['venueid'],$data['venue_sec'][0]->id); 
        $sec = ucfirst($data['location']);
        @$data['already_booked_data'] = $this->model->get_already_booked($data['content'],$data['venueid'],$data['rdate'],$data['time'],$sec);
        //
        $already_count = count($data['already_booked_data']);
        for($i = 0; $i < $already_count; $i++) {
          $data['already_booked'][$i] = $data['already_booked_data'][$i]->seatrow."-".$data['already_booked_data'][$i]->seat;
        }
        //echo "<pre>"; print_r($data['already_booked']); "</pre>"; die;
      } else {
        $data['seats'] = [];

      }
      
      $data['total_rows'] = $data['venue_sec'][0]->total_rows;
      $data['avail'] = [];
      $data['row_names'] = $this->model->get_row_names($data['venue_sec'][0]->id);
      $pr = 0;
      $data['row_seat_count'] = [];
      foreach($data['row_names'] as $key => $val) {
        $data['seats_prow'][$pr] = $val."-".$this->model->get_row_count($data['venue_sec'][0]->id,$val);
        $data['seat_count_row'] = $this->model->get_row_count($data['venue_sec'][0]->id,$val);
        array_push($data['row_seat_count'],$data['seat_count_row']);
        $data['rowSeatCount']=array_combine(range(1, count($data['row_seat_count'])), $data['row_seat_count']);
        $data['rowNames']=array_combine(range(1, count($data['row_names'])), $data['row_names']);

        $pr++;
      }
      //for($())
      //echo "<pre>"; print_r($data['rowNames']); "</pre>"; die;
      return view('product_seating',$data);
    }
    //Function for show ticket selection
    function ticket_booking() {
      //print_r($this->request->getUserAgent()->getReferrer()); die;
      
      $data = [];
      $_SESSION['ccancel_url'] = $this->request->getUserAgent()->getReferrer();
      $data['time'] = $this->request->getVar('rtime');
      $data['rdate'] = $this->request->getVar('rdate');
      $data['content'] = $this->request->getVar('content');
      $data['priceset'] = $this->request->getVar('priceset');
      $data['location'] = $this->request->getVar('location');
      $data['showid'] = $this->request->getVar('showid');
      $data['pcount'] = $this->request->getVar('pcount');
      $data['venueid'] = $this->request->getVar('venueid');
      $data['venue_details'] = $this->model->get_venue($data['venueid']);
      
      $data['page_title'] = 'POS - Ticket Booking';
      $data['search_url'] = base_url().'/shows/search';
      $_SESSION['chart_type'] = @$data['venue_details'][0]->chart_type;
      if($_SESSION['chart_type'] == 'seats') {
        $data['form_action'] = base_url().'/seatings';
        $data['sectype'] = 1;
      } else {
        $data['form_action'] = base_url().'/cart';
        $data['sectype'] = 2;
      }
      
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

    function lock_tickets(){
      //echo "<pre>"; print_r($_POST); "</pre>"; die;
      $time = time();
      //Locked for 10mins
      $end_time = $time + 600;
      $total_count = count($_POST['seat_arr']);
      
      if(!empty($_POST['seat_arr'])) {
        for($i = 0; $i < $total_count; $i++) {
          $row = explode("-",$_POST['seat_arr'][$i]);
          $data = [
            'status' => 2,
            'content' => $_POST['content'],
            'venue' => $_POST['venue'],
            'date' => $_POST['date'],
            'time' => $_POST['time'],
            'section' => $_POST['section'],
            'seatrow' => $row[0],
            'seat' => $row[1],
            'reservedat' => date('Y-m-d h:i:a'),
            'created_time' => $time,
            'end_time' => $end_time
          ];
          
          $arr[] = $data;
          $this->session->set('cart_details',$_POST);
        }
        //print_r($_SESSION['cart_details']); die;
        $ins = $this->db->table('seats');
		    $ins->insertBatch($arr);


      } else {
        return false;
      }
      

    }

    function check_selectedseats_booked() {
      $data = [];
      
      if(!empty($_POST['section'])) {
      $data['check_seats'] = $this->model->check_individual_seat_booked($_POST['content'],$_POST['venue'],$_POST['date'],$_POST['time'],$_POST['section'],$_POST['seat_arr']);
      }
      //print_r($data['check_seats']); die;
      return json_encode($data['check_seats']);
    }

    function qrcode_reader() {
      $data = []; 
      $data['page_title'] = 'POS - Qrcode Reader';
      $data['categories'] = $this->model->get_categories();
      $data['buy_url'] = base_url().'/show/';
      $data['search_url'] = base_url().'/shows/search';
      $data['current'] = 'qrcodereader';
      $data['today'] = date("Y-m-d");

      return view('qrcode_reader',$data);
    }

    function print_pdf(){
      $mpdf = new \Mpdf\Mpdf();
      $html = view('pdf_view',[]);
      //echo $html; die;
      $mpdf->WriteHTML($html);
      $this->response->setHeader('Content-Type', 'application/pdf');
      //$mpdf->Output('arjun.pdf','I'); // opens in browser
      $mpdf->Output('test.pdf','D');
    }
}