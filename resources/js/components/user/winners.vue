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
              <a href="/leaders" class="btn btn-success form-control">Go back to Leaderboard and Results</a><br><br>
              <a class="btn btn-dark form-control" @click.prevent='showlowest'>Click to see consolation prizes</a><br><br>
              <a class="btn btn-dark form-control" @click.prevent='showpick2'>Click to see <b class="text-warning">Pick 2</b> Winners</a><br><br>
              <a class="btn btn-dark form-control" @click.prevent='showpick3'>Click to see <b class="text-warning">Pick 3</b> Winners</a><br><br>
              <a class="btn btn-dark form-control" @click.prevent='showpick4'>Click to see <b class="text-warning">Pick 4</b> Winners</a><br><br>
              <a class="btn btn-dark form-control" @click.prevent='showpick5'>Click to see <b class="text-warning">Pick 5</b> Winners</a><br><br>
              <a class="btn btn-dark form-control" @click.prevent='showpick6'>Click to see <b class="text-warning">Pick 6</b> Winners</a><br><br>
              <a class="btn btn-dark form-control" @click.prevent='showpick8'>Click to see <b class="text-warning">Pick 8</b> Winners</a><br><br>
              <a class="btn btn-dark form-control" @click.prevent='showpick14'>Click to see <b class="text-warning">Pick 14</b> Winners</a><br><br>
            </div>
            <div class="col-md-3">
              <div class="card table-responsive">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners highest scores</div>
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
                            <td  class="">{{t.name.substring(0,2)+"***"}} <a v-if="t.role==9" class="font-weight-bold text-dark">Bet ID : {{t.id}}</a></td>
                            <td  class="">{{Number(t.winnings).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                          </tr>
                        </tbody>
                      </table>
                       <p v-if="!pastwinners.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
              </div>
            </div>
            <div class="col-md-3" >
              <div class="card table-responsive">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners 2nd to the highest</div>
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
                            <td  class="">{{t.name.substring(0,2)+"***"}} <a v-if="t.role==9" class="font-weight-bold text-dark">Bet ID : {{t.id}}</a></td>
                            <td  class="">{{Number(t.winnings).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                          </tr>
                        </tbody>
                      </table>
                      <p v-if="!pastwinners2.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
              </div>
            </div>
              <div class="col-md-3 mx-auto" >
                <div class="card table-responsive">
                    <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners 3rd to the highest</div>
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
                              <td  class="">{{t.name.substring(0,2)+"***"}} <a v-if="t.role==9" class="font-weight-bold text-dark">Bet ID : {{t.id}}</a></td>
                              <td  class="">{{Number(t.winnings).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                            </tr>
                          </tbody>
                        </table>
                        <p v-if="!pastwinners3.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
                </div>
              </div>
            <div class="col-md-3">
              <div class="card table-responsive">
                  <div class="card-header bg-dark text-warning font-weight-bold text-center">Winners 4th to the highest</div>
                      <table class="table table-striped table-sm table-hover">
                        <thead>
                          <tr>
                            <th class="">Date</th>
                            <th class="">Name</th>
                            <th class="">Prize</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in pastwinners4">
                            <th  class="">{{t.fightdate|datec}}</th>
                            <td  class="">{{t.name.substring(0,2)+"***"}} <a v-if="t.role==9" class="font-weight-bold text-dark">Bet ID : {{t.id}}</a></td>
                            <td  class="">{{Number(t.winnings).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                          </tr>
                        </tbody>
                      </table>
                       <p v-if="!pastwinners.length" class="text-center font-weight-bold">There`s no Current winners yet..</p>
              </div>
            </div>
            <!-- TOP 4 LOWEST SCORES -->
            <div class="modal fade" id="lowest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-dark">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Consolation Prizes for lowest</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" style="padding:0">
                    <div class="card" v-for="a in lowestwinners">
                      <div class="card-header bg-dark text-warning font-weight-bold text-center">
                        Starting Fight : {{a.startingfight}} | Date : {{a.fightdate|datec}}
                      </div>
                      <table class="table table-striped table-sm table-hover">
                        <thead>
                          <tr>
                            <!-- <th class="">Date</th> -->
                            <th class="">Name</th>
                            <th class="">Prize</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in a.past_expertbet" v-if="a.status==2">
                            <!-- <th  class="">{{a.fightdate|datec}}</th> -->
                            <td  class="">{{t.user.name.substring(0,2)+"***"}} </td>
                            <td  class="">{{Number(t.result).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                          </tr>
                          <tr v-for="t in a.expertbet" v-if="a.status==1||a.status==0">
                            <!-- <th  class="">{{a.fightdate|datec}}</th> -->
                            <td  class="">{{t.user.name.substring(0,2)+"***"}}</td>
                            <td  class="">{{Number(t.result).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- END OF TOP 4 LOWEST SCORES -->
            <!-- TOP 4 LOWEST SCORES -->
            <div class="modal fade" id="pick2winners" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-dark">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Winners of Pick {{pick}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" style="padding:0">
                    <div class="card" v-for="a in pick2winners">
                      <div class="card-header bg-dark text-warning font-weight-bold text-center">
                        Starting Fight : {{a.startingfight}} | Date : {{a.fightdate|datec}}
                      </div>
                      <table class="table table-striped table-sm table-hover">
                        <thead>
                          <tr>
                            <!-- <th class="">Date</th> -->
                            <th class="">Name</th>
                            <th class="">Prize</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in a.past_expertbet" v-if="a.status==2">
                            <!-- <th  class="">{{a.fightdate|datec}}</th> -->
                            <td  class="">{{t.user.name.substring(0,2)+"***"}} <a v-if="t.user.role==9" class="font-weight-bold text-dark">Bet ID : {{t.id}}</a> </td>
                            <td  class="">{{Number(t.result).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                          </tr>
                          <tr v-for="t in a.expertbet" v-if="a.status==0||a.status==1">
                            <!-- <th  class="">{{a.fightdate|datec}}</th> -->
                            <td  class="">{{t.user.name.substring(0,2)+"***"}} <a class="font-weight-bold text-dark" v-if="t.user.role==9">Bet ID : {{t.id}}</a></td>
                            <td  class="">{{Number(t.result).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- END OF TOP 4 LOWEST SCORES -->
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
          pick:'',
          computed:null,
          bets:[],
          control:[],
          winners:[],
          losers:[],
          pastwinners:[],
          pastwinners2:[],
          pastwinners3:[],
          pastwinners4:[],
          lowestwinners:[],
          pick2winners:[],
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
        showpick14(){
          this.pick = 6;
          this.loading=true;
          axios.get('/pick20/pick14winners').then(response=>{
            this.pick2winners = response.data;
            this.loading=false;
            $('#pick2winners').modal('show');
          });
        },
        showpick8(){
          this.pick = 6;
          this.loading=true;
          axios.get('/pick20/pick8winners').then(response=>{
            this.pick2winners = response.data;
            this.loading=false;
            $('#pick2winners').modal('show');
          });
        },
        showpick6(){
          this.pick = 6;
          this.loading=true;
          axios.get('/pick20/pick6winners').then(response=>{
            this.pick2winners = response.data;
            this.loading=false;
            $('#pick2winners').modal('show');
          });
        },
        showpick5(){
          this.pick = 5;
          this.loading=true;
          axios.get('/pick20/pick5winners').then(response=>{
            this.pick2winners = response.data;
            this.loading=false;
            $('#pick2winners').modal('show');
          });
        },
        showpick4(){
          this.pick = 4;
          this.loading=true;
          axios.get('/pick20/pick4winners').then(response=>{
            this.pick2winners = response.data;
            this.loading=false;
            $('#pick2winners').modal('show');
          });
        },
        showpick3(){
          this.pick = 3;
          this.loading=true;
          axios.get('/pick20/pick3winners').then(response=>{
            this.pick2winners = response.data;
            this.loading=false;
            $('#pick2winners').modal('show');
          });
        },
        showpick2(){
          this.pick = 2;
          this.loading=true;
          axios.get('/pick20/pick2winners').then(response=>{
            this.pick2winners = response.data;
            this.loading=false;
            $('#pick2winners').modal('show');
          });
        },
        showlowest(){
          this.loading=true;
          axios.get('/pick20/lowestwinners').then(response=>{
            this.lowestwinners = response.data;
            this.loading=false;
            $('#lowest').modal('show');
          });
        },
        getpastwinners4(){
          axios.get('/pick20/pastwinners4').then(response=>{
            this.pastwinners4 = response.data;
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
      },
        created() {
            this.getpastwinners4();
            this.getpastwinners3();
            this.getpastwinners2();
            this.getpastwinners();
            this.getevent();
            this.getcontrol();
            Echo.private('announce')
            .listen('announcement',(event)=>{
              // console.log(event.events.announcement);
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
              this.getpastwinners4();
              this.getpastwinners3();
              this.getpastwinners2();
              this.getpastwinners();
            })
        }
    }
</script>
