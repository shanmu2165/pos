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
    </style>
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
                    
                    <!-- <div class="col-lg-6 col-md-12">
                        <div class="ticket-details">
                            <h4>Purchase Tickets for
                                the 8:00 pm Show on
                                May 15, 2021</h4>
                            <div class="step1-ticket">
                                <h2>Step 1: Select A Section</h2>
                                <div class="tsec">
                                    <span class="dgrey"><a href="#">Left</a></span>
                                    <span class="dblue"><a href="#">Left</a></span>
                                    <span class="dgreen"><a href="#">Left</a></span>
                                </div>
                                <div class="tsec">
                                    <span class="dgrey"><a href="#">center</a></span>
                                    <span class="dblue"><a href="#">center</a></span>
                                    <span class="dgreen"><a href="#">center</a></span>
                                </div>
                                <div class="tsec">
                                    <span class="dgrey"><a href="#">Right</a></span>
                                    <span class="dblue"><a href="#">Right</a></span>
                                    <span class="dgreen"><a href="#">Right</a></span>
                                </div>
                            </div>
                            <div class="step2-ticket">
                                <table class="table mt-4" style="border:none;">
                                    <tr>
                                        <th>
                                            <h4>Regular Seating</h4>
                                        </th>
                                        <th></th>
                                        <th>
                                            <h4>Quantity</h4>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Adult (ages 13 and up)</h6>
                                        </td>
                                        <td><strong>$30.00</strong></td>
                                        <td>
                                            <div class="qty-input">
                                                <button class="qty-count qty-count--minus" data-action="minus"
                                                    type="button">-</button>
                                                <input class="product-qty" type="number" name="product-qty" min="0"
                                                    max="10" value="1">
                                                <button class="qty-count qty-count--add" data-action="add"
                                                    type="button">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Child (12 and under)</h6>
                                        </td>
                                        <td><strong>$0.00</strong></td>
                                        <td>
                                            <div class="qty-input">
                                                <button class="qty-count qty-count--minus" data-action="minus"
                                                    type="button">-</button>
                                                <input class="product-qty" type="number" name="product-qty" min="0"
                                                    max="10" value="1">
                                                <button class="qty-count qty-count--add" data-action="add"
                                                    type="button">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div> -->
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
                            <?php } ?>
                            <div class="seating">
                            <?php if(!empty($seats)) {
                                    for($i=1; $i<=$venue_sec[0]->total_rows; $i++) { ?>
                                       <span>Row <?= $i ?></span>
                                      <div class="row">    
                                      <?php  foreach($seats as $seat) {  
                                          if($seat->seatrow == $i) { ?>
                                        <div class="seat"><?= $seat->seat; ?></div>
                                        <?php } } ?> 
                                      </div>
                                     
                            <?php   }  ?> 
                                       <p class="text">
                                       You have selected <span id="count">0</span>
                                     </p>
                                     <?php  } else {?>
                                       <div style="text-align:center;"><span style="color:red !important;" class="blink_me">No Seats Assigned Yet!</span></div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </section>
    <script type="text/javascript">
const container = document.querySelector('.seating');
const seats = document.querySelectorAll('.row .seat:not(.booked)');
const count = document.getElementById('count');


//Update total and count
function updateSelectedCount(selectedCount) {
  // const selectedSeats = document.querySelectorAll('.row .seat.selected');
  // const selectedSeatsCount = selectedSeats.length;
  //console.log('here12')
  count.innerText = selectedCount;
}

//Seat click event
container.addEventListener('click', e => {
  const selectedSeats = document.querySelectorAll('.row .seat.selected');
  let selectedSeatsCount = selectedSeats.length;

  if (e.target.classList.contains('seat') &&
     !e.target.classList.contains('booked') && e.target.classList['value']!= 'seat selected') {
       
      if(selectedSeatsCount < 11) {
        console.log('here1')
        e.target.classList.toggle('selected');
        updateSelectedCount(selectedSeatsCount);
        
      } else {
       console.log(count);
        alert("Maximum seat selection exceeded!")
      }
  } else {
    e.target.classList.toggle('selected');
    console.log('here13')
          selectedSeatsCount--;
          console.log('Untoggle Count:'+selectedSeatsCount);
          updateSelectedCount(selectedSeatsCount);
  }
  
  // if(selectedSeatsCount < 11) {
      
  // }
  
});
</script>
<?= $this->endSection(); ?>
