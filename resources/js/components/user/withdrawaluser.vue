<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-5">
              <div class="card">
                <div class="card-header bg-dark text-warning font-weight-bold">
                  <b class="text-white" style="font-weight:normal">Withdrawal</b>
                  <hr style="margin-top:0.5rem;margin-bottom:0.5rem">
                  <center><b class="text-muted">Current Cash : </b> <b>{{Number(user.cash).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</b></center>
                </div>
                <div class="card-body">
                  <!-- <a class="">Current Cash : <a class=""><b>{{Number(user.cash).toLocaleString().slice(0,-4)}}</b></a></a><br> -->
                  <div class="form-group">
                    <label for="exampleInputEmail1">Amount</label>
                    <input :disabled='isDisabled' type="text" class="form-control"   min="1"  v-model="price"/>
                    <!-- <input type="number" name="123" step=".01" v-model="form.amount" value="" class="form-control"  oninput="this.value = Math.round(this.value);"> -->
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="456" value="" class="form-control" v-model='form.passwords' autocomplete="false">
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Details</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Type your bank, account number or gcash" v-model='form.details' rows="3"></textarea>
                  </div>
                  <!-- <label for="">Amount</label>
                  <input type="number" name="123" step=".01" placeholder="Amount you want to transfer" v-model="form.amount" value="" class="form-control">
                  <label for="">Password</label>
                  <input type="password" name="456" value="" placeholder="type your password" class="form-control" v-model='form.passwords' autocomplete="false"> -->
                </div>
                <div class="card-footer">
                  <button type="button" class="btn btn-primary" @click.prevent='withdrawaluser' :disabled='isDisabled'>Withdraw</button>
                  <button type="button" class="btn btn-danger" @click.prevent='reset'>Reset</button>
                </div>
              </div>
              <div class="card" v-if="pending.length">
                <div class="card-header bg-dark text-warning font-weight-bold">
                  Your Current Pending Transaction
                </div>
                <div class="card-body table-responsive" style="padding:0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Amount</th>
                        <th>Details</th>
                        <th>status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in pending">
                        <td>{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td>{{t.details}}</td>
                        <td><a v-if="t.active===1" class="text-danger font-weight-bold">Pending</a></td>
                        <td> <a class="btn btn-danger btn-sm" @click.prevent='cancelwitdraw(t)'>Cancel Withdraw</a> </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              <!-- <div class="modal fade" id="userwithdraw" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning" id="exampleModalLabel">Withdrawal</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <a class="text-muted">Current Cash : <a class="text-success">{{Number(userx.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></a><br>
                      <label for="">Amount</label>
                      <input type="text" name="" step=".01" v-model="form.amount" value="" class="form-control">
                      <label for="">Password</label>
                      <input type="password" name="" value="" class="form-control" v-model='form.password'>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" @click.prevent='withdrawaluser'>Withdraw</button>
                    </div>
                  </div>
                </div>
              </div> -->
              </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      props:['userx'],
      data(){
        return{
          disabled:false,
          loading:false,
          user:[],
          pending:[],
          total:null,
          price:null,
          price2:null,
          startingbalance:null,
          form:new Form({
            id:'',
            amount:'',
            passwords:'',
            details:'',
          })
        }
      },
      watch: {
         price: function(newValue) {
           const result = newValue.replace(/\D/g, "")
           .replace(/^0+/g, '')
             .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
           // Vue.nextTick(() => this.forms.amount = result);
           // Vue.nextTick(() => this.price = result);
           this.price = result;
           this.price2 = String(newValue).replace(/,/g, "");;
           this.form.amount = result;
         }
       },
      computed:{
        isDisabled: function(){
         return this.disabled;
       }},
      methods:{
        cancelwitdraw(t){
            Swal.fire({
              title: 'Are you sure?',
              text: "That you want to withdraw?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm Withdraw'
            }).then((result) => {
              if (result.isConfirmed) {
              this.form.fill(t);
              this.form.post('/pick20/cancelpending').then(response=>{
                  this.form.reset();
                this.getpending()
                Swal.fire(
                  'Success!',
                  'Successfully Cancelled',
                  'success'
                );
              });}
            })

        },
        getpending(){
          axios.get('/pick20/getpendingtransactions').then(response=>{
            this.pending = response.data;
          });
        },
        reset(){
          this.form.amount=null;
          this.form.passwords='';
        },
        getuser(){
          axios.get('/pick20/getuser').then(response=>{
            this.user = response.data;
          })
        },
        withdrawaluser(){
          this.disabled = true;
          this.form.amount = parseInt(this.price2);
          if (this.form.amount<100 ) {
              this.disabled = false;
            Swal.fire(
              'Please double check your input',
              'amount must atleast 100',
              'error'
            );
          }
          else if (!this.form.passwords) {
              this.disabled = false;
            Swal.fire(
              'Please double check your password',
              'Password is blank',
              'error'
            );
          }
          else if (!this.form.details) {
            this.disabled = false;
            Swal.fire(
              'Please double check your details',
              'Details is blank',
              'error'
            );
          }
          else if (parseInt(this.form.amount)>parseInt(this.user.cash.slice(0,-4))) {
              this.disabled = false;
            Swal.fire(
              'Please double check your amount',
              'Unsufficient balance',
              'error'
            );
          }else {


          this.form.post('/checkpassword').then(()=>{
            // startofthis
            this.total = parseInt(this.userx.cash.slice(0,-4)) - parseInt(this.form.amount);
            this.startingbalance = parseInt(this.userx.cash.slice(0,-4));
            Swal.fire({
              title: 'Please Confirm Withdrawal',
              html:
              "Starting Balance : "+this.startingbalance.toLocaleString()+
              "<br>Amount : "+this.form.amount.toLocaleString()+
              "<br>Ending balance : "+this.total.toLocaleString()+
              "<br>Details : <pre>"+this.form.details+"</pre>",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {
              if (result.isConfirmed) {
          this.form.post('/pick20/withdrawaluser').then(response=>{
            if (response.data.status==='error') {
                this.disabled = false;
              Swal.fire(
                'Success!',
                response.data.msg,
                'error'
              );
            }else{
              this.getpending();
                this.disabled = false;
              this.form.reset();
              // $('#userwithdraw').modal('hide')
              Swal.fire(
                'Success!',
                response.data.msg,
                'success'
              );
            }
          }).catch(response => {
              this.disabled = false;
            Swal.fire(
              'Error',
              response.message,
              'error'
            );
          });
        }else{
          this.disabled=false;
        }
          });
          // endofthis
        }).catch(()=>{
          this.disabled = false;
          this.loading = false;
          Swal.fire(
            'Please double check your input',
            'Password not match',
            'error'
          );
        });
          }
        }
      },
      created() {
        this.getpending();
        this.getuser();
        this.form.passwords='';
        this.reset();
        Echo.private('cashupdate')
        .listen('userupdate',(event)=>{
            // this.getevents();
          // console.log(event.id.id)
          if (this.user.id === event.id.id) {
            this.getpending();
            this.getuser();
          }
        })
      }
    }
</script>
