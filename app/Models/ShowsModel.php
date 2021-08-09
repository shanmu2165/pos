<?php
namespace App\Models;
use CodeIgniter\Model;

class ShowsModel extends Model {
    protected $table      = 'content';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    function get_all_shows($ids=NULL) {
        $db      = \Config\Database::connect();
        if(!empty($ids)) { 
            $query = $db->query("SELECT id,title,image,summary FROM content WHERE type='listing' AND `id` IN ($ids) ORDER BY title ASC");
        } else { 
            $query = $db->query("SELECT id,title,image,summary FROM content WHERE type='listing' ORDER BY title ASC");
        }
        $content = $query->getResult();
        return $content;
    }

    function get_show($id) {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT id,title,body,summary,image,image2,image3 FROM content WHERE id=".$id);
        $content = $query->getResult();
        return $content;
    }

    function get_show_data($val) {
        $db      = \Config\Database::connect();
        if(is_int($val)) { 
            $query = $db->query("SELECT * FROM content WHERE id=".$val);   
        } else {  
            $query = $db->query("SELECT * FROM content WHERE path=".$val);
        }
        
        $content = $query->getResult();
        //print_r($content[0]); die;
        $pageinfo = new \stdClass();
        $pageinfo->pageinfo = $content[0];
        $pageinfo->preblocks = false;
        $pageinfo->blocks = false;
        return $pageinfo;
    }

    function show_other_details($id,$type=NULL) {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT * FROM instances WHERE content=".$id);  
        if(!empty($type)) {
            $content = $query->getResultArray();  
        } else {
            $content = $query->getResult();
        }
        
        return $content;
        
    }

    function show_other_details_datewise($id) {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT * FROM instances WHERE content=".$id." AND start_date >= CURDATE()");  
        $content = $query->getResult();
        return $content;
        
    }

    function get_price_detail($id) {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT MAX(price1) AS adult_price, MAX(price2) AS child_price FROM pricesets WHERE content=".$id);
        $content = $query->getResult();
        return $content;
    }

    function get_searched_show($search=NULL,$filter=NULL,$ids=NULL) {
        $db      = \Config\Database::connect();
        if(!empty($search) && !empty($ids)) {
            $query = $db->query("SELECT id,title,image,summary FROM content WHERE type='listing' AND title LIKE '%".$search."%' AND `id` IN ($ids) ORDER BY title ASC");
        } else if(!empty($search)) {
            $query = $db->query("SELECT id,title,image,summary FROM content WHERE type='listing' AND title LIKE '%".$search."%' ORDER BY title ASC");
        }
        else if (!empty($filter) && !empty($ids)) { $filter = str_replace('_',' ',$filter); 
            $query = $db->query("SELECT id,title,image,summary FROM content WHERE type='listing' AND terms LIKE '%".$filter."%' AND `id` IN ($ids) ORDER BY title ASC");
        } else {
            $query = $db->query("SELECT id,title,image,summary FROM content WHERE type='listing' AND terms LIKE '%".$filter."%' ORDER BY title ASC");
        }
        //print_r($query->getResult()); die;
        $content = $query->getResult();
        return $content;
    }

    function get_categories() {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT id,term,icon FROM terms WHERE type='listings' ORDER BY term ASC");
        $content = $query->getResult();
        return $content; 
    }

    function get_individual_date($val=NULL,$date=NULL) {
        $db      = \Config\Database::connect();
       
        //query any schedule instances for this listing
        $dow = date("w", strtotime($date)) + 1; //this date's day of the week

        $query = $db->query("SELECT * FROM instances WHERE content=".$val." AND start_date <='".$date."' AND end_date >= '".$date."' AND recurrence LIKE '%".$dow."%' ORDER BY start_time,start_date ASC");
        $content = $query->getResult();
        return $content;
    }

    function pricesets($url=NULL,$content=NULL){
        $db      = \Config\Database::connect(); 
        if(!empty($content)) {
            $query = $db->query("SELECT * FROM pricesets WHERE content='".$content."' ORDER BY defaultset DESC,id ASC");
        } else {
            $query1 = $db->query("SELECT * FROM content WHERE path='".$url."' ");
            $get_dta = $query1->getRow();

            $query = $db->query("SELECT * FROM pricesets WHERE content='".$get_dta->id."' ORDER BY defaultset DESC,id ASC");

        }
           
        $tr = array();
        foreach ($query->getResult() as $key=>$value){
            $tr[$value->id] = $value;
        }

        return $tr;
    }

    function get_options($content) {

    $db      = \Config\Database::connect();
    $query = $db->query("SELECT * FROM priceoptions WHERE content='".$content."' ORDER BY optionname ASC");
    $content = $query->getResult();
    return $content; 
    }

    function get_venue($id) {

        $db      = \Config\Database::connect();
        $query = $db->query("SELECT * FROM venues WHERE id='".$id."' LIMIT 1");
        $content = $query->getResult();
        return $content; 
    }

    function get_price_exception($content,$date) {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT * FROM priceoption_exceptions WHERE content='".$content."' AND date='".$date."' ORDER BY time ASC");
        $content = $query->getResult();
        return $content;
    }

    function get_price_data($id) {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT * FROM pricesets WHERE id='".$id."'");
        $content = $query->getResult();
        return $content;
    }

    function structured_markup($content, $thisdate, $thistime, $pricesets=NULL) {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT * FROM venues WHERE id='".$content->venue."' LIMIT 1");
        $cont = $query->getResult();
        $venue = '';


        foreach($cont as $value) {
         $venue =  $value;  
        }
        //print_r($venue); die;
        $endtime = date("H:i:s", strtotime($thistime." +2 hours"));
        $title = explode(" - ", $content->title);
        $thispage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $toreturn ='
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Event",
    "name": "'.$title[0].'",
    "startDate": "'.$thisdate.'T'.$thistime.'",
    "endDate": "'.$thisdate.'T'.$endtime.'",
    "location": {
      "@type": "Place",
      "name": "'.$venue->name.'",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "'.$venue->address.'",
        "addressLocality": "'.$venue->city.'",
        "postalCode": "'.$venue->zip.'",
        "addressRegion": "'.$venue->state.'",
        "addressCountry": "US"
      }
    },
    "image": [
      "https://'.$_SERVER['SERVER_NAME'].'/files/'.$content->id.'/'.$content->image.'"
    ],
    "description": "'.strip_tags($content->summary).'",
    "performer": {
      "@type": "PerformingGroup",
      "name": "'.$title[0].'"
    }';

    foreach ($pricesets as $priceset){
        if ($priceset->price1 && $priceset->sale1){
            $toreturn .= '
            "offers": {
                "@type": "Offer",
                "availability": "http://schema.org/InStock",
                "priceCurrency": "USD",
                "validFrom": "'.date("Y-m-d").'",
                "price": "'.str_replace("$", "", $priceset->sale1).'",
                "url": "'.$thispage.'"
            }';
        } elseif ($priceset->price1){
        $toreturn .= '
            "offers": {
                "@type": "Offer",
                "availability": "http://schema.org/InStock",
                "priceCurrency": "USD",
                "validFrom": "'.date("Y-m-d").'",
                "price": "'.str_replace("$", "", $priceset->price1).'",
                "url": "'.$thispage.'"
            }';
        }

    }
    $toreturn .='
    }
    </script>';
     return $toreturn;
    }

    function getTax() {
        $db      = \Config\Database::connect();
        $query = $db->query("SELECT value1 FROM sitesettings WHERE setting='tax1' AND site='1' LIMIT 1");
        $content = $query->getResult();
        return $content; 
    }

    function get_coupon_code($code) {
        $db      = \Config\Database::connect();
        $today = date("Y-m-d");
        $lcode = strtolower($code);
        
        $query = $db->query("SELECT * FROM discounts WHERE lower(code)='".$lcode."' AND site='1' AND start_date <='".$today."' AND end_date >='".$today."' LIMIT 1");
        $content = $query->getResult();
        return $content; 
    }
}