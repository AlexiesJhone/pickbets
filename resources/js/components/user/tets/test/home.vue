<template>
    <div class="container">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>

          <div class="col-md-5">
            <changepassword></changepassword>
            <userwithdrawal></userwithdrawal>
            <modalcash :userx='user'></modalcash>
            <!-- <marquee width="100%" direction="left" height="auto" scrollamount="13">
              <div class="alert alert-success" role="alert">
                <b>Jackpot For Today {{Number(events.jackpot).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} </b>
              </div>
            </marquee> -->

            <marquee width="100%" direction="left" height="auto" scrollamount="13" v-if="control==='Last'">
              <div class="alert alert-warning" role="alert">
                 <b>LAST CALL .. PLEASE PLACE YOUR BET..</b>
              </div>
            </marquee>
            <marquee width="100%" direction="left" height="auto" scrollamount="13" v-if="control==='Close'">
              <div class="alert alert-danger" role="alert">
                FIGHT <b>CLOSED </b>.. PLEASE STANDBY FOR THE NEXT FIGHT..
              </div>
            </marquee>
            <marquee width="100%" direction="left" height="auto" scrollamount="13" v-if="!events">
              <div class="alert alert-danger" role="alert">
                There`s no Current fight please wait for announcement
              </div>
            </marquee>
            <div class="card">
              <div class="card-header bg-dark text-white"  v-if="control==='Close'">
                <cash :user="user"></cash>
              </div>
            </div>
            <div class="card" v-if="control==='Open' || control==='Last'">
              <!-- <div class="card-header bg-dark text-white">
                <barcode value="1234567890"></barcode>
              </div> -->
              <div class="card-header bg-dark text-white">
                <b class="text-warning">PICK {{events.pick}}</b> - {{events.event_name}} [{{events.fights}} Fights]<hr style="margin-top:0.5rem;margin-bottom:0.5rem">
                <cash v-if="refreshmoney" :user="user"></cash>
              </div>
              <div class="card-body" style="padding: 0.25rem;padding-top:0;"><center>
                <div class="alert alert-success" style="margin-bottom:0" role="alert">
                  <b>Jackpot For Today {{Number(events.jackpot).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} </b>
                </div>
                <!-- <div class="alert alert-success" role="alert">
                  <b>Highest Combination Prize {{Number(events.jackpot).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} </b>
                </div> -->
                <!-- <h4>Starting Fight Number : <b class="text-danger">{{fight.start}}</b></h4> -->
                <p class="h5">Starting Fight Number : <b class="text-danger">{{events.startingfight}}</b></p>
                <p class="h6">Amount : <b class="text-success">{{fight.amount}}</b></p>
              </center>
                <div class="form-group">
                  <!-- <div class="input-group input-group-sm mb-3 hidethis" >
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-sm" >Starting fight number</span>
                    </div>
                    <input type="text" class="form-control" v-model="fight.start" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
                  </div> -->

                  <!-- <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Starting fight number</label>
                    </div>
                    <input type="text" class="custom-input" name="" value="">
                    <select class="custom-select" id="inputGroupSelect01" v-on:change="placebet" v-model="fight.start" placeholder='select fight number.'>
                      <option selected disabled>Choose...</option>
                      <option  v-for='n in events.fights' :value="n" v-if="n = events.startingfight" selected>{{n}}</b></option>
                    </select>
                  </div> -->
                  <div class="" v-if='customize'>
                  <div class="row" v-if='awd'>
                    <div class="col-md-6 col-md-3">
                      <a @click.prevent='meron' class="btn btn-danger btn-lg form-control"><b>MERON</b></a>
                    </div>
                    <div class="col-md-6 col-md-3">
                      <a @click.prevent='wala' class="btn btn-info btn-lg form-control text-white"><b>WALA</b></a>
                    </div>
                  </div>
                  <!-- <div class="input-group input-group-sm mb-3 hidethis">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Fixed bet amount</label>
                    </div>
                    <input type="text" name=""class="form-control" value="100" v-model='fight.amount' disabled>
                  </div> -->
                  <center>
                  <div class="form-check">
                    <input class="form-check-input" v-on:change="placebet" type="checkbox"  id="defaultCheck1" v-model="randombets">
                    <label class="form-check-label" for="defaultCheck1">
                      <b class="">Random Picks</b>
                    </label>
                  </div></center>
                </div>
                </div>
                <!-- receipt -->
                <div class="card" id='printMe' v-show="awd">
                  <!-- <barcode :value="receipt.barcode" tag="img"></barcode> -->
                  <barcode :value="receipt.barcode" tag="svg"></barcode>
                  <p>Event name : {{events.event_name}}</p>
                  <p>Bet ID : {{receipt.id}}</p>
                  <p>Pick : {{events.pick}}</p>
                  <p>Date : {{new Date().toLocaleString()}}</p>
                  <p>Cashier : {{user.name}}</p>
                  <p>Amount : {{events.amount}}</p>
                  <p v-for="t in receipt.prebets">Fight # :{{t.fightnumber}} = {{t.selection}}</p>
                </div>
                <!-- Customize PICK20 -->
                <div class="card" v-if='customize'>
                  <div class="card-header bg-dark text-white">
                  Customize  Your <b class="text-warning">PICK {{events.pick}}</b>
                  </div>
                  <div class="card-body table-responsive-sm" style="max-height:60vh; overflow:auto; padding:0;">
                    <table class="table table-sm  table-striped table-borderless table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th class="text-center">Fight #</th>
                          <th class="text-center">Pick</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>

                      <tbody class="" style="width:100%">
                        <tr v-for='(bet,index) in selected' :index="bet.id">
                          <td class="text-center"><b>{{bet.fightnumber}}</b></td>
                          <td class="text-center"><b class="btn btn-danger btn-sm" v-if='bet.selection==="Meron"'>{{bet.selection}}</b> <b class="text-white btn btn-primary btn-sm" v-if='bet.selection==="Wala"'>{{bet.selection}}</b><b class="text-white btn btn-success btn-sm" v-if='bet.selection==="Draw"'>{{bet.selection}}</b> </td>
                          <td class="text-center"> <button @click.prevent='switchw(index)' v-if='bet.selection==="Meron"' class="btn btn-outline-dark btn-sm">
                          <b> Change Pick</b>
                        </button>
                        <button class="btn btn-outline-dark btn-sm" @click.prevent='switchd(index)' v-if='bet.selection==="Wala"'><b> Change Pick<span class="glyphicon glyphicon-refresh"></span></b></button>
                        <button class="btn btn-outline-dark btn-sm" @click.prevent='switchm(index)' v-if='bet.selection==="Draw"'><b> Change Pick<span class="glyphicon glyphicon-refresh"></span></b></button>
                      </td>
                      <!-- <td>
                          <input :checked="bet.selection === 'Meron'" class="" type="radio" @click.prevent='switchbet(bet)' :name="bet.id" id="flexRadioDefault1" > <b class="text-danger">Meron</b>
                          <input :checked="bet.selection ==='Wala'" class="" type="radio" @click.prevent='switchbet(bet)' :name="bet.id" id="flexRadioDefault2" > <b class="text-primary">Wala</b>
                          </td> -->
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-footer" >
                <a class="btn btn-success form-control" @click.prevent='showconfirm()' v-if='customize'>Place Bet</a>
                  <button type="button" class="btn btn-success form-control btn-sm" @click.prevent='getevents' name="button" v-if='rebet'>Bet Again</button>
                <!-- <a class="btn btn-success form-control" @click.prevent='insertbet()'>Place Bet</a> -->
              </div>
            </div><br>
            <button type="button" class="btn btn-secondary form-control" name="button" @click.prevent='viewpendingbets'>View Current Pending Bets</button>
            <!-- <div class="card table-responsive-sm" id="containerx" style="" v-if="bets.length">
              <div class="card-header bg-dark text-white" v-if="user.role===0">
            Pending <b class="text-warning">Pick {{events.pick}}</b> bets
              </div>
              <div class="card-header bg-dark text-white" v-if="user.role===3">
            Your Current <b class="text-warning">Pick {{events.pick}}</b> picks on startingfight # {{events.startingfight}}
              </div>
              <div class="card-body" style="padding:0px;max-height:50vh; overflow:auto">



                  </div>
            </div> -->
          </div>
          <div class="modal fade" id="pendingbets" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content" style="border:none !important;">
                <div class="modal-header bg-dark text-white">
                  <h5 class="modal-title text-warning" id="exampleModalLabel">Current Pending Bets for Starting Fight Number : {{events.startingfight}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" style="padding:0">
                  <table class="table tabl-sm table-striped table-borderless table-hover">
                    <thead class="thead-dark">
                      <tr>
                        <th>#</th>
                        <th>Starting Fight</th>
                        <th>Bet</th>
                        <th>Fight#</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(t,index) in bets.data" :index='index'>
                      <!-- <tr v-for="(t,index) in bets.slice().reverse()" :index='index'> -->
                        <td>{{index+1}}</td>
                        <td>{{t.startingfight}}</td>
                        <td>{{t.bet}} <br><a class="btn btn-success btn-sm" @click.prevent='reprint(t)' v-if="user.role===0">Reprint</a </td>
                        <td v-if="events.pick===20 "><b>{{t.prebets[0].fightnumber}}</b> to <b>{{t.prebets[19].fightnumber}}</b></td>
                        <td v-if="events.pick===15 "><b>{{t.prebets[0].fightnumber}}</b> to <b>{{t.prebets[14].fightnumber}}</b></td>
                        <td v-if="events.pick===24 "><b>{{t.prebets[0].fightnumber}}</b> to <b>{{t.prebets[23].fightnumber}}</b></td>
                        <td>{{t.created_at|datef}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer justify-content-center">
                  <pagination :data="bets" :limit='5' @pagination-change-page="geteventbetss">
                    <!-- <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span> -->
                  </pagination><hr>
                  <button type="button" class="btn btn-secondary col-md-12" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="width:100%;">
                <center><tile></tile>
                <h6 class="text-muted">Please Wait...</h6></center>
            </div>
          </div>
          </div>
          <div class="modal fade" id="confirmation2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
              <div class="modal-content"  style="border:none !important;">
                <div class="modal-header bg-dark text-white">
                  <h5 class="modal-title text-warning" id="exampleModalCenterTitle">Confirmation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" style=" border:none !important;">
                  <center>Are you sure you want to bet this <br> <b>PICK 20?</b></center>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary form-control" :disabled='isDisabled' @click.prevent='insert2'>Confirm</button>
                  <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Go Back</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
      props:['user','data'],
      data(){
        return{
          loading:false,
          randombets:null,
          awd:null,
          customize:null,
          rebet:null,
          hasbet:'wala',
          refreshmoney:true,
          bets:{},
          events:[],
          selected:[],
          receipt:[],
          control:'',
          disabled:false,
          confirm:[],
          pageOfItems: [],
          fight:new Form({
            id:'',
            start:null,
            selection:'Meron',
            amount:null,
            user_id:this.user.id,
          }),
          confirm:new Form({
            id:'',
            fightnumber:null,
            selection:'Meron',
            amount:null,
            user_id:this.user.id,
          }),
        }
      },
      computed: {
  	     isDisabled: function(){
        	return this.disabled;
        },
      },
      methods:{
        geteventbetss(page = 1) {
          this.loading=true;
          // this.bets={};
            axios.get('/pick20/getbets?page=' + page)
                .then(response => {
                  this.loading=false;
                    this.bets = response.data;
                    if (this.bets) {
                      this.hasbet='meron';
                    }else {
                      this.hasbet='wala';
                    }
                });
        },
        viewpendingbets(){
          this.geteventbetss();
          $('#pendingbets').modal('show');
        },
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        reprint(t){
          Swal.fire({
            title: 'Please Confirm',
            text: "Do you really want to reprint this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.fight.id=t.id;
              this.fight.post('/pick20/reprint').then(response=>{
                this.receipt=response.data;
              }).then(()=>{
                this.$htmlToPaper('printMe');
                this.loading=false;
              }).catch(()=>{
                Swal.fire(
                  'error',
                  'Invalid Reprint.',
                  'error'
                )
              })
            }
          })

        },
        geteventbets(){
          // $('#confirmation2').modal('show');
          this.loading=true;
          this.bets=[];
          axios.get('/pick20/getbets').then(response=>{
            this.loading=false;
            this.bets=response.data;
            if (this.bets) {
              this.hasbet='meron';
            }else {
              this.hasbet='wala';
            }
            $('#confirmation2').modal('hide');
          }).catch(()=>{
            this.loading=false;
          });
        },
        switchm(index){
          this.selected[index].selection = 'Meron';
         // Toast.fire({
         //         icon: 'warning',
         //         title: 'Successfully switched !'
         //       });
        },
        switchw(index){
          this.selected[index].selection = 'Wala';
         // Toast.fire({
         //         icon: 'warning',
         //         title: 'Successfully switched !'
         //       });
        },
        switchd(index){
          this.selected[index].selection = 'Draw';
         // Toast.fire({
         //         icon: 'warning',
         //         title: 'Successfully switched !'
         //       });
        },
        showconfirm(){
          this.disabled=false;
          $('#confirmation2').modal('show')
        },
        insert2(){
          this.loading=true;
          this.disabled=true;
          this.refreshmoney=false;
          axios({
            method: 'post',
            url: '/pick20/testpost',
            data: {
              data : this.selected
            }
          }).then(response=>{
            this.receipt=response.data;
            this.selected=[];
            this.customize=null;
            this.fight.start=null;
            this.randombets=false;
            this.rebet=1;
            Swal.fire(
              'Success!',
              'Your Pick has been entered.',
              'success'
            )
            this.refreshmoney=true;
            $('#confirmation2').modal('hide')
          })
          .then(()=>{
            if (this.user.role===0) {
              this.$htmlToPaper('printMe');
            }
            this.loading=false;
          }).catch(()=>{
            $('#confirmation2').modal('hide');
            this.loading=false;
            if (this.user.role===3) {
              Swal.fire(
                'error',
                'Your Pick has not been entered.<br>Make sure you have enough balance',
                'error'
              )
            }else {
              Swal.fire(
                'error',
                'Your Pick has not been entered.',
                'error'
              )
            }
          });
        },
        insertbet(){
          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Go back'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              axios({
                method: 'post',
                url: '/pick20/testpost',
                data: {
                  data : this.selected
                }
              }).then(response=>{
                this.loading=true;
                this.receipt=response.data;
                this.selected=[];
                this.customize=null;
                this.fight.start=null;
                this.randombets=false;
                this.rebet=1;
                Swal.fire(
                  'Success!',
                  'Your Pick has been entered.',
                  'success'
                )
              })
              .then(()=>{
                if (this.user.role==0) {
                  this.$htmlToPaper('printMe');
                }
              }).catch(()=>{
                Swal.fire(
                  'error',
                  'Your Pick has not been entered.',
                  'error'
                )
              });
            }
          })
        },
        deleteprebets(){
          axios.post('/pick20/deleteprebets');
        },
        switchbet(bet){
          this.confirm.fill(bet);
          this.loading=true;
          this.confirm.post('/pick20/switchbet').then(response=>{
            this.loading=false;
            this.selected=response.data;
            // Toast.fire({
            //         icon: 'warning',
            //         title: 'Successfully switched !'
            //       });
          // if (bet.selection==="Meron") {
          //   Swal.fire({
          //     title: 'Are you sure?',
          //     text: "You want to switch from "+bet.selection+" to Wala?",
          //     icon: 'warning',
          //     showCancelButton: true,
          //     confirmButtonColor: '#3085d6',
          //     cancelButtonColor: '#d33',
          //     confirmButtonText: 'Yes!'
          //   }).then((result) => {
          //     if (result.isConfirmed) {
          //       this.confirm.post('/switchbetw').then(response=>{
          //         this.selected=response.data;
          //         Toast.fire({
          //                 icon: 'warning',
          //                 title: 'Successfully switched !'
          //               });
          //       }).catch(()=>{
          //         Toast.fire({
          //                 icon: 'error',
          //                 title: 'Please refresh !'
          //               });
          //       });
          //     }
          //   })
          // }
          // else {
          //     Swal.fire({
          //       title: 'Are you sure?',
          //       text: "You want to switch from "+bet.selection+" to Meron?",
          //       icon: 'warning',
          //       showCancelButton: true,
          //       confirmButtonColor: '#3085d6',
          //       cancelButtonColor: '#d33',
          //       confirmButtonText: 'Yes!'
          //     }).then((result) => {
          //       if (result.isConfirmed) {
          //         this.confirm.post('/switchbetm').then(response=>{
          //           this.selected=response.data;
          //           Toast.fire({
          //                   icon: 'warning',
          //                   title: 'Successfully switched !'
          //                 });
          //         }).catch(()=>{
          //           Toast.fire({
          //                   icon: 'error',
          //                   title: 'Please refresh !'
          //                 });
          //         });
          //       }
          //     })
        })
        },
        placebet(){

          if (this.randombets) {
            // ito random
            this.fight.start=this.events.startingfight;
            this.fight.post('/pick20/randompick').then(response=>{
              this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Please select fightnumber first'
              });
            });
          }else {
            // ito hnd random
              this.fight.start=this.events.startingfight;
            this.fight.post('/pick20/selection').then(response=>{
              this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Theres no current event'
              });
            });
          }
        },
        wala(){
          this.fight.selection='Wala';
          Toast.fire({
                  icon: 'warning',
                  title: 'You selected WALA and please confirm'
                });
                this.fight.post('/pick20/allwala').then(response=>{
                  this.selected=response.data;});
        },
        meron(){
          this.fight.selection='Meron';
          Toast.fire({
                  icon: 'warning',
                  title: 'You selected MERON and please confirm'
                });
                this.fight.post('/pick20/allmeron').then(response=>{
                  this.selected=response.data;});
        },
        getevents(){
          axios.get('/pick20/getevents').then(response=>{
            this.selected=[];
            this.events=[];
            this.events=response.data;
            this.control=this.events.control;
            this.fight.amount=this.events.amount;
            this.confirm.amount=this.events.amount;
              this.fight.start=this.events.startingfight;
              this.placebet();
              this.rebet=null;

          });
        },
        getevent(){
          axios.get('/pick20/geteventx').then(response=>{
            this.confirm=response.data;
              this.control=this.confirm.control;
              this.fight.amount=this.events.amount;
              this.confirm.amount=this.events.amount;
              // this.rebet=null;
          });
        },
      },
        created() {
          this.getevents();
          Echo.private('eventupdate')
          .listen('eventlistener',(event)=>{
            console.log(event.events.control)
            this.getevent();

            if (event.events.control==='Last') {
              this.control='Last';
            }
              else if (event.events.control==="Open"){
                this.control='Open';
                this.getevents();
              }else if(event.events.control==='Close') {
                  // this.control='Close';
                  // this.closing=1;
                  this.bets=[];
                  this.getevents();
                }else {
                    // $('#confirmation2').modal('hide')
                    // this.Swal.close()
                }

          })
        }
    }
</script>
