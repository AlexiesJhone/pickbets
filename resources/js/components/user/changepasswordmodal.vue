<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12">
              <!-- change password -->
              <div class="modal fade" id="changepassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content" style="border:none !important;">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning" id="exampleModalLabel">Change Password</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <label for="old">Old Password</label>
                      <input type="password" id="old" class="form-control" v-model="form.oldpassword">
                      <label for="new">New Password</label>
                      <input type="password" id="new" class="form-control" v-model="form.newpassword">
                      <label for="confirm">Confirm Password</label>
                      <input type="password" id="confirm" class="form-control" v-model="form.confirmpassword">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" @click.prevent="changepassword">Update Password</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- account details -->
              <div class="modal fade" id="changeaccountdetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content" style="border:none !important;">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning" id="exampleModalLabel">Account Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <label for="olds">Name</label>
                      <input type="text" id="olds" class="form-control" v-model="userdetail.name" disabled>
                      <label for="news">Email</label>
                      <input type="email" id="news" class="form-control" v-model="userdetail.email" disabled>
                      <label for="confirms">Phone(Gcash)</label>
                      <input type="text" id="confirms" class="form-control" v-model="form2.pnumber">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" @click.prevent="Updatedetails">Update Details</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal fade" id="changeaccountdetails2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog">
                  <div class="modal-content" style="border:none !important;">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning" id="exampleModalLabel">Account Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <label for="we">Name</label>
                      <input type="text" id="we" class="form-control" v-model="userdetail.name" disabled>
                      <label for="awd">Email</label>
                      <input type="email" id="awd" class="form-control" v-model="userdetail.email" disabled>
                      <label for="sss">Phone(Gcash)</label>
                      <input type="text" id="sss" class="form-control" v-model="form2.pnumber">
                    </div>
                    <div class="modal-footer">
                      <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                      <button type="button" class="btn btn-primary" @click.prevent="Updatedetails2">Update Details</button>
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
      props:['user'],
      data(){
        return{
          loading:false,
          userdetail:[],
          form2:new Form({
            id:'',
            name:'',
            email:'',
            pnumber:''
          }),
          form:new Form({
            oldpassword:'',
            newpassword:'',
            confirmpassword:''
          })
        }
      },
      methods:{
        // $('#changeaccountdetails2').modal('show');
        Updatedetails(){
          this.loading=true;
          this.form2.post('/pick20/updateaccount').then(()=>{
            this.getauthdetails();
            this.loading=false;
            Toast.fire({
              icon: 'success',
              title: 'Account has been successfully updated'
            });
          }).catch(()=>{
            this.loading=false;
            Toast.fire({
              icon: 'error',
              title: 'Account not updated, and please make sure the phone number is 11 digits and not duplicated.'
            });
          })
        },
        Updatedetails2(){
          this.loading=true;
          this.form2.post('/pick20/updateaccount').then(()=>{
            $('#validator').modal('hide');
            this.getauthdetails();
            this.loading=false;
            Toast.fire({
              icon: 'success',
              title: 'Account has been successfully updated'
            });
          }).catch(()=>{
            this.loading=false;
            Toast.fire({
              icon: 'error',
              title: 'Account not updated, and please make sure the phone number is 11 digits and not duplicated.'
            });
          })
        },
        getauthdetails(){
          this.loading=true;
          axios.get('/pick20/getuser').then(response=>{
            this.loading=false,
            this.userdetail = response.data;
            this.form2.fill(response.data);
            this.form2.pnumber = this.userdetail.pnumber;
          }).catch(()=>{
            this.loading=false;
          });
        },
        changepassword(){
          this.loading=true,
          this.form.post('/pick20/changepassword').then(()=>{
            this.loading=false,
            Toast.fire({
              icon: 'success',
              title: 'Password has been successfully updated'
            });
            $('#changepassword').modal('hide');
            this.form.reset();
          }).catch(()=>{
            this.loading=false,
            Toast.fire({
              icon: 'error',
              title: 'Password not matched'
            });
          })
        }
      },
        mounted() {
            this.getauthdetails();


    }
  }
</script>
