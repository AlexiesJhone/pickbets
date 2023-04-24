<style media="screen">
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
-webkit-appearance: none !important;
margin: 0;
}

/* Firefox */
input[type=number] {
-moz-appearance: textfield !important;
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
              <changepassword :userx='user'></changepassword>
                <session :userx='userx'></session>
              <div class="card">
                <div class="card-header bg-dark text-warning font-weight-bold">
                  <b class="text-white" style="font-weight:normal">Transfer Funds</b>
                  <hr style="margin-top:0.5rem;margin-bottom:0.5rem">
                  <center><b class="text-muted">Current Cash : </b> <b>{{Number(user.cash).toLocaleString().slice(0,-4)}}</b></center>
                </div>
                <div class="card-body">
                  <!-- <a class="">Current Cash : <a class=""><b>{{Number(user.cash).toLocaleString().slice(0,-4)}}</b></a></a><br> -->
                  <div class="form-group">
                    <label for="exampleInputEmail1">Amount</label>
                    <input type="number" name="123" step=".01" v-model="form.amount" value="" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="456" value="" class="form-control" v-model='form.passwords' autocomplete="false">
                  </div>
                  <!-- <label for="">Amount</label>
                  <input type="number" name="123" step=".01" placeholder="Amount you want to transfer" v-model="form.amount" value="" class="form-control">
                  <label for="">Password</label>
                  <input type="password" name="456" value="" placeholder="type your password" class="form-control" v-model='form.passwords' autocomplete="false"> -->
                </div>
                <div class="card-footer">
                  <button type="button" class="btn btn-primary" @click.prevent='transferfunds' :disabled='isDisabled'>Transfer Funds</button>
                  <button type="button" class="btn btn-danger" @click.prevent='reset'>Reset</button>
                </div>
              </div>
              <div class="modal fade" id="transferfunds" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning" id="exampleModalLabel">Transfer Funds</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <a class="text-muted">Current Cash : <a class="text-success">{{Number(userx.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></a><br>
                      <label for="">Amount</label>
                      <input type="text" name="3215" step=".01" v-model="form.amount" value="" class="form-control">
                      <label for="">Password</label>
                      <input type="password" name="312312" value="" class="form-control" v-model='form.password'>
                    </div>
                    <div class="modal-footer justify-content-center">
                      <center>
                      <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button class="btn btn-primary" :disabled='isDisabled' @click.prevent='transferfunds' >Transfer Funds</button>
                    </center>
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
          loading:false,
          disabled:false,
          total:null,
          totalconfirm:null,
          currentcash:this.userx.cash,
          startingbalance:null,
          user:[],
          form:new Form({
            id:this.userx.id,
            amount:null,
            passwords:'',
            sitename:'pitlive.ph'
          })
        }
      },
      computed:{
        isDisabled: function(){
         return this.disabled;
       },
      },
      methods:{
        getuser(){
          axios.get('/pick20/getuser').then(response=>{
            this.user = response.data;
          })
        },
        reset(){
          this.form.amount=null;
          this.form.passwords='';
        },
        transferfunds(){
          // this.totalconfirm = '';
          // this.totalconfirm = parseInt(this.user.cash.slice(0,-4)) - parseInt(this.form.amount);
          if (this.form.amount<100 ) {
            Swal.fire(
              'Please double check your input',
              'amount must atleast 100',
              'error'
            );
          }else if (!this.form.passwords) {
            Swal.fire(
              'Please double check your password',
              'Password is blank',
              'error'
            );
          }
          else if (parseInt(this.form.amount)>parseInt(this.user.cash.slice(0,-4))) {
            Swal.fire(
              'Please double check your amount',
              'Unsufficient balance',
              'error'
            );
          }
          else {
            this.form.post('/checkpassword').then(()=>{
          this.total = parseInt(this.userx.cash.slice(0,-4)) - parseInt(this.form.amount);
          this.startingbalance = parseInt(this.userx.cash.slice(0,-4));
          Swal.fire({
            title: 'Please Confirm Transfer Funds',
            html:
            "Starting Balance : "+this.startingbalance.toLocaleString()+
            "<br>Amount : "+this.form.amount+
            "<br>Ending balance : "+this.total.toLocaleString(),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading = true;
              this.disabled = true;
              this.form.post('/deductfunds').then(response=>{
                if (response.data.error==='Password not match') {
                  Swal.fire(
                    'Please double check your password',
                    'Password not match',
                    'error'
                  );
                  this.disabled = false;
                  this.loading = false;
                }
                if (response.data.error==='Unsufficient Balance') {
                  Swal.fire(
                    'Please double check your cash',
                    'Unsufficient Balance',
                    'error'
                  );
                  this.disabled = false;
                  this.loading = false;
                }
                if (response.data.cash) {
                  this.form.amount = response.data.cash;
                  this.form.post('https://pick20.sabongkiosk.com/api/transferfunds',{
                    headers:{
                      'Authorization' : 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI3IiwianRpIjoiZTgxYzRhNjJkN2ZhMWRiNDBkOGRiMGNkOWIxMDIzZDIwNjVmMWRjMWZhNzRlN2VlNDBmYjFjY2JiOTA3ZjNjM2UxMzQzYWUwZjQzNTcwMTAiLCJpYXQiOjE2MjYzOTk2NTMuODc0ODg4ODk2OTQyMTM4NjcxODc1LCJuYmYiOjE2MjYzOTk2NTMuODc0ODg4ODk2OTQyMTM4NjcxODc1LCJleHAiOjE2NTc5MzU2NTMuODY5ODg5MDIwOTE5Nzk5ODA0Njg3NSwic3ViIjoiMSIsInNjb3BlcyI6W119.pWShxUhjmDgKU1uceAKmN2QtjxCVMxN0dEume4bm9uR9lz8hNw1Emm0WylaJl9E2H_j695FyfjHDN5VpxolNaSt23r5QcXDBsNuthO8HrPCaCtHBi5hx79_j8SU2Cc3NhiAhy_M1gnWmiHFdwFIQstvEs_tHLhhrjYgLoUnAkd418KFMzpNVucjwUd7FQZwjPEvIEgoeAlTkWKcg7SDNaK0NW_FFAF8nYaVeS_syHv40mQMeEqHi3mohwqHBcYMfM6DUD9DCDEZOm7Pos0GgKkutvnJpUunQ-iLIWLE7oTetK5cjKeSVKoSIJmlail5tvEVynxuDdZppYbJ6pOgOHOSHgkiaK-OY-ymvq-I1yKoogSHPrCzuxa5fmEhomDFPhAICMgu9-7l-MoWOFgeBrQ1vYtkdVRULXLG5f4P1gtCzsrHBrDz4O5kd7ffHpdOf55xKta6-uPYkjOaaIjIzNfog7sdSOKFvHr0YllnFQTeBgkzjZmpHh6N9i4WvgJ0u3iadvugGVr2_hyySB_q3dyyI85l9XAxhkMcpmGMXEgUvunAMjtLTZqWCbtcaEq56VTJGFEBYTt1xtV_etW2xJ6lTj9N4IGbrGZKuP2spT5JxThQ70AHpyinKD5QetY6NsKrKQeBUL7yAItcgDjNwc9pwzAwJhced9jkzt4f1ic8',
                      "Accept": "application/json",
                    }
                }).then(response=>{
                  // this.form.post('/pick20/withdrawaluser').then(response=>{
                    if (response.data.amount) {
                      $('#transferfunds').modal('hide');
                      Swal.fire(
                        'Success!',
                        'You transfered : '+response.data.amount,
                        'success'
                      );
                      this.getuser();
                      this.form.reset();
                      this.disabled = false;
                      this.loading = false;
                    }else{
                      this.form.post('/transferrollback').then(()=>{
                        this.disabled = false;
                        this.loading = false;

                        Swal.fire(
                          'Error',
                          response.message,
                          'error'
                        );
                      })
                    }
                  }).catch(response => {
                    this.form.post('/transferrollback').then(()=>{
                      this.disabled = false;
                      this.loading = false;

                      Swal.fire(
                        'Error',
                        response.message,
                        'error'
                      );
                    })
                  })
                }
              }).catch(response=>{
                this.disabled = false;
                this.loading = false;
                Swal.fire(
                  'Please double check your input',
                  response.message,
                  'error'
                );
              })
            }
          })


        }).catch(()=>{
          Swal.fire(
            'Please double check your password',
            'Password not match',
            'error'
          );
        });
      }}
      },
      mounted() {
        this.getuser();
        this.form.password='';
        this.reset();
      }
    }
</script>
