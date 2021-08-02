<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<section class="content-part pb-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 pt-3 pb-3 event-title">
                <h2>My Shows</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-0">
                <div class="wrapper">
                    <?php if(!empty($my_shows)) { ?>
                    <div class="carousel">
                        <?php foreach($my_shows as $my) { ?>
                        <div>
                            <div class="events-details"> <img src="<?= base_url().'/images/'.$my->image; ?>" />
                                <div class="events-inner">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <h4><?= $my->title ?></h4>
                                            <!--<h5 class="black-clr">From $42.95/person</h5>-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <div class="price-details ">
                                                <!-- <p>Purchase Tickets for the 2:00 pm Show on May 5, 2021</p> -->
                                                <?php if(!empty($my->summary)) { ?>
                                                <p><strong>Description:</strong><?= substr_replace($my->summary, "...", 100);  ?>
                                                </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center"> <a href="<?= $buy_url.$my->id; ?>">
                                                <button>Buy Tickets</button>
                                            </a> </div>
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
                    <div class="carousel">
                        <?php foreach($all_shows as $my) { ?>
                        <div>
                            <div class="events-details"> <img src="<?= base_url().'/images/'.$my->image; ?>" />
                                <div class="events-inner">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <h4><?= $my->title ?></h4>
                                            <!--<h5 class="black-clr">From $42.95/person</h5>-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <div class="price-details ">
                                                <!-- <p>Purchase Tickets for the 2:00 pm Show on May 5, 2021</p> -->
                                                <?php if(!empty($my->summary)) { ?>
                                                <p><strong>Description:</strong><?= substr_replace($my->summary, "...", 100);  ?>
                                                </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center"> <a href="<?= $buy_url.$my->id; ?>">
                                                <!--<button>Buy Tickets</button>-->
                                            </a> </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection(); ?>