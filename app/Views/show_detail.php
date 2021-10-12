<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<section class="content-part pb-4">
    <div class="banner-section">
        <div class="container-fluid">
            <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active"> <img src="<?= base_url() . '/images/' . $show_details[0]->image3; ?>"
                            alt="" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-section dark-grey">
        <div class="container-fluid p-0 pt-5 pb-4">
            <div class="container table-head">
                <div class="row">
                    <div class="col-lg-4 col-md-6 pb-4">
                        <a href="<?= $go_back; ?>" class="btn btn-primary">Go Back</a>
                    </div>
                    <div class="col-md-12 pb-4">
                        <h2>Click a date to select your tickets
                            <?php
foreach ($time_list as $ltime => $lid) {
    if (strpos($ltime, "|")) {
        //multiple instances
        $ltimes  = explode("|", $ltime);
        $timearr = array();
        foreach ($ltimes as $thistime) {
            if (strpos($thistime, "-")) {
                //start and end
                $ltim      = explode("-", $thistime);
                $timearr[] = date("g:i a", strtotime($ltim[0])) . "-" . date("g:i a", strtotime($ltim[1]));
            } else {
                //start only
                $timearr[] = date("g:i a", strtotime($thistime));
            }
        }
        $timestr = implode(" & ", $timearr);
?>

                            <span
                                class="cal-inst<?= $lid; ?>"><?php
        echo str_replace(" ", "&nbsp;", $timestr);
?></span>
                            <?php
    } else {
        //single instance
        if (strpos($ltime, "-")) {
            //start and end
            $ltim    = explode("-", $ltime);
            $timestr = date("g:i a", strtotime($ltim[0])) . "-" . date("g:i a", strtotime($ltim[1]));
        } else {
            //start only
            $timestr = date("g:i a", strtotime($ltime));
        }
?>

                            <span class="cal-inst<?= $lid; ?>"><?= $timestr; ?></span>
                            <?php
    }
}
?>
                           <?php
if ($christmasflag) {
?>
                           <div class="cal-leginst cal-clear">
                                <div class="faux-box">
                                    <div class="christmas-date"></div>&nbsp;
                                </div> Christmas Show
                            </div>
                            <?php
}
?>
                           <?php
if ($gospelflag) {
?>
                           <div class="cal-leginst cal-clear">
                                <div class="faux-box">
                                    <div class="gospel-date"></div>&nbsp;
                                </div> Gospel Show
                            </div>
                            <?php
}
?>
                           <?php
if ($countryflag) {
?>
                           <div class="cal-leginst cal-clear">
                                <div class="faux-box">
                                    <div class="country-date"></div>&nbsp;
                                </div> Country Show
                            </div>
                            <?php
}
?>
                       </h2>
                    </div>
                </div>
            </div>
            <div class="wrapper">
                <div class="carousel-table">
                    <?php
$time12 = date('H:i:s');
$time12 = strtotime($time12);
while ($cmonth <= $endmonth) {
    $fday = date("w", strtotime($cmonth));
    $lday = date("t", strtotime($cmonth));
    $day  = 1;
    $rctr = 0;
?>
                   <div>
                        <div class="cal-div">
                            <table class="cal-table" cellpadding="2" border="1">
                                <thead>
                                    <tr>
                                        <th colspan="7"><?= date("F Y", strtotime($cmonth)); ?></th>
                                    </tr>
                                    <tr>
                                        <th>S</th>
                                        <th>M</th>
                                        <th>T</th>
                                        <th>W</th>
                                        <th>T</th>
                                        <th>F</th>
                                        <th>S</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
    $dctr = 0;
    while ($dctr < $fday) {
?>
                                       <td>&nbsp;</td>
                                        <?php
        $dctr++;
        $rctr++;
    }
    while ($day <= $lday) {
        if ($rctr == 7) {
?>
                                   </tr>
                                    <tr>
                                        <?php
            $rctr = 0;
        }
        $thisday = date("Y-m-d", strtotime(date("F", strtotime($cmonth)) . " " . $day . ", " . date("Y", strtotime($cmonth))));
        //var_dump($cal_data[$thisday]); die;
        if (isset($cal_data[$thisday])) {
            $itimes = array();
            $ttip   = "";
            foreach ($cal_data[$thisday] as $itime => $idetails) {
                $itimes[] = $itime;
                $ttip .= " " . date("g:i A", strtotime($itime)) . " " . $cal_data[$thisday][$itime]->type;
            }
            sort($itimes);
            $itimesstr = implode("|", $itimes);
            if ($deeppages) { //$brk = "\r\n\r\n\r\n"; 
                //   if ($forceurl){
                //       $urlprefix = $cal_data[$thisday][$itime]->url;
                //   }
                //var_dump($urlprefix); die; 
                //$trlink = $urlprefix.$cal_data[$thisday][$itime]->listingpath.$cal_data[$thisday][$itime]->path."/".$thisday;
                if ($thisday == $today && $etime < $time12) {
?>
                                       <td class="cal-inst cal-past cal-inst<?= $time_list[$itimesstr]; ?>">
                                            <?= $day; ?></td>
                                        <?php
                } else if ($thisday >= $today) {
?>

                                        <td class="cal-inst cal-inst<?= $time_list[$itimesstr]; ?>">
                                            <?php //add christmas div if present
                    $xmas     = FALSE;
                    $gsp      = FALSE;
                    $cou      = FALSE;
                    $daytimes = explode("|", $itimesstr);
                    foreach ($daytimes as $daytime) {
                        if (stripos($cal_data[$thisday][$daytime]->type, 'christmas') !== false) {
                            $xmas = TRUE;
                        }
                        if (stripos($cal_data[$thisday][$daytime]->type, 'gospel') !== false) {
                            $gsp = TRUE;
                        }
                        if (stripos($cal_data[$thisday][$daytime]->type, 'country') !== false) {
                            $cou = TRUE;
                        }
                    }
                    if ($xmas) {
?>
                                           <div class="christmas-date"></div>
                                            <?php
                    }
                    if ($gsp) {
?>
                                           <div class="gospel-date"></div>
                                            <?php
                    }
                    if ($cou) {
?>
                                           <div class="country-date"></div>
                                            <?php
                    }
?>

                                            <a href="<?= base_url() . "/shows/select/" . $show_details[0]->id . "/" . $thisday; ?>"
                                                data-toggle="tooltip" data-html="false"
                                                title="<?= strip_tags("<b>" . date("l, F j", strtotime($thisday)) . "</b>"); ?><?= $ttip; ?>"
                                                target="_parent"><?= $day; ?></a>

                                        </td>

                                        <?php
                } else {
?>
                                       <td class="cal-inst cal-past cal-inst<?= $time_list[$itimesstr]; ?>">
                                            <?= $day; ?></td>
                                        <?php
                }
            } else {
?>
                                       <td class="cal-inst<?= $time_list[$itimesstr]; ?>"><?= $day; ?></td>
                                        <?php
            }
        } else {
?>
                                       <td><?= $day; ?></td>
                                        <?php
        }
        $day++;
        $rctr++;
    }
?>
                                   </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
    $cmonth = date("Y-m-01", strtotime($cmonth . " +1 month"));
}
?>

                </div>
            </div>
        </div>
    </div>
    <div class="product-details">
        <div class="container pt-5 pb-5">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <div class="product-logo">
                        <p><img src="<?= base_url() . '/images/' . $show_details[0]->image2; ?>" alt="" /></p>
                        <!-- <h5>Adult (ages 13 and up) <?= $price_detail[0]->adult_price; ?></h5>
                        <h5>Child (12 and under) <?= $price_detail[0]->child_price; ?></h5> -->
                    </div>
                </div>
                <div class="col-lg-7 col-md-7">
                    <div class="product-text">
                        <p> <?= strip_tags($show_details[0]->body); ?></p>
                        <p><?= strip_tags($show_details[0]->summary); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<?= $this->endSection(); ?>