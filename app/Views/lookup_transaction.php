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
                    <div class="col-md-12 text-left">
                        <?php if($details[0]->type == 'Cash') { ?>
                            <h3>Status : Paid Cash</h3>
                        <?php } else if($details[0]->type == 'Free') { ?>
                            <h3>Status : Comp</h3>
                        <?php } else { ?>
                            <h3>Status : Credit Card</h3>
                        <?php } ?>    
                    </div>
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
                            <th class="text-right" style="text-align:right;">Total</th>
                        </tr>
                        <?php $total_amt = 0; for($i=1; $i <= $pcount; $i++) { 
                            if($json_details["item"][$i]["qty"] > 0) { ?>
                        <tr class="item-row">
                            <td>
                                <p><?= $json_details['content'][0]['title']; ?> - <?= date('M d, Y',strtotime($json_details["item"][$i]['date'])); ?>,
                                    <?= date('h:i a',strtotime($json_details['item'][$i]['time'])); ?><br/>
                                <?= $json_details["item"][$i]['name']; ?> -
                                    <?= $json_details["item"][$i]['location']; ?> <?php if(strpos($json_details["item"][$i]['name'],'Family') !== false) { ?>
                                       [<?= $json_details['family_seats']; ?> - Preferred]
                                <?php } ?>    <br/>
                                
                                <?= $json_details["item"][$i]['venue']; ?></p>
                                
                            </td>
                            <?php $total_val = $json_details["item"][$i]["qty"] * $json_details["item"][$i]["price"]; 
                               $total_amt += $total_val;
                            ?>
                            <td class="text-right" title="Quantity"><?= $json_details["item"][$i]["qty"]; ?></td>
                            <?php if($json_details['itotal'] != number_format(0,2)){ ?>
                                <td class="text-right" title="Price">$<?= $json_details["item"][$i]["price"]; ?></td>
                            <?php }else{ ?>
                                <td class="text-right" title="Price">$0.00</td>
                            <?php } ?>
                            <?php if($json_details['itotal'] != number_format(0,2)){ ?>
                                <td class="text-right" title="Total" style="text-align:right;">$<?= number_format($total_val,2); ?></td>
                            <?php }else{ ?>
                                <td class="text-right" title="Total" style="text-align:right;">$0.00</td>
                            <?php } ?>
                        </tr>
                        <?php } } ?>
                        <?php if(isset($json_details['seats_selected'])){ ?>
                         <tr class="item-row item-row-last">
                          <?php $seats = implode(',',$json_details['seats_selected']); ?>
                            <td class="text-right" title="Total">Seats Selected - <?= $seats; ?> </td>
                        </tr> 
                        <?php } ?>
                        <tr class="total-row">

                            <td class="text-right" colspan="3"><strong>Tax</strong></td>
                            <td class="text-right" style="text-align:right;">
                            <?php if($json_details['itotal'] != number_format(0,2)){ ?>
                                <strong>$<?= getenv('salestax') ?></strong>
                                <?php }else{ ?>
                                    <strong>$0.00</strong>
                            <?php } ?>
                            </td>
                          
                        </tr>
                        <tr class="total-row">
                            <td class="text-right" colspan="3"><strong>Processing Fees</strong></td>
                            <td class="text-right" style="text-align:right;">
                            <?php if($json_details['itotal'] != number_format(0,2)){ ?>
                                <strong>$<?= getenv('processingfees') ?></strong>
                                <?php }else{ ?>
                                    <strong>$0.00</strong>
                            <?php } ?>
                            </td>
                        </tr>
                        <?php $total_amt +=  getenv('salestax') + getenv('processingfees');
                         if(!empty($json_details["code_name"])) { ?>
                        <tr class="total-row">

                            <td class="text-right" colspan="3"><strong>Discount - <?=  @$json_details["code_name"];?>
                                    (<?=  @$json_details["ccode"];?>)</strong></td>
                            <td class="text-right" style="text-align:right;">
                                <strong>$<?=  number_format(@$json_details["discount_amt"],2); ?></strong>
                            </td>
                        </tr>
                        <?php } ?>
                        
                        <tr class="total-row info">

                            <td class="text-right" colspan="3"><strong>Total</strong></td>
                            <?php if($json_details['itotal'] != number_format(0,2)){ ?>
                            <td class="text-right" style="text-align:right;"><strong>$<?=  @$total_amt;?></strong></td>
                            <?php }else{ ?>
                                <td class="text-right"><strong>$0.00</strong></td>
                            <?php } ?>
                        </tr>
                    </table>
                   
                    
                </div>
                <div class="row" id="print_div" style="display:none;">
                <center style="width: 100%; background-color: #ccc;">
      <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fff;">
    <tr>
    <td>
    <![endif]-->  
      
      <!-- Email Body : BEGIN -->
      <?php if(isset($json_details['seats_selected'])){ ?>
      <?php foreach ($json_details['seats_selected'] as $key => $val) { 
          $split = explode('-',$val);  ?>
      <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="250" style="margin: auto;" class="email-container" style="background: #fff;">     
     
      
      <!-- 1 Column Text + Button : BEGIN -->
      <tr>
        <td style="background-color: #ffffff;"><table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
            <td style="padding:0 15px 15px 15px; font-family: 'Source Sans Pro', sans-serif; line-height: 20px; color: #555555;" class="thank">
                <h2 style="word-break: break-word;
    line-height: 30px;margin: 0 0 10px; text-align:left;margin-bottom:0px;font-size: 25px; text-align:center; font-weight:600;padding: 15px 0;text-transform: uppercase;
color: #000;"><?= $json_details['content'][0]['title']; ?></h2>
<h4 style="margin: 0 0 10px; text-align:left;word-break: break-all; margin-bottom:0px;font-size: 14px; text-align:center; font-weight:600;padding:0 0 5px;
color: #000;"><?= date('D',strtotime($json_details["item"][1]['date'])); ?>,<?= date('M d, Y',strtotime($json_details["item"][1]['date'])); ?> <?= date('h:i a',strtotime($json_details['item'][1]['time'])); ?></h4>
<!-- <h5 style="margin: 0 0 10px; text-align:left;word-break: break-all; margin-bottom:0px;font-size: 16px; text-align:center; font-weight:500;padding:0 0 5px;
color: #000;">modglin, Joe</h5> -->
           </td>
          </tr>
          </table>
      </td>
      </tr>
      <!-- 1 Column Text + Button : END --> 
      
      <!-- 2 Even Columns : BEGIN -->
      <tr>
        <td style="padding: 0;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: #fff;">
            <tr> 
            <!-- Column : BEGIN -->
            <th valign="top" width="50%" class="stack-column-center">
             <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                <td style="font-family: 'Source Sans Pro', sans-serif; font-size: 14px; line-height: 23px; color: #000; padding: 0px 15px; text-align: left;" class="center-on-narrow">
                    
                    <table width="100%">
                    <tr>
                        <td width="38%" style="text-align:center; padding:5px;">
                            <p style="color: #333; font-size:12px !important; line-height:18px;text-align: left;width: 100%;">
                            Sec: <b style="float: right;"><?= $json_details["item"][1]['location']; ?></b>
                           </p>
                           <p style="color: #333; font-size:12px !important; line-height:18px;text-align: left;width: 100%;">
                            Row: <b style="float: right;"><?= $split[0]; ?></b>
                           </p>
                           <p style="color: #333; font-size:12px !important; line-height:18px;text-align: left;width: 100%;">
                            Seat: <b style="float: right;"><?= $split[1]; ?></b>
                           </p>                        
                       </td>
                        <td width="62%" style="text-align:center; padding:5px;">
                            <p style="color: #333; font-size:12px !important; line-height:18px;text-align: left;width: 100%;">
                            Processing Fee<b style="float: right;"><?= getenv('processingfees') ?></b>
                           </p>
                           <p style="color: #333; font-size:12px !important; line-height:18px;text-align: left;width: 100%;">
                            Tax <b style="float: right;"><?= getenv('salestax') ?></b>
                           </p>
                           <p style="color: #333; font-size:12px !important; line-height:18px;text-align: left;width: 100%;">
                             Total  <b style="float: right;"><?= $total_amt; ?></b>
                           </p>
                       </td>
                      </tr>
                  </table>
              </td>
              </tr>
              </table>
              </th>
            <!-- Column : END -->
             
          </tr>
          </table>
      </td>
      </tr>  
      <!-- 2 Even Columns : END --> 

      <!-- 3 Column Text + Button : BEGIN -->
      <tr>
      <td style="background-color: #ffffff;">
           <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
           <tr>
           <td style="padding:0 10px 5px; font-family: 'Source Sans Pro', sans-serif; line-height: 20px; color: #555555;" class="thank">
                <h2 style="margin: 0 0 10px; text-align:left;word-break: break-all; margin-bottom:0px;font-size: 20px; text-align:center; font-weight:500;padding: 15px 0;text-transform: uppercase;color: #000;"><?= $details[0]->randid ?></h2>
           </td>
          </tr>
          </table>
      </td>
      </tr>
      <!-- 3 Column Text + Button : END --> 

      <!-- 4 Even Columns : BEGIN -->
      <tr>
        <td style="padding: 0;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: #fff; border-top: 2px dashed #333;">
            <tr> 
            <!-- Column : BEGIN -->
            <th valign="top" width="50%" class="stack-column-center">
             <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                <td style="font-family: 'Source Sans Pro', sans-serif; font-size: 14px; line-height: 23px; color: #000; padding: 0px 15px; text-align: left;" class="center-on-narrow">
                    
                    <table width="100%">
                    <tr>
                        <td width="100%" style="text-align:center; padding:5px;">
                            <h2 style="word-break: break-word;line-height: 30px;margin: 0 0 10px;font-size: 25px;text-align: center;font-weight: 600;padding: 15px 0;text-transform: uppercase; color: #000;"><?= $json_details['content'][0]['title']; ?></h2>
                            <h4 style="margin: 0 0 10px; text-align:left;word-break: break-all; margin-bottom:0px;font-size: 20px; text-align:center; font-weight:500;padding:0 0 5px;
                                color: #000;"> <?= date('h:i a',strtotime($json_details['item'][1]['time'])); ?></h4>
                            <h5 style="margin: 0 0 10px; text-align:left;word-break: break-all; margin-bottom:0px;font-size: 16px; text-align:center; font-weight:500;padding:0 0 5px;
                                color: #000;"><?= date('D',strtotime($json_details["item"][1]['date'])); ?>, <?= date('M d, Y',strtotime($json_details["item"][1]['date'])); ?></h5>
                           <table width="50%" style="margin:0 auto;">
                               <tr>
                                   <td style="margin:0 3px;"><b><?= $json_details["item"][1]['location']; ?></b></td>
                                   <td style="margin:0 3px;"><b><?= $split[0]; ?></b></td>
                                   <td style="margin:0 3px;"><b><?= $split[1]; ?></b></td>
                               </tr>
                           </table>
                           <h2 style="margin: 0 0 10px; text-align:left;word-break: break-all; margin-bottom:0px;font-size: 20px; text-align:center; font-weight:500;padding: 15px 0;text-transform: uppercase;
color: #000;"><?= $details[0]->randid ?></h2>
                           </p>                        
                       </td>
                      </tr>
                  </table>
              </td>
              </tr>
              </table>
              </th>
            <!-- Column : END -->
             
          </tr>
          </table>
      </td>
      </tr>  
      <!-- 4 Even Columns : END --> 

    </table>
    <?php } ?>
    <?php } ?>
    </center>
                </div>
                <div class="row">
                    <div class="col-md-5"><a class="btn btn-primary" href="<?= base_url().'/transactions/update_transaction/'.$details[0]->id.'/lookup'; ?>">Check In</a></div>
                    <div class="col-md-7">
                        <div class="row">
                            <?php if(isset($json_details['seats_selected'])){ ?>
                            <div class="col-md-6"><a class="btn btn-success" onclick="printDiv()">Print Ticket</a>
                            </div>
                            <?php } ?>
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
    var divContents = document.getElementById("print_div").innerHTML;
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