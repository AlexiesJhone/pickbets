<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
              <changepassword :user='user'></changepassword>
              <modalcash :userx='user'></modalcash>
              <session :userx='user'></session>
                <div class="card">
                    <div class="card-header bg-dark text-white">Bet History<br> <a class="text-warning">All your pick20 bets starting from the latest.</a> </div>
                    <div class="card-body table-responsive" style="padding:0"  v-if="bets.data.length">
                        <table class="table table-sm table-striped">
                          <thead>
                            <!-- <tr>
                              <th class="text-center">#</th>
                              <th class="text-center">Event Name</th>
                              <th class="text-center">Date</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Action</th>
                            </tr> -->
                            <tr>
                              <th class="text-center">#</th>
                              <th class="text-center">Event Date</th>
                              <th class="text-center">Starting Fight</th>
                              <th class="text-center">Bet</th>
                              <th class="text-center">Wins</th>
                              <th class="text-center">Result</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(t,index) in bets.data" :index='index'>
                              <td class="text-center">{{index+total}}</td>
                              <td class="text-center">{{t.event.fightdate|datec}}</td>
                              <td class="text-center">{{t.startingfight}}</td>
                              <td class="text-center">{{t.bet}}</td>
                              <td class="text-center">{{t.wins}}</td>
                              <td><a v-if="t.winner===0" class="text-danger">Pending</a><a v-if="t.winner===1||t.winner===2" class="text-success">+{{Number(t.result).toLocaleString()}}</a><a v-if="t.winner===3" class="text-danger">-{{Number(t.amount).toLocaleString()}}</a></td>
                            </tr>
                          </tbody>
                          <!-- <tbody>
                            <tr v-for="(t,index) in bets.data" :index='index'>
                              <th class="text-center">{{index+total}}</th>
                              <td class="text-center">{{t.event_name}}</td>
                              <td class="text-center">{{t.startingfight}}</td>
                              <td  class="text-center">{{t.fightdate|datec}}</td>
                              <td class="text-center"><a v-if="t.status===2">Finished</a><a v-if="t.status===1" class="text-success">Active</a></td>
                              <td>
                                <a class="btn btn-success btn-sm" @click.prevent='viewbets(t)'>View Bets</a>
                               </td>
                            </tr>
                          </tbody> -->
                        </table>
                        <div class="card-footer justify-content-center"  v-if="bets.data.length">
                            <pagination class="justify-content-center" :data="bets" :limit='5' @pagination-change-page="historybets">
                              <!-- <span slot="prev-nav">&lt; Previous</span>
                              <span slot="next-nav">Next &gt;</span> -->
                            </pagination>
                        </div>
                    </div>
                    <div class="card-body font-weight-bold" v-if="!bets.data.length">
                      You have no bet history yet..
                    </div>
                </div>
                <div class="modal fade" id="betdetailed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="border:none !important;">
                      <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title text-warning" id="exampleModalLabel" >{{form.event_name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body table-responsive" style="padding:0">
                        <table class="table tabl-sm table-striped table-borderless table-hover">
                          <thead class="thead-dark">
                            <tr>
                              <th>#</th>
                              <th>Amount</th>
                              <th>Bet</th>
                              <th>Wins</th>
                              <th>Result</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(t,index) in bet.data" :index='index'>
                            <!-- <tr v-for="(t,index) in bets.slice().reverse()" :index='index'> -->
                              <th>{{index+totals}}</th>
                              <td>{{Number(t.amount).toLocaleString()}}</td>
                              <td>{{t.bet}}</td>
                              <td>{{t.wins}}</td>
                              <td><a v-if="t.winner===0" class="text-danger">Pending</a><a v-if="t.winner===1||t.winner===2" class="text-success">+{{Number(t.result).toLocaleString()}}</a><a v-if="t.winner===3" class="text-danger">-{{Number(t.amount).toLocaleString()}}</a></td>
                              <!-- <td v-if="events.pick===20 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+19}}</b></td>
                              <td v-if="events.pick===15 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+14}}</b></td>
                              <td v-if="events.pick===24 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+23}}</b></td> -->
                            </tr>
                          </tbody>
                        </table>

                      </div>
                      <!-- <div class="modal-body font-weight-bold" v-if="!bets">
                        You have no pending bets yet..
                      </div> -->
                        <div class="card-footer justify-content-center" >
                            <pagination class="justify-content-center" :data="bet" :limit='5' @pagination-change-page="viewbet">
                              <!-- <span slot="prev-nav">&lt; Previous</span>
                              <span slot="next-nav">Next &gt;</span> -->
                            </pagination>
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
            loading:false,
            events:[],
            bet:{},
            bets:{},
            pages:0,
            pagex:0,
            form:new Form({
              id:'',
              event_name:''
            }),
          }
        },
        computed:{
          total: function(){
           return Number(this.pages);
         },
          totals: function(){
           return Number(this.pagex);
         },
        },
        methods:{
          viewbet(page=1){
            this.form.post('/getbetxx?page='+page).then(response =>{
              this.bet = response.data;
              this.pagex=1;
              $('#betdetailed').modal('show');
            });
          },
          viewbets(t){
            this.form.id=t.id;
            this.form.event_name=t.event_name;
            axios.get('/getbet/'+t.id).then(response =>{
              this.bet = response.data;
              this.pagex=1;
              $('#betdetailed').modal('show');
            });
          },
          historybets(page=1) {
            this.pending=false;
            // this.bets=[];
            this.loading=true,
              axios.get('/viewhistorybets?page=' + page)
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
          getevents(){
            this.loading=true,
            axios.get('/pick20/getevents').then(response=>{
              this.loading=false,
              this.events=response.data;
              document.title = "Pick "+this.events.pick;
            })
          }
        },
        mounted() {
          this.historybets();
          this.getevents();
        }
    }
</script>
