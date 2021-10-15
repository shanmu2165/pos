<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); 
?>
 <link rel="stylesheet" href="<?= base_url('css/H-confirm-alert.css'); ?>">
    <script src="<?= base_url('js/H-confirm-alert.js'); ?>" ></script>
<section class="content-part pb-4">
    <div class="banner-section">
        <div class="container-fluid">
            <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <?php
if (!empty($content_detail[0])) {
?>
                        <img src="<?= base_url() . "/images/" . $content_detail[0]->image3; ?>" alt="cart-image" />
                        <?php
}
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cart-title pt-4 pb-4">
        <div class="container">
            <div class="row"></div>
        </div>
    </div>
    <div class="cart-details bg-white">
        <div class="container pt-4 pb-5">
            <div class="row">
                <div class="col-lg-12 relative">
                    <div class="row">
                        <!-- <div class="col-lg-4 col-md-6 pb-4">
                            <a href="<?= @$go_back; ?>" class="btn btn-primary">Go back</a>
                        </div> -->
                        <?php if (!empty($_SESSION['cart']['item'])) { ?>
                            <div id="timer"><i class="far fa-clock" style="font-size: 25px;"></i><span id="demo"></span></div>
                        <?php } ?>
                        
                        
                        <div class="col-lg-6 col-md-6">
                            <h2>Step 3: My Cart & Apply Coupon</h2>
                        </div>
                        
                        <div class="col-lg-6 col-md-6">
                            <?php
if (session("msg")) {
?>

                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?php
    echo session("msg");
?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php
}
?>
                            <form method="POST" action="<?= base_url() . '/cart/apply_coupon'; ?>">
                                <p class="row">

                                    <span class="col-8">
                                        <?= csrf_field() ?>
                                        <input type="text" name="ccode" class="form-control" placeholder="Enter Coupon/Voucher Code"
                                            required />
                                    </span><span class="col-4 p-0">
                                        <input type="submit" class="form-control" value="Apply" />
                                    </span>

                                </p>
                            </form>
                        </div>
                    </div>
                    <form method="POST" id="pay_form" action="<?= base_url() . '/transactions/'; ?>">
                        <?= csrf_field() ?>
                        <table class="table table-xs">

                            <tr>
                                <th>Item</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Each</th>

                                <!-- <th class="text-right">Delete</th> -->
                                <th class="text-right">Total</th>
                            </tr>

                            <input type="hidden" name="date"
                                value="<?= date('M d, Y', strtotime(@$_SESSION['cart']['item'][1]['date'])); ?>">
                            <input type="hidden" name="time"
                                value="<?= date('h:i a', strtotime(@$_SESSION['cart']['item'][1]['date'] . " " . @$_SESSION['cart']['item'][1]['time'])); ?>">

                            <input type="hidden" name="ticket_location"
                                value="<?= @$_SESSION['cart']['item'][1]['location']; ?>">
                            <input type="hidden" name="ticket_venue"
                                value="<?= @$_SESSION['cart']['item'][1]['venue']; ?>">
                            <?php
if (!empty($_SESSION['cart']['item'])) {
?>
                            <input type="hidden" name="pcount" value="<?php
    echo count($_SESSION['cart']['item']);
?>">

                            <?php
}
$total   = 0;
$name    = '';
$tot_qty = 0;
if (!empty($_SESSION['cart']['item'])) {
    foreach ($_SESSION['cart']['item'] as $key => $val) {
        if ($val['qty'] > 0) {
            $total = $val['qty'] * $val['price'];
            $tot_qty += $val['qty'];
?>
                            <tr class="item-row">
                                <td class="text-right" title="Desc" id="desc">
                                    <p id="desc_title">
                                        <?= $_SESSION['cart']['content'][0]->title; ?> -
                                        <?= date('M d, Y', strtotime($val['date'])); ?>,
                                        <?= date('h:i a', strtotime($val['date'] . " " . $val['time'])); ?>
                                    </p>
                                    <input type="hidden" name="ticket_title"
                                        value="<?= @$_SESSION['cart']['content'][0]->title; ?>">
                                    <input type="hidden" name="ticket_type<?= $key; ?>" value="<?= $val['name']; ?>">
                                    <p id="desc_location">
                                    <?php if (strpos($val['name'], 'Family') !== false) { ?>
                                        <?= $val['name']; ?> - <?= $val['location']; ?> [<?= $_SESSION['cart']['family_seats']; ?> selected]
                                    <?php } else { ?>
                                        <?= $val['name']; ?> - <?= $val['location']; ?> 
                                    <?php } ?>  
                                    </p>

                                    <p id="desc_venue"><?= $val['venue']; ?></p>

                                </td>
                                <td class="text-right" title="Qty" id="qty"><?= $val['qty']; ?></td>
                                <input type="hidden" name="qty<?= $key; ?>" value="<?= $val['qty']; ?>">

                                <td class="text-right" title="Price" id="price">$<?= number_format($val['price'], 2); ?>
                                </td>
                                <input type="hidden" name="price<?= $key; ?>"
                                    value="<?= number_format($val['price'], 2); ?>">

                                <!--<td class="text-right" title="Delete">
                                    <a class="btn btn-danger" data-href="<?= base_url() . '/remove_item/' . $key; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $key; ?>" id="a_del">Delete</a>
                                    <div class="modal fade" id="exampleModal<?= $key; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel" style="color:red;">Delete Confirmation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure to delete cart item?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <?php $itm = '/remove_item/'; ?>
                                                <button type="button" class="btn btn-danger del_item" onclick='myFunction(<?= $key ?>)'>Delete</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>-->
                                <td class="text-right" title="Total" id="total_val" style="text-align:end;">
                                    <strong>$<?= number_format($total, 2); ?></strong>
                                </td>
                            </tr>

                            <?php
        }
    }
}
@$seats = implode(",",@$_SESSION['cart']['seats_selected']); 
if(!empty($seats)) {?>
                            <tr>
                                <td colspan="4">
                                Selected Seats : - <?= $seats; ?>
                                </td>

                            </tr>
<?php } ?>
                            <input type="hidden" name="tot_qty" value="<?= $tot_qty; ?>">
                            <input type="hidden" name="tot_amount"
                                value="<?= number_format(@$_SESSION['cart']['total'], 2); ?>">

                            <tr>
                                <td colspan="4">
                                    <div class="row mb-3">
                                        <div class="col-lg-6 col-md-6">

                                        </div>
                                        <div class="col-lg-6 col-md-6" style="text-align:right;">
                                            <?php
if (@$_SESSION['cart']['tcount'] > 0) {
?>
                                            <!-- <button class="btn btn-primary" type="submit" id="pay_btn">Proceed To Pay</button> -->
                                            <input type="submit" class="btn btn-primary" name="pay_btn" value="Proceed To Pay"/>
                                            
                                            <button type="button" class="btn btn-danger" onclick='myFunction1()'>Cancel</button>
                                            <?php
} else {
?>
                                             <!--<button class="btn btn-primary" type="submit" id="pay_btn">Book Now</button> -->
                                             <!--<input type="submit" class="btn btn-primary" name="pay_btn" value="Book Now"/>-->
                                             <?php
}
?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    </form>

                    <?php 
if (!empty($_SESSION['ccode'])) {
?>
                    <form method="POST" id="remove_coupon" action="<?= base_url() . '/shows/remove_coupon'; ?>">
                        <?= csrf_field(); ?>
                        <tr class="total-row ">
                            <input type="hidden" name="ccode" id="ccode" value="<?= @$_SESSION['ccodeinfo']->code; ?>">
                            <input type="hidden" name="coupon_val" id="ccvalue"
                                value="<?= number_format(@$_SESSION['cart']['ptotal'], 2); ?>">
                            <td class="text-right" colspan="2"><strong>Coupon Code
                                    Applied: (<?= @$_SESSION['ccodeinfo']->code; ?>) -
                                    <?= @$_SESSION['ccodeinfo']->discount; ?> </strong></td>
                            <td class="text-right" title="Remove">
                                <!-- <a class="btn btn-primary" href="<?= base_url() . '/remove_item/' . @$key; ?>">Remove</a> -->
                                <button class="btn btn-danger" type="submit" id="remove_btn">Remove</button>
                            </td>
                            <td class="text-right" id="total">
                                <strong>$<?= number_format(@$_SESSION['cart']['ptotal'], 2); ?></strong>
                            </td>

                        </tr>
                    </form>

                    <?php
}
?>
                    <tr class="total-row tax">
                        <?php
if (!empty($_SESSION['cart']['salestax']) && $_SESSION['cart']['salestax'] > 0) {
?>
                        <td class="text-right" colspan="3"><strong>Sales Tax</strong></td>
                        <td class="text-right" id="total" style="text-align:end;">
                            <strong>$<?= $_SESSION['cart']['salestax']; ?></strong>
                        </td>
                        <?php
}
?>
                    </tr>
                    <tr class="total-row tax">
                        <?php
if (!empty($_SESSION['cart']['processingfees']) && $_SESSION['cart']['processingfees'] > 0) {
?>
                        <td class="text-right" colspan="3"><strong>Processing Fees</strong></td>
                        <td class="text-right" id="total" style="text-align:end;">
                            <strong>$<?= $_SESSION['cart']['processingfees']; ?></strong>
                        </td>
                        <?php
}
?>
                    </tr>
                    <tr class="total-row info">


                        <td class="text-right" colspan="3"><strong>Total</strong></td>
                        <td class="text-right" id="total" style="text-align:end;">
                            <strong>$<?= number_format(@$_SESSION['cart']['total'], 2); ?></strong>
                        </td>

                    </tr>
                    </table>
                </div>
                <!--<div class="col-lg-12">
                    <div class="make-cart">
                        <h4>Make Card Payment</h4>
                        <form>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" placeholder="First Name">
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-7 col-md-7 pr-0">
                                    <label>Contact Phone<sup>*</sup></label>
                                    <input class="form-control" type="text" placeholder="Phone Number">
                                </div>
                                <div class="col-lg-5 col-md-5 pl-0 relative">
                                    <div class="ch">
                                        <input type="checkbox" id="email" name="email" value="">
                                        <label for="email">Text Receipt?</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <label>Address</label>
                                    <input class="form-control" type="text" placeholder="Address">
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <label>City</label>
                                    <input class="form-control" type="text" placeholder="City">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <label>State</label>
                                    <input class="form-control" type="text" placeholder="State">
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <label>Zip</label>
                                    <input class="form-control" type="text" placeholder="Zip">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-7 col-md-7 pr-0">
                                    <label>Email Address<sup>*</sup></label>
                                    <input class="form-control" type="email" placeholder="Email Address">
                                </div>
                                <div class="col-lg-5 col-md-5 pl-0 relative">
                                    <div class="ch">
                                        <input type="checkbox" id="email" name="email" value="">
                                        <label for="email">Join mailing list</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12 col-md-12">
                                    <label>Credit or Debit Card<sup>*</sup></label>
                                    <input class="form-control" type="text" placeholder="Credit or Debit Card">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-8 col-md-8">
                                    <p class="row">
                                        <span class="col-6 pl-0"> <label>Month<sup>*</sup></label><input
                                                class="form-control" type="text" placeholder="Month"></span>
                                        <span class="col-6 pr-0"> <label>Year<sup>*</sup></label><input
                                                class="form-control" type="text" placeholder="Year"></span>
                                    </p>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <label>CVV<sup>*</sup></label>
                                    <input class="form-control" type="text" placeholder="CVV">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12 col-md-12 text-center">
                                    <a href="#"><button>Pay $66.00 Now</button></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>-->
            </div>


        </div>
    </div>
</section>
<script>
// Set the date we're counting down to
var minutesToAdd=10;
var currentDate = new Date();
var countDownDate = new Date(currentDate.getTime() + minutesToAdd*60000);
// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    myFunction2();
  }
}, 1000);
</script>

<script>
    function myFunction(key) {
        var baseurl = '<?= base_url(); ?>';
        var link = '/remove_item/'+ key;
        //alert(baseurl + link);
        window.location.href = baseurl + link;
    }

    function myFunction1() {
         var can_url = '<?= @$_SESSION['ccancel_url']; ?>';
         $.confirm.show({
                "message":"Are you sure you want to cancel this transaction?",
                //"hideNo":true,// hide cancel button
                "yesText":"YES",
                "noText":"NO",
                "yes":function (){
                    $.ajax({
                        url : '<?= base_url()."/cart/cancel_transaction"; ?>',
                        type: "POST",
                        data : {
                            cancel : can_url
                        },
                        success: function(data, textStatus, jqXHR)
                        {
                          if(data == 'success') {
                            window.location.href = can_url;
                          } else {

                          }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            
                        }
                    });
                },
                "no":function (){
                    location.reload();
                },
              })  
        // window.location.href = url;
                    

    }

    function myFunction2() {
         var can_url = '<?= @$_SESSION['ccancel_url']; ?>';
         $.confirm.show({
                "message":"Your Transaction time limit exceeded!",
                "hideNo":true,// hide cancel button
                "yesText":"OK",
                "noText":"CANCEL",
                "yes":function (){
                    $.ajax({
                        url : '<?= base_url()."/cart/cancel_transaction"; ?>',
                        type: "POST",
                        data : {
                            cancel : can_url
                        },
                        success: function(data, textStatus, jqXHR)
                        {
                          if(data == 'success') {
                            window.location.href = can_url;
                          } else {

                          }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            
                        }
                    });
                },
                "no":function (){
                    location.reload();
                },
              })  
        // window.location.href = url;
                    

    }

    $(document).ready(function(){

        // $('.del_item').click(function(){
        // var url = $('#a_del').attr('data-href');
        // window.location.href = url;
        // });
        $('.confirm h4.title').text('Cancel Alert');
        $("#pay_btn").click(function(){
            fetchConnectionToken()

            function unexpectedDisconnect() {
                // In this function, your app should notify the user that the reader disconnected.
                // You can also include a way to attempt to reconnect to a reader.
                console.log("Disconnected from reader")
            }
        });
        
    });
    function fetchConnectionToken() {
  // Do not cache or hardcode the ConnectionToken. The SDK manages the ConnectionToken's lifecycle.
  return fetch('<?= base_url('/get_token'); ?>', { method: "POST" })
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {
      return data.secret;
    });
    }

       
   
</script>
<?= $this->endSection(); ?>
