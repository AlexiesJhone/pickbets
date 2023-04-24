<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
              <changepassword :userx='user'></changepassword>
              <modalcash :userx='user'></modalcash>
              <session :userx='user'></session>
              <div id="overlay" v-if="loading">
                <tile style="color:white"></tile>
                <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
              </div>

                <div class="card">
                    <div class="card-header bg-dark text-white">Pending Bets<br> <a class="text-warning">All your pick20 pending bets starting from the latest.</a></div>
                    <div class="card-body table-responsive" style="padding:0"  v-if="bets.data.length">
                        <table class="table table-sm table-striped" >
                          <!-- <thead>
                            <tr>
                              <th class="text-center">#</th> -->
                              <!-- <th class="text-center">Date</th>
                              <th class="text-center">Starting Fight</th> -->
                              <!-- <th class="text-center">Fight Date</th>
                              <th class="text-center">Fight number</th>
                              <th class="text-center">Wins</th>
                              <th class="text-center">Result</th>
                              <th class="text-center">View</th>
                            </tr> -->
                          </thead>
                          <tbody>
                            <tr v-for="(t,index) in bets.data" :index='index'>
                              <div class="card">
                                  <div class="card-header bg-dark text-warning text-center">Fightdate : <b>{{t.fightdate|datec}}</b> - Starting Fight <b>{{t.startingfight}}</b> [Pick : <b>{{t.pick}}</b>]</div>
                                  <div class="card-body table-responsive" style="padding:0">
                                    <table class="table table-sm table-striped" >
                                      <thead>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Bet</th>
                                        <th class="text-center">Wins</th>
                                        <!-- <th class="text-center">Result</th> -->
                                        <th class="text-center">View</th>
                                      </thead>
                                      <tbody>
                                        <tr v-for="(a,indexx) in t.expertbet" :index='indexx'>
                                          <th class="text-center">{{indexx+1}}</th>
                                           <td class=" text-center">{{Number(a.amount).toLocaleString()}}</td>
                                          <td class="text-center">{{a.bet}}</td>
                                          <td class="text-center">{{a.wins}}</td>
                                          <!-- <td class="text-center"> <a v-if="a.winner===0" class="text-danger">Pending</a> </td> -->
                                          <td class="text-center"> <a class="btn btn-success btn-sm" @click.prevent='showdetailedbets(a.id)'>View bets</a> </td>
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
                        <div class="card-footer justify-content-center" v-if="bets.data.length">
                            <pagination class="justify-content-center" :data="bets" :limit='5' @pagination-change-page="pendings">
                              <!-- <span slot="prev-nav">&lt; Previous</span>
                              <span slot="next-nav">Next &gt;</span> -->
                            </pagination>
                        </div>
                    </div>
                    <div class="card-body font-weight-bold" style="" v-if="!bets.data.length">
                      You have no pending bets yet..
                    </div>
                </div>
                <div class="modal fade" id="detailed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content" style="border:none !important;">
                      <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title text-warning" id="exampleModalLabel" v-if="detailed.length">Bets for starting fight number {{detailed[0].fightnumber}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body table-responsive" style="padding:0">
                        <table class="table tabl-sm table-striped table-borderless table-hover">
                          <thead class="thead-dark">
                            <tr>
                              <th>Fight #</th>
                              <th>Bet</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="t in detailed" :index='index' v-if="bets.data">
                            <!-- <tr v-for="(t,index) in bets.slice().reverse()" :index='index'> -->
                              <th style="padding: 0.20rem">{{t.fightnumber}}</th>
                              <td style="padding: 0.20rem"><b v-if="t.selection==='Meron'" class="text-danger">Red Apple</b><b v-if="t.selection==='Wala'" class="text-info">White Apple</b>
                              <b v-if="t.selection==='Draw'" class="text-success">{{t.selection}}</b></td>
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
                      <div class="modal-footer justify-content-center" >
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
            detailed:[],
            events:[],
            bets:{},
            pages:0
          }
        },
        methods:{
          showdetailedbets(id){
            this.loading=true,
              axios.get('/pick20/showdetailedbets/' + id).then(response=>{
                this.loading=false,
                this.detailed = response.data;
                $('#detailed').modal('show');
              })
          },
          pendings(page=1) {
            this.pending=false;
            // this.bets=[];
            this.loading=true,
              axios.get('/pick20/pendingbets?page=' + page)
                  .then(response => {
                    if (page===1) {
                      this.pages=page;
                    }else {
                      //this.pages=page*10-9;
                      this.pages=page*5-4;
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
              document.title = "Pickbets";
            })
          }
        },
        mounted() {
          this.pendings();
          this.getevents();
        }
    }
</script>
