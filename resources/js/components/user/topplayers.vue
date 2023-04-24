<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
          <changepassword v-if="user" :user='user'></changepassword>
          <session :userx='user' v-if="user"></session>
	    <modalcash :userx='user' v-if="user"></modalcash>
          <div class="col-md-12 row">
            <div class="col-md-12">

                <div class="alert alert-success" role="alert" v-if="this.control.announcement"><center>
              <h4 class="alert-heading">Announcement</h4><hr>
              <p>{{this.control.announcement}}</p>
            </center>
              </div>
              <div class="card">
                <div class="card-header bg-dark text-warning font-weight-bold text-center">
                  {{events.event_name}} [{{events.fights}} Fights]<br> <b class="text-success">JACKPOT FOR TODAY : <a v-if="events">{{Number(events.jackpot).toLocaleString()}}</a> <a v-if="!events">To Be Announced</a> </b>
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
                    <th class="font-weight-normal" style="vertical-align:center">Red Apple</th>
                    <th class="font-weight-normal" v-if="t.meron">{{t.meron}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr>
                  <tr v-for="t in resultstotal" class="bg-primary font-weight-normal text-center text-white">
                    <th class="font-weight-normal">White Apple</th>
                    <th class="font-weight-normal" v-if="t.wala">{{t.wala}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr>
                  <!-- <tr v-for="t in resultstotal"  class="bg-success font-weight-normal text-center text-white">
                    <th class="font-weight-normal">Draw</th>
                    <th class="font-weight-normal" v-if="t.draw">{{t.draw}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr> -->
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
                    <td class="text-center bg-danger"  v-if="t.result==='Meron'"><a class="text-white font-weight-normal" style="text-decoration:none;cursor: default">Red Apple</a></td>
                    <!-- <td class="text-center bg-danger"  v-if="t.result==='Meron'"><p class="text-white font-weight-normal">{{t.result.toUpperCase()}}</p></td> -->
                    <!-- <td class="text-center bg-danger"  v-if="t.result==='Meron'"><p class="text-white font-weight-normal">{{t.result.slice(0,-4)}}</p></td> -->
                    <td  v-if="t.result==='Wala'" class="bg-primary font-weight-normal text-center text-white" style="text-decoration:none;cursor: default">{{t.fightnumber}}</td>
                    <td class="text-center bg-primary"  v-if="t.result==='Wala'"><a class="text-white font-weight-normal text-center" style="text-decoration:none;cursor: default">White Apple</a></td>
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
                <div class="card">
                    <div class="card-header bg-dark text-warning font-weight-bold text-center" v-if="bets.length">Current top Leaderboard starting fight [Starting fight : {{ bets[0].startingfight}}]</div>
                    <div class="card-header bg-dark text-warning font-weight-bold text-center"v-if="!bets.length&&!winners.length">Take Note</div>
                        <div class="card-body table-responsive" style="min-height:100px;max-height:500px; padding:0;" v-if="bets.length">
                        <table class="table table-striped table-sm table-hover" v-show="bets.length">
                          <thead>
                            <tr>
                              <th class="">#</th>
                              <th  class="text-center">Name/Bet ID</th>
                              <!-- <th  class="text-center">Starting Fight</th> -->
                              <th  class="text-center">Wins</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(t,index) in bets" :index='index' v-if="t.wins>=5">
                              <td  class="font-weight-bold">{{index+1}}</td>
                              <td  class="text-center">{{t.user.name.substring(0,2)+"***"}}<a v-if="t.user.role===9">({{t.id}})</a></td>
                            <!--  <td  class="text-center">{{t.startingfight}}</td>-->
                              <td  class="text-center">{{t.wins}}</td>
                            </tr>
                          </tbody>
                        </table>
                        </div>
                        <p v-if="!bets.length&&!winners.length" class="text-center font-weight-bold"><br>Pick20 Leaderboards will appear at the first 5 results.</p>
                </div>
                <div class="card"  v-for="a in winners" :index='index' v-if="winners.length">
                    <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners for today [Starting fight : {{a.startingfight}}]</div>
                      <div class="card-body table-responsive" style="min-height:100px;max-height:500px; padding:0;"  v-if="winners.length">
                        <table class="table table-striped table-sm table-hover" v-if="winners.length">
                          <thead>
                            <tr>
                              <th class="">#</th>
                             <!-- <th class="text-center">Starting fight</th> -->
                              <th  class="text-center">Name/Bet ID</th>
                              <th  class="text-center">Wins</th>
                              <!-- <th  class="text-center">Losses</th> -->
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(t,index) in a.expertbet" :index='index'>
                              <th  class="">{{index+1}}</th>
                              <td  class="text-center">{{t.user.name.substring(0,2)+"***"}}<a v-if="t.user.role===9">({{t.id}})</a></td>
                              <td  class="text-center">{{t.wins}}</td>
                            </tr>
                            <!-- <tr v-for="(t,index) in winners" :index='index' v-if="t.winner===1||t.winner===2"> -->
                            <!-- <td  class="text-center">{{t.startingfight}}</td> -->
                            <!-- <td  class="text-center">{{t.lose}}</td> -->
                          </tbody>
                        </table>

                      </div>
                        <p v-if="!winners.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
                </div>

            </div>
            <div class="col-md-5" v-if="results.length>=5">
              <div class="card">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center" v-if="losersleaders.length">Current Lowest for today [Starting fight : {{losersleaders[0].startingfight}}]</div>
                  <div class="card-header bg-dark text-warning font-weight-bold text-center" v-if="!losersleaders.length&& !pastlowesttoday.length">There are no current lowest yet</div>
                      <div class="card-body table-responsive" style="min-height:100px;max-height:500px; padding:0;" v-if="losersleaders.length">
                      <table class="table table-striped table-sm table-hover" v-if="results.length>=5 && losersleaders.length">
                        <thead>
                          <tr>
                            <th class="">#</th>
                            <th  class="text-center">Name/Bet ID</th>
                            <th  class="text-center">Wins</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(t,index) in losersleaders" :index='index'>
                            <th  class="">{{index+1}}</th>
                            <td  class="text-center">{{t.name.substring(0,2)+"***"}}<a v-if="t.role===9">({{t.id}})</a></td>
                            <td  class="text-center">{{t.wins}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                      <p v-if="!losersleaders.length && !pastlowesttoday.length" class="text-center font-weight-bold"><br>No Current Lowest yet..</p>
              </div>
              <div class="card" v-for="a in pastlowesttoday" :index='index' v-if="pastlowesttoday.length">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center" >Winners for lowest today [Starting fight : {{a.startingfight}}]</div>
                    <div class="card-body table-responsive" style="min-height:100px;max-height:500px; padding:0;">
                      <table class="table table-striped table-sm table-hover" v-if="pastlowesttoday.length">
                        <thead>
                          <tr>
                            <th class="">#</th>
                           <!-- <th class="text-center">Starting fight</th> -->
                            <th  class="text-center">Name/Bet ID</th>
                            <th  class="text-center">Wins</th>
                            <!-- <th  class="text-center">Losses</th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(t,index) in a.expertbet" :index='index' v-if="t.wins==0||t.wins==1||t.wins==2||t.wins==3">
                            <th  class="">{{index+1}}</th>
                            <td  class="text-center">{{t.user.name.substring(0,2)+"***"}} <a v-if="t.user.role===9">({{t.id}})</a> </td>
                            <td  class="text-center">{{t.wins}}</td>
                          </tr>
                          <!-- <tr v-for="(t,index) in winners" :index='index' v-if="t.winner===1||t.winner===2"> -->
                          <!-- <td  class="text-center">{{t.startingfight}}</td> -->
                          <!-- <td  class="text-center">{{t.lose}}</td> -->
                        </tbody>
                      </table>
                    </div>
                      <p v-if="!pastlowesttoday.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
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
          losersleaders:[],
          pastlowesttoday:[]
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
        pastlowestfortoday(){
          axios.get('/pick20/pastlowesttoday').then(response=>{
            this.pastlowesttoday = response.data;
          });
        },
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
          this.pastlowestfortoday();
          // this.getpastwinners3();
          // this.getpastwinners2();
            // this.getlowest();
            this.getpastwinners();
            this.getlowestleaderboard();
          this.getresultstotal();
            this.getbets();
            this.getwinners();
            this.getresults();
            this.getevent();
            this.getcontrol();
            Echo.private('announce')
            .listen('announcement',(event)=>{
              // console.log('announce')
              this.control.announcement=event.events.announcement;
              if (event.events.announcement) {
                Swal.fire(
                  'Announcement',
                  event.events.announcement,
                  'info'
                )
              }
            });
            Echo.channel('leaders')
            .listen('leaderboards',(event)=>{
              this.getevent();
              this.getbets();
              this.getwinners();
              this.getresults();
              this.getresultstotal();
              this.pastlowestfortoday();
              this.getlowestleaderboard();
              this.getpastwinners();
            })
        }
    }
</script>
