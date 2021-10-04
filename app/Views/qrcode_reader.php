<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<section class="content-part pb-4">

    <div id="qr-reader" style="width:500px"></div>
    <div id="qr-reader-results"></div>
    <div style="color:#fff; font-weight:bold;text-align:center;padding:15px;">
        <span id="qr-result">     
        </span>
    </div>
    <div class="container-fluid">
    <div class="row">
    <div class="col-lg-4 col-md-8"></div>
            <div class="col-lg-4 col-md-4">
    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert" style="display:none; ">These seats have been checked in already.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
</div>
</div>
</div>
</section>
<script src="<?= base_url('js/html5-qrcode.min.js'); ?>"></script>
<script>
    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var resultContainer = document.getElementById('qr-reader-results');
        var lastResult, countResults = 0;
        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                // Handle on success condition with the decoded message.
                console.log(`Scan result ${decodedText}`, decodedResult);
                //$('#qr-result').html('<div class="payment_header"><div class="check"><i class="fa fa-check" aria-hidden="true"></i></div></div>');
                    $.ajax({
                        url : '<?= base_url()."/shows/lookup"; ?>',
                        type: "POST",
                        data : {
                            randid : `${decodedText}`
                        },
                        success: function(data, textStatus, jqXHR)
                        {  //console.log("ewghew",JSON.stringify(data));
                            var parsed = $.parseJSON(data);
                            // console.log('parsed', parsed);
                            var url = '<?= base_url().'/transactions/update_transaction/' ?>';
                            //console.log("valData",parsed.id);
                            //data - response from server
                            // if(data == 'success') {
                            //     $('#qr-result').addClass("success").show().delay(5000).fadeOut();
                            // } else {
                            //     $('#qr-result').addClass("failure").show().delay(5000).fadeOut();
                            // }
                            if(parsed.seat_status == 1){
                                $('#qr-result').html("<p style='color:#fff;'>Show Name: "+parsed.show_name+"<br/>  Name: "+ parsed.name+" <br/>Date: "+ parsed.date+"<br/>Time: "+ parsed.time+" <br/><a href="+url+parsed.id+" class='btn btn-success'>Check-in</a></p>");
                            }else{
                                $('#qr-result').html("<p style='color:#fff;'>Show Name: "+parsed.show_name+"<br/>   Name: "+ parsed.name+" <br/>Date: "+ parsed.date+"<br/>Time: "+ parsed.time+" <br/><a href="+url+parsed.id+" class='btn btn-success disabled'>Check-in</a></p>");
                                $('#alert').show();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            $('#qr-result').text('Something Went Wrong!');
                        }
                    });
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
<?= $this->endSection(); ?>