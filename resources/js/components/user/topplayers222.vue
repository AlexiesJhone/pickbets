<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
          <changepassword v-if="user" :user='user'></changepassword>
          <session :userx='user' v-if="user"></session>
          <div class="col-md-12 row">
            <div class="col-md-12">

              <!-- <div class="alert alert-success" role="alert"><center>
              <h4 class="alert-heading">Take Note</h4><hr>
              <p>All bets for Dec. 13 will be moved to Dec. 14 with the same starting number #141</p>
            </center>
              </div> -->
              <div class="card">
                <div class="card-header bg-dark text-warning font-weight-bold text-center">
                  {{events.event_name}} [{{events.fights}} Fights]<br> <b class="text-success">JACKPOT FOR TODAY : 100,000</b>
                  <!-- {{events.event_name}} - {{events.event_name}} [{{events.fights}} Fights]<br> <b class="text-success">JACKPOT FOR TODAY : {{Number(jackpotfinal).toLocaleString()}}</b> -->
                </div>
              </div><hr>
              <a href="/pastwinners" class="btn btn-success form-control">Go to past winners</a><br><br>
            </div>
            <div class="col-md-2" v-if="results.length">
            <div class="card" style="">
              <div class="card-header bg-dark text-warning font-weight-bold text-center">
                Results
              </div>
              <div class="card-body table-responsive" style="padding:0px;overflow-x:auto;height:480px">
              <table class="table table-sm table-bordered table-hover" >
                <thead>
                  <tr v-for="t in resultstotal" class="bg-danger font-weight-normal text-center text-white">
                    <th class="font-weight-normal" style="vertical-align:center">Meron</th>
                    <th class="font-weight-normal" v-if="t.meron">{{t.meron}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr>
                  <tr v-for="t in resultstotal" class="bg-primary font-weight-normal text-center text-white">
                    <th class="font-weight-normal">Wala</th>
                    <th class="font-weight-normal" v-if="t.wala">{{t.wala}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr>
                  <tr v-for="t in resultstotal"  class="bg-success font-weight-normal text-center text-white">
                    <th class="font-weight-normal">Draw</th>
                    <th class="font-weight-normal" v-if="t.draw">{{t.draw}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr v-for="t in resultstotal">
                  <tr v-for="t in resultstotal" class="bg-secondary font-weight-normal text-center text-white">
                    <th class="font-weight-normal">Cancelled</th>
                    <th class="font-weight-normal" v-if="t.cancelled">{{t.cancelled}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr>
                </thead>
                <thead class="bg-dark">
                  <tr>
                    <th style="border: 1px solid #464748;" class="text-warning text-center">Fight#</th>
                    <th style="border: 1px solid #464748;" class="text-warning text-center">Winner</th>
                  </tr>
                </thead>
                  <tr v-for="t in results" >
                    <td  v-if="t.result==='Meron'" class="bg-danger font-weight-normal text-center text-white" style="vertical-align: middle;">{{t.fightnumber}}</td>
                    <td class="text-center bg-danger"  v-if="t.result==='Meron'"><a class="text-white font-weight-normal" style="text-decoration:none;cursor: default">{{t.result}}</a></td>
                    <!-- <td class="text-center bg-danger"  v-if="t.result==='Meron'"><p class="text-white font-weight-normal">{{t.result.toUpperCase()}}</p></td> -->
                    <!-- <td class="text-center bg-danger"  v-if="t.result==='Meron'"><p class="text-white font-weight-normal">{{t.result.slice(0,-4)}}</p></td> -->
                    <td  v-if="t.result==='Wala'" class="bg-primary font-weight-normal text-center text-white" style="text-decoration:none;cursor: default">{{t.fightnumber}}</td>
                    <td class="text-center bg-primary"  v-if="t.result==='Wala'"><a class="text-white font-weight-normal text-center" style="text-decoration:none;cursor: default">{{t.result}}</a></td>
                    <td  v-if="t.result==='Cancelled'" class="bg-secondary font-weight-normal text-center text-white" style="text-decoration:none;cursor: default">{{t.fightnumber}}</td>
                    <td class="text-center bg-secondary"  v-if="t.result==='Cancelled'"><a class="text-white font-weight-normal text-center" style="text-decoration:none;cursor: default">{{t.result}}</a></td>
                    <td  v-if="t.result==='Draw'" class="bg-success font-weight-normal text-center text-white" style="text-decoration:none;cursor: default">{{t.fightnumber}}</td>
                    <td class="text-center bg-success"  v-if="t.result==='Draw'"><a class="text-white font-weight-normal text-center" style="text-decoration:none;cursor: default">{{t.result}}</a></td>
                  </tr>
              </table>
              </div>
            </div>
            </div>

            <div class="col-md-5 mx-auto" >
                <div class="card table-responsive">
                    <div class="card-header bg-dark text-warning font-weight-bold text-center" v-if="bets.length">Current top Leaderboard</div>
                    <div class="card-header bg-dark text-warning font-weight-bold text-center"v-if="!bets.length">Take Note</div>
                        <table class="table table-striped table-sm table-hover" v-show="bets.length">
                          <thead>
                            <tr>
                              <th class="text-center">#</th>
                              <th  class="text-center">Name/Bet ID</th>
                              <th  class="text-center">Starting Fight</th>
                              <th  class="text-center">Wins</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(t,index) in bets" :index='index' v-if="t.wins>=5">
                              <td  class="text-center">{{index+1}}</td>
                              <td  class="text-center">{{t.user.name}}<a v-if="t.user.role===0">({{t.id}})</a></td>
                              <td  class="text-center">{{t.startingfight}}</td>
                              <td  class="text-center">{{t.wins}}</td>
                            </tr>
                          </tbody>
                        </table>
                        <p v-if="!bets.length" class="text-center font-weight-bold"><br>Pick20 event for today will start at fight 141 around 5pm</p>
                </div>
                <div class="card" v-if="winners.length">
                    <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners for today</div>
                        <table class="table table-striped table-sm table-hover" v-if="winners.length">
                          <thead>
                            <tr>
                              <th class="">#</th>
                              <th class="text-center">Starting fight</th>
                              <th  class="text-center">Name/Bet ID</th>
                              <th  class="text-center">Wins</th>
                              <!-- <th  class="text-center">Losses</th> -->
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(t,index) in winners" :index='index'>
                            <!-- <tr v-for="(t,index) in winners" :index='index' v-if="t.winner===1||t.winner===2"> -->
                              <th  class="">{{index+1}}</th>
                              <td  class="text-center">{{t.startingfight}}</td>
                              <td  class="text-center">{{t.user.name}}  <a v-if="t.user.role===0">({{t.id}})</a> </td>
                              <td  class="text-center">{{t.wins}}</td>
                              <!-- <td  class="text-center">{{t.lose}}</td> -->
                            </tr>
                          </tbody>
                        </table>
                        <p v-if="!winners.length" class="text-center font-weight-bold">There`s no Current winners yet.. <br>Come back around 5-6pm</p>
                </div>

            </div>
            <div class="col-md-5" v-if="results.length>=145">
              <div class="card">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Lowest for today with only 2 or less draws</div>
                      <table class="table table-striped table-sm table-hover" v-if="results.length>=145">
                        <thead>
                          <tr>
                            <th class="">#</th>
                            <th  class="text-center">Name/Bet ID</th>
                            <th class="text-center">Starting fight</th>
                            <th  class="text-center">Wins</th>
                            <!-- <th  class="text-center">Losses</th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <!-- <tr> -->
                          <tr v-for="(t,index) in losersleaders" :index='index'>
                          <!-- <tr v-for="(t,index) in winners" :index='index' v-if="t.winner===1||t.winner===2"> -->
                            <th  class="">{{index+1}}</th>
                            <td  class="text-center">{{t.name}}</td>
                            <td  class="text-center">{{t.startingfight}}</td>
                            <td  class="text-center">{{t.wins}}</td>
                            <!-- <td  class="text-center">{{t.lose}}</td> -->

                            <!-- <th>1</th>
                            <td class="text-center">wency</td>
                            <th class="text-center">141</th>
                            <td class="text-center">2</td>
                          </tr> -->
                          <!-- <tr>
                            <th>2</th>
                            <td class="text-center">Mega Sardines</td>
                            <th class="text-center">141</th>
                            <td class="text-center">2</td>-->
                          </tr>
                        </tbody>
                      </table>
                      <p v-if="!losersleaders.length" class="text-center font-weight-bold">There`s no Current lowest yet.. <br>Come back around 5-6pm results after 10 fights of the starting fight</p>
              </div>
              <!-- <div class="card table-responsive">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners highest scores [prize : 500]</div>
                      <table class="table table-striped table-sm table-hover">
                        <thead>
                          <tr>
                            <th class="">Date</th>
                            <th class="">Name</th>
                            <th class="">Prize</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in pastwinners">
                            <th  class="">{{t.fightdate|datec}}</th>
                            <td  class="">{{t.name}}</td>
                            <td  class="">{{Number(t.winnings).toLocaleString()}}</td>
                          </tr>
                          <tr>
                            <th>Dec 4, 2021</th>
                            <td>Richard Pascual</td>
                            <td>500</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Ashiel Cereneo</td>
                            <td>500</td>
                          </tr>
                          <tr>
                            <th>Dec 2, 2021</th>
                            <td>Lance</td>
                            <td>250</td>
                          </tr>
                          <tr>
                            <th>Dec 2, 2021</th>
                            <td>Marcus Tomada</td>
                            <td>250</td>
                          </tr>
                          <tr>
                            <th>Dec 1, 2021</th>
                            <td>Richard Pascual</td>
                            <td>500</td>
                          </tr>
                        </tbody>
                      </table>
                       <p v-if="!pastwinners.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
              </div> -->
              <!-- <div class="card table-responsive">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners 2nd to the highest [Prize : 250]</div>
                      <table class="table table-striped table-sm table-hover">
                        <thead>
                          <tr>
                            <th class="">Date</th>
                            <th class="">Name</th>
                            <th class="">Prize</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in pastwinners2">
                            <th  class="">{{t.fightdate|datec}}</th>
                            <td  class="">{{t.name}}</td>
                            <td  class="">{{Number(t.winnings).toLocaleString()}}</td>
                          </tr>
                        </tbody>
                      </table>
                      <p v-if="!pastwinners2.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
              </div> -->
              <!-- <div class="card table-responsive">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners 3rd to the highest [Prize : 150]</div>
                      <table class="table table-striped table-sm table-hover">
                        <thead>
                          <tr>
                            <th class="">Date</th>
                            <th class="">Name</th>
                            <th class="">Prize</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in pastwinners3">
                            <th  class="">{{t.fightdate|datec}}</th>
                            <td  class="">{{t.name}}</td>
                            <td  class="">{{Number(t.winnings).toLocaleString()}}</td>
                          </tr>
                        </tbody>
                      </table>
                      <p v-if="!pastwinners3.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
              </div> -->
              <!-- <div class="card table-responsive">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Lowest Scores Consolation Prize [Prize : 100]</div>
                      <table class="table table-striped table-sm table-hover">
                        <thead>
                          <tr>
                            <th class="">Date</th>
                            <th class="">Name</th>
                            <th class="">Prize</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in losers">
                            <th  class="">{{t.fightdates|datec}}</th>
                            <td  class="">{{t.name}}</td>
                            <td  class="">{{Number(t.winnings).toLocaleString()}}</td>
                          </tr>
                          <tr>
                            <th>Dec 4, 2021</th>
                            <td>Kathlene Gangan</td>
                            <td>50</td>
                          </tr>
                          <tr>
                            <th>Dec 4, 2021</th>
                            <td>Geeflor Manambid</td>
                            <td>50</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Amor</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Rhodeta	</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Donald		</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Lance</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Amor	</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Rhodeta		</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Donald		</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Donald		</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 3, 2021</th>
                            <td>Lance</td>
                            <td>10</td>
                          </tr>
                          <tr>
                            <th>Dec 2, 2021</th>
                            <td>Pauline	</td>
                            <td>100</td>
                          </tr>
                          <tr>
                            <th>Dec 1, 2021</th>
                            <td>Paul Domasig</td>
                            <td>50</td>
                          </tr>
                          <tr>
                            <th>Dec 1, 2021</th>
                            <td>Edwin Simbillo</td>
                            <td>50</td>
                          </tr>
                        </tbody>
                      </table>
                       <p v-if="!pastwinners.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
              </div> -->

            </div>
            <!-- <div class="col-md-12">
              <div class="card">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Past Winners</div>
                      <table class="table table-striped table-sm table-hover">
                        <thead>
                          <tr>
                            <th class="">Date</th>
                            <th class="">Name</th>
                            <th class="">Prize</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in pastwinners">
                            <td  class="">{{t.created_at|datec}}</td>
                            <td  class="">{{t.name}}</td>
                            <td  class="">{{Number(t.winnings).toLocaleString()}}</td>
                          </tr>
                        </tbody>
                      </table>
                      <p v-if="!pastwinners.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
              </div>
            </div> -->
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
          computed:null,
          bets:[],
          control:[],
          winners:[],
          losers:[],
          pastwinners:[],
          pastwinners2:[],
          pastwinners3:[],
          results:[],
          resultstotal:[],
          events:[],
          losersleaders:[]
        }
      },
      computed:{
        jackpotfinal: function(){
          if (this.control.addtojackpot) {

            return  parseFloat(this.control.addtojackpot)+parseFloat(this.control.jackpot);
          }else {
            return parseFloat(this.control.jackpot);
          }
       },
      },
      methods:{
        getpastwinners3(){
          axios.get('/pick20/pastwinners3').then(response=>{
            this.pastwinners3 = response.data;
          });
        },
        getpastwinners2(){
          axios.get('/pick20/pastwinners2').then(response=>{
            this.pastwinners2 = response.data;
          });
        },
        getlowest(){
          axios.get('/pick20/lowest').then(response=>{
            this.losers = response.data;
          });
        },
        getlowestleaderboard(){
          axios.get('/pick20/lowestleaders').then(response=>{
            this.losersleaders = response.data;
          });
        },
        getpastwinners(){
          axios.get('/pick20/pastwinners').then(response=>{
            this.pastwinners = response.data;
          });
        },
        computethis(){
          this.computed = this.events.startingfight - this.events.currentfight +1 ;
        },
        getevent(){
          axios.get('/pick20/getevents').then(response=>{
            this.events = response.data;
            this.computethis();
          })
        },
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.control = response.data;
            this.computethis();
          })
        },
        getresults(){
          this.loading=true,
          axios.get('/pick20/getresults').then(response=>{
            this.loading=false,
            this.results=response.data;
          })
        },
        getwinners(){
          this.loading=true,
          axios.get('/winnersfortoday').then(response =>{
            this.loading=false,
            this.winners=response.data;
          }).catch(()=>{
            this.loading=false,
            Swal.fire(
              'Please be inform',
              'Theres no active event',
              'warning'
            );
          })
        },
        getbets(){
          this.loading=true,
          axios.get('/pendingtopplayersx').then(response=>{
            this.loading=false,
            $('#prebets').modal('show')
            this.bets=response.data;
          }).catch(()=>{
            this.loading=false,
            Swal.fire(
              'Please be inform',
              'Theres no active event',
              'warning'
            );
          })
        },
        getresultstotal(){
          this.loading=true,
          axios.get('/pick20/getresultstotal').then(response=>{
            this.loading=false,
            this.resultstotal=response.data;
          })
        },
      },
        created() {
          this.getpastwinners3();
          this.getpastwinners2();
            this.getlowest();
            this.getpastwinners();
            this.getlowestleaderboard();
          this.getresultstotal();
            this.getbets();
            this.getwinners();
            this.getresults();
            this.getevent();
            this.getcontrol();
            Echo.channel('leaders')
            .listen('leaderboards',(event)=>{
              this.getlowestleaderboard();
              this.getpastwinners();
              this.getevent();
              this.getbets();
              this.getwinners();
              this.getresults();
                this.getresultstotal();
            })
        }
    }
</script>
