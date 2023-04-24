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
                <table class="table" v-if="Events.length">
                  <thead class="bg-dark text-warning font-weight-bold">
                    <tr>
                      <th colspan="11"><b>Pick 2 Payout Monitoring</b></th>
                    </tr>
                    <tr>
                      <th>Starting Fight</th>
                      <th>Event Name</th>
                      <th>RR</th>
                      <th>Rw</th>
                      <!-- <th>MD</th> -->
                      <th>wR</th>
                      <th>ww</th>
                      <!-- <th>wD</th> -->
                      <!-- <th>DM</th>
                      <th>Dw</th>
                      <th>DD</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="t in Events">
                      <td>{{t.startingfight}}</td>
                      <td>{{t.eventname}}</td>
                      <td><a class="text-danger" v-if="t.mm<=129">{{Number(t.mm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.mw<=129">{{Number(t.mw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mw).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.md<=129">{{Number(t.md).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.md).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.wm<=129">{{Number(t.wm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.ww<=129">{{Number(t.ww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.ww).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.wd<=129">{{Number(t.wd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wd).toLocaleString()}}</a></td> -->
                      <!-- <td><a class="text-danger" v-if="t.dm<=129">{{Number(t.dm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dm).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.dw<=129">{{Number(t.dw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.dd<=129">{{Number(t.dd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dd).toLocaleString()}}</a></td> -->
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
          axios.get('/pick20/possiblepayoutall').then(response=>{
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
              console.log(event.alldata.mm);
              if (val.id==event.id) {

                val.mm = event.alldata[0].mm;
                val.mw = event.alldata[0].mw;
                val.md = event.alldata[0].md;
                val.wm = event.alldata[0].wm;
                val.ww = event.alldata[0].ww;
                val.wd = event.alldata[0].wd;
                val.dm = event.alldata[0].dm;
                val.dw = event.alldata[0].dw;
                val.dd = event.alldata[0].dd;
                // this.possible = event.alldata;
                // alexlang
              }
            });
          });
    }
    }
</script>
