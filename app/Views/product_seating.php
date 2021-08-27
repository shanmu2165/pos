<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
    <style>
        .blink_me {
               animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        .seat {
  background: url('../public/images/available.png') no-repeat;
  background-size: 50px;
  height: 50px;
  width: 50px;
  margin: 3px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  color: #fff;
  text-shadow: 1px 1px 1px #333;
  font-size: 18px;
}

.booked {
  background: url('../public/images/booked.png') no-repeat; background-size: 50px;cursor: not-allowed !important;
}
.special {
  background: url('../public/images/special.png') no-repeat; background-size: 50px;
}
.selected {
  background: url('../public/images/selected.png') no-repeat; background-size: 50px;
}
.seat:nth-of-type(2) {
  margin-right: 0px;
}
.seat:nth-last-of-type(2) {
  margin-left: 0px;
}
.seat:not(.occupied):hover {
  cursor: pointer;
  transform: scale(1.2);
}
.showcase .seat:not(.occupied):hover {
  cursor: default;
  transform: scale(1);
}

.showcase .seat:not(.occupied):hover {
  cursor: default;
  transform: scale(1);
}
.product-seats .showcase {
  display: flex;
  justify-content: space-between;
  list-style-type: none;
  background: rgba(0,0,0,0.1);
  padding: 5px 10px;
  border-radius: 5px;
  color: #777;
}
.product-seats .showcase li {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 10px;
}
.product-seats .showcase li small {
  margin-left: 2px;
}


.overlay{
    display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999;
    background: rgba(255,255,255,0.8) url("../public/images/loader_img.gif") center no-repeat;
}
/* Turn off scrollbar when section element has the loading class */
section.loading{
    overflow: hidden;   
}
/* Make spinner image visible when section element has the loading class */
section.loading .overlay{
    display: block;
}
    </style>
    <link rel="stylesheet" href="<?= base_url('css/H-confirm-alert.css'); ?>">
    <script src="<?= base_url('js/H-confirm-alert.js'); ?>" ></script>
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
                    
                    
                    <div class="col-lg-12 col-md-12">
                        <div class="product-seats">
                            <?php if(!empty($seats)) { ?>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <button type="button" class="btn btn-primary">Assign Best Available</button>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <button type="button" class="btn btn-primary">Clear Set Selections</button>
                                </div>
                            </div>
                            <ul class="showcase">
                                <li>
                                  <div class="seat "></div>
                                  <small>Available</small>
                                </li>
                                <li>
                                  <div class="seat selected"></div>
                                  <small>Selected</small>
                                </li>    
                                <li>
                                  <div class="seat special"></div>
                                  <small>Special</small>
                                </li> 
                                <li>
                                  <div class="seat booked"></div>
                                  <small>Booked</small>
                                </li>    
                            </ul>
                            
                            <div style="display:none;">
                              <span id="content"><?= $content; ?></span>
                              <span id="venue"><?= $venueid; ?></span>
                              <span id="date"><?= $rdate; ?></span>
                              <span id="time"><?= $time; ?></span>
                              <span id="section"><?= $venue_sec[0]->name; ?></span>
                              <span id="location"><?= $location; ?></span>
                              <span id="priceset"><?= $priceset; ?></span>
                              <span id="pcount"><?= $pcount; ?></span>
                              <span id="total_price"><?= $total_price; ?></span>
                              <span id="family_seats"><?= $family_seats; ?></span>
                              <span id="total_seats_sel"><?= $total_seats_sel; ?></span>
                              <!-- <span id="date"></span> -->
                            </div>
                            <?php } ?>
                            <div class="seating">
                              
                            <?php if(!empty($seats)) {
                                    for($i=1; $i<=$venue_sec[0]->total_rows; $i++) { ?>
                                       <span>Row <?= $i ?></span>
                                      <div class="row">    
                                      <?php  foreach($seats as $seat) {  
                                          if($seat->seatrow == $i) { 
                                            $status = '';
                                          if(!empty($already_booked) && in_array($i."-".$seat->seat, $already_booked)){ $status = "booked";} ?>
                                        <div class="seat <?= $status; ?>" data-value="<?= $i."-".$seat->seat; ?>"><?= $seat->seat; ?>
                                       
                                      </div>
                                        <?php } } ?> 
                                      </div>
                                     
                            <?php   }  ?> 
                                       <p class="text">
                                       You have selected <span id="count">0</span>
                                     </p>
                                     
                                     <?php  } else {?>
                                       <div style="text-align:center;"><span style="color:red !important;" class="blink_me">No Seats Assigned Yet!</span></div>
                            <?php } ?>

                            <button type="button" id="tick_submit" class="btn btn-primary">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
    </section>
    <script type="text/javascript">
        const container = document.querySelector('.seating');
        const seats = document.querySelectorAll('.row .seat:not(.booked)');
        let count = document.getElementById('count');
        let selectedSeatsArr = [];

        function updateSelectedCount(selectedCount) {
          count.innerText = selectedCount;
          console.log('countText:',count.innerText);
          console.log("Final Array:",selectedSeatsArr)
        }

        // Remove seat no without affecting index
        function remove_array_element(array, n)
        {
          var index = array.indexOf(n);
          if (index > -1) {
            array.splice(index, 1);
          }
          return array;
        }

        //test start
        $(".seating .seat").on("click", function(){
          const seatValue = $(this).data("value");
            
          if(selectedSeatsArr.includes(seatValue) == false) {
            //Push seat value to global variable - for using in PHP
             selectedSeatsArr.push(seatValue);
          } else { 
            //selectedSeatsArr.pop(seatValue);
            selectedSeatsArr = remove_array_element(selectedSeatsArr,seatValue);
          }
        });
        //test end

//Seat click event
container.addEventListener('click', e => {
  console.log("seat clicked");
  const selectedSeats = document.querySelectorAll('.row .seat.selected');
  let selectedSeatsCount = selectedSeats.length;
  var totalSelectedSeats = parseInt($("#total_seats_sel").text()) + 1;  

  if (e.target.classList.contains('seat') &&
     !e.target.classList.contains('booked') && e.target.classList['value']!= 'seat selected') {
       
      if(selectedSeatsCount < totalSelectedSeats) {
        //console.log('here1')
        e.target.classList.toggle('selected');
        
        updateSelectedCount(selectedSeatsCount);

      } else {
       //console.log(count);
              $.confirm.show({
                "message":"Seat selected by you exceeded your ticket limits.",
                "hideNo":true,// hide cancel button
                "yesText":"OK",
                "yes":function (){
                },
              })
      }
  } else {
    e.target.classList.toggle('selected');
          selectedSeatsCount = selectedSeatsCount - 1;
       
        console.log(selectedSeatsCount)
        updateSelectedCount(selectedSeatsCount);
  }
});

$("#tick_submit").click(function(){
 
          
        $.ajax({
            type: "POST",
            url: '<?= base_url() . '/check_selectedseats_booked'; ?>',
            data: {
              content: $('#content').text(),
              venue: $('#venue').text(), 
              date: $('#date').text(),
              time: $('#time').text(),
              section: $('#section').text(),
              seat_arr: selectedSeatsArr
            },
            beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
              $("section").addClass("loading");
            },
            success: function(data){
              
              $("section").removeClass("loading"); 
              //console.log("Result",data);
              if(data === 'false') {
                
                $.ajax({
                      type: "POST",
                      url: '<?= base_url() . '/lock_seats'; ?>',
                      data: {
                        content: $('#content').text(),
                        venue: $('#venue').text(), 
                        date: $('#date').text(),
                        time: $('#time').text(),
                        section: $('#section').text(),
                        location: $('#location').text(),
                        priceset: $('#priceset').text(),
                        pcount: $('#pcount').text(),
                        total_price: $('#total_price').text(),
                        family_seats:$('#family_seats').text(),
                        tot_seats_selected : $('#total_seats_sel').text(),
                        seat_arr: selectedSeatsArr
                      },
                      success: function(data){
                        $("#result").append(data);
                        
                       window.location.href = "<?= base_url() . '/cart/'; ?>" ;
                      },
                      error: function( jqXhr, textStatus, errorThrown ){
                        console.log( errorThrown );
                      } 
                });

              } else {
               
                $.confirm.show({
                        "message":"Seat(s) selected by you is already booked. Please  select your seats again!",
                        "hideNo":true,// hide cancel button
                        "yesText":"OK",
                        //"type":"danger",
                        "yes":function (){
                          $("section").addClass("loading");
                          setTimeout(location.reload.bind(location), 2000);
                        },
                        
                })
                console.log(data);
              }
              
            },
            error: function( jqXhr, textStatus, errorThrown ){
                        console.log( errorThrown );
            } 
        });      
});
    </script>
<?= $this->endSection(); ?>
