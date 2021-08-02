<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<section class="content-part pb-4">
   <div class="banner-section">
      <div class="container-fluid">
         <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
               <div class="carousel-item active"> <img
                  src="<?= base_url()."/images/".$content_detail[0]->image3; ?>" alt="" /> </div>
            </div>
         </div>
      </div>
   </div>
   <div class="cart-title pt-4 pb-4">
      <div class="container">
         <div class="row">
            <div class="col-12 text-center"></div>
         </div>
      </div>
   </div>
   <div class="booking-details bg-white">
      <div class="container pt-4 pb-5">
         <form method="POST" action="<?= $form_action; ?>" id="ticketForm">
            <?= csrf_field(); ?>
            <input type="hidden" name="location" value="<?= $location; ?>">
            <input type="hidden" name="time" value="<?= $time; ?>">
            <input type="hidden" name="date" value="<?= $rdate; ?>">
            <input type="hidden" name="priceset" value="<?= $priceset; ?>">
            <input type="hidden" name="taxinclusive" value="<?= $get_price_details[0]->taxinclusive; ?>">
            <input type="hidden" name="feesinclusive" value="<?= $get_price_details[0]->feesinclusive; ?>">
            <input type="hidden" name="content" value="<?= $showid; ?>">
            <input type="hidden" name="venue" value="2563">
            <input type="hidden" name="pcount" id="pcount" value="<?= $pcount; ?>">
            <input type="hidden" name="referrer" value="<?= $go_back; ?>">
            <div class="row">
               <div class="col-lg-4 col-md-6 pb-4">
                  <a href="<?= $go_back; ?>" class="btn btn-primary">Go back</a>
               </div>
               <div class="col-lg-12">
                  <h2>Step 2: Select Number of Tickets</h2>
                  <div class="row">
                     <div class="col-lg-12 col-md-12">
                        <div class="ticket-book mb-3">
                           <div class="row mb-3">
                              <div class="col-lg-12 col-md-6">
                                 <table class="table mt-4" style="border:none;">
                                    <tr>
                                       <th>
                                          <h5>Regular Seating</h5>
                                       </th>
                                       <th>Price/Qty</th>
                                       <th>
                                          <h5>Quantity</h5>
                                       </th>
                                    </tr>
                                    <?php $regex = []; for($x = 1; $x <= $pcount; $x++) { ?>
                                    <tr>
                                       <td>
                                          <h6><?= $price_type[$x]; ?></h6>
                                       </td>
                                       <td><strong>$<?php $regex[$x] = ltrim($price_seg[$x],'$');
                                          if($regex[$x] > 0) { echo $regex[$x] ;} else { echo "0.00"; }  ?></strong>
                                       </td>
                                       <td>
                                          <div class="qty-input">
                                             <button type="button" id="sub<?= $x; ?>" class="sub"
                                                onclick='calculateTicketPriceTotal(<?= $regex[$x]; ?>, "qty<?= $x; ?>", "sub", <?= $x; ?>)'
                                                ;>-</button>
                                             <input type="number" id="qty<?= $x; ?>" readonly value="0"
                                                min="1" max="10" name="qty<?= $x; ?>" />
                                             <button type="button" id="add<?= $x; ?>" class="add"
                                                onclick='calculateTicketPriceTotal(<?= $regex[$x]; ?>, "qty<?= $x; ?>", "add", <?= $x; ?>)'
                                                ;>+</button>
                                             <?php if(isset($no_of_seats[$x])) { ?>
                                             <input type="number" id="seats<?= $x; ?>" class="seat_val"
                                                value="0" min="0" max="10" name="seats<?= $x; ?>" />
                                             <?php } ?>
                                             <span id="errorMsg" style="display:none; color:red;">Select atleast one ticket</span>
                                          </div>
                                          <input type="hidden" id="type<?= $x; ?>-price"
                                             name="type<?= $x; ?>_price" value="<?= $regex[$x]; ?>">
                                          <input type="hidden" id="type<?= $x; ?>-desc"
                                             name="type<?= $x; ?>_desc" value="<?= $price_type[$x]; ?>">
                                       </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                       <td>
                                          <h6>Total Price</h6>
                                       </td>
                                       <td><strong>$<span id="td_total">0.00</span></strong></td>
                                       <td></td>
                                       <input type="hidden" id="total_price" name="total_price"
                                          value="0.00">
                                       <input type="hidden" id="seats" name="family_seats" value="0">
                                    </tr>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-lg-12 col-md-12 text-center">
                              <!--<button type="button" class="btn btn-primary" id="btn_name">Total:
                                 $50</button>-->
                           </div>
                        </div>
                     </div>
                     <div class="ticket-btn mb-3">
                        <div class="row">
                           <div class="col-lg-3 col-md-6 text-center black-btn"></div>
                           <div class="col-lg-6 col-md-6 text-center">
                              <button type="submit" class="btn btn-primary" id="cart_btn">Add to Cart</button>
                           </div>
                           <div class="col-lg-3 col-md-6 text-center black-btn"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
   </div>
</section>
<script>

$(document).on('submit','#ticketForm',function(){
    if(($('#qty1').val()) <= 0){
        $('#errorMsg').show();
        return false;
    } else{
        $('#errorMsg').hide();
        form.submit();
    }
});
</script>
<?= $this->endSection(); ?>