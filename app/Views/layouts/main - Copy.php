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
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick-theme.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick.js"></script>
</head>

<body>
    <header class="blog-header py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-4 search-top">
                    <form class="d-flex" method="POST" action="<?= $search_url; ?>">
                        <?= csrf_field() ?>
                        <input class="form-control me-2" type="text" placeholder="Search" aria-label="Search"
                            name="search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
                <div class="col-lg-4 col-md-4 text-center logo-top"> <a class="link-secondary"
                        href="<?= base_url()."/shows"; ?>" aria-label="Search"> <img
                            src="<?= base_url('images/logo.png'); ?>" alt="" /> </a> </div>
                <div class="col-lg-4 col-md-4 right-menu">
                    <!-- Large button groups (default and split) -->
                    <div class="right-top">
                        <div class="dropdown category">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false"> Category </button>
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
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false"> Dropdown button </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">English</a></li>
                                <li><a class="dropdown-item" href="#">French</a></li>
                            </ul>
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
                            <a href="<?= base_url().'/cart'; ?>"><button class="btn dropdown-toggle" type="button"
                                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown
                                    button </button>
                                <?php if( isset($_SESSION['cart']) && $_SESSION['cart']['tcount'] > 0 ) { ?>
                                <span> <?= $_SESSION['cart']['tcount']; ?></span>
                                <?php } ?></a>

                        </div>
                    </div>
                    <div class="right-bottom"> <a href="#">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModalXl">Lookup Transaction</button>
                            <div class="modal fade" id="exampleModalXl" tabindex="-1"
                                aria-labelledby="exampleModalXlLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl lookup">
                                    <div class="modal-content">
                                        <div class="modal-header mb-4">
                                            <h5 class="modal-title h4" id="exampleModalXlLabel">Lookup Transaction</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-lg-6 text-left">
                                                        <div class="form-group">
                                                            <label for="">Transaction id</label>
                                                            <select class="form-select mb-3"
                                                                aria-label=".form-select-lg example">
                                                                <option selected>Open this select menu</option>
                                                                <option value="1">One</option>
                                                                <option value="2">Two</option>
                                                                <option value="3">Three</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 text-left">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Email address</label>
                                                            <input type="email" class="form-control"
                                                                id="exampleInputEmail1" aria-describedby="emailHelp"
                                                                placeholder="Enter email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 text-center pt-4 pb-4">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a> </div>
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
        source: "post_search.php",
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
<?php if($current == 'shows') { ?>
$(document).ready(function() {
    $('.carousel').slick({
        speed: 500,
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: false,
        arrows: true,
        autoplaySpeed: 2000,
        dots: false,
        centerMode: true,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                arrows: true,
                slidesToScroll: 1,
                // centerMode: true,

            }

        }, {
            breakpoint: 800,
            settings: {
                slidesToShow: 2,
                arrows: true,
                slidesToScroll: 2,
                dots: true,
                infinite: true,

            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                arrows: true,
                slidesToScroll: 1,
                dots: true,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 2000,
            }
        }]
    });
});
<?php } ?>
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

<?php if($current == 'ticket') { ?>
/*var QtyInput = (function() {
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

    /*$countBtn.on('click keyup paste', function() {
        var operator = this.dataset.action;
        var $this = $(this);
        var $input = $this.siblings('input[name=product_qty1]');
        var $input2 = $this.siblings('input[name=product_qty2]');

        var qty = parseInt($input.val());
        var qty2 = parseInt($input2.val());
        if (operator == "add") {
            qty += 1;
            if (qty >= qtyMin + 1) {
                $this.siblings(".qty-count--minus").attr("disabled", false);
            }

            if (qty >= qtyMax) {
                $this.attr("disabled", true);
            }

            qty2 += 1;
            if (qty2 >= qtyMin + 1) {
                $this.siblings(".qty-count--minus").attr("disabled", false);
            }

            if (qty2 >= qtyMax) {
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

            qty2 = qty2 <= qtyMin ? qtyMin : (qty2 -= 1);

            if (qty2 == qtyMin) {
                $this.attr("disabled", true);
            }

            if (qty2 < qtyMax) {
                $this.siblings(".qty-count--add").attr("disabled", false);
            }
        }
        if (isNaN(qty)) {
            qty = 1;
        }
        if (isNaN(qty2)) {
            qty2 = 1;
        }
        $('input[name=product_qty1]').val(qty);
        $('input[name=product_qty2]').val(qty2);
        var qty1 = $('input[name=product_qty1]').val();
        var qty2 = $('input[name=product_qty2]').val();
        var price1 = parseFloat($('#type1-price').val());
        var price2 = parseFloat($('#type2-price').val());
        var total1 = price1 * qty1;
        var total2 = price2 * qty2;
        var total = parseFloat(total1) + parseFloat(total2);
        $("#btn_name").html('Total: $' + total);
        $input.val(qty);
    });*/

function calculateTicketPriceTotal(ticketPrice, ticketQtyReferer, type, id) {
    const qtyMin = $("#" + ticketQtyReferer).attr("min");
    const qtyMax = $("#" + ticketQtyReferer).attr("max");

    console.log(ticketPrice, "ticketPrice");
    console.log(ticketQtyReferer, "ticketQtyReferer");
    console.log(type, "type");

    const prevTotal = $("#total_price").val();
    const formattedPrevTotal = parseFloat(prevTotal);
    const formattedTicketPrice = parseFloat(ticketPrice);

    const currentQty = $("#" + ticketQtyReferer).val();
    const formattedCurrentQty = parseFloat(currentQty);
    console.log(currentQty, "currentQty");

    let newQty = 0;
    let total = 0;

    if (type === "add") {
        newQty = formattedCurrentQty + 1;
        total = formattedPrevTotal + formattedTicketPrice;
        console.log(newQty + "Max - " + qtyMax);
        //Check max limit
        if (newQty >= qtyMax) {
            $("#add" + id).attr("disabled", true);
        }
    }
    if (type === "sub") {
        if (currentQty <= 0) {
            return
        }

        if (currentQty <= qtyMax) {
            $("#add" + id).attr("disabled", false);
        }

        newQty = formattedCurrentQty - 1;
        total = formattedPrevTotal - formattedTicketPrice;
    }

    console.log(newQty, "newQty");

    $("#" + ticketQtyReferer).val(newQty)

    $("#total_price").val(parseFloat(total));
    $("#td_total").html(parseFloat(total));
}

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
<?php } ?>
</script>

</html>