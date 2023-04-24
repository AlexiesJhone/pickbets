<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
            <modalcash :userx='user'></modalcash>
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-6">
                  <div class="alert alert-warning" role="alert" v-if="control.announcement">
                    <h4 class="alert-heading font-weight-bold">Announcement</h4>
                    <p>{{control.announcement}}</p>
                  </div>
                <div class="card">
                    <div class="card-header bg-dark text-white font-weight-bold">Deposit</div>
                    <div class="card-body">
                        <label for="user">Select Usernames</label>
                        <!-- <v-select :options="users" :reduce="farm_owner => farm_owner.entry_name" label="entry_name" taggable :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/> -->
                        <v-select @change="changeRoute" v-model='deposit.id' :options="users" placeholder="Select username.." :reduce="username => username.id" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                          <label for="amount">Amount</label>
                          <input type="text" class="form-control"   min="1"  v-model="price"/>
                          <!-- <input type="number" id="amount" class="form-control" v-model='deposit.amount' oninput="this.value = Math.round(this.value);"> -->
                          <label for="">Pin of Supervisor</label>
                          <input type="password" class="form-control" v-model="deposit.pin" maxlength="4">
                          <!-- <div class="form-group">
                             <div class="input-group">
                                   <input :type="passwordfieldtype" class="form-control" v-model="deposit.pin" maxlength="4">
                                 <span class="input-group-btn">
                                   <button class="btn btn-secondary btn-fab btn-fab-mini btn-round" @click.prevent='switchvisibility'>
                                     <i class="material-icons">visibility</i>
                                   </button>
                                 </span>
                             </div>
                           </div> -->

                    </div>
                    <div class="card-footer">
                      <a class="btn btn-success btn-sm col-md-12 text-white" @click.prevent='depositx'>Deposit</a>
                    </div>
                </div>
                <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">account_balance</i>
                  </div>
                  <p class="card-category">Your Wallet Account</p>
                  <h3 class="card-title">{{Number(cash.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}

                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">update</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>

              </div>
              <div class="card card-stats" v-if="deposit.id">
              <div class="card-header card-header-primary card-header-icon" v-for="t in users"  v-if="t.id==deposit.id">
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
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      props:['user'],
      data(){
        return{
          price:'',
          price2:null,
          passwordfieldtype:'password',
          loading:false,
          users:[],
          cash:[],
          control:[],
          name:'',
          checkcash:[],
          deposit:new Form({
            id:'',
            amount:'',
            pin:''
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
           this.deposit.amount = result;
         }
       },
      methods:{
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.control = response.data;
            this.computethis();
          })
        },
        changeRoute(a){
          console.log(a)
        },
        switchvisibility(){
          this.passwordfieldtype = this.passwordfieldtype === "password" ? "text" :"password";
        },
        getmycash(){
          axios.get('/pick20/getmycash').then(response=>{
            this.cash = response.data
          })
        },
        depositx(){
          this.users.forEach((val)=>{
            if (val.id==this.deposit.id) {

              this.name = val.username ;
            }
          });
          if (!this.deposit.id||!this.deposit.pin) {
            if (!this.deposit.id) {
              Swal.fire(
                'Ooops!',
                'You need to select a user first!',
                'error'
              );
            }
            if (!this.deposit.pin) {
              Swal.fire(
                'Ooops!',
                'You need to put supervisor PIN!',
                'error'
              );
            }

          }else {

            this.deposit.amount = parseFloat(this.price2);
          if (this.deposit.amount>=100) {
            Swal.fire({
              title: 'Please Confirm',
              html:"Do you really want to deposit <b style='color:yellow'>"+Number(this.deposit.amount).toLocaleString()+"</b> to <b style='color:yellow'>"+this.name+"</b>",
              // text: "Do you really want to deposit <b>"+Number(this.deposit.amount).toLocaleString()+"</b> to <b>"+this.name+"</b>",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm',
              cancelButtonText: 'Go Back',
            }).then((result) => {
              if (result.isConfirmed) {
                this.loading=true;
                this.deposit.post('/pick20/depositconfirmed').then(response=>{
                  if (response.data.error) {
                    this.loading=false;
                    Swal.fire(
                      'Ooops!',
                      'Pin is Incorrect',
                      'error'
                    );
                  }else {
                    this.loading=false;
                    Swal.fire(
                      'Success!',
                      'Deposited '+this.deposit.amount+' to user',
                      'success'
                    );
                    this.getmycash();
                      this.getallusers()
                      this.deposit.reset();
                  }

                }).catch(()=>{
                  this.loading=false;
                  Swal.fire(
                    'Ooops!',
                    'You dont have enough balance',
                    'error'
                  );
                });
              }
            })
          }else {
            Swal.fire(
              'Ooops!',
              'Please make sure that the amount is 100 minimum.',
              'error'
            );
          }
          }

        },
        checkcashin(){
          axios.get('pick20/getuser').then(response=>{
            this.checkcash = response.data;
            if (this.checkcash.role===4&&this.checkcash.cash<100) {
              $('#cashin2').modal('show');
            }
          })
        },
        getallusers(){
          this.loading=true;
          axios.get('/pick20/allusersdeposit').then(response=>{
            this.users = response.data;
            this.loading=false;
          })
        }
      },
        mounted() {
          this.getcontrol();
          this.checkcashin();
          this.getmycash();
          this.getallusers();
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
        }
    }
</script>
