<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<section class="content-part pb-4">
    <div class="booking-details pay-options min-normal bg-white">
        <div class="cart-title pt-4 pb-4">
            <div class="container pb-5">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2>Transaction Details</h2>
                    </div>
                </div>
            </div>
            <div class="container pt-2 pb-2">
                
                <div class="row" id="GFG">
                    <!-- <div class="col-md-12 text-center"></div> -->
                        <table style="width:500px;">
                            <tr>
                                <td style="padding:5px;">Transaction Id #</td>
                                <td style="padding:5px;"><?= $details[0]->randid ?></td>
                            </tr>
                            <tr>
                                <td style="padding:5px;">Name</td>
                                <td style="padding:5px;"><?= $details[0]->name ?></td>
                            </tr>
                            <tr>
                                <td style="padding:5px;">Email</td>
                                <td style="padding:5px;"><?= $details[0]->email ?></td>
                            </tr>
                            <tr>
                                <td style="padding:5px;">Phone No</td>
                                <td style="padding:5px;"><?= $details[0]->phone ?></td>
                            </tr>
                            <?php if(!empty($details[0]->ticket_status)) { ?>
                            <tr>
                                <td style="padding:5px;">Status</td>
                                <td style="padding:5px;"><?php if($details[0]->ticket_status == 1) { echo "Issued"; } else { echo "Checked-in";}?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    
                    <?php if(!empty($details[0]->ticket_status)) { ?>
                    <!-- <div class="col-md-6 pb-2">
                        Status :
                        <?php if($details[0]->ticket_status == 1) { echo "Issued"; } else { echo "Checked-in";}?>
                    </div> -->
                    <?php } ?>
                    <table class="table table-xs">
                        <tr>
                            <th>Description</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Each</th>
                            <th class="text-right">Total</th>
                        </tr>
                        <?php  for($i=1; $i <= $pcount; $i++) { 
                            if($json_details["qty".$i] > 0) {?>
                        <tr class="item-row">
                            <td>
                                <p><?= $json_details['ticket_title']; ?> - <?= $json_details['date']; ?>,
                                    <?= $json_details['time']; ?><br/>
                                <?= $json_details["ticket_type".$i]; ?> -
                                    <?= $json_details['ticket_location']; ?> <?php if(strpos($json_details["ticket_type".$i],'Family') !== false) { ?>
                                       [<?= $json_details['family_seats']; ?> - Preferred]
                                <?php } ?>    <br/>
                                
                                <?= $json_details['ticket_venue']; ?></p>
                                
                            </td>
                            <?php $total_val = $json_details["qty".$i] * $json_details["price".$i]; ?>
                            <td class="text-right" title="Amount"><?= $json_details["qty".$i]; ?></td>
                            <td class="text-right" title="Price">$<?= $json_details["price".$i]; ?></td>
                            <td class="text-right" title="Total">$<?= number_format($total_val,2); ?></td>
                        </tr>
                        <?php } } ?>
                        <!-- <tr class="item-row item-row-last">
                            <td>
                                <p>Re-Vibe - May 15, 2021, 8:00pm</p>
                                <p>Adult(ages 13 and up)</p>
                                <p>Center Hughes Brothers Theatre</p>
                            </td>

                            <td class="text-right" title="Amount">3</td>
                            <td class="text-right" title="Price">4.00</td>
                            <td class="text-right" title="Total">12.00</td>
                        </tr> -->
                        <tr class="total-row">

                            <td class="text-right" colspan="3"><strong>Tax</strong></td>
                            <td class="text-right">
                                <strong>$<?= getenv('salestax') ?></strong>
                            </td>
                          
                        </tr>
                        <tr class="total-row">
                            <td class="text-right" colspan="3"><strong>Processing Fees</strong></td>
                            <td class="text-right">
                                <strong>$<?= getenv('processingfees') ?></strong>
                            </td>
                        </tr>
                        <?php if(!empty($json_details["code_name"])) { ?>
                        <tr class="total-row">

                            <td class="text-right" colspan="3"><strong>Discount - <?=  @$json_details["code_name"];?>
                                    (<?=  @$json_details["ccode"];?>)</strong></td>
                            <td class="text-right">
                                <strong>$<?=  number_format(@$json_details["discount_amt"],2); ?></strong>
                            </td>
                        </tr>
                        <?php } ?>
                        
                        <tr class="total-row info">

                            <td class="text-right" colspan="3"><strong>Total</strong></td>
                            <td class="text-right"><strong>$<?=  $json_details["tot_amount"];?></strong></td>
                        </tr>
                    </table>
                   
                    
                </div>
                <div class="row">
                    <div class="col-md-5"></div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6"><a class="btn btn-success" onclick="printDiv()">Print Ticket</a>
                            </div>
                            <div class="col-md-6"> <a class="btn btn-danger" href="<?= base_url().'/shows/' ?>">Back
                                    To Home</a></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<script>
function printDiv() {
    var divContents = document.getElementById("GFG").innerHTML;
    var a = window.open('', '', 'height=300, width=600');
    a.document.write('<html>');
    a.document.write('<body>');
    a.document.write(divContents);
    a.document.write('</body></html>');
    a.document.close();
    a.focus();
    
    a.print();
    a.close();
    
}
</script>
<!-- function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=100,width=600');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
} -->
<?= $this->endSection(); ?>