<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<section class="content-part pb-4">
    <div id="qr-reader" style="width:500px"></div>
    <div id="qr-reader-results"></div>
    <div style="color:#fff; font-weight:bold;text-align:center;padding:15px;"><span id="qr-result"></span></div>
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
                        {
                            //data - response from server
                            if(data == 'success') {
                                $('#qr-result').addClass("success").show().delay(5000).fadeOut();
                            } else {
                                $('#qr-result').addClass("failure").show().delay(5000).fadeOut();
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