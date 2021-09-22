<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('css/H-confirm-alert.css'); ?>">
    <script src="<?= base_url('js/H-confirm-alert.js'); ?>" ></script>
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
            <input type="hidden" name="venue" value="<?= $venueid; ?>">
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
                                    <input type="hidden" id="total_price" name="total_price"
                                          value="0.00">
                                    <input type="hidden" id="seats" name="family_seats" value="0">
                                    <input type="hidden" id="total_qty" name="total_qty" value="0">
                                    <input type="hidden" id="tot_qty" name="tot_qty" value="0">
                                    <?php $regex = []; for($x = 1; $x <= $pcount; $x++) { ?>
                                    <tr>
                                       <td>
                                          <h6><?= $price_type[$x]; ?></h6>
                                       </td>
                                       <td><strong>$<?php $regex[$x] = ltrim($price_seg[$x],'$');
                                          if($regex[$x] > 0) { echo $regex[$x] ;} else { echo "0.00"; }  ?></strong>
                                       </td>
                                       <td>
                                          <div class="qty-input" id="<?= $x; ?>">
                                             
                                             <button type="button" id="sub<?= $x; ?>" class="sub"
                                                onclick='calculateTicketPriceTotal(<?= $regex[$x]; ?>, "qty<?= $x; ?>", "sub")'
                                                ;>-</button>
                                             <input type="number" id="qty<?= $x; ?>" 
                                                min="1" max="10" name="qty<?= $x; ?>" value="0" readonly />
                                             <button type="button" id="add<?= $x; ?>" class="add"
                                                onclick='calculateTicketPriceTotal(<?= $regex[$x]; ?>, "qty<?= $x; ?>", "add")'
                                                ;>+</button>
                                             <?php if(isset($no_of_seats[$x])) { ?>
                                             <input type="number" id="seats<?= $x; ?>" class="seat_val"
                                                value="" min="1" max="10" name="seats<?= $x; ?>"/>
                                             <?php } else { ?>
                                                <input type="number" id="seats25" class="seat_val"
                                                value="0" min="0" max="10" name="seats25" style="display:none;"/>
                                             <?php }   ?>
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
                              <button type="button" class="btn btn-primary" id="cart_btn">Add to Cart</button>
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


var totalQty =0;
 var family_seat = 0;
 var totQty = 0;
 var k=0;
function calculateTicketPriceTotal(ticketPrice, ticketQtyReferer, type, id) {
    const qtyMin = $("#" + ticketQtyReferer).attr("min");
    const qtyMax = $("#" + ticketQtyReferer).attr("max");
    //alert('helo');
    //console.log(qtyMax, "qtyMax");
    //console.log(ticketPrice, "ticketPrice");
   // console.log(ticketQtyReferer, "ticketQtyReferer");
    //console.log(type, "type");

    const prevTotal = $("#total_price").val();
    const formattedPrevTotal = parseFloat(prevTotal);
    const formattedTicketPrice = parseFloat(ticketPrice);

    const currentQty = $("#" + ticketQtyReferer).val();
    const formattedCurrentQty = parseFloat(currentQty);
   // console.log(currentQty, "currentQty");

    let newQty = 0;
    let total = 0;
    if (type === "add") {
        newQty = formattedCurrentQty + 1; k++;
        total = formattedPrevTotal + formattedTicketPrice;
        totalQty = totalQty + 1; 
        console.log(newQty + "Max - " + qtyMax);
        //Check max limit
        if (newQty > qtyMax) { 
            $("#add{id}").attr("disabled", true);
        }
    }
    if (type === "sub") { k--;
        if (currentQty <= 0) {
            return
        }
        totalQty = totalQty - 1;
        if (currentQty <= qtyMax) {
            $("#add" + id).attr("disabled", false);
        }

        newQty = formattedCurrentQty - 1;
        total = formattedPrevTotal - formattedTicketPrice;
    }
    
    
    
    $("#" + ticketQtyReferer).val(newQty)
    console.log("Total Seats1", totalQty);
    $("#total_qty").val(totalQty);
    if($("#seats").val() > 0) {
        var seats_value = parseInt($("#seats").val());
        var tot = parseInt(totalQty) + parseInt(seats_value);
        $("#tot_qty").val(parseInt(tot));
    } else {
        $("#tot_qty").val(totalQty);
    }
    
    
    $("#total_price").val(total.toFixed(2));
    $("#td_total").html(total.toFixed(2));
}

$(document).ready(function() {
    $(".seat_val").on("keyup change", function(e) {
        const seat = $(this).val();
        const max = parseFloat($(this).attr('max'));
        
        if(isNaN($(this).val())) {
            family_seat = 0; 
        } else {
            family_seat = $("#seats").val();
        }
        if(family_seat > 0) {
            totQty = (parseInt($("#total_qty").val()) + parseInt(family_seat) - 1);
        } else {
            totQty = (parseInt($("#total_qty").val()) + parseInt(family_seat)); 
        }
       // totQty = (parseInt($("#total_qty").val());
       
        console.log("Total Seats2", parseInt(totQty));
        
        $("#seats").val(seat);
        if (seat > max) {
            $(this).val('0');
            $("#seats").val('0');
        }
        //console.log(family_seat, "Family");
        $("#tot_qty").val(totQty);
        
    });

   $('#cart_btn').on('click', function(){
     
      if($('#tot_qty').val() >= 1) {
          $("#ticketForm").submit();

      } else {
         $.confirm.show({
            "message":"Select Some Ticket Option to proceed!",
            "hideNo":true,// hide cancel button
            "yesText":"OK",
            "noText":"CANCEL",
         })  
      }
   });
});

</script>
<?= $this->endSection(); ?>