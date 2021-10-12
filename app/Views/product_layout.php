<?= $this->extend('layouts/main'); ?>
<?= $this->section('content');  ?>
<section class="content-part pb-4">
   <div class="banner-section">
      <div class="container-fluid">
         <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
               <div class="carousel-item active"> <img
                  src="<?= base_url().'/images/'.$content->pageinfo->image3; ?>" alt="" />
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="product-details bg-white">
      <div class="container pt-5 pb-5">
         <form id="<?= 'ticketform'.str_replace(':','',$instances[0]->start_time)?>" method="post"
            action="<?= $form_action; ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="time" value="<?= date("g:i a", strtotime($instances[0]->start_time)); ?>">
            <input type="hidden" name="date" value="<?= date(" M j, Y", strtotime($date)); ?>">
            <input type="hidden" name="rtime" id="time" value="<?= $instances[0]->start_time; ?>">
            <input type="hidden" name="rdate" value="<?= $date; ?>">
            <input type="hidden" name="content" value="<?= $showid; ?>">
            <input type="hidden" name="priceset" value="<?= $instances[0]->pricesets; ?>">
            <input type="hidden" name="pcount" value="<?= $pcount; ?>">
            <input type="hidden" name="showid" value="<?= $showid; ?>">
            <input id="<?= 'seatpicker'.str_replace(':','',$instances[0]->start_time)?>" type="hidden"
               name="location" value="">
            <?php
               $otr ='';
               $actopt = 0; ?>
            <div class="row">
               <div class="col-lg-6 col-md-12">
                  <div class="col-lg-4 col-md-6 pb-4">
                     <a href="<?= $go_back; ?>" class="btn btn-primary">Go Back</a>
                  </div>
                  <div class="ticket-details">
                     <h4>Purchase Tickets for
                        the <?= date("g:i a", strtotime($instances[0]->start_time)); ?> Show on
                        <?= date("M j, Y", strtotime($date)); ?>
                     </h4>
                     <?php  $btn = 0; $btncnt = count($listingoptions['opts']); foreach ($listingoptions['opts'] as $key => $value){  
                        $scr = strtolower(str_replace(" ","-",$value->optionname));
                        //$pss[$value->priceset] = $value->priceset;
                        $thisps = explode(",",$value->pricesets);
                        
                        $actualps = 0;
                        foreach ($thisps as $possibleps){
                        if (isset($pricesets[$possibleps])){
                            $actualps = $possibleps;
                        }
                        }
                        
                        if (isset($exceptions[$instances[0]->start_time][$value->id])){
                        $dis = " disabled"; $dist = " (".$exceptions[$instances[0]->start_time][$value->id].")";
                        $forsrc .= '$("#sc-'.$scr.'").removeClass("sc-default").addClass("sc-disabled");
                        ';
                        } elseif (isset($exceptions[$instances[0]->start_time][0])){
                        $dis = " disabled"; $dist = " (".$exceptions[$instances[0]->start_time][0].")";
                        $forsrc .= '$("#sc-'.$scr.'").removeClass("sc-default").addClass("sc-disabled");
                        ';
                        } elseif (!$value->pricesets){
                        $dis = " disabled"; $dist = " (not currently avail. online)";
                        $forsrc .= '$("#sc-'.$scr.'").removeClass("sc-default").addClass("sc-disabled");
                        ';
                        } else {
                        $dis = ""; $dist="";
                        $actopt++;
                        }
                        //sat flag if options are in overall instance settings
                        //$data['listingoptions']['opts']
                        $psact = FALSE;
                        
                        foreach ($thisps as $eachps){
                        if (isset($pricesets[$eachps])){
                        $psact = TRUE;
                        }
                        }
                        //print_r($psact); die;
                        if ($psact){ 
                                if($key == 0 || ($key % 3) == 0) {
                                $otr .=  '<div class="tsec">'; 
                                }
                                $otr .='<span class=""><button type="button" onclick="myFunction();" class="button'.str_replace(':','',$instances[0]->start_time).'" data-id="sc-'.$scr.'" data-ps="'.$actualps.'" value="'.$scr.'"'.$dis.'>'.$value->optionname.$dist.'</button></span>';
                                $btn++;
                                
                                
                                if($btn != 0 && ($btn % 3) == 0) {
                                $otr .=  '</div>'; 
                                } 
                                
                               if($key == $btncnt-1) { 
                                    $otr .=  '</div>';   
                                    } 
                        }
                        //
                        } ?>
                     <div class="step1-ticket">
                        <h2>Step 1: Select A Section</h2>
                        
                        <div class="step1-ticket">
                           <?= $otr; ?>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-success" id="tickets">Select Tickets</button>
                     <div class="step2-ticket">
                        <table class="table mt-4" style="border:none;">
                           <tr>
                              <th>
                                 <h4>Regular Seating</h4>
                              </th>
                              <th>
                                 <h4>Pricing</h4>
                              </th>
                           </tr>
                           <?php for($x = 1; $x <= $pcount; $x++) { ?>
                           <tr>
                              <td>
                                 <h6><?= $price_type[$x]; ?></h6>
                              </td>
                              <?php if($price_seg[$x] > 0) { ?>
                              <td><strong>$<?= $price_seg[$x] ?></strong></td>
                              <?php } else { ?>
                              <td><strong>$0.00</strong></td>
                              <?php } ?>
                           </tr>
                           <?php } ?>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6 col-md-12">
                  <div class="product-seats">
                     <div id="<?= 'diagram'.str_replace(':','',$instances[0]->start_time)?>"
                        style="margin: 20px auto; max-width: 500px;">
                        <h4><?= $listingoptions['venues'][0]->name; ?></h4>
                        <?= str_replace("venuesection", "venuesection".str_replace(":","",$instances[0]->start_time),$listingoptions['venues'][0]->diagram); ?>
                     </div>
                  </div>
               </div>
               
            </div>
            <input type="hidden" name="venueid" value="<?= $listingoptions['venues'][0]->id; ?>">
         </form>
      </div>
   </div>
   <script>
      function myFunction() {
        document.getElementById("tickets").style.display = "block";
      }
   </script>
</section>
<?= $this->endSection(); ?>