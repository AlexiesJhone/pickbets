<style media="screen">
.table>tbody>tr {
position: none;
}
</style>
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
                  Select Event
                </div>
                <div class="card-body table-responsive">
                  <v-select v-model="search.event_name" class="col-sm-12" :options="allevents" placeholder="Choose Event" :reduce="event_name => event_name.event_name" id="user" label="event_name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                  <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='allevent'>Search Event</a><a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='alleventclear'>Clear Search</a>
                  <table class="table table-hover table-stripped table-bordered">
                    <thead>
                      <tr class="bg-dark text-white">
                        <!-- <th>Event Id</th> -->
                        <th>Event Name</th>
                        <th>Fight Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in events.data">
                        <!-- <td>{{t.id}}</td> -->
                        <td>{{t.event_name}}</td>
                        <td>{{t.fightdate|datef}}</td>
                        <td> <a v-if="t.status===1" class="text-success font-weight-bold">Active</a><a v-else-if="t.status===2" class="text-info font-weight-bold">Finished</a><a v-else="t.status===0" class="text-danger font-weight-bold">Pending</a> </td>
                        <td> <a class="btn btn-sm btn-success text-white" @click.prevent='viewusers(t)'>View</a> </td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination :data="events" :show-disabled=true :limit='5' @pagination-change-page="allevent">
                    <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span>
                  </pagination>
                </div>
              </div>
              <!-- monitoring modal -->
              <div class="modal modal1 fade" id="monitoringmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">{{eventdatailed.event_name}}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1"  style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="bg-dark" style="border-radius:5px">
                            <th class="text-warning font-weight-bold">Group</th>
                            <th class="text-warning font-weight-bold">Username</th>
                            <th class="text-warning font-weight-bold">Date cash in</th>
                            <th class="text-warning font-weight-bold">Money on Hand</th>
                            <th class="text-warning font-weight-bold">Date cash out</th>
                            <th class="text-warning font-weight-bold">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in users">
                            <td>{{t.group.name}}</td>
                            <td>{{t.username}}</td>
                            <td><a v-if="t.cashin">{{t.cashin|datef}}</a> <a v-else> - </a></td>
                            <td>{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td><a v-if="t.cashout">{{t.cashout|datef}}</a> <a v-else> - </a></td>
                            <td> <a class="btn btn-sm btn-success text-white" @click.prevent='viewreport(t)'>View Report</a><a class="btn btn-sm btn-danger text-white" v-if="t.cash>0&&user.role==1" @click.prevent='forcecashout(t)'>Force Cash Out</a><a class="btn btn-sm btn-danger text-white disabled" v-else>Force Cash Out</a></td>
                          </tr>
                        </tbody>
                      </table>

                    </div>
                    <div class="modal-footer">
                      <download-excel
                        class="btn btn-success"
                        :data="users"
                        :fields="usersfields"
                        worksheet="My Worksheet"
                        name="Pickbetmonitoring.xls"
                      >
                        Download Excel
                      </download-excel>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- REPORT MODAL -->
              <div class="modal modal1 fade" id="reportmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">{{eventdatailed.event_name}}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="bg-dark">
                            <th colspan="3" class="font-weight-bold text-warning">Total Bet Amount</th>
                            <th colspan="4" class="font-weight-bold text-warning">Total Withdraw Amount</th>
                            <th colspan="3" class="font-weight-bold text-warning">Total Deposit Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="3" class="" v-for="t in transactions.data">{{Number(t.totalbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="4" class="" v-for="t in transactions.data">{{Number(t.totalw).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="3" class="" v-for="t in transactions.data">{{Number(t.totald).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="bg-dark">
                            <th colspan="8" class="font-weight-bold text-center text-warning">TRANSACTIONS</th>
                          </tr>
                          <tr class="">
                            <th class="font-weight-bold">Id</th>
                            <th class="font-weight-bold">Type</th>
                            <th class="font-weight-bold">Barcode</th>
                            <th class="font-weight-bold">Transacted To</th>
                            <th class="font-weight-bold">startingbalance</th>
                            <th class="font-weight-bold">Amount</th>
                            <th class="font-weight-bold">Ending Balance</th>
                            <th class="font-weight-bold">Created Date</th>
                          </tr>
                        </thead>
                        <tbody v-for="t in transactions.data">
                          <tr v-for="s in t.transactions" class="">
                            <td>{{s.id}}</td>
                            <td>{{s.type}}</td>
                            <td> <a v-if="s.type==='Cash Out'||s.type==='Cash In'||s.type==='Deposit'||s.type==='Withdrawal'">-</a><a v-else>{{s.barcode}}</a></td>
                            <td>{{s.user.name}}</td>
                            <td>{{Number(s.startingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(s.endingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{s.created_at|datef}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="bg-dark">
                            <th colspan="8" class="font-weight-bold text-center text-warning">BET HISTORY</th>
                          </tr>
                          <tr class="">
                            <th class="font-weight-bold">Id</th>
                            <th class="font-weight-bold">Barcode</th>
                            <th class="font-weight-bold">Startingfight</th>
                            <th class="font-weight-bold" colspan="2">Bet</th>
                            <th class="font-weight-bold">Amount</th>
                            <th class="font-weight-bold">Result</th>
                            <th class="font-weight-bold">Created Date</th>
                          </tr>
                        </thead>
                        <tbody v-for="t in transactions.data">
                          <tr v-for="s in t.bets">
                            <td>{{s.id}}</td>
                            <td>{{s.barcode}}</td>
                            <td>{{s.startingfight}}</td>
                            <td colspan="2">{{s.bet}}</td>
                            <td>{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td><a v-if="s.winner===0" class="text-danger font-weight-bold">Pending</a> <a v-else-if="s.winner === 3" class="text-danger font-weight-bold">Loss -{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success font-weight-bold">{{Number(s.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                            <td>{{s.created_at|datef}}</td>
                          </tr>
                        </tbody>
                      </table>

                    </div>
                    <!-- <div class="modal-footer">
                      <pagination :data="transactions" :show-disabled=true :limit='5' @pagination-change-page="viewreportpage" class="justify-content-center">
                        <span slot="prev-nav">&lt; Previous</span>
                        <span slot="next-nav">Next &gt;</span>
                      </pagination>
                    </div> -->
                    <div class="modal-footer">
                      <download-excel
                        class="btn btn-success"
                        :data="transactionsexcel"
                        :fields="transfields"
                        worksheet="My Worksheet"
                        name="Pickbetmonitoring.xls"
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
      props:['user'],
      data(){
        return{
          loading:false,
          events:{},
          users:[],
          eventdatailed:[],
          transactions:{},
          transactionsexcel:[],
          allevents:[],
          form : new Form({
            id:'',
            event_id:'',
            event_name:'',
          }),
          search:new Form({
            name:'',
            id:'',
            event_name:''
          }),
          usersfields: {
             'Group': 'group.name',
             'Username': 'username',
             'Date Cash In': 'cashin',
             'Money on Hand': 'cash',
             'Date Cash Out': 'cashout',
           },
          transfields: {
             'id': 'id',
             'Type': 'type',
             'Transacted To': 'user.name',
             'Starting Balance': 'startingbalance',
             'Amount': 'amount',
             'Ending Balance': 'endingbalance',
             'Created Date': 'created_at',
           },
        }
      },
      methods:{
        viewreportpage(page=1){
          this.loading=true;
          $('#reportmodal').modal('show');
          this.form.post('/pick20/gettransactions?page='+page).then(response=>{
            this.loading=false;
            this.transactions = response.data;
            this.transactionsexcel = this.transactions[0].transactions;
          })
        },
        viewreport(t,page=1){
          this.form.id = t.id;
          this.form.event_id = this.eventdatailed.id;
          this.loading=true;
          $('#reportmodal').modal('show');
          this.form.post('/pick20/gettransactions?page='+page).then(response=>{
            this.loading=false;
            this.transactions = response.data;
            this.transactionsexcel = this.transactions[0].transactions;
          })
        },
        forcecashout(t){
          this.form.id = t.id;
          Swal.fire({
            title: 'Please Confirm',
            text: "That you want to force cash out this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading = true;
              this.form.post('/pick20/forcecashout').then(()=>{
                this.loading = false;
                $('#monitoringmodal').modal('hide');
                Swal.fire(
                  'Success',
                  'User has now 0 balance',
                  'success'
                )
              })
            }
          })
        },
        viewusers(t){
          this.eventdatailed = t;
          this.form.id = t.id;
          this.form.event_name = t.event_name;
          this.loading = true;
          this.form.post('/pick20/getusersfrommonitoring').then(response=>{
            this.loading = false;
            this.users = response.data;
            $('#monitoringmodal').modal('show');
          })
        },
        alleventclear(page = 1){
          this.search.event_name='';
          this.loading = true;
          this.search.post('/pick20/geteventsformonitoring?page='+page).then(response=>{
            this.events = response.data;
            this.search.post('/pick20/getalleventsreports').then(response=>{
              this.allevents = response.data;
              this.loading=false;
            })
          })
        },
        allevent(page = 1){
          this.loading = true;
          this.search.post('/pick20/geteventsformonitoring?page='+page).then(response=>{
            this.events = response.data;
            this.search.post('/pick20/getalleventsreports').then(response=>{
              this.allevents = response.data;
              this.loading=false;
            })
          })
        }
      },
      created() {
        this.allevent();
      }
    }
</script>
