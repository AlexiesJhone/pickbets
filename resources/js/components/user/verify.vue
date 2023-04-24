<template>
            <div class="">
              <changepassword :userx='user'></changepassword>
              <modalcash :userx='user'></modalcash>
              <session :userx='user'></session>
              <div id="overlay" v-if="loading">
                <tile style="color:white"></tile>
                <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
              </div>

                <a class="btn btn-secondary btn-sm" @click.prevent='changeemail'>Change your email ({{user.email}})</a>
                <div class="modal fade" id="email" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content" style="border:none !important;">
                      <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title text-warning" id="exampleModalLabel">Change your email</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body table-responsive">
                        <label for="">Email</label>
                        <input type="text" v-model="form.email" class="form-control">
                      </div>
                      <!-- <div class="modal-body font-weight-bold" v-if="!bets">
                        You have no pending bets yet..
                      </div> -->
                      <div class="modal-footer justify-content-center" >
                        <button type="button" class="btn btn-success col-md-12" @click.prevent='changeemailpost'>Change</button>
                        <button type="button" class="btn btn-secondary col-md-12" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
    </div>
</template>

<script>
    export default {
      props:['user','post-route'],
        data(){
          return{
            loading:false,
            form:new Form({
              id:this.user.id,
              email:'',
            }),
          }
        },
        methods:{
          changeemailpost(){
            this.form.post('/pick20/changeemail').then(()=>{
              location.reload();
            }).catch(()=>{
              Swal.fire(
                'error',
                'Please double check email and make sure its not duplicated.',
                'error'
              )
            });
          },
          changeemail(){
            $('#email').modal('show');
          }
        },
        mounted() {
          // this.pendings();
          // this.getevents();
        }
    }
</script>
