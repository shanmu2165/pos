<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pos</title>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css?ver=5.6">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/table-date.css" />
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
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
                <div class="col-lg-4 col-md-4 text-center logo-top"> <a class="link-secondary" href="#"
                        aria-label="Search"> <img src="images/logo.png" alt="" /> </a> </div>
                <div class="col-lg-4 col-md-4 right-menu">
                    <!-- Large button groups (default and split) -->
                    <div class="right-top">
                        <div class="dropdown category">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false"> Category </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Category 1</a></li>
                                <li><a class="dropdown-item" href="#">Category 2</a></li>
                                <li><a class="dropdown-item" href="#">Category 3</a></li>
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
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
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
    <section class="content-part pt-4 pb-4">
        <div class="banner-section">
            <div class="container-fluid">
                <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active"> <img src="images/banner.jpg" alt="" /> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cart-title pt-4 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h4>Adult (ages 13 and up) $50.00 , Child (12 and under) $10.00</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="cart-details bg-white">
            <div class="container pt-4 pb-5">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <h2>My Cart</h2>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <p class="row"><span class="col-8"><input type="text" class="form-control"
                                            placeholder="1234" /></span><span class="col-4 p-0"><input type="submit"
                                            class="form-control" value="Apply" /></span></p>
                            </div>
                        </div>
                        <table class="table table-xs">
                            <tr>
                                <th>Description</th>
                                <th>Item</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Each</th>
                                <th class="text-right">Total</th>
                            </tr>
                            <tr class="item-row">
                                <td>
                                    <p>Re-Vibe - May 15, 2021, 8:00pm</p>
                                    <p>Adult(ages 13 and up)</p>
                                    <p>Center Hughes Brothers Theatre</p>
                                </td>
                                <td> <img src="http://placehold.it/50x50" /></td>
                                <td class="text-right" title="Amount">3</td>
                                <td class="text-right" title="Price">2.00</td>
                                <td class="text-right" title="Total">6.00</td>
                            </tr>
                            <tr class="item-row">
                                <td>
                                    <p>Re-Vibe - May 15, 2021, 8:00pm</p>
                                    <p>Adult(ages 13 and up)</p>
                                    <p>Center Hughes Brothers Theatre</p>
                                </td>
                                <td> <img src="http://placehold.it/50x50" /></td>
                                <td class="text-right" title="Amount">3</td>
                                <td class="text-right" title="Price">2.00</td>
                                <td class="text-right" title="Total">6.00</td>
                            </tr>
                            <tr class="item-row">
                                <td>
                                    <p>Re-Vibe - May 15, 2021, 8:00pm</p>
                                    <p>Adult(ages 13 and up)</p>
                                    <p>Center Hughes Brothers Theatre</p>
                                </td>
                                <td> <img src="http://placehold.it/50x50" /></td>
                                <td class="text-right" title="Amount">3</td>
                                <td class="text-right" title="Price">2.00</td>
                                <td class="text-right" title="Total">6.00</td>
                            </tr>
                            <tr class="item-row">
                                <td>
                                    <p>Re-Vibe - May 15, 2021, 8:00pm</p>
                                    <p>Adult(ages 13 and up)</p>
                                    <p>Center Hughes Brothers Theatre</p>
                                </td>
                                <td> <img src="http://placehold.it/50x50" /></td>
                                <td class="text-right" title="Amount">3</td>
                                <td class="text-right" title="Price">2.00</td>
                                <td class="text-right" title="Total">6.00</td>
                            </tr>
                            <tr class="item-row item-row-last">
                                <td>
                                    <p>Re-Vibe - May 15, 2021, 8:00pm</p>
                                    <p>Adult(ages 13 and up)</p>
                                    <p>Center Hughes Brothers Theatre</p>
                                </td>
                                <td> <img src="http://placehold.it/50x50" /></td>
                                <td class="text-right" title="Amount">3</td>
                                <td class="text-right" title="Price">4.00</td>
                                <td class="text-right" title="Total">12.00</td>
                            </tr>
                            <tr class="total-row info">
                                <td class="text-right" colspan="4"><strong>Total</strong></td>
                                <td class="text-right"><strong>18.00</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-4">
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
                    </div>
                </div>


            </div>
        </div>
    </section>
    </div>
</body>
<script type="text/javascript">
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
</script>

</html>