<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12">
              <!-- <div class="card">
                <button type="button" class="btn btn-success" @click.prevent='getallpossiblepayout' name="button">Add Combination</button>
                <table class="table">
                  <thead>
                    <th>Combinations</th>
                  </thead>
                  <tbody>
                    <tr v-for="t in Events">
                      <td>{{t.bet}}</td>
                    </tr>
                  </tbody>
                </table>
              </div> -->
              <div class="card table-responsive"  >
                <table class="table table-sm" v-for="t in Events">
                  <thead class="bg-dark text-warning font-weight-bold">
                    <tr >
                      <th colspan="2"><b>Pick 8 Payout Monitoring Available Combinations for {{t.startingfight}}</b></th>
                    </tr>
                    <tr>
                      <th>Combination</th>
                      <th>Possible Payout</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="s in t.combination" v-if="t.combination.length">
                      <td>{{s.bet}}</td>
                      <td><a class="text-danger" v-if="s.total<=129">{{s.total}}</a> <a class="text-success" v-else>{{s.total}}</a> </td>
                    </tr>
                    <tr v-if="!t.combination.length">
                      <td>There are no current bets for <b>Starting Fight {{t.startingfight}}</b>..</td>
                    </tr>
                  </tbody>
                </table>
                <a v-if="!Events.length">There are no current active event for now..</a>
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
          axios.get('/pick20/possiblepayoutallpick8').then(response=>{
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
                this.Events = event.alldata;
              }
            });
          });
    }
    }
</script>
