<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-8">
              <changepassword></changepassword>
                <session :userx='user'></session>
              <!-- <div class="card">
                <div class="card-header bg-dark text-warning font-weight-bold">
                  Transaction history
                </div>
                <div class="card-body">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Transactions</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Pending</a>
                </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" style="background:white" id="home" role="tabpanel" aria-labelledby="home-tab"> -->
                  <!-- dito ung transactions -->
                  <!-- <table class="table table-sm table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Event Name</th>
                        <th>status</th>
                        <th>Fight date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in event" v-if="t.status===1">
                        <td>{{t.event_name}}</td>
                        <td><a v-if="t.status===1">Active</a> </td>
                        <td>{{t.fightdate|datef}}</td>
                        <td><a class="btn btn-success btn-sm" @click.prevent='showtransactions(t)'>View</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane fade" id="profile" style="background:white" role="tabpanel" aria-labelledby="profile-tab"> -->
                  <!-- dito ung pending -->
                  <!-- <table class="table table-sm table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Event Name</th>
                        <th>status</th>
                        <th>Fight date</th>
                        <th> Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in event" v-if="t.status===1">
                        <td>{{t.event_name}}</td>
                        <td>{{t.status}}</td>
                        <td>{{t.fightdate|datef}}</td>
                        <td><a class="btn btn-success btn-sm" @click.prevent='showpending(t)'>View</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </div>
                </div>
                  </div> -->

              <div class="card">
                <div class="card-header bg-dark text-white">
                  Transactions<br> <a class="text-warning">All your pick20 transactions starting from the latest.</a>
                </div>
                <div class="card-body table-responsive" style="padding:0">
                  <table class="table table-sm table-striped table-hover">
                    <tbody>
                      <tr v-for="(t,index) in bets.data" :index='index'>
                        <div class="card">
                            <div class="card-header bg-dark text-warning text-center">Fightdate : {{t.fightdate|datec}} - Starting Fight {{t.startingfight}}</div>
                            <div class="card-body table-responsive" style="padding:0">
                              <table class="table table-sm table-striped" >
                                <thead>
                                  <th class="text-center">#</th>
                                  <th class="text-center">Type</th>
                                  <th class="text-center">Starting Balance</th>
                                  <th class="text-center">Amount</th>
                                  <th class="text-center">Ending Balance</th>
                                  <th class="text-center">Remarks</th>
                                  <th class="text-center">Date</th>
                                </thead>
                                <tbody>
                                  <tr v-for="(a,indexx) in t.transactions" :index='indexx'>
                                    <th class="text-center">{{indexx+1}}</th>
                                     <td class=" text-center">{{a.type}}</td>
                                    <td class="text-center">{{Number(a.startingbalance).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                                    <td class="text-center">{{Number(a.amount).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                                    <td class="text-center"> {{Number(a.endingbalance).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                                    <td class="text-center"> <a v-if="a.remarks">{{a.remarks}}</a> <a v-else>-</a> </td>
                                    <td class="text-center"> {{a.created_at|fightdatex}}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                        </div>
                        <!-- <th class="text-center">{{index+pages}}</th> -->
                        <!-- <td class="text-center">{{t.created_at|datebet}}</td>
                        <td class="text-center">{{t.startingfight}}</td> -->
                        <!-- <td class=" text-center">{{Number(t.amount).toLocaleString()}}</td>
                        <td class="text-center">{{t.bet}}</td>
                        <td class="text-center">{{t.wins}}</td>
                        <td class="text-center"> <a v-if="t.winner===0" class="text-danger">Pending</a> </td>
                        <td class="text-center"> <a class="btn btn-success btn-sm" @click.prevent='showdetailedbets(t.id)'>View bets</a> </td> -->
                      </tr>
                    </tbody>
                  </table>
                  <div class="card-footer justify-content-center"  v-if="bets.data.length">
                      <pagination class="justify-content-center" :data="bets" :limit='5' @pagination-change-page="transactions">
                      </pagination>
                  </div>
                </div>
                <div class="card-body font-weight-bold"  v-if="!bets.data.length">
                  You have no transactions yet..
                </div>
                  </div>

                  <!-- modal for transactions -->
                  <div class="modal fade" id="transactions" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header bg-dark">
                          <h5 class="modal-title text-warning" id="exampleModalLabel">Transactions of {{form.event_name}}</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body table-responsive" style="padding:0">
                        <table class="table table-sm table-striped table-hover">
                          <thead>
                            <tr>
                              <th>Type</th>
                              <!-- <th>Barcode</th> -->
                              <th>Starting Balance</th>
                              <th>Amount</th>
                              <th>Ending Balance</th>
                              <th>Date</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- <tr v-for="t in bets.data">
                              <td>{{t.type}}</td>
                              <td>{{Number(t.startingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td> -->
                              <!-- <td><a v-if="t.startingfight===null"> - </a><a v-else> {{t.startingfight}} </a></td> -->
                              <!-- <td>{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                              <td>{{Number(t.endingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                              <td>{{t.updated_at|datef}}</td> -->
                            </tr>
                          </tbody>
                        </table>
                        </div>
                        <div class="modal-footer justify-content-center">
                          <!-- <jw-pagination :items="bets" @changePage="onChangePage" :maxPages='5' :pageSize='10'></jw-pagination> -->
                          <!-- <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">Close</button> -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Modal fo pendings -->
                  <div class="modal fade" id="pendings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content" style="border:none">
                        <div class="modal-header bg-dark">
                          <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Pending for {{form.event_name}}</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" style="padding:0px">
                          <table class="table table-stripped table-hover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- <tr v-for="(t,index) in bets" :index='index'> -->
                                <!-- <td>{{index+1}}</td>
                                <td>{{t.amount}}</td>
                                <td>{{t.created_at|datef}}</td>
                                <td> <a href="#" class="btn btn-danger btn-sm" @click.prevent='cancelpending(t)' v-if="t.active===1">Cancel</a>
                                  <button href="#" class="btn btn-danger btn-sm" @click.prevent='cancelpending(t)' v-if="t.active===0" disabled>Cancelled</button> -->
                                 </td>
                              <!-- </tr> -->
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
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
          pageOfItems: [],
          pageOfItems2: [],
          loading:false,
          pages:null,
          events:[],
          event:[],
          bets:{},
          form:new Form({
            id:'',
            event_name:'',
            status:'',
          })
        }
      },
      methods:{
        cancelpending(t){
          this.form.id=t.id;
          this.loading=true;
          this.form.post('/pick20/cancelpending').then(()=>{
            this.loading=false;
            $('#pendings').modal('hide');
          }).catch(()=>{
            Swal.fire(
              'error',
              'No data',
              'error'
            );
          })
        },
        showpending(t){
          this.form.fill(t);
          this.loading=true;
          this.form.post('/pick20/getpending').then(response=>{
            this.loading=false;
            this.bets = response.data;
            $('#pendings').modal('show');
          }).catch(()=>{
            this.loading=false;
            Swal.fire(
              'error',
              'No data',
              'error'
            )
          })

        },
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        onChangePage2(pageOfItems2) {
            // update page of items
            this.pageOfItems2 = pageOfItems2;
        },
        // showtransactions(){
        //   this.loading=true;
        //   this.form.post('/pick20/getallwithdrawals').then(response=>{
        //     this.loading=false;
        //     this.bets=response.data;
        //   })
        // },
        transactions(page=1) {
          // this.bets=[];
          this.loading=true,
            axios.get('/pick20/getalltransactionhistory?page=' + page)
                .then(response => {
                  if (page===1) {
                    this.pages=page;
                  }else {
                    this.pages=page*10-9;
                  }
                  this.loading=false,
                  this.bets=response.data;
                  // $('#prebets').modal('show')
                });
        },
        geteventforpending(){
          axios.get('/pick20/getforpendings').then(response=>{
            this.event = response.data;
          })
        },
        getevent(){
          axios.get('/pick20/getevents').then(response=>{
            this.events = response.data;
          })
        },
      },
        created() {
          this.getevent();
          this.geteventforpending();
          this.transactions();
        }
    }
</script>
