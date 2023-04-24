<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Total Sales Per Event
                </div>
                <div class="card-body table-responsive" style="">
                  <v-select v-model="form.event_name" class="col-sm-12" :options="allevents" placeholder="Choose Event" :reduce="event_name => event_name.event_name" id="user" label="event_name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                  <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='geteventswithtransactions'>Search Event</a><a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='cleargeteventswithtransactions'>Clear Search</a>
                  <table class="table table-stripped table-hover table-bordered">
                    <thead>
                      <tr class="bg-dark text-white">
                        <th class="font-weight-bold">Event Name</th>
                        <th class="font-weight-bold">Status</th>
                        <th class="font-weight-bold">Created Date</th>
                        <th class="font-weight-bold">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in events.data">
                        <td>{{t.event_name}}</td>
                        <td> <a v-if="t.status===1">Active</a><a v-if="t.status===2">Finished</a><a v-if="t.status===0">Pending</a> </td>
                        <td>{{t.created_at|datef}}</td>
                        <td><a class="btn btn-sm btn-success text-white" @click.prevent='transmodal(t)'>View Transactions</a></td>
                      </tr>
                    </tbody>
                  </table>

                </div>
              </div>
              <div class="card-footer justify-content-center">
                <pagination :data="events" :show-disabled=true :limit='5' @pagination-change-page="geteventswithtransactions">
                    <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span>
                </pagination>
              </div>
              <div class="modal modal1 fade" id="transactionmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Transactions</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1 table-responsive" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Event Name</th>
                            <th class="font-weight-bold">Fight Date</th>
                            <th class="font-weight-bold">Status</th>
                            <th class="font-weight-bold">Fights</th>
                            <th class="font-weight-bold">Fight Opened</th>
                            <th class="font-weight-bold">Fight Closed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>{{form.event_name}}</td>
                            <td>{{form.fightdate|datef}}</td>
                            <td><a v-if="form.status===1" class="text-success">On Going</a><a v-if="form.status===2" class="text-info">Finished</a><a v-if="form.status===0" class="text-dagner">Pending</a></td>
                            <td>{{form.fights}}</td>
                            <td>{{form.fightopened|datef}}</td>
                            <td>{{form.fightopened|datef}}</td>
                          </tr>
                        </tbody>
                        <thead>
                            <tr class="bg-dark text-warning text-center">
                            <th colspan="6" class="font-weight-bold">List of Transactions</th>
                          </tr>
                          <tr>
                            <th class="font-weight-bold">Processed at</th>
                            <th class="font-weight-bold">Transaction</th>
                            <th class="font-weight-bold">Amount</th>
                            <th class="font-weight-bold">Transacted by</th>
                            <th class="font-weight-bold">Transacted to</th>
                            <th class="font-weight-bold">Group</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(t, index) in modal.data" :index='index+1'>
                            <td>{{t.created_at|datef}}</td>
                            <td>{{t.type}}</td>
                            <td class="text-success">{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td><a v-if="t.cashier">{{t.cashier.username}}</a></td>
                            <td><a v-if="t.type==='Cash Out'||t.type==='Cash In'">-</a> <a v-else-if="t.type==='Withdrawal'||t.type==='Deposit'||t.type==='Transfered'">{{t.user.name}}</a> <a v-else>{{t.barcode}}</a> </td>
                            <td>{{t.user.group.name}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="bg-dark text-warning">
                            <th colspan="3" class="font-weight-bold">Total Withdraw</th>
                            <th colspan="3" class="font-weight-bold">Total Deposit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(t,index) in total" :index='index'>
                            <td colspan="3" v-if="index===total.length-1">{{Number(t.totalw).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="3" v-if="index===total.length-1">{{Number(t.totald).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="card-footer justify-content-center">
                      <pagination :data="modal" :show-disabled=true :limit='5' @pagination-change-page="transmodalpage">
                        <span slot="prev-nav">&lt; Previous</span>
                        <span slot="next-nav">Next &gt;</span>
                      </pagination>
                    </div>
                    <div class="modal-footer">
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="modal.data"
                        :fields="modalfields"
                        worksheet="My Worksheet"
                        name="Transactions.xls"
                      >
                        Download Excel
                      </download-excel>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
      data(){
        return{
          events:{},
          loading:false,
          transactions:[],
          modal:{},
          total:[],
          allevents:[],
          search:new Form({
            name:'',
            id:'',
            event_name:''
          }),
          form:new Form({
            id:'',
            total:'',
            event_name:'',
            fightdate:'',
            fights:'',
            status:'',
            fightopened:'',
            fightclosed:'',
          }),
          modalfields: {
             'Processed At': 'created_at',
             'Transaction': 'type',
             'amount': 'amount',
             'Transacted By': 'cashier.username',
             'Transacted To': 'user.username',
             'Group': 'user.group.name',
           },
        }
      },
      methods:{
        transmodalpage(page = 1){
          // this.form.fill(t);
          this.loading=true;
          this.form.post('/pick20/transmodal?page='+page).then(response=>{
            this.modal = response.data;
            this.loading=false;
            // this.form.post('/pick20/transtotal').then(response =>{
            //   this.loading=false;
            //   this.total = response.data;
            // }).then(()=>{
            //   this.loading=false;
            //   // $('#transactionmodal').modal('show');
            // })
          })
        },
        transmodal(t, page = 1){
          this.form.fill(t);
          this.loading=true;
          this.form.post('/pick20/transmodal?page='+page).then(response=>{
            this.modal = response.data;
            this.form.post('/pick20/transtotal').then(response =>{
              this.loading=false;
              this.total = response.data;
            }).then(()=>{
              this.loading=false;
              $('#transactionmodal').modal('show');
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
        geteventswithtransactions(page = 1){
          this.loading=true;
          this.form.post('/pick20/geteventswithtransactions?page='+page).then(response=>{
            this.events = response.data;
            this.form.post('/pick20/getalleventsreports').then(response=>{
              this.allevents = response.data;
              this.loading=false;
            })
          })
        }
      },
      created() {
        this.geteventswithtransactions();
      }
    }
</script>
