<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<!-- <script src="<?= base_url('js/client.js'); ?>" defer></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<style>
.alert{
    display: none;
}
.error{
  color:red;
}
</style>
<section class="content-part pb-4">
    <div class="booking-details pay-option min-normal bg-white">
        <div class="cart-title pt-4 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2>Step 4: Checkout </h2>
                    </div>
                </div>
            </div>
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
        
                    <strong class="me-auto">Terminal Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                       Trying to connect to stripe terminal please wait.
                    </div>
                </div>
                <div id="connectToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
        
                    <strong class="me-auto">Terminal Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                      Terminal connected successfully!
                    </div>
                </div>
                <div id="connectFailToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
        
                    <strong class="me-auto">Terminal Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                      Failed to connect to terminal,Please check with terminal!
                    </div>
                </div>
            </div>
            <div class="container pt-5 pb-5">
                <?php //print_r($_SESSION); die; ?>
                    <!-- <form class="pt-5" action="<?= base_url().'/payment_success'; ?>" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-3 text-right">                           
                            <input type="submit" name="pay" class="btn btn-primary" value="Stripe" />
                        </div>
                        <div class="col-md-3 text-left">                           
                            <input type="submit" name="pay" class="btn btn-primary" value="Cash" />
                        </div>
                    </div>
                    </form> -->
                    <div class="col-lg-12">
                    <div class="make-cart">
                        <!--<h4>Make Card Payment</h4> -->
                        <form class="pt-5" action="<?= base_url().'/payment_success'; ?>" method="POST" name="pay_form">
                        <?= csrf_field() ?>
                        <div class="col-lg-4 col-md-6 pb-4">
                          <a href="<?= $cart_url; ?>" class="btn btn-primary">Go Back</a>
                        </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <label>First Name<sup style="color:red;">*</sup></label>
                                    <input class="form-control" type="text" placeholder="First Name" name="fname">
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" placeholder="Last Name" name="lname">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6 pr-0">
                                    <label>Contact Phone<sup style="color:red;">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Phone Number" id="phone" onKeyPress="if(this.value.length==13) return false;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"; name="phone" required="" />
                                </div>
                                <div class="col-lg-6 col-md-6 pr-0">
                                    <label>Email Address<sup style="color:red;">*</sup></label>
                                    <input class="form-control" type="email" placeholder="Email Address" name="email" required="">
                                    <?php $amount = number_format(($_SESSION['cart']['total']*100) , 0, '', ''); ?>
                                    <input id="amount-input" type="hidden" value="<?= $amount; ?>">
                                    <input type="hidden" name="capture_pay" id="capture_pay">
                                </div>
                            </div>
                            <?php if($_SESSION['cart']['total'] > 0) { ?>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6 pr-0">
                                    <label>Payment Type<sup style="color:red;">*</sup></label>
                                    <input type="radio" placeholder="Stripe" name="payment_type" id="stripe" value="Stripe">
                                    <label>Stripe</label>
                                    <input type="radio" placeholder="Cash" name="payment_type" id="cash" value="Cash">
                                    <label>Cash</label>
                                    <input type="radio" placeholder="Free" name="payment_type" id="free" value="Free">
                                    <label>Free</label>
                                    <br>
                                    <label id="payment_type-error" class="error" for="payment_type"></label>
                                </div>
                                <!-- <div class="col-lg-6 col-md-6 pr-0">
                                    <label>Email Address<sup>*</sup></label>
                                    <input class="form-control" type="email" placeholder="Email Address">
                                </div> -->
                            </div>
                            <div class="row mb-3">
                                <div id="Stripe" class="desc row">
                                  
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                    <button type="button" class="btn btn-success" id="collect-button" style="display:none;">Collect Payment</button>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                    <button type="button" class="btn btn-info" id="capture-button" style="display:none;">Capture Payment</button>
                                    </div>
                                    
                                </div>
                                <div id="Cash" class="desc row" style="display: none;">
                                   
                                </div>
                                
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-sm-12" id="logs" style="display: none;"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12 col-md-12 text-center">
                                    <!-- <a href="#"><button>Pay $66.00 Now</button></a> -->
                                    <input type="submit" class="btn btn-success" id="submit_btn" value="Submit"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>

    </div>
</section>
<script>

//Form Validation Part 
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='pay_form']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      fname: "required",
      payment_type: "required",
      email: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
      phone: {
        required: true,
        minlength: 13
      }
    },
    // Specify validation error messages
    messages: {
      fname: "First name is required",
      payment_type: "Payment type is required",
      phone: {
        required: "Contact phone is required",
        minlength: "Contact number should be atleast 10 characters"
      },
      email: "Email address is required"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});

//Payment type radio option hide/show
$(document).ready(function() {
    $("div.desc").hide();
    $("input[name$='payment_type']").click(function() {
        var test = $(this).val();
        if(test == 'Stripe'){
          $('#submit_btn').prop('disabled', true);
        }
        if(test == 'Cash' || test == 'Free'){
          $('#submit_btn').prop('disabled', false);
        }
        $("div.desc").hide();
        $("#" + test).show();
       
    });

    //Client Stripe
    //console.log('before');
    var terminal = StripeTerminal.create({
    onFetchConnectionToken: fetchConnectionToken,
    onUnexpectedReaderDisconnect: unexpectedDisconnect,
    });
    //console.log('after');

function unexpectedDisconnect() {
  // In this function, your app should notify the user that the reader disconnected.
  // You can also include a way to attempt to reconnect to a reader.
  console.log("Disconnected from reader")
}

function fetchConnectionToken() {
  //alert('good');
  // Do not cache or hardcode the ConnectionToken. The SDK manages the ConnectionToken's lifecycle.
  return fetch('<?= base_url('/get_token'); ?>', { method: "POST" })
    .then(function (response) {
     //console.log("data full1", response)
      return response.json();
    })
    // .err(function(error) {
    //     //console.log("data full12", response)
    //     console.log("error",error)
    // })
    .then(function (data) {
      //console.log("data full3", data.secret)  
      return data.secret;

    });
}

// Handler for a "Discover readers" button
function discoverReaderHandler() {
  const location_id = "<?= $location[0]->location_id; ?>";
  const config = { simulated: false, location: location_id };
  terminal.discoverReaders(config).then(function (discoverResult) {
    
    if (discoverResult.error) {
        //alert('yes');
      console.log('Failed to discover: ', discoverResult.error);
    } else if (discoverResult.discoveredReaders.length === 0) {
      console.log('No available readers.');
    } else {
        //alert('yes11');
      discoveredReaders = discoverResult.discoveredReaders;
      console.log('terminal.discoverReaders', discoveredReaders);
    }
  });
}

var connectTerminal = null;
// Handler for a "Connect Reader" button
function connectReaderHandler(discoveredReaders) {
  // Just select the first reader here.
  var selectedReader = discoveredReaders[1];
  terminal.connectReader(selectedReader, {fail_if_in_use: true}).then(function (connectResult) {
    connectTerminal = connectResult;
    console.log('terminal-confirm', connectTerminal);
    if (connectResult.error) {
      console.log('Failed to connect: ', connectResult.error);
      $('#connectFailToast').toast('show');
    } else {
      console.log('Connected to reader: ', connectResult.reader.label);
      $('#connectToast').toast('show');
      $('#collect-button').css('display', 'block');
      log('terminal.connectReader', connectResult)
    }
  });
}

function fetchPaymentIntentClientSecret(amount) {
  const bodyContent = JSON.stringify({ amount: amount });

  return fetch('<?= base_url('/create_intent'); ?>', {
    method: "POST",
    headers: {
      'Content-Type': 'application/json'
    },
    body: bodyContent
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      return data.client_secret;
    });
}

function collectPayment(amount) {
    
  fetchPaymentIntentClientSecret(amount).then(function (client_secret) {
    terminal.setSimulatorConfiguration({ testCardNumber: '4242424242424242' });
    terminal.collectPaymentMethod(client_secret).then(function (result) {
      if (result.error) {
        // Placeholder for handling result.error
      } else {
        log('terminal.collectPaymentMethod', result.paymentIntent);
        terminal.processPayment(result.paymentIntent).then(function (result) {
          if (result.error) {
            console.log(result.error)
          } else if (result.paymentIntent) {
            paymentIntentId = result.paymentIntent.id;
            $('#capture-button').css('display', 'block');
            log('terminal.processPayment', result.paymentIntent);
          }
        });
      }
    });
  });
}



function capture(paymentIntentId) {
  $('#submit_btn').prop('disabled', false);
  return fetch('<?= base_url('/capture_pay'); ?>', {
    method: "POST",
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ "id": paymentIntentId })
   
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      //log('server.capture', data);
      //console.log('server.capture',data.json())
      $('#capture_pay').val(JSON.stringify(data));
    });
}

var discoveredReaders;
var paymentIntentId;

//const discoverButton = document.getElementById('discover-button');

const checkButton = document.getElementById('stripe');
checkButton.addEventListener('click', async (event) => {
    if(discoveredReaders == null) {
        discoverReaderHandler();
        //console.log(discoveredReaders);
        setTimeout(function(){
            //checkButton.addEventListener('load', async (event) => {
                
                $('#liveToast').toast('show');  
            connectReaderHandler(discoveredReaders);
        //     $(document).trigger("add-alerts", [
        //   {
        //     "message": "Your account updated successfully.",
        //     "priority": 'success'
        //   }
        // ]);
                

            //});
        }, 5000); //run this after 5 seconds
    }
});


//const connectButton = document.getElementById('connect-button');


const collectButton = document.getElementById('collect-button');
collectButton.addEventListener('click', async (event) => {
  amount = document.getElementById("amount-input").value
  collectPayment(amount);
});

const captureButton = document.getElementById('capture-button');
captureButton.addEventListener('click', async (event) => {
  capture(paymentIntentId);
});

function log(method, message) {
  var logs = document.getElementById("logs");
  var title = document.createElement("div");
  var log = document.createElement("div");
  var lineCol = document.createElement("div");
  var logCol = document.createElement("div");
  title.classList.add('row');
  title.classList.add('log-title');
  title.textContent = method;
  log.classList.add('row');
  log.classList.add('log');
  var hr = document.createElement("hr");
  var pre = document.createElement("pre");
  var code = document.createElement("code");
  code.textContent = formatJson(JSON.stringify(message, undefined, 2));
  pre.append(code);
  log.append(pre);
  logs.prepend(hr);
  logs.prepend(log);
  logs.prepend(title);
}

function stringLengthOfInt(number) {
  return number.toString().length;
}

function padSpaces(lineNumber, fixedWidth) {
  // Always indent by 2 and then maybe more, based on the width of the line
  // number.
  return " ".repeat(2 + fixedWidth - stringLengthOfInt(lineNumber));
}

function formatJson(message) {
  var lines = message.split('\n');
  var json = "";
  var lineNumberFixedWidth = stringLengthOfInt(lines.length);
  for (var i = 1; i <= lines.length; i += 1) {
    line = i + padSpaces(i, lineNumberFixedWidth) + lines[i - 1];
    json = json + line + '\n';
  }
  return json
}

});

$("input[name='phone']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
});

</script>
<script src="https://js.stripe.com/terminal/v1/"></script>
<?= $this->endSection(); ?>