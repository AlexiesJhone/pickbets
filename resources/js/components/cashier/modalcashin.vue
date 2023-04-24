<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
              <div class="modal fade" id="cashin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog" role="document">
                  <div class="modal-content" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Cash In</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <label style="color:gray">Amount</label>
                      <!-- oninput="this.value = Math.round(this.value);" -->
                      <input :disabled='isDisabled' type="text" class="form-control"   min="1"  v-model="price"/>
                      <!-- <input :disabled='isDisabled' type="number" class="form-control"  oninput="this.value = Math.round(this.value);" min="1"  v-model="forms.amount"/> -->
                      <label style="color:gray">Pin of Supervisor</label>
                      <input :disabled='isDisabled' type="password" class="form-control" maxlength="4" v-model="forms.pin"/>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success" @click.prevent='cashinconfirmed'>Cash In</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal fade" id="cashin2" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog" role="document">
                  <div class="modal-content" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Please Cash In First</h5>
                      <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button> -->
                    </div>
                    <div class="modal-body">
                      <label style="color:gray">Amount</label>
                      <!-- <input :disabled='isDisabled' type="number" class="form-control"  oninput="this.value = Math.round(this.value);" min="1"  v-model="forms.amount"/> -->
                      <input :disabled='isDisabled' type="text" class="form-control"   min="1"  v-model="price"/>
                      <label style="color:gray">Pin of Supervisor</label>
                      <input :disabled='isDisabled' type="password" class="form-control" maxlength="4" v-model="forms.pin"/>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" @click.prevent='logoutmuna'>Logout</button>
                      <button type="button" class="btn btn-success" @click.prevent='cashinconfirmed'>Cash In</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal fade" id="cashout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Please Confirm</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" style="color:gray">
                      That you want to cash out {{Number(userx.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} ?
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success" @click.prevent='cashouttemporary' v-if="!this.userx.lock">Confirmed</button>
                      <button type="button" class="btn btn-success" @click.prevent='cashout' v-else>Cash Out</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal fade" id="cashout2" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-keyboard="false">
              <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content" style="border:none">
                  <div class="modal-header bg-dark"><center>
                    <h5 class="modal-title text-warning font-weight-bold" id="staticBackdropLabel">Please be inform</h5></center>
                  </div>
                  <div class="modal-body text-center text-dark">
                    You need to cashout {{Number(userx.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}.
                    <!-- Your Account is Deactivated. Please Logout. -->
                  </div>
                  <div class="modal-footer" style="padding:0.5rem">
                    <button type="button" class="btn btn-success" @click.prevent='cashout2'>Confirm</button>

                  </div>
                </div>
              </div>
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
            error:null,
            price:'',
            price2:null,
            disabled:false,
            group:[],
            forms: new Form({
              id:'',
              amount:'',
              user_id:this.userx.id,
              pin:'',
            })
          }
        },
        computed:{
          isDisabled: function(){
         	return this.disabled;
         },
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
             this.this.forms.amount = result;
           }
         },
        methods:{
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
          cashouttemporary(){
            axios.get('/pick20/cashouttemporary').then(response=>{
              if (response.data.error) {
                this.error = 1;
                Swal.fire(
                  'Ooops!',
                  response.data.error,
                  'error'
                )
              }else {
                Swal.fire(
                  'Success!',
                  'All cash has been removed from your account.',
                  'success'
                )
              }
            }).then(()=>{
              // window.location.href = "http://127.0.0.1:8000/summary"
              if (this.error) {
                this.error = null;
                $('#cashout').modal('hide');
              }else {
                window.location.href = "/summary"
                this.error = null;
                $('#cashout').modal('hide');
              }
            });
          },
          logoutmuna(){
             document.getElementById("logout").click();
          },
          cashin(){
            $('#cashin').modal('show');
          },
          cashout2(){
                axios.get('/pick20/cashout').then(()=>{
                  Swal.fire(
                    'Success!',
                    'All cash has been removed from your account.',
                    'success'
                  )
                  $('#staticBackdrop').modal('show');
                }).then(()=>{
                  window.location.reload();
                });
            // Swal.fire({
            //   title: 'Please Confirm',
            //   text: "That you really want to cash out "+this.userx.cash+' ?',
            //   icon: 'warning',
            //   showCancelButton: true,
            //   confirmButtonColor: '#3085d6',
            //   cancelButtonColor: '#d33',
            //   confirmButtonText: 'Confirm'
            // }).then((result) => {
            //   if (result.isConfirmed) {
            //     axios.get('/pick20/cashout').then(()=>{
            //       Swal.fire(
            //         'Success!',
            //         'All cash has been removed from your account.',
            //         'success'
            //       )
            //     }).then(()=>{
            //       window.location.reload();
            //     });
            //   }
            // })
          },
          cashout(){
                axios.get('/pick20/cashout').then(()=>{
                  Swal.fire(
                    'Success!',
                    'All cash has been removed from your account.',
                    'success'
                  )
                }).then(()=>{
                  window.location.href = "https://pickbets.pitlive.ph"
                });
            // Swal.fire({
            //   title: 'Please Confirm',
            //   text: "That you really want to cash out "+this.userx.cash+' ?',
            //   icon: 'warning',
            //   showCancelButton: true,
            //   confirmButtonColor: '#3085d6',
            //   cancelButtonColor: '#d33',
            //   confirmButtonText: 'Confirm'
            // }).then((result) => {
            //   if (result.isConfirmed) {
            //     axios.get('/pick20/cashout').then(()=>{
            //       Swal.fire(
            //         'Success!',
            //         'All cash has been removed from your account.',
            //         'success'
            //       )
            //     }).then(()=>{
            //       window.location.reload();
            //     });
            //   }
            // })
          },
          cashinconfirmed(){
            this.forms.amount = parseFloat(this.price2);
            axios.get('pick20/getuser').then(response=>{
              if (response.data.lock==1) {
                  window.location.href = "https://pickbets.pitlive.ph/summary"
              }
            });
            if (!this.forms.pin) {
              Swal.fire(
                'Ooops!',
                'Pin Required.',
                'error'
              );
            }else {
            if (this.forms.amount>=100) {
              this.disabled=true;
              Swal.fire({
                title: 'Please Confirm',
                text: "That you want to cash in "+this.forms.amount+' ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
              }).then((result) => {

                if (result.isConfirmed) {
                  this.forms.post('/pick20/cashin').then(response=>{
                    if (response.data.error) {
                      this.disabled=false;
                      Swal.fire(
                        'Ooops!',
                        response.data.error,
                        'error'
                      );
                    }else {
                      this.disabled=false;
                      this.forms.amount='';
                      this.forms.pin='';
                      Swal.fire(
                        'Success!',
                        'Cash in added to your account.',
                        'success'
                      );
                      window.location.reload();
                    }
                  }).then(()=>{

                  });
                }else {
                  this.disabled=false;
                }
              })
            }else {
              this.disabled=false;
              Swal.fire(
                'Ooops!',
                'Please make sure that the amount is 100 minimum.',
                'error'
              );
            }
          }
          },
        },
        created() {
          this.getmygroup();
          if (this.userx.role===9&&this.userx.cash<100&&this.userx.active===1) {
            $('#cashin2').modal('show');
          }
          if (this.userx.role==4&&this.userx.cash<100&&this.userx.active===1) {
            $('#cashin2').modal('show');
          }
          if (this.group.active==2) {
              $('#cashin2').modal('hide');
          }
        }
    }
</script>
