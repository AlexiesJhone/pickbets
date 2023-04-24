<style media="screen">
.modal1 {
padding: 0 !important;
}
.modal1 .modal-dialog1 {
width: 100%;
max-width: none;
height: 100%;
margin: 0;
}
.modal1 .modal-content1 {
height: 100%;
border: 0;
border-radius: 0;
}
.modal1 .modal-body1 {
overflow-y: auto;
}
</style>
<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-6">
              <changepassword></changepassword>
              <modalcash :userx='user'></modalcash>
                <div class="card">
                    <div class="card-header bg-dark text-warning font-weight-bold">Bet History</div>
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Event Name</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in pageOfItems1" v-if="pageOfItems1.length">
                            <td><b>{{t.event_name}}</b> - [Pick : {{t.pick}}] - {{t.fightdate|datef}}</td>
                            <td><p v-if="t.status===1" class="text-success font-weight-bold">Active</p><p v-if="t.status===0" class="text-danger font-weight-bold">Pending</p><p v-if="t.status===2" class="text-dark font-weight-bold">Finished</p></td>
                            <td>
                              <!-- <a class="btn btn-success btn-sm" @click.prevent='showmodal(t)'>History</a><a class="btn btn-warning btn-sm" @click.prevent='showmodal(t)'>Pending</a> -->
                              <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-success btn-sm" @click.prevent='showmodal(t)'>History</button>
                                <button type="button" class="btn btn-danger btn-sm" @click.prevent='showmodal2(t)'>Pending</button>
                              </div>
                             </td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="card-footer"><center>
                        <jw-pagination :items="events" @changePage="onChangePage1" :maxPages='5' :pageSize='15'></jw-pagination></center>
                      </div>
                </div>
            </div>
        </div>
        <div class="modal modal1 fade" id="prebets" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog1 modal-fullscreen">
            <div class="modal-content modal-content1 table-responsive"  style="border:none !important;">
              <div class="modal-header bg-dark">
                <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel" v-if="pending">Bet History of  <b>{{form.event_name}} - Pick {{form.pick}}</b> </h5>
                <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel"v-if="!pending">Pending Bets of  <b>{{form.event_name}} - Pick {{form.pick}}</b> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body modal-body1" style="padding:0">
              <div v-if="bets" style="heigh:100vh">
                <table class="table table-striped table-hover table-sm">
                  <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Starting Fight</th>
                        <th class="text-center">Amount</th>
                      <th class="text-center">Bet</th>
                      <th class="text-center">Wins</th>
                      <th class="text-center" v-if="pending">Losses</th>
                      <th class="text-center">Result</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(t, index) in bets.data" :index='index'>
                      <td class="text-center font-weight-bold">{{index+1}}</td>
                      <td class="text-center">{{t.startingfight}}</td>
                      <td class="text-center">{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                      <td class="text-center">{{t.bet}}</td>
                      <!-- <td class="text-center">{{form.pick}}</td> -->
                      <td class="text-center">{{t.wins}}</td>
                      <td class="text-center" v-if="pending">{{t.lose}}</td>
                      <td class="text-center"><p class="text-success" v-if="t.winner===1||t.winner===2">Won <a v-if="t.result">+{{Number(t.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></p> <p class="text-danger" v-if="t.winner===0">Pending</p>
                        <p class="text-danger" v-if="t.winner===3">Loss -{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</p></td>
                    </tr>
                  </tbody>
                </table>
                </div>
                <div v-else  style="height:100%">
                  <p class="h3 text-center align-middle"><br><br><br><br> No Data..</p>
                </div>
              </div>

              <div class="modal-footer justify-content-center" v-if="!pending">
                <pagination :data="bets" :show-disabled=true :limit='5' @pagination-change-page="showmodal2page">
                  <span slot="prev-nav">&lt; Previous</span>
                  <span slot="next-nav">Next &gt;</span>
                </pagination>
                <button type="button" class="btn btn-secondary form-control col-sm-12" data-dismiss="modal">Close</button>
              </div>
              <div class="modal-footer justify-content-center" v-if="pending">
                <pagination :data="bets" :show-disabled=true :limit='5' @pagination-change-page="showmodalpage">
                  <span slot="prev-nav">&lt; Previous</span>
                  <span slot="next-nav">Next &gt;</span>
                </pagination>
                <button type="button" class="btn btn-secondary form-control col-sm-12" data-dismiss="modal">Close</button>
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
          pending:false,
          loading:false,
          bets:{},
          pageOfItems: [],
          pageOfItems1: [],
          events:[],
          form:new Form({
            id:'',
            event_name:'',
            currentfight:'',
            pick:'',
          })
        }
      },
      computed:{
      },
      methods:{
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        onChangePage1(pageOfItems1) {
            // update page of items
            this.pageOfItems1 = pageOfItems1;
        },
        showmodal2(t,page=1) {
          this.pending=false;
          this.form.fill(t);
          // this.bets=[];
          this.loading=true,
            this.form.post('/pick20/pendingbets?page=' + page)
                .then(response => {
                  this.loading=false,
                  this.bets=response.data;
                  $('#prebets').modal('show')
                });
        },
        showmodal2page(page=1) {
          this.pending=false;
          // this.bets=[];
          this.loading=true,
            this.form.post('/pick20/pendingbets?page=' + page)
                .then(response => {
                  this.loading=false,
                  this.bets=response.data;
                  $('#prebets').modal('show')
                });
        },
        showmodal(t,page=1){
          this.form.fill(t);
          this.pending=true;
          this.loading=true;
          this.form.post('/pick20/historybets?page=' + page)
              .then(response => {
                this.loading=false,
                this.bets=response.data;
                $('#prebets').modal('show')
              });
        },
        showmodalpage(page=1){
          // this.bets=[];
          this.pending=true;
          this.loading=true;
          this.form.post('/pick20/historybets?page=' + page)
              .then(response => {
                this.loading=false,
                this.bets=response.data;
                $('#prebets').modal('show')
              });
        },
        getevents(){
          this.loading=true,
          axios.get('/pick20/getallevents').then(response=>{
            this.loading=false,
            this.events=response.data;
            document.title = "Pick "+this.events.pick;
          })
        }
      },
      created() {
        this.getevents();
      }
    }
</script>
