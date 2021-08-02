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
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css" rel="stylesheet">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/owl.carousel.js"></script>
</head>

<body>

    <section class="content-part pt-4 pb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 pt-3 pb-3 event-title">
                    <h2>My Shows</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 pt-3 pb-3">
                    <?php if(!empty($my_shows)) { ?>
                    <div class="owl-carousel owl-theme">
                        <?php foreach($my_shows as $my) {  ?>
                        <div class="item">
                            <div class="events-details">
                                <div class="ribbon ribbon-top-left">
                                    <?php if(!empty($cal_data[$my->id][$today])) { 
                                            $show_time = date($cal_data[$my->id][$today]->start_time); 
                                            
                                            $show_time = strtotime($show_time);
                                            $end_time = $show_time + 3600;
                                            $time = date('H:i:s');  $time = strtotime($time); 

                                         if($time < $show_time && $time < $end_time) { ?>
                                    <span class="up">Upcoming Show</span>
                                    <?php } else if($time > $show_time && $time < $end_time) { ?>
                                    <span class="now">Now Showing</span>
                                    <?php } else  { ?>
                                    <span class="past">Past Show</span>
                                    <?php } } else { ?>
                                    <span class="no">Not Showing</span>
                                    <?php } ?>
                                </div>
                                <img src="<?= base_url().'/images/'.$my->image; ?>" />
                                <div class="events-inner">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <h4><?= $my->title ?></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <div class="price-details ">
                                                <?php if(!empty($my->summary)) { ?>
                                                <p><strong>Description:</strong><?= substr_replace($my->summary, "...", 100);  ?>
                                                </p>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <a href="<?= $buy_url.$my->id."/".$end_time; ?>" tabindex="-1">
                                                <button tabindex="-1">Buy Tickets</button>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                    <?php } else { ?>
                    <div>
                        <h2 style="text-align:center;color:#0a58ca;">No Shows Available...</h2>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 pt-3 pb-3 event-title">
                    <h2>Upcoming Shows</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 pt-3 pb-3">
                    <?php if(!empty($my_shows)) { ?>
                    <div class="owl-carousel owl-theme">
                        <?php foreach($all_shows as $my) { ?>
                        <div class="item">
                            <div class="events-details">
                                <!-- <div class="ribbon ribbon-top-left">
                                    <span class="up">Upcoming Show</span>
                                </div> -->
                                <img src="<?= base_url().'/images/'.$my->image; ?>" />
                                <div class="events-inner">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <h4><?= $my->title ?></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <div class="price-details ">
                                                <?php if(!empty($my->summary)) { ?>
                                                <p><strong>Description:</strong><?= substr_replace($my->summary, "...", 100);  ?>
                                                </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <a href="<?= $buy_url.$my->id; ?>" tabindex="-1">
                                                <!-- <button tabindex="-1">Buy Tickets</button> -->
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <?php } ?>
                    </div>
                    <?php } else { ?>
                    <div>
                        <h2 style="text-align:center;color:#0a58ca;">No Shows Available...</h2>
                    </div>
                    <?php } ?>

                </div>
            </div>
        </div>



</body>
<script>
$(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
        margin: 10,
        nav: true,
        loop: true,
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
})
</script>
<script type="text/javascript">
// This depends on jquery 
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
</script>

</html>