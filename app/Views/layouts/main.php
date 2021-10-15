<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title; ?></title>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css?ver=5.6">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/style.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/table-date.css'); ?>">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick-theme.min.css" />

    <link rel="stylesheet" href="<?= base_url('css/owl.carousel.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/owl.theme.default.min.css'); ?>">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="<?= base_url('js/owl.carousel.js'); ?>"></script>
    
    <style>
        #tickets {
            display:none;
        }
        #qr-reader__status_span {
            display:none;
        }
        #qr-reader span a{
            display:none;
        }
        </style>
</head>

<body>
    <header class="blog-header py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-4 search-top">
                    <form class="d-flex" method="POST" action="<?= $search_url; ?>">
                        <?= csrf_field() ?>
                        <input class="form-control me-2" type="text" id="txtSearch" placeholder="Search"
                            aria-label="Search" name="search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
                <div class="col-lg-4 col-md-4 text-center logo-top"> <a class="link-secondary"
                        href="<?= base_url()."/shows"; ?>" aria-label="Search"> <img
                            src="<?= base_url('images/logo.png'); ?>" alt="" /> </a> </div>
                <div class="col-lg-4 col-md-4 right-menu">
                    <!-- Large button groups (default and split) -->
                    <div>
                        <div class="right-top">
                            <div class="dropdown category">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false"> Show Types </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="<?= base_url('/shows'); ?>">All Shows</a></li>
                                    <?php foreach($categories as $cat) { ?>

                                    <li><a class="dropdown-item <?= $cat->icon; ?>"
                                            href="<?= base_url().'/shows/search/'.str_replace(' ', '_', $cat->term); ?>"><?= $cat->term ?></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="dropdown language">
                                <!-- <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false"> Dropdown button </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#">English</a></li>
                                    <li><a class="dropdown-item" href="#">French</a></li>
                                </ul> -->
                            </div>
                            <div class="dropdown user">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false"> User </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <!--<li><a class="dropdown-item" href="#">Profile</a></li>-->
                                    <li><a class="dropdown-item" href="<?= base_url('login/logout'); ?>">Logout</a></li>
                                </ul>
                            </div>
                            <div class="dropdown cart">
                                <a href="<?= base_url().'/cart'; ?>">

                                    <?php if( isset($_SESSION['cart']) && $_SESSION['cart']['tcount'] > 0 ) { ?>
                                    <span class="cbtn"> <?= $_SESSION['cart']['tcount']; ?></span>
                                    <?php } else { ?>
                                    <span class="cbtn"> 0</span>
                                    <?php } ?>
                                </a>

                            </div>
                        </div>
                        <div class="right-bottom"> 
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModalXl"  data-backdrop="static" data-keyboard="false">Lookup Transaction</button>
                                <div class="modal fade" id="exampleModalXl">
                                    <div class="modal-dialog modal-dialog-centered modal-xl lookup">
                                        <div class="modal-content" >
                                            <div class="modal-header mb-4">
                                                <h5 class="modal-title h4" id="exampleModalXlLabel">Lookup Transaction
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <div class="alert alert-warning alert-dismissible fade show" id="modal_alert_msg" role="alert" style="display:none; color:red; background-color:#000; font-weight:bold; text-align:center;">
                                                        <span id="alert_modal"></span>
                                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-3"></div> -->
                                                </div>
                                                <form method="POST" action="<?= base_url()."/shows/lookup"; ?>" id="lookup_form">
                                                    <?= csrf_field() ?>
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-lg-6 text-left">
                                                                <div class="form-group">
                                                                    <label for="">Transaction id</label>
                                                                    <input type="text" class="form-control"
                                                                        id="exampleInputTransaction"
                                                                        aria-describedby="emailHelp"
                                                                        placeholder="Enter Transaction Id"
                                                                        name="trans_id" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 text-left">
                                                                <div class="form-group">
                                                                    <label for="exampleInputEmail1">Email
                                                                        address</label>
                                                                    <input type="email" class="form-control"
                                                                        id="exampleInputEmail1"
                                                                        aria-describedby="emailHelp"
                                                                        placeholder="Enter Email Address"
                                                                        name="trans_email" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 text-left pt-1">
                                                                <div class="form-group lookup">
                                                                    <p>Please follow any one of the step and input the transaction id<br/>
                                    * Check email for transaction id<br/>
                                    * Use the below button to scan the QR Code to view transaction id</p>
                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6"></div>
                                                            <div class="col-lg-6 pt-3 qrcode" style="margin-top:-30px;">
                                                               <a href="<?= base_url().'/qrcode_reader'; ?>" target="_blank"> <i class="fa fa-qrcode" style="font-size:35px;" aria-hidden="true"></i></a>
                                                            </div>
                                                            <!-- <div class="col-lg-5 text-left pt-1">
                                                            
                                                            </div> -->
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12 text-center pt-4 pb-4">
                                                                <button type="button"
                                                                    class="btn btn-primary" id="lookup_submit">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a> </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?= $this->renderSection('content'); 
    //var_dump($forsrc); die; ?>
</body>
<script type="text/javascript">
// This depends on jquery 
//Search
$(document).ready(function() {

    $('#txtSearch').autocomplete({
        source: "<?= base_url().'/shows/search_one'; ?>",
        minLength: 2,
        select: function(event, ui) {
            var url = ui.item.id;
            if (url != '#') {
                location.href = url
            }
        },
        open: function(event, ui) {
            $(".ui-autocomplete").css("z-index", 1000)
        }
    })

});
//Search

$(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
        margin: 10,
        nav: true,
        loop: false,
        dots: false,
        autoplay: false,
        autoplayTimeout: 1000,
        autoplayHoverPause: true,
        navText: ["<div class='nav-btn prev-slide'></div>", "<div class='nav-btn next-slide'></div>"],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1024: {
                items: 3
            },
            1366: {
                items: 5
            }
        }
    })
});
// $('#lookup_submit').modal({
//     backdrop: 'static',
//     keyboard: false
// })
$('#lookup_submit').click(function(){
    $.ajax({
      type:"POST",
      url: '<?= base_url()."/shows/lookup"; ?>',
      data: {
       trans_id: $('#exampleInputTransaction').val(),
       trans_email: $('#exampleInputEmail1').val()
      },
      success: function(data){
          //console.log('Data', data);
          var focusSet = false;
            if (!$('#exampleInputTransaction').val()) {
                if ($("#exampleInputTransaction").parent().next(".validation").length == 0) // only add if not added
                {   //alert('1');
                    $("#exampleInputTransaction").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;margin-right:300px;'>Transaction Id is required</div>");
                    if (!$('#exampleInputEmail1').val()) {
                        //alert('2');
                        if ($("#exampleInputEmail1").parent().next(".validation").length == 0) // only add if not added
                        {
                            $("#exampleInputEmail1").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;margin-right:300px;'>Email address is required</div>");
                        }
                        e.preventDefault(); // prevent form from POST to server
                        if (!focusSet) {
                            $("#exampleInputEmail1").focus();
                        }
                    } else { //alert('3');
                        $("#exampleInputEmail1").parent().next(".validation").remove(); // remove it
                    }
                }
                
                e.preventDefault(); // prevent form from POST to server
                $('#exampleInputTransaction').focus();
                focusSet = true;
            } else {
                //alert('there');
                $("#exampleInputTransaction").parent().next(".validation").remove(); // remove it
               // return false;
            }
            if ($('#exampleInputTransaction').val() && !$('#exampleInputEmail1').val()) {
                        if ($("#exampleInputEmail1").parent().next(".validation").length == 0) // only add if not added
                        {
                            $("#exampleInputEmail1").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;margin-right:300px;'>Email address is required</div>");
                        }
                        e.preventDefault(); // prevent form from POST to server
                        if (!focusSet) {
                            $("#exampleInputEmail1").focus();
                        }
                    } else { //alert('3');
                        $("#exampleInputEmail1").parent().next(".validation").remove(); // remove it
                    }
        if(data == 'invalid') {
          $("#alert_modal").text('Invalid Transaction Id or Email');
          $("#modal_alert_msg").css('display','block');
          
        } else {    
            $("#lookup_form").submit();
        }
      }, 
        error: function( jqXhr, textStatus, errorThrown ) {
                        console.log( errorThrown );
        }  
    });

    $(".btn-close").click(function(){
        $('#exampleInputTransaction').val('');
        $('#exampleInputEmail1').val('');
        $("#alert_modal").text('');
        $("#modal_alert_msg").css('display','none');
    })
});

// This depends on jquery 
$(document).ready(function() {
    $('.carousel-table').slick({
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        arrows: true,
        autoplaySpeed: 2000,
        dots: false,
        centerMode: true,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 2,
                arrows: true,
                slidesToScroll: 1,
                // centerMode: true,

            }

        }, {
            breakpoint: 800,
            settings: {
                slidesToShow: 1,
                arrows: true,
                slidesToScroll: 2,
                dots: false,
                infinite: true,

            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                arrows: true,
                slidesToScroll: 1,
                dots: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 2000,
            }
        }]
    });
});
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
<?php if($current == 'layout') { ?>
var QtyInput = (function() {
    var $qtyInputs = $(".qty-input");

    if (!$qtyInputs.length) {
        return;
    }

    var $inputs = $qtyInputs.find(".product-qty");
    var $countBtn = $qtyInputs.find(".qty-count");
    var qtyMin = parseInt($inputs.attr("min"));
    var qtyMax = parseInt($inputs.attr("max"));

    $inputs.change(function() {
        var $this = $(this);
        var $minusBtn = $this.siblings(".qty-count--minus");
        var $addBtn = $this.siblings(".qty-count--add");
        var qty = parseInt($this.val());

        if (isNaN(qty) || qty <= qtyMin) {
            $this.val(qtyMin);
            $minusBtn.attr("disabled", true);
        } else {
            $minusBtn.attr("disabled", false);

            if (qty >= qtyMax) {
                $this.val(qtyMax);
                $addBtn.attr('disabled', true);
            } else {
                $this.val(qty);
                $addBtn.attr('disabled', false);
            }
        }
    });

    $countBtn.click(function() {
        var operator = this.dataset.action;
        var $this = $(this);
        var $input = $this.siblings(".product-qty");
        var qty = parseInt($input.val());

        if (operator == "add") {
            qty += 1;
            if (qty >= qtyMin + 1) {
                $this.siblings(".qty-count--minus").attr("disabled", false);
            }

            if (qty >= qtyMax) {
                $this.attr("disabled", true);
            }
        } else {
            qty = qty <= qtyMin ? qtyMin : (qty -= 1);

            if (qty == qtyMin) {
                $this.attr("disabled", true);
            }

            if (qty < qtyMax) {
                $this.siblings(".qty-count--add").attr("disabled", false);
            }
        }

        $input.val(qty);
    });
})();


// console.log(time);
//console.log(forsrc);
$(document).ready(function() {
    '<?php echo $forsrc; ?>'
    $('.button<?php echo str_replace(":","",$instances[0]->start_time); ?>').click(function() {
        //alert('yes');
        $('#diagram<?php echo str_replace(":","",$instances[0]->start_time); ?> .sc').removeClass(
            "sc-selected sc-default").addClass("sc-default");
        $('#diagram<?php echo str_replace(":","",$instances[0]->start_time); ?> #' + $(this).data('id'))
            .removeClass("sc-default")
            .addClass("sc-selected");
    });

    $('.button<?php echo str_replace(":","",$instances[0]->start_time); ?>').on('click', function() {
        //var act = $(this).find(':selected').data('ps');
        var act = $(this).data('ps');
        //$('#seatpicker<?php echo str_replace(":","",$instances[0]->start_time); ?>').val($(this).data('id'));
        $('#seatpicker<?php echo str_replace(":","",$instances[0]->start_time); ?>').val($(this).val());
        $(".priceset").hide();
        $(".priceset input").prop('disabled', true);
        $(".priceset select").prop('disabled', true);
        $(".ps-" + act).show();
        $(".ps-" + act + " input").prop('disabled', false);
        $(".ps-" + act + " select").prop('disabled', false);

    });

    $('.venuesection<?php echo str_replace(":","",$instances[0]->start_time); ?>').click(function() {
        //alert('yru');
        $('#seatpicker<?php echo str_replace(":","",$instances[0]->start_time); ?>').val($(this).data(
            'id'));
        $('#ticketform<?php echo str_replace(":","",$instances[0]->start_time); ?>').submit();
    });

    $(".priceopt").on('change', function() {
        let price = 0;
        $(".priceopt").find(':selected').each(function() {
            price += (parseInt($(this).val()) * parseFloat($(this).data(
                'price').replace("$", "")));
        });
        price = price.toFixed(2);
        $('#displaytotal').text(price);
    });
});

<?php } ?>
<?php if($current == 'cart') { ?>
var QtyInput = (function() {
    var $qtyInputs = $(".qty-input");

    if (!$qtyInputs.length) {
        return;
    }

    var $inputs = $qtyInputs.find(".product-qty");
    var $countBtn = $qtyInputs.find(".qty-count");
    var qtyMin = parseInt($inputs.attr("min"));
    var qtyMax = parseInt($inputs.attr("max"));

    $inputs.change(function() {
        var $this = $(this);
        var $minusBtn = $this.siblings(".qty-count--minus");
        var $addBtn = $this.siblings(".qty-count--add");
        var qty = parseInt($this.val());

        if (isNaN(qty) || qty <= qtyMin) {
            $this.val(qtyMin);
            $minusBtn.attr("disabled", true);
        } else {
            $minusBtn.attr("disabled", false);

            if (qty >= qtyMax) {
                $this.val(qtyMax);
                $addBtn.attr('disabled', true);
            } else {
                $this.val(qty);
                $addBtn.attr('disabled', false);
            }
        }
    });

    $countBtn.click(function() {
        var operator = this.dataset.action;
        var $this = $(this);
        var $input = $this.siblings(".product-qty");
        var qty = parseInt($input.val());

        if (operator == "add") {
            qty += 1;
            if (qty >= qtyMin + 1) {
                $this.siblings(".qty-count--minus").attr("disabled", false);
            }

            if (qty >= qtyMax) {
                $this.attr("disabled", true);
            }
        } else {
            qty = qty <= qtyMin ? qtyMin : (qty -= 1);

            if (qty == qtyMin) {
                $this.attr("disabled", true);
            }

            if (qty < qtyMax) {
                $this.siblings(".qty-count--add").attr("disabled", false);
            }
        }

        $input.val(qty);
    });
})();

function removeItem(aId) {
    $.ajax({
        type: "POST",
        url: "<?= base_url().'/shows/remove_item' ?>",
        data: {
            aId: aId
        },
        success: function(data) {
            alert(data);

        },
        error: function(xhr, ajaxOptions, thrownError) {

        }
    });
    return false;
}

// $(document).ready(function() {
//     $("#remove_btn").click(function() {
//         // CSRF Hash
//         let csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
//         let csrfHash = $('.txt_csrfname').val(); // CSRF hash
//         let cccode = $("#ccode").val();
//         let ccvalue = $("#ccvalue").val();
//         $.ajax({
//             url: "<?= base_url().'/shows/remove_coupon'; ?>",
//             method: 'post',
//             data: {
//                 'ccode': cccode,
//                 'ccvalue': ccvalue,
//                 [csrfName]: csrfHash
//             },
//             dataType: 'json',
//             success: function(response) {
//                 if (response.success == 1) {

//                 } else {
//                     // Error
//                     alert(response.error);
//                 }
//             }
//         });
//     });
// });
<?php } ?>
$(document).ready(function() {
    $('#exampleModalXl').modal({
        
        backdrop: 'static',
        keyboard: false
    });
    $(".btn-close").click(function(){
        $("#exampleInputTransaction").parent().next(".validation").remove(); // remove it
        $("#exampleInputEmail1").parent().next(".validation").remove(); // remove it
    }); 
    
});
</script>

</html>