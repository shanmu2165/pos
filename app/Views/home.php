<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<section class="content-part pb-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 pt-3 pb-3 event-title">
                <h2>My Shows </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6"></div>
            <div class="col-lg-6 col-md-6">
                <?php if(session("msg")){ ?>

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo session("msg"); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php } ?>
            </div>
            <div class="col-lg-3 col-md-6"></div>
            <div class="col-12 p-0">
                <div class="wrapper">

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
                                    <!-- <div class="row">
                                        <div class="col-12 text-center">
                                            <div class="price-details ">
                                                <?php if(!empty($my->summary)) { ?>
                                                <p><strong>Description:</strong><?= substr_replace($my->summary, "...", 100);  ?>
                                                </p>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <?php if(!empty($cal_data[$my->id][$today])) { ?>
                                            <a href="<?= $buy_url.$my->id."/".$end_time; ?>" tabindex="-1">
                                                <button tabindex="-1">Tickets</button>
                                            </a>
                                            <?php } else { ?>
                                            <a href="<?= $buy_url.$my->id; ?>" tabindex="-1">
                                                <button tabindex="-1">Tickets</button>
                                            </a>
                                            <?php } ?>
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
        <div class="row">
            <div class="col-12 pt-3 pb-3 event-title">
                <h2>All Shows</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-0">
                <div class="wrapper">
                    <?php if(!empty($all_shows)) { ?>
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

    </div>
</section>
<?= $this->endSection(); ?>