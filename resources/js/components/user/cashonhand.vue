<template>
    <a><session :userx='user'></session>
      <modalcash :userx='user'></modalcash>
      <center>
      <b v-if="form.cash">
      <b v-if="user.role===9||user.role===3||user.role===4" class="text-muted">Current Cash : </b><b class="text-warning" v-if="user.role===9||user.role===3||user.role===4">
        {{Number(cash).toLocaleString()}}</b><br>
        </b>
      <a v-if="events">
      <b v-if="user.role===9||user.role===3||user.role===4" class="text-muted text-warning">Current Jackpot for Pick 20 : </b><b class="text-success" v-if="user.role===9||user.role===3||user.role===4">{{Number(events.jackpot).toLocaleString(undefined, {minimumFractionDigits: 0,maximumFractionDigits: 0})}}</b></a>
      <!-- <b v-if="user.role===0||user.role===3||user.role===4" class="text-muted text-warning">Current Jackpot[20/20] : </b><b class="text-success" v-if="user.role===0||user.role===3||user.role===4">{{Number(jackpotfinal).toLocaleString()}}</b></a> -->
    </center>
  </a>
</template>
<script>
    export default {
      props:['user'],
      data(){
        return{
          userx:[],
          events:[],
          controls:[],
          users:this.user.cash,
          form:new Form({
            id:'',
            cash:null,
          })
    }
  },
  computed:{
    cash: function () {
      return parseFloat(this.form.cash).toFixed(2);
    },
    jackpotfinal: function(){
      if (this.controls.addtojackpot) {
        return  parseFloat(this.controls.addtojackpot)+parseFloat(this.controls.jackpot);
      }else {
        return parseFloat(this.controls.jackpot);
      }
   },
  },
  methods:{
    getevents(){
      axios.get('/pick20/getevents').then(response=>{
        this.events=response.data;
          // document.title = "Pick "+this.events.pick;

      });
    },
    getuser() {
      axios.get('/pick20/getuser').then(response=>{
        this.form.fill(response.data);
      })
    },
    getcontrol(){
      axios.get('/pick20/control').then(response=>{
        this.controls=response.data;
      });
    },
  },
  created(){
    this.getcontrol();
    this.getuser();
    this.getevents();
    Echo.private('cashupdate')
    .listen('userupdate',(event)=>{
        this.getevents();
      console.log(event.id.id)
      if (this.user.id === event.id.id) {
        this.form.cash = event.id.cash;
      }
    })
  }
}
</script>
