<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12">
              <div class="card table-responsive">
                <table class="table table-sm" v-if="Events.length">
                  <thead class="bg-dark text-warning font-weight-bold" >
                    <tr >
                      <th colspan="29"><b>Pick 3 Payout Monitoring</b></th>
                    </tr>
                    <tr >
                      <th class="sizengtext">Starting Fight</th>
                      <th class="sizengtext">Event Name</th>
                      <th class="sizengtext">RRR</th>
                      <th class="sizengtext">RRw</th>
                      <!-- <th class="sizengtext">MMD</th>
                      <th class="sizengtext">MwD</th> -->
                      <th class="sizengtext">Rww</th>
                      <th class="sizengtext">RwR</th>
                      <!-- <th class="sizengtext">MDM</th> -->
                      <!-- <th class="sizengtext">MDw</th>
                      <th class="sizengtext">MDD</th> -->
                      <th class="sizengtext">wwR</th>
                      <th class="sizengtext">www</th>
                      <!-- <th class="sizengtext">wwD</th>
                      <th class="sizengtext">wDD</th>
                      <th class="sizengtext">wDw</th>
                      <th class="sizengtext">wDM</th> -->
                      <th class="sizengtext">wRR</th>
                      <th class="sizengtext">wRw</th>
                      <!-- <th class="sizengtext">wMD</th> -->
                      <!-- <th class="sizengtext">DDD</th>
                      <th class="sizengtext">DDM</th>
                      <th class="sizengtext">DDw</th>
                      <th class="sizengtext">Dww</th>
                      <th class="sizengtext">DwD</th>
                      <th class="sizengtext">DwM</th>
                      <th class="sizengtext">DMM</th>
                      <th class="sizengtext">DMw</th>
                      <th class="sizengtext">DMD</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="t in Events" style="font-size: 12px !important;">
                      <td>{{t.startingfight}}</td>
                      <td>{{t.eventname}}</td>
                      <td><a class="text-danger" v-if="t.mmm<=129">{{Number(t.mmm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mmm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.mmw<=129">{{Number(t.mmw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mmw).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.mmd<=129">{{Number(t.mmd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mmd).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.mwd<=129">{{Number(t.mwd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mwd).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.mww<=129">{{Number(t.mww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mww).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.mwm<=129">{{Number(t.mwm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mwm).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.mdm<=129">{{Number(t.mdm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mdm).toLocaleString()}}</a></td> -->
                      <!-- <td><a class="text-danger" v-if="t.mdw<=129">{{Number(t.mdw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mdw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.mdd<=129">{{Number(t.mdd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mdd).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.wwm<=129">{{Number(t.wwm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.www<=129">{{Number(t.www).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.www).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.wwd<=129">{{Number(t.wwd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwd).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wdd<=129">{{Number(t.wdd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wdd).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wdw<=129">{{Number(t.wdw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wdw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wdm<=129">{{Number(t.wdm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wdm).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.wmm<=129">{{Number(t.wmm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wmm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wmw<=129">{{Number(t.wmw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wmw).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.wmd<=129">{{Number(t.wmd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wmd).toLocaleString()}}</a></td> -->
                      <!-- <td><a class="text-danger" v-if="t.ddd<=129">{{Number(t.ddd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.ddd).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.ddm<=129">{{Number(t.ddm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.ddm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.ddw<=129">{{Number(t.ddw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.ddw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.dww<=129">{{Number(t.dww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dww).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.dwd<=129">{{Number(t.dwd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dwd).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.dwm<=129">{{Number(t.dwm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dwm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.dmm<=129">{{Number(t.dmm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dmm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.dmw<=129">{{Number(t.dmw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dmw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.dmd<=129">{{Number(t.dmd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dmd).toLocaleString()}}</a></td> -->
                    </tr>
                  </tbody>
                </table>
                <a v-if="!Events.length">There are no current active event for now..</a>
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
          Events:[],
          pin:new Form({
            id:'',
            username:'',
            pin:'',
          }),
        }
      },
      methods:{
        getallpossiblepayout(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutallpick3').then(response=>{
            this.Events = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          }).catch(()=>{
            this.loading = false;
          })
        }
      },
        created() {
          this.getallpossiblepayout();
          Echo.private('insert_bet')
          .listen('betevent',(event)=>{
            console.log(event);
            // if (event.startingfight===this.select&&this.fight.id===event.id) {
            //   this.odds = this.odds + event.bet;
            //   console.log(event)
            // }

            this.Events.forEach((val)=>{
              console.log(event.alldata);
              if (val.id==event.id) {

                val.mmm = event.alldata[0].mmm;
                val.mmw = event.alldata[0].mmw;
                val.mmd = event.alldata[0].mmd;
                val.mwd = event.alldata[0].mwd;
                val.mww = event.alldata[0].mww;
                val.mwm = event.alldata[0].mwm;
                val.mdm = event.alldata[0].mdm;
                val.mdw = event.alldata[0].mdw;
                val.mdd = event.alldata[0].mdd;
                val.wwm = event.alldata[0].wwm;
                val.www = event.alldata[0].www;
                val.wwd = event.alldata[0].wwd;
                val.wdd = event.alldata[0].wdd;
                val.wdw = event.alldata[0].wdw;
                val.wdm = event.alldata[0].wdm;
                val.wmm = event.alldata[0].wmm;
                val.wmw = event.alldata[0].wmw;
                val.wmd = event.alldata[0].wmd;
                val.ddd = event.alldata[0].ddd;
                val.ddm = event.alldata[0].ddm;
                val.ddw = event.alldata[0].ddw;
                val.dww = event.alldata[0].dww;
                val.dwd = event.alldata[0].dwd;
                val.dwm = event.alldata[0].dwm;
                val.dmm = event.alldata[0].dmm;
                val.dmw = event.alldata[0].dmw;
                val.dmd = event.alldata[0].dmd;
                // this.possible = event.alldata;
                // alexlang
              }
            });
          });
    }
    }
</script>
