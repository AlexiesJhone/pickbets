<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
          <modalcash :userx='user'></modalcash>
          <div class="col-md-12  alert alert-warning" role="alert" v-if="control.announcement">
            <h4 class="alert-heading font-weight-bold">Announcement</h4>
            <p>{{control.announcement}}</p>
          </div>
            <div class="col-md-12 row">
              <div class="col-md-6 col-sm-6">
                <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">account_balance</i>
                  </div>
                  <p class="card-category">Wallet Account</p>
                  <h3 class="card-title">{{Number(user.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}

                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">update</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
              </div>
              <div class=" col-md-6 col-sm-6">
                <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">money</i>
                  </div>
                  <p class="card-category">Total Deposit for this event</p>
                  <h3 class="card-title">{{Number(totaldeposit).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <div class="stats">
                    <i class="material-icons">update</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                  </div>
                </div>
              </div>
              </div>
              <div class=" col-md-6 col-sm-6">
                <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">credit_score</i>
                  </div>
                  <p class="card-category">Total Withdraw for this event</p>
                  <h3 class="card-title">{{Number(totalwithdraw).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">update</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
              </div>
              <div class=" col-md-6 col-sm-6">
                <div class="card card-stats">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">payment</i>
                  </div>
                  <p class="card-category">Total Unclaimed for this event</p>
                  <h3 class="card-title">{{Number(totalunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">update</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="alert alert-success" role="alert">
                <h4 class="alert-heading font-weight-bold"><i class="material-icons text-white">payments</i> Cash In</h4>
                <hr>
                <p class="mb-0"><button type="button" class="btn btn-sm btn-secondary" name="button" @click.prevent='cashin1'>Click To Cash In</button></p>
              </div>
              </div>
            <div class="col-md-6">
              <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading font-weight-bold"><i class="material-icons text-white">payments</i> Cash Out</h4>
                <hr>
                <p class="mb-0"><button type="button" class="btn btn-sm btn-secondary" name="button" @click.prevent='cashout1'>Click To Cash Out</button></p>
              </div>
            </div>
            <div class="col-md-12">
               <div class="card">
                   <div class="card-header card-header-icon card-header-rose">
                     <div class="card-icon">
                       <i class="material-icons">language</i>
                     </div>
                   </div>
                   <div class="card-body">
                       <h4 class="card-title">Here is the Registration Link for your Mobile Players</h4>
                          <a @click.prevent='copytext' ref="mylink">{{url}}/registers/{{user.group_id}}</a>
                   </div>
               </div>
           </div>
              <!-- <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Add Cash to Account
                </div>
                  <div class="card-body table-responsive row">
                    <div class="col-md-12 row">
                      <div class="col-md-6">
                        <button type="button" class="btn btn-md btn-success col-md-12" name="button" @click.prevent='cashin1'>Cash In</button>
                      </div>
                      <div class="col-md-6">
                      <button type="button" class="btn btn-md btn-danger col-md-12" name="button" @click.prevent='cashout1'>Cash Out</button>
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
      props:['user'],
      data(){
        return{
          checkcash:[],
          group:[],
          control:[],
          totalwithdraw:[],
          totalunclaimed:[],
          totaldeposit:[],
          url:'',
          url2:''
        }
      },
      methods:{
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.control = response.data;
          })
        },
        getmygroup(){
        axios.get('/pick20/getmygroup').then(response=>{
          this.group = response.data;
          if (this.group.active==2) {
            if (this.userx.cash>=100) {
              $('#cashout2').modal('show');
            }else {
              $('.cashin2').modal('hide');
              $('#staticBackdrop').modal('show');
            }
          }
        })
      },
        copytext(){
          // var Url = this.$refs.mylink;
          // Url.innerHTML = 'Pickbets.pitlive.ph/registers/'+this.user.group_id;
          this.url = this.url+'/registers/'+this.user.group_id;
          // console.log(Url.innerHTML)
          // Url.select();
          // document.execCommand("copy");
          navigator.clipboard.writeText(this.url);
          this.url = window.location.origin;
          Toast.fire({
            icon: 'success',
            title: 'Copied to Clipboard'
          });
        },
        cashin1(){
          $('#cashin').modal('show');
        },
        cashout1(){
          $('#cashout').modal('show');
        },
        checkcashin(){
          axios.get('pick20/getuser').then(response=>{
            this.checkcash = response.data;
            if (this.checkcash.role===4&&this.checkcash.cash<100&&this.group.active!=2&&this.user.active===1) {
              $('#cashin2').modal('show');
            }
          })
        },
        gettotaldeposit(){
          axios.get('/pick20/gettotaldeposit').then(response=>{
            this.totaldeposit = response.data;
          })
        },
        gettotalunclaimed(){
          axios.get('/pick20/gettotalunclaimed').then(response=>{
            this.totalunclaimed = response.data;
          })
        },
        gettotalwithdraw(){
          axios.get('/pick20/totalwithdraw').then(response=>{
            this.totalwithdraw = response.data
          })
        }
      },
        created() {
          this.url = window.location.origin;
          this.getmygroup();
          this.getcontrol();
        console.log(this.url);
          this.gettotaldeposit();
          this.gettotalunclaimed();
          this.gettotalwithdraw();
          this.checkcashin();
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
