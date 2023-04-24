<style media="screen">
.modal1 {
padding: 0 !important;
}
.modal1 .modal-dialog1 {
width: 100%;
max-width: none;
height: 100%;
margin: 0;
}
.modal1 .modal-content1 {
height: 100%;
border: 0;
border-radius: 0;
}
.modal1 .modal-body1 {
overflow-y: auto;
}
</style>
<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Transactions
                </div>
                <div class="card-body table-responsive" style="">
                  <v-select v-model="form.event_name" class="col-sm-12" :options="allevents" placeholder="Choose Event" :reduce="event_name => event_name.event_name" id="user" label="event_name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                  <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='geteventswithtransactions'>Search Event</a><a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='cleargeteventswithtransactions'>Clear Search</a>
                  <table class="table table-stripped table-hover table-bordered">
                    <thead>
                      <tr class="bg-dark text-white">
                        <th class="font-weight-bold">Event ID</th>
                        <th class="font-weight-bold">Event Name</th>
                        <th class="font-weight-bold">Status</th>
                        <th class="font-weight-bold">Created Date</th>
                        <th class="font-weight-bold">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in events.data">
                        <td>{{t.id}}</td>
                        <td>{{t.event_name}}</td>
                        <td> <a v-if="t.status===1">Active</a><a v-if="t.status===2">Finished</a><a v-if="t.status===0">Pending</a> </td>
                        <td>{{t.created_at|datef}}</td>
                        <td><a class="btn btn-sm btn-success text-white" @click.prevent='showtransactionmodal(t)'>View Transactions</a></td>
                      </tr>
                    </tbody>
                  </table>

                </div>
                <div class="card-footer justify-content-center">
                  <pagination :data="events" :show-disabled=true :limit='5' @pagination-change-page="geteventswithtransactions">
                      <span slot="prev-nav">&lt; Previous</span>
                      <span slot="next-nav">Next &gt;</span>
                  </pagination>
                </div>
              </div>
                <div class="modal modal1 fade" id="transactions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog1" role="document">
                    <div class="modal-content modal-content1">
                      <div class="modal-header bg-dark">
                        <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Transactions of {{form.event_name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body modal-body1 table-responsive" style="padding:0">
                        <table class="table table-hover table-stripped">
                          <thead>
                            <tr>
                              <th class="font-weight-bold">Type</th>
                              <th class="font-weight-bold">User</th>
                              <th class="font-weight-bold">Barcode</th>
                              <th class="font-weight-bold">Starting Balance</th>
                              <th class="font-weight-bold">Amount</th>
                              <th class="font-weight-bold">Ending Balance</th>
                              <th class="font-weight-bold">Remarks</th>
                              <th class="font-weight-bold">Event Name</th>
                              <th class="font-weight-bold">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in transactions">
                            <td>{{t.type}}</td>
                            <td>{{t.user.username}}</td>
                            <td> <a v-if="t.type==='Cash Out'||t.type==='Cash In'||t.type==='Deposit'">-</a><a v-else>{{t.barcode}}</a> </td>
                            <td>{{Number(t.startingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.endingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td><a v-if="t.remarks">{{t.remarks}}</a> <a v-else>-</a></td>
                            <td>{{t.event.event_name}}</td>
                            <td>{{t.created_at|datef}}</td>
                          </tr>
                        </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary col-md-12" data-dismiss="modal">Close</button>
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
          transactions:[],
          events:{},
          loading:false,
          form:new Form({
            id:'',
            event_name:''
          })
        }
      },
      methods:{
        showtransactionmodal(t){
          this.transactions = [];
          this.form.id = t.id;
          this.form.event_name = t.event_name;
          loading:true;
          this.form.post('/pick20/newgetcashiertrans').then(response=>{
            loading:false;
            $('#transactions').modal('show');
            this.transactions = response.data;
          }).catch(()=>{
            loading:false;
          })
        },
        geteventswithtransactions(page = 1){
          this.loading=true;
          this.form.post('/pick20/geteventswithtransactions?page='+page).then(response=>{
            this.events = response.data;
            this.form.post('/pick20/getalleventsreports').then(response=>{
              this.allevents = response.data;
              this.loading=false;
            })
          })
        },
        cleargeteventswithtransactions(page = 1){
          this.form.event_name = '';
          this.loading=true;
          this.form.post('/pick20/geteventswithtransactions?page='+page).then(response=>{
            this.events = response.data;
            this.form.post('/pick20/getalleventsreports').then(response=>{
              this.allevents = response.data;
              this.loading=false;
            })
          })
        },
        getcashiertransactions(){
          this.loading=true;
          axios.get('/pick20/getcashiertrans').then(response=>{
            this.transactions = response.data;
            this.loading=false;
          }).catch(()=>{
            this.loading=false;
          })
        }
      },
      mounted() {
        // this.getcashiertransactions();
        this.geteventswithtransactions();
      }
    }
</script>
