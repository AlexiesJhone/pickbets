<style media="screen">
  #swal2-input{
    color: white !important;
    width: 50%;
    text-align: center;
  }
</style>
<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-5">
              <changepassword :user='user'></changepassword>
              <session :userx='user'></session>
              <modalcash :userx='user'></modalcash>
              <div class="alert alert-warning" role="alert" v-if="control.announcement">
                <h4 class="alert-heading font-weight-bold">Announcement</h4>
                <p>{{control.announcement}}</p>
              </div>
                <div class="card">
                    <div class="card-header bg-dark text-white font-weight-bold">Withdrawals</div>

                    <div class="card-body">
                      <input type="radio" id="two" value="Barcode" v-model="picked">
                      <label for="two">Barcode</label>
                      <input type="radio" id="one" value="Player" v-model="picked">
                      <label for="one">Player</label>
                      <br>
                      <div v-if="picked==='Barcode'">
                        <label for="barcode font-weight-bold"><b>Check Barcode</b></label>
                        <input type="text"  v-on:input="getbarcode" id="barcode" class="form-control" v-model="barcodex.barcode" placeholder="Insert Barcode Here ...">
                      </div>
                      <div v-if="picked==='Player'">
                        <label for="">Select Player</label>
                        <!-- <select class="form-control" v-model="player.id" @change.prevent='getplayerpending'>
                          <option value="" disabled selected>Select user</option>
                          <option :value="t.id" v-for="t in users" >{{t.name}}</option>
                        </select> -->
                        <v-select v-on:change="getplayerpending" v-model='player.id' placeholder="Select username.." :options="users" :reduce="username => username.id" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                          <a class="btn btn-success btn-sm col-md-12 text-white" @click.prevent='getplayerpending'>Check</a>
                      </div>
                    </div>
                </div>
                <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">account_balance</i>
                  </div>
                  <p class="card-category">Your Wallet Account</p>
                  <h3 class="card-title">{{Number(cash.cash).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}

                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">update</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
              <div class="card card-stats" v-if="player.id">
              <div class="card-header card-header-primary card-header-icon" v-for="t in users"  v-if="t.id==player.id">
                <div class="card-icon">
                  <i class="material-icons">account_balance</i>
                </div>
                <p class="card-category">Wallet Account of <b class="text-success">{{t.username}}</b></p>
                <h3 class="card-title">{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}

                </h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons">update</i>  As of {{new Date().toLocaleString()}}.
                </div>
              </div>
              </div>
              <div class="alert alert-success" role="alert">
                <h4 class="alert-heading font-weight-bold">Reminder for the cashier/accounting</h4>
                <p>If the player wins the jackpot, we will give the winner/s a cheque. if not jackpot we can transfer the prize on their bank account or cash.</p>
              </div>

                <div class="card" id='printMe' v-show="actualreceipt && picked==='Barcode'">
                  <center>
                  <!-- <barcode :value="receipt.barcode" tag="img"></barcode> -->
                  <p><b v-if="tickets.winner===4">Cancelled Ticket Due to Low Payout</b></p>
                  <p><b>Event name</b> :{{events.event_name}} <br>
                  <b v-if="tickets.turn">Pick </b>: {{tickets.turn}} <br>
                  <b v-if="tickets.id">Bet ID </b>: {{tickets.id}} <br>
                  <b>Cashier </b>: {{user.name}}<br>
                  <b>Arena</b> : {{events.venue}}<br>
                  <a v-if="tickets.winner===3">No pay out </a>
                  <a v-if="tickets.winner===1||tickets.winner===2||tickets.winner===4"><b>Payout </b>: {{Number(tickets.result).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}} </a>
                  <a v-else>Type: Withdrawal <br><br> Amount : {{Number(withdrawuser.amount).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</a>
                  <a v-if="tickets.bet"><b><br>Bet </b>: {{tickets.bet}}  <br><b>Thank you for playing Pickbets.</b> </a>
                   <a v-if="!tickets.bet"><br>Thank you for playing Pickbets.</a> </p>
                  <!-- <p v-for="t in prebets">Fight # :{{t.fightnumber}} = {{t.selection}} -->
                     <!-- = <b v-if="t.win===0||t.win===null">Lose</b><b v-if="t.win===1">Won</b> -->
                   <!-- </p> -->
                 </center>
                </div>
            </div>
            <div class="col-md-5" v-if="tickets.id">
                  <div class="card" >
                    <div class="card-header bg-dark text-white font-weight-bold">
                      Ticket Number: {{tickets.barcode}}
                    </div>
                      <div class="alert alert-success text-center" style="margin-bottom:0px" role="alert" v-if="tickets.winner===1 || tickets.winner===2">
                        <b v-if="tickets.claimed===null">Winner - {{Number(tickets.result).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</b>
                        <b v-if="tickets.claimed===1">Winner - Claimed - {{Number(tickets.result).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</b>
                      </div>
                      <div class="alert alert-danger text-center font-weight-bold"  style="margin-bottom:0px"  role="alert" v-if="tickets.winner===3">
                        Lose
                      </div>
                      <div class="alert alert-warning text-center font-weight-bold"  style="margin-bottom:0px"  role="alert" v-if="tickets.winner===0">
                        Pending
                      </div>
                      <div class="alert alert-warning text-center font-weight-bold"  style="margin-bottom:0px"  role="alert" v-if="tickets.winner===4">
                        Cancelled
                      </div>
                      <div class="card-body table-responsive" style="padding:0">
                      <table class="table table-sm table-striped table-borderless table-hover" style="padding:none">
                        <thead class="thead-dark"  style="padding:none">
                          <tr style="padding:none">
                            <th scope="col" class="text-center" >Event Name</th>
                            <th scope="col" class="text-center">Starting Fight</th>
                            <th scope="col" class="text-center">Amount</th>
                            <th scope="col" class="text-center">Bet</th>
                            <!-- <th scope="col" class="text-center">Result</th> -->
                          </tr>
                        </thead>
                        <tbody  style="padding:none">
                          <tr style="padding:none">
                            <th class="text-center">{{potmoney.event_name}}</th>
                            <td class="text-center">{{tickets.startingfight}}</td>
                            <td class="text-center">{{Number(tickets.result).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                            <td class="text-center">{{tickets.bet}}</td>
                            <!-- <td><p class="text-danger font-weight-bold text-center" v-if="p.win===0||p.win===null">Lose</p><p class="text-success font-weight-bold text-center" v-if="p.win===1">Won</p></td> -->
                          </tr>
                        </tbody>
                      </table>
                      </div>
                      <div class="card-footer">
                        <a v-if='tickets.winner===1||tickets.winner===2||tickets.winner===4' v-show='tickets.claimed===null' class='btn btn-success col-md-12 text-white' @click.prevent='withdraw'>Withdraw</a>
                        <a v-if='tickets.claimed===1' class='btn btn-success col-md-12 text-white' @click.prevent='reprint'>Reprint</a>
                        <a v-if='tickets.winner===3' class='btn btn-success col-md-12 text-white' @click.prevent='reprint'>Print</a>
                        <button v-if='tickets.winner===0' class='btn btn-warning col-md-12 text-white' @click.prevent='reprint' disabled>Pending</button>
                      </div>
                      <div class="" v-if="transactions.length">
                        <div class="card">
                        <div class="card-header bg-dark text-white font-weight-bold">
                          Previous Transaction for {{barcodex.barcode}}
                        </div>
                        <div class="card-tbody table-responsive" style="padding:0">
                          <table class="table">
                            <thead class="thead-dark font-weight-bold"  style="padding:none">
                              <tr>
                                <th>Transaction ID</th>
                                <th>Starting Balance</th>
                                <th>Amount</th>
                                <th>Ending Balance</th>
                                <th>Date Processed</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="trans in transactions">
                                <td >{{trans.id}}</td>
                                <td >{{Number(trans.startingbalancecashier).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                                <td >{{trans.amount}}</td>
                                <td >{{Number(trans.endingbalancecashier).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                                <td >{{trans.created_at |datef}}</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="card-footer">
                          <a class="text-success font-weight-bold" v-if="totalamountfinal>0">Balance : {{totalamountfinal}}</a>
                          <a class="text-danger font-weight-bold" v-if="totalamountfinal<0">Abono : {{totalamountfinal}}</a><br>
                          <a v-if="totalamountfinal>0" class='btn btn-success col-md-8 text-white' @click.prevent='withdraw'>Withdraw</a>
                        </div>
                        </div>
                      </div>
                  </div>

            </div>

            <div class="col-md-5" v-if="withdrawuser.id>0 && picked==='Player'">
              <div class="card">
                <div class="card-header bg-dark text-warning">
                  Pending withdraw
                </div>
                <div class="card-body table-responsive" style="padding:0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Username</th>
                        <th>Amount</th>
                        <th>Details</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{withdrawuser.user.name}}</td>
                        <td>{{Number(withdrawuser.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td> <pre>{{withdrawuser.details}}</pre> </td>
                        <td><button type="button" class="btn btn-success btn-sm" @click.prevent='withdrawpending(withdrawuser)' v-if="show">Withdraw</button>
                          <button type="button" class="btn btn-danger btn-sm" @click.prevent='rejectwithdrawalconfirm(withdrawuser)' v-if="show">Reject</button>
                          <button type="button" class="btn btn-success btn-sm" @click.prevent='reprint' v-if="!show">Reprint</button>
                          <button type="button" class="btn btn-danger btn-sm" @click.prevent='clear' v-if="!show">Clear</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
            <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-dark text-warning font-weight-bold">
                    <h5 class="modal-title" id="exampleModalLabel">Reject Withdrawal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <label for="">ID</label>
                    <input class="form-control" type="text" name="" v-model="player.id" disabled>
                    <label for="">Amount</label>
                    <input class="form-control" type="text" name="" v-model="player.amount" disabled>
                    <label for="">Details</label>
                    <textarea class="form-control" name="name" rows="3" cols="80" v-model="player.details" disabled></textarea>
                    <label for="">Remarks</label>
                    <textarea class="form-control" name="name" rows="3" cols="80" v-model="player.note"></textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click.prevent='rejectwithdrawal'>Confirm Reject</button>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      props:['user'],
      data(){
        return{
          picked:'Barcode',
          loading:false,
          events:[],
          users:[],
          withdrawuser:[],
          checkcash:[],
          actualreceipt:false,
          tickets:[],
          transactions:[],
          totalamount:0,
          totalamountfinal:0,
          potmoney:[],
          control:[],
          prebets:[],
          cash:[],
          show:true,
          player:new Form({
            id:'',
            amount:'',
            event_id:'',
            user_id:'',
            details:'',
            note:'',
            pin:'',
          }),
          barcodex:new Form({
            barcode:'',
            pin:'',
            amount:''
          })
        }
      },
      computed:{
        // computecash(){
        //   return this.cash=this.user.cash;
        // }
      },
      methods:{
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.control = response.data;
            this.computethis();
          })
        },
        rejectwithdrawalconfirm(withdrawuser){
          this.player.fill(withdrawuser);
          $('#reject').modal('show')
        },
        clear(){
          this.player.reset();
          this.withdrawuser=[];
        },
        reprint(){
          Swal.fire({
            title: 'Please Confirm',
            text: "Do you really want to reprint this?",
            icon: 'warning',
            showCancelButton: true,
            color:'black',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            input: 'password',
            inputLabel: 'Pin of supervisor',
            inputAttributes: {
                maxlength: 4,
            },
            inputValidator: (value) => {
              if (!value) {
                return 'You need the pin of your supervisor!';
              }
            }
          }).then((result) => {
            if (result.isConfirmed) {
              // this.loading=true;
              // this.player.id=a.id;
              this.player.pin=result.value;
              $('#pendingbets').modal('hide');
              this.player.post('/pick20/reprint').then(response=>{
                if (response.data.error) {
                  Swal.fire(
                    'error',
                    response.data.error,
                    'error'
                  )
                  // this.receipt=null;
                }else {
                  this.$htmlToPaper('printMe2');

                }

              })
            }
          })
        },
        rejectwithdrawal(){
          Swal.fire({
            title: 'Please Confirm',
            text: "Are you sure you want to reject this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            // this.player.fill(withdrawuser);
            if (result.isConfirmed) {
              this.player.post('/pick20/rejectwithdrawal').then(()=>{
                Swal.fire(
                  'Success',
                  'Withdrawal rejected ',
                  'success'
                );
                this.player.reset();
                this.withdrawuser=[];
                this.getmycash();
                $('#reject').modal('hide')
              });
            }
          })
        },
        getmycash(){
          axios.get('/pick20/getmycash').then(response=>{
            this.cash = response.data
          })
        },
        withdrawpending(withdrawuser){
           Swal.fire({
          title: 'Please Confirm',
          text: "Are you sure you want to withdraw "+Number(withdrawuser.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})+" from "+withdrawuser.user.name+' ?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirm',
          input: 'password',
          inputLabel: 'Pin of supervisor',
          inputAttributes: {
              maxlength: 4,
          },
          inputValidator: (value) => {
            if (!value) {
              return 'You need the pin of your supervisor!';
            }
          }
        }).then((result) => {
          if (result.isConfirmed) {
          this.player.fill(withdrawuser);
          this.player.event_id = this.events.id;
          this.player.pin = result.value;
          this.player.post('/pick20/confirmwithdrawuser').then(response=>{
            if (response.data.error) {
              Swal.fire(
                'Ooops',
                 response.data.error,
                'error'
              );
            }else {
              this.player.reset();
              this.show = false;
            this.getmycash();
              Swal.fire(
                'Success',
                'Please give '+Number(withdrawuser.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})+' to the user',
                'success'
              )
              this.$htmlToPaper('printMe');
            }
          }).catch(()=>{
            Swal.fire(
              'Oops',
              'Please double check your current balance',
              'error'
            )
          })
          }
        })
          this.player.fill(withdrawuser);
        },
        getplayerpending(){
          this.loading=true;
          this.player.post('/pick20/getplayerpending').then(response=>{
            this.loading=false;
            this.show = true;
            this.withdrawuser=response.data;
          })
        },
        reprint(){
          Swal.fire({
            title: 'Please Confrim',
            text: "Do you really want to reprint this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            input: 'password',
            inputLabel: 'Pin of supervisor',
            inputAttributes: {
                maxlength: 4,
            },
            inputValidator: (value) => {
              if (!value) {
                return 'You need the pin of your supervisor!';
              }
            }
          }).then((result) => {
            if (result.isConfirmed) {
              this.player.pin = result.value;
              this.loading=true;
              this.player.post('/pick20/checkpin').then(response=>{
                if (response.data.error) {
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                  this.loading=false;
                }else {
                  this.loading=false;
                  this.$htmlToPaper('printMe');
                }
              })

            }
          })

        },
        getplayers(){
          axios.get('/pick20/getplayers').then(response=>{
            this.users=response.data;
          })
        },
        getactiveevent(){
          this.loading=true;
          axios.get('/pick20/getevents').then(response=>{
            this.loading=false;
            this.events=response.data;
          })
        },
        withdraw(){
          if (this.totalamountfinal>0) {
            this.tickets.result = this.totalamountfinal;
          }
          Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to withdraw "+Number(this.tickets.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})+" ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm Withdraw',
            input: 'password',
            inputLabel: 'Pin of supervisor',
            inputAttributes: {
                maxlength: 4,
            },
            inputValidator: (value) => {
              if (!value) {
                return 'You need the pin of your supervisor!';
              }
            }
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true,
              this.barcodex.pin = result.value;
              if (this.totalamountfinal>0) {
                this.barcodex.post('/pick20/withdrawkulang').then(response=>{
                  if (response.data.error) {
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                    this.loading=false;
                  }else {
                    this.$htmlToPaper('printMe');
                    Swal.fire(
                      'Success!',
                      'Please give the cash to the player.',
                      'success'
                    );
                    this.getbarcode();
                    this.getmycash();
                    this.loading=false;
                  }
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Claim disabled / Already Claimed'
                  });
                  // this.potmoney=[];
                  // this.tickets=[];
                });
              }else {
                this.barcodex.post('/pick20/withdraw').then(response=>{
                  if (response.data.error) {
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                    this.loading=false;
                  }else {
                    this.$htmlToPaper('printMe');
                    Swal.fire(
                      'Success!',
                      'Please give the cash to the player.',
                      'success'
                    );
                    this.getbarcode();
                    this.getmycash();
                    this.loading=false;
                  }
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Claim disabled / Already Claimed'
                  });
                  // this.potmoney=[];
                  // this.tickets=[];
                });
              }
            }
          })
        },
        checkcashin(){
          axios.get('pick20/getuser').then(response=>{
            this.checkcash = response.data;
            if (this.checkcash.role===4&&this.checkcash.cash<100) {
              $('#cashin2').modal('show');
            }
          })
        },
        getbarcode(){
          this.loading=true;
          this.barcodex.post('/pick20/getbarcode').then(response=>{
            if (response.data.error) {
              Toast.fire({
                icon: 'error',
                title: response.data.error
              });
            }
            this.loading=false;
            this.tickets=response.data;
            this.barcodex.barcode=this.tickets.barcode;
            this.barcodex.post('/pick20/getbarcodewin').then(response=>{
              this.player.user_id=this.tickets.user_id;
              this.loading=false;
              this.potmoney=response.data;
              this.barcodex.post('/pick20/gettransactionscashierwithdraw').then(response=>{
                this.transactions = response.data;
                this.totalamount = 0;
                this.totalamountfinal =0;
                this.barcodex.amount = 0;
                this.transactions.forEach((val)=>{
                  this.totalamount =this.totalamount + parseInt(val.amount) ;
                });
                if (this.totalamount>0) {
                  this.totalamountfinal = this.tickets.result - this.totalamount;
                  this.barcodex.amount = this.totalamountfinal;
                }
              })
              // this.barcodex.post('/pick20/getbarcodewinprebets').then(response=>{
              //   this.prebets=response.data;
              //   this.loading=false;
              // })
            })
          }).catch(()=>{
            this.loading=false,
            Toast.fire({
              icon: 'warning',
              title: 'No Record'
            });
            this.potmoney=[];
            this.tickets=[];
          })
        }
      },
      created() {
        this.getcontrol();
        this.checkcashin();
        this.getmycash();
        this.getplayers();
        this.getactiveevent();
        Echo.private('announce')
        .listen('announcement',(event)=>{
          // console.log(event.events.announcement);
          this.control.announcement=event.events.announcement;
          if (event.events.announcement) {
            Swal.fire(
              'Announcement',
              event.events.announcement,
              'info'
            )
          }

        });
        Echo.private('cashupdate')
        .listen('userupdate',(event)=>{
          console.log(event)
        })
      }
    }
</script>
