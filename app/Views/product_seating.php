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
    background-size: 20px;
    height: 40px;
    width: 20px;
    margin: 3px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #000;
    text-shadow: 0px 0px 0px #333;
    font-size: 12px;
    text-align: center;
    background-position: center top;
    padding-top: 15px;
}

.booked {
  background: url('../public/images/booked.png') no-repeat; background-size: 20px;cursor: not-allowed !important;background-position: center top;
}
.special {
  background: url('../public/images/special.png') no-repeat; background-size: 20px;background-position: center top;
}
.selected {
  background: url('../public/images/selected.png') no-repeat; background-size: 20px;background-position: center top;
}
.seat:nth-of-type(2) {
  margin-right: 0px;
}
.seat:nth-last-of-type(2) {
  margin-left: 0px;
}
.seat:not(.occupied):hover {
  cursor: pointer;
  transform: scale(1.02);
}
.showcase .seat:not(.occupied):hover {
  cursor: default;
  transform: scale(1);
}
.showcase .seat:not(.occupied):hover {
  cursor: default;
  transform: scale(1);
}
.showcase .seat {
    height: 20px;
}
.product-seats .showcase {
  display: flex;
  justify-content: space-between;
  list-style-type: none;
  background: rgba(0,0,0,0.1);
  padding: 5px 10px;
  border-radius: 5px;
  color: #777;
  width: 768px;
  margin: 0 auto 15px;
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
.seat.empty {
    background: transparent;
    text-indent: -10000px;
    width: 20px;
    margin: 0!important;
    height: a;
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
.seating button#tick_submit {
    width: auto;
    margin: 0  auto;
}
.seating{
  pointer-events: none;
}
.seating .seat, .seating #tick_submit{
  pointer-events: auto;
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
                                    <!--<button type="button" class="btn btn-primary">Assign Best Available</button>-->
                                    <button type="button" class="btn btn-primary" id="assign_best">Assign Best Available</button>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <button type="button" class="btn btn-primary" id="clear_seat">Clear Seat Selections</button>
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
                              
                            <?php if(!empty($seats)  && count($row_names) == $venue_sec[0]->total_rows) {
                                    for($i=0; $i< $venue_sec[0]->total_rows; $i++) { ?>
                                       
                                      <div class="row justify-content-center"> 
                                      <span> <?= $row_names[$i]; ?></span>   
                                      <?php  foreach($seats as $seat) { //print_r($seat); die; 
                                          if($seat->seatrow == $row_names[$i]) { 
                                            $status = '';
                                          if(!empty($already_booked) && in_array($row_names[$i]."-".$seat->seat, $already_booked)){ 
                                            $status = "booked";
                                            } else if($seat->seattype == 'wheelchair' ) {?>
                                            <?php $status = "special";
                                             } ?>
                                          <?php if($seat->space_left == 1) {?>
                                            <div class="seat empty"></div>
                                          <?php } 
                                          if( $seat->seattype == 'blocked')  {?>  
                                            <div class="seat empty">
                                        <?php } else { ?>
                                          <div class="seat <?= $status; ?>" data-value="<?= $row_names[$i]."-".$seat->seat; ?>" id="<?= $row_names[$i]."-".$seat->seat; ?>"><?= $seat->seat; ?>
                                        <?php }  ?>
                                      </div>
                                      <?php if($seat->space_right == 1) {?>
                                            <div class="seat empty"></div>
                                          <?php } ?>  
                                        <?php } } ?> 
                                      </div>
                                     
                            <?php   }  ?> 
                                       <p class="text" style="visibility:hidden;">
                                       You have selected <span id="count">0</span>
                                     </p>
                                     
                                     <?php  } else {?>
                                       <div style="text-align:center;"><p style="color:red !important;" class="blink_me">No Seats Assigned Yet!</p></div>
                            <?php } ?>
                             <?php if(empty($seats) || count($row_names) != $venue_sec[0]->total_rows) { ?>
                              <button type="button" id="tick_submit" class="btn btn-primary"><a href="<?= $_SESSION['ccancel_url']; ?>">Go Back</a></button>
                              <?php } else { ?>
                            <button type="button" id="tick_submit" class="btn btn-primary">Proceed</button>
                            <?php } ?>  
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
    </section>
    <script>
        const container = document.querySelector('.seating');
        const seats = document.querySelectorAll('.row .seat:not(.booked)');
        let count = document.getElementById('count');
        let selectedSeatsArr = [];
        var totalSelectedSeats = parseInt($("#total_seats_sel").text()) + 1;
        var totSelSeats = parseInt($("#total_seats_sel").text());
        var booked_arr = <?= json_encode(@$already_booked); ?>;
        var total_rows = <?= json_encode(@$total_rows); ?>;
        var total_seats_perrow = <?= json_encode(@$seats_prow); ?>;
          let formattedSeatsPerRow = formatArrayToObj(total_seats_perrow);
          // console.log('total_rows', total_rows);
          console.log('Seats Per',formattedSeatsPerRow);
          var RowNames = <?= json_encode(@$row_names); ?>;
          // console.log('rowNames', RowNames);
          var keys = Object.keys(RowNames);
        //  console.log('key', keys);
          var json = {};
          for(var i=0; i<=keys[total_rows-1]; i++){
            var index = RowNames[i];
            var totalSeats = formattedSeatsPerRow[index][0];
            if(booked_arr != null){
                let formattedBookedTicket = formatArrayToObj(booked_arr);
                if(formattedBookedTicket[index]){
                  var totalSeatsBooked = formattedBookedTicket[index].length;
                  var seatsBooked = formattedBookedTicket[index]
                }else{
                  var totalSeatsBooked = 0;
                  var seatsBooked = [0];
                }
              }else{
                var totalSeatsBooked = 0;
                var seatsBooked = [0];
              }
              json[i] = {rowName:index, totalSeats:parseInt(totalSeats), totalSeatsBooked:totalSeatsBooked , seatsBooked:seatsBooked};
          }
          console.log('bestSeat', json);



        
        
        // Merge them recursively.
        var newJson = $.extend(true, {}, booked_arr, total_seats_perrow);
        //var reqData = {};
        // var reqData = formattedBookedTicket.keys(data).map(key => {
        //     return formattedSeatsPerRow[key];
        // });
        console.log('REQ DATA',newJson);
        
        function formatArrayToObj(array){
	        //IP: ["1-1", "1-3", "4-1", "4-2", "4-4"]
	        //OP: {"1": ["1", "3"], "4": ["1", "2", "4"] }
	
	        let output = {};
	        let arr = array.map((arr) => {
            const name = arr.split("-")[0];
            const value = arr.split("-")[1];

            if(output[name]){
              const tempValue = [...output[name], value];
              output[name] = tempValue;
            }else{
              output[name] = [value];
            }

          });
	
	        return output;
          }

        function updateSelectedCount(selectedCount) {
          count.innerText = selectedCount;
          console.log('countText:',count.innerText);
          console.log("Final Array:",selectedSeatsArr)
          const formattedData = formatArrayToObj(selectedSeatsArr);

          console.log("formattedData", formattedData);

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
        //$(".seating .seat").on("click", function(){
        $(".seating .seat:not(.booked)").on("click", function(){  
          //alert('123');
          const seatValue = $(this).data("value");
            
          if(selectedSeatsArr.includes(seatValue) == false) {
            // console.log("countbeforepush",count.innerText);
            // console.log("before",totalSelectedSeats);
            var usable = totalSelectedSeats - 1;
            //Push seat value to global variable - for using in PHP
            if(count.innerText < usable) {
              selectedSeatsArr.push(seatValue);
            }
             
          } else { 
            //console.log("SeatVal",seatValue);
            console.log("Seat removed", $("div#" + seatValue ).addClass('seat'));
            selectedSeatsArr = remove_array_element(selectedSeatsArr,seatValue);
          }
          
        });
        //test end

        //Clear seats
        $("#clear_seat").on("click", function(){  
          console.log("woowww")
          $('.seating div.seat').removeClass('selected');
          selectedSeatsArr.length = 0;
          $('#assign_best').prop('disabled', false);
          console.log('ARR',selectedSeatsArr);
        });

        //Assign Best seats
        var mvar = [];
        $("#assign_best").on("click", function(){
          function getBestSeats(seatsNeeded, allSeats, bookedSeats, type){
            const allSeatsAsArray = Array.from({length: allSeats}, (x, i) => i + 1);
            let nonBookedSeats = [];
            let bestPickSeats = [];         
            const bookedSeatsCounts = bookedSeats.length;
            const nonBookedSeatsCount = allSeats - bookedSeatsCounts;
            // console.log('allSeatsAsArray', allSeatsAsArray.length);
            // console.log('seatsNeeded', seatsNeeded);
            // console.log('nonBookedSeatsCount', nonBookedSeatsCount);
            if(seatsNeeded <= nonBookedSeatsCount){
                // console.log("Possible To Have Best Seats In This Row");
                nonBookedSeats = allSeatsAsArray.filter((seat) => {
                  return !bookedSeats.includes(seat);
                });
                if(type === "consecutive"){
                      //get seats - non consecutive seat in a row
                    return consecutiveSeats(nonBookedSeats, seatsNeeded);
                }
                            
                if(type === "non-consecutive"){
                  //get seats - non consecutive seats in a row
                    return nonConsecutiveSeats(nonBookedSeats, seatsNeeded);
                }			
            }else{
              return false;
            }
          }
              
          function consecutiveSeats(nonBookedSeats, seatsNeeded){
            const temp_bestPickSeats = nonBookedSeats.reduce((seq, v, i, a) => {
                if (i && a[i - 1] !== v - 1) {
                    seq.push([]);
                  }
                  seq[seq.length - 1].push(v);
                  return seq;
              }, [[]]).filter(({length}) => length >= seatsNeeded)[0];
                          
              if(temp_bestPickSeats){
                  return temp_bestPickSeats.slice(0, seatsNeeded);
              }else{
                  return false;
              }
          }
              
          function nonConsecutiveSeats(nonBookedSeats, seatsNeeded){
            return nonBookedSeats.slice(0, seatsNeeded);
          }
          for(let i=keys[total_rows-1]; i>=0; i--){
            let row = json[i];
            let rowName = row['rowName'];
            // console.log('rowName', rowName);
            let availableSeatsPerRow = row['totalSeats'] - row['totalSeatsBooked'];
              var selectedSeatsRowInt = [];
              for (let j = 0; j < row['totalSeatsBooked']; j++){
                selectedSeatsRowInt.push(parseInt(row['seatsBooked'][j]));
              }
              const seatsNeeded = totalSelectedSeats-1;
              const allSeats = row['totalSeats'];
              const bookedSeats = selectedSeatsRowInt;
              var bestSeats1 = getBestSeats(seatsNeeded, allSeats, bookedSeats, "consecutive");
              if(bestSeats1 != false){
                for(let k=0; k<bestSeats1.length; k++){
                  let id = rowName+'-'+bestSeats1[k];
                  $("#"+id).addClass('selected');
                  selectedSeatsArr.push(id);
                }
                if(selectedSeatsArr != ''){
                  $('#assign_best').prop('disabled', true);
                }
                return false;
              }
          }
          if(bestSeats1 == false){
            for(let i=keys[total_rows-1]; i>=0; i--){
            let row = json[i];
            let rowName = row['rowName'];
            // console.log('rowName1', rowName);
            let availableSeatsPerRow = row['totalSeats'] - row['totalSeatsBooked'];
              var selectedSeatsRowInt = [];
              for (let j = 0; j < row['totalSeatsBooked']; j++){
                selectedSeatsRowInt.push(parseInt(row['seatsBooked'][j]));
              }
              const seatsNeeded = totalSelectedSeats-1;
              const allSeats = row['totalSeats'];
              const bookedSeats = selectedSeatsRowInt;
              var bestSeats2 = getBestSeats(seatsNeeded, allSeats, bookedSeats, "non-consecutive");
              if(bestSeats2 != false){
                for(let k=0; k<bestSeats2.length; k++){
                  let id = rowName+'-'+bestSeats2[k];
                  $("#"+id).addClass('selected');
                  selectedSeatsArr.push(id);
                }
                if(selectedSeatsArr != ''){
                  $('#assign_best').prop('disabled', true);
                }
                return false;
              }
            }
          }
          if(bestSeats1 == false && bestSeats2==false){
              $.confirm.show({
                "message":"No best seats are available, please select the seats manually.",
                "hideNo":true,// hide cancel button
                "yesText":"OK",
                "yes":function (){
                },
              });
          }
            $(".seating  .seat.booked").each(function() {
              mvar.push($(this).html);
            });
            //console.log("Html: ",$(".seating #1 .seat.booked").html());
            console.log("Array: ",mvar);
        });
        

//Seat click event
container.addEventListener('click', e => {
  console.log("seat clicked");
  const selectedSeats = document.querySelectorAll('.row .seat.selected');
  var selectedSeatsCount = selectedSeats.length;
  
  if (e.target.classList.contains('seat') &&
     !e.target.classList.contains('booked') && e.target.classList['value']!= 'seat selected') {
       
      if(selectedSeatsCount < totalSelectedSeats) {
       
        e.target.classList.toggle('selected');
        
        updateSelectedCount(selectedSeatsCount);

      } else {
       
              $.confirm.show({
                "message":"Seat selected by you exceeded your ticket limits.",
                "hideNo":true,// hide cancel button
                "yesText":"OK",
                "yes":function (){
                },
              })
      }
  } else if (!e.target.classList.contains('booked')){ 
    e.target.classList.toggle('select');
          selectedSeatsCount = selectedSeatsCount - 1;
       
        console.log(selectedSeatsCount)
        updateSelectedCount(selectedSeatsCount);
  }

    
});

$("#tick_submit").click(function(){
    const selectedSeats = document.querySelectorAll('.seating .seat.selected');
    var selectedSeatsCount = selectedSeats.length;

    if(totSelSeats > selectedSeatsCount ) {  
      $.confirm.show({
            "message":" Tickets quantity and seats selected count must be same!",
            "hideNo":true,// hide cancel button
            "yesText":"OK",
            "yes":function (){
            },
          })


    
    } else { 
      if(selectedSeatsArr.length > selectedSeatsCount){
        selectedSeatsArr = selectedSeatsArr.splice(0, selectedSeatsArr.length-1);
      }else{
        selectedSeatsArr = selectedSeatsArr;
      }        
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
                    // console.log( errorThrown );
                    $.confirm.show({
                    "message":"Please Select your seats to proceed further!",
                    "hideNo":true,// hide cancel button
                    "yesText":"OK",
                    //"type":"danger",
                    "yes":function (){
                      $("section").addClass("loading");
                      setTimeout(location.reload.bind(location), 2000);
                    },
                    
            })
        } 
      });  
    }    
});
  $(document).ready(function(){
    $('.confirm h4.title').text('Ticket Alert');
  });
    </script>
<?= $this->endSection(); ?>
