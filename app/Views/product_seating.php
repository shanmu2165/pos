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
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/style.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/table-date.css'); ?>" />
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
        <div class="product-details bg-white">
            <div class="container pt-5 pb-5">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="ticket-details">
                            <h4>Purchase Tickets for
                                the 8:00 pm Show on
                                May 15, 2021</h4>
                            <div class="step1-ticket">
                                <h2>Step 1: Select A Section</h2>
                                <div class="tsec">
                                    <span class="dgrey"><a href="#">Left</a></span>
                                    <span class="dblue"><a href="#">Left</a></span>
                                    <span class="dgreen"><a href="#">Left</a></span>
                                </div>
                                <div class="tsec">
                                    <span class="dgrey"><a href="#">center</a></span>
                                    <span class="dblue"><a href="#">center</a></span>
                                    <span class="dgreen"><a href="#">center</a></span>
                                </div>
                                <div class="tsec">
                                    <span class="dgrey"><a href="#">Right</a></span>
                                    <span class="dblue"><a href="#">Right</a></span>
                                    <span class="dgreen"><a href="#">Right</a></span>
                                </div>
                            </div>
                            <div class="step2-ticket">
                                <table class="table mt-4" style="border:none;">
                                    <tr>
                                        <th>
                                            <h4>Regular Seating</h4>
                                        </th>
                                        <th></th>
                                        <th>
                                            <h4>Quantity</h4>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Adult (ages 13 and up)</h6>
                                        </td>
                                        <td><strong>$30.00</strong></td>
                                        <td>
                                            <div class="qty-input">
                                                <button class="qty-count qty-count--minus" data-action="minus"
                                                    type="button">-</button>
                                                <input class="product-qty" type="number" name="product-qty" min="0"
                                                    max="10" value="1">
                                                <button class="qty-count qty-count--add" data-action="add"
                                                    type="button">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Child (12 and under)</h6>
                                        </td>
                                        <td><strong>$0.00</strong></td>
                                        <td>
                                            <div class="qty-input">
                                                <button class="qty-count qty-count--minus" data-action="minus"
                                                    type="button">-</button>
                                                <input class="product-qty" type="number" name="product-qty" min="0"
                                                    max="10" value="1">
                                                <button class="qty-count qty-count--add" data-action="add"
                                                    type="button">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="product-seats">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <button type="button" class="btn btn-primary">Assign Best Available</button>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <button type="button" class="btn btn-primary">Clear Set Selections</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row">A</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td class="dgrey">6</td>
                                            <td>5</td>
                                            <td>4</td>
                                            <td class="dblue">3</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td class="dgreen">5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">A</th>
                                        </tr>
                                        <tr>
                                            <th scope="row">B</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td>6</td>
                                            <td>5</td>
                                            <td class="dgreen">4</td>
                                            <td>3</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td class="dblue">2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td class="dgrey">7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">B</th>
                                        </tr>
                                        <tr>
                                            <th scope="row">C</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td class="dgrey">6</td>
                                            <td>5</td>
                                            <td>4</td>
                                            <td>3</td>
                                            <td>2</td>
                                            <td class="dblue">1</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td class="dgreen">8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">C</th>
                                        </tr>
                                        <tr>
                                            <th scope="row">D</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td>6</td>
                                            <td>5</td>
                                            <td>4</td>
                                            <td>3</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">D</th>
                                        </tr>
                                        <tr>
                                            <th scope="row">E</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td class="dgrey">6</td>
                                            <td>5</td>
                                            <td>4</td>
                                            <td class="dblue">3</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td class="dgreen">5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">E</th>
                                        </tr>
                                        <tr>
                                            <th scope="row">F</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td class="dgrey">6</td>
                                            <td>5</td>
                                            <td>4</td>
                                            <td class="dblue">3</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td class="dgreen">5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">F</th>
                                        </tr>
                                        <tr>
                                            <th scope="row">G</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td>6</td>
                                            <td>5</td>
                                            <td class="dgreen">4</td>
                                            <td>3</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td class="dblue">2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td class="dgrey">7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">G</th>
                                        </tr>
                                        <tr>
                                            <th scope="row">H</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td class="dgrey">6</td>
                                            <td>5</td>
                                            <td>4</td>
                                            <td class="dblue">3</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td class="dgreen">5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">H</th>
                                        </tr>
                                        <tr>
                                            <th scope="row">I</th>
                                            <td>10</td>
                                            <td>9</td>
                                            <td>8</td>
                                            <td>7</td>
                                            <td class="dgrey">6</td>
                                            <td>5</td>
                                            <td>4</td>
                                            <td class="dblue">3</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td class="dgreen">5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <th scope="row">I</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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