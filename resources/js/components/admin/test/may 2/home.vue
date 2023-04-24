<template>
    <div class="container">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>

          <div class="col-md-5">
            <changepassword :user='user'></changepassword>
            <modalcash :userx='user'></modalcash>
            <!-- <marquee width="100%" direction="left" height="auto" scrollamount="13">
              <div class="alert alert-success" role="alert">
                <b>Jackpot For Today {{Number(events.jackpot).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} </b>
              </div>
            </marquee> -->

            <marquee width="100%" direction="left" height="auto" scrollamount="13" v-if="control==='Last Call'">
              <div class="alert alert-warning" role="alert">
                 <b>LAST CALL .. PLEASE PLACE YOUR BET..</b>
              </div>
            </marquee>
            <!-- <marquee width="100%" direction="left" height="auto" scrollamount="13" v-if="control==='Close'">
              <div class="alert alert-danger" role="alert">
                FIGHT <b>CLOSED </b>.. PLEASE STANDBY FOR THE NEXT FIGHT..
              </div>
            </marquee> -->
            <marquee width="100%" direction="left" height="auto" scrollamount="13" v-if="!events">
              <div class="alert alert-danger" role="alert">
                There`s no Current Event please wait for announcement
              </div>
            </marquee>
            <div class="card">
              <div class="card-header bg-dark text-white" v-if="!events">
                <cash :user="user"></cash>

              </div>
            </div>
            <div class="card table-responsive-sm" v-if="events">
              <div class="card-header bg-dark text-white"  v-if="!select&&!startings.length&&!live">
                <center>

                <cash :user="user"></cash><br>
                   <a class="text-warning font-weight-bold">PICK {{events.pick}}</b> - {{events.event_name}} [{{events.fights}} Fights]</a> <br><hr>
                   <div class="alert alert-danger" role="alert" style="margin-bottom:0">
                      There`s no current open fights.
                   </div> </center>
              </div>
              <div class="card">
                <div class="card-header bg-dark text-white" v-if="!select&&startings.length">
                  <cash :user="user"></cash>

                </div>
              </div>
              <table class="table table-sm table-striped" v-if="!select&&startings.length">
                <thead class="thead-dark ">
                  <tr>
                    <th colspan="3" class="" style="text-align: center;" ><b class="text-muted">All available starting fights of </b> <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b></th>
                  </tr>
                </thead>
                <thead>
                  <tr>
                    <th>Starting Fight</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                    <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td> <a class="btn btn-outline-dark btn-sm" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play </a> </td>
                  </tr>
                </tbody>
              </table>
              <!-- reciept 2 -->
              <div class="card" id='printMe' v-show="awd" v-for="t in receipt">
                <!-- <barcode :value="receipt.barcode" tag="img"></barcode> -->
                <barcode :value="t.barcode" tag="svg"></barcode>
                <p>Event name : {{events.event_name}}</p>
                <p>Bet ID : {{t.id}}</p>
                <p>Pick : {{controls.pick}}</p>
                <p>Date : {{new Date().toLocaleString()}}</p>
                <p>Cashier : {{user.name}}</p>
                <p>Amount : {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</p>
                <p v-for="s in t.selection">Fight # :{{s.fightnumber}} = {{s.selection}}</p>
              </div>
            </div>


            <div class="card" v-if="select" style="background-color: transparent;">
              <a class="float-right btn btn-sm btn-danger" @click.prevent='goback' style="margin-bottom:0.5rem;background:rgb(221 92 86); border-color:rgb(221 92 86);" v-if="!rebet"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Go back to startingfights</a>
              <!-- <div class="card-header bg-dark text-white">
                <barcode value="1234567890"></barcode>
              </div> -->
              <div class="card-header bg-dark text-white">
                <center>
                <b class="text-warning">PICK {{controls.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b>
                <hr style="margin-top:0.5rem;margin-bottom:0.5rem">

                <cash v-if="refreshmoney" :user="user"></cash><b class="text-muted" v-if="odds>=10000">Most Correct Picks : <b class="text-success">{{odds.toLocaleString()}}</b></b> </center>
              </div>
              <div class="card-body" style="padding: 0.25rem;padding-top:0; background-color: white"><center>

                <!-- <div class="alert alert-success" role="alert">
                  <b>Highest Combination Prize {{Number(events.jackpot).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} </b>
                </div> -->
                <!-- <h4>Starting Fight Number : <b class="text-danger">{{fight.start}}</b></h4> -->
                <p class="h5" style="margin-top:0.5rem">Starting Fight Number : <b class="text-danger">{{select}}</b></p>
                <p class="h6">Bet Amount[per single bet] : <b class="text-success">{{Number(controls.amount).toLocaleString()}}</b></p>
              </center>
                <div class="form-group">
                  <!-- <div class="input-group input-group-sm mb-3 hidethis" >
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-sm" >Starting fight number</span>
                    </div>
                    <input type="text" class="form-control" v-model="fight.start" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
                  </div> -->

                  <!-- <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Starting fight number</label>
                    </div>
                    <input type="text" class="custom-input" name="" value="">
                    <select class="custom-select" id="inputGroupSelect01" v-on:change="placebet" v-model="fight.start" placeholder='select fight number.'>
                      <option selected disabled>Choose...</option>
                      <option  v-for='n in events.fights' :value="n" v-if="n = events.startingfight" selected>{{n}}</b></option>
                    </select>
                  </div> -->
                  <div class="" v-if='customize'>
                  <div class="row" v-if='awd'>
                    <div class="col-md-6 col-md-3">
                      <a @click.prevent='meron' class="btn btn-danger btn-lg form-control"><b>MERON</b></a>
                    </div>
                    <div class="col-md-6 col-md-3">
                      <a @click.prevent='wala' class="btn btn-info btn-lg form-control text-white"><b>WALA</b></a>
                    </div>
                  </div>
                  <!-- <div class="input-group input-group-sm mb-3 hidethis">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Fixed bet amount</label>
                    </div>
                    <input type="text" name=""class="form-control" value="100" v-model='fight.amount' disabled>
                  </div> -->
                  <center>
                  <div class="form-check">
                    <input class="form-check-input" v-on:change="randomplacebet" type="checkbox"  id="defaultCheck1" v-model="randombets">
                    <label class="form-check-label" for="defaultCheck1">
                      <b class="">Random Picks</b>
                    </label>&nbsp&nbsp&nbsp&nbsp&nbsp
                    <button type="button" name="button" class="btn btn-success btn-sm" @click.prevent='switchmultiple' v-if="!single">Switch to multiple bets</button>
                    <button type="button" name="button" class="btn btn-success btn-sm" @click.prevent='switchsingle' v-if="single">Switch to Single bet</button>
                  </div>
                </center>
                </div>
                </div>
                <!-- receipt -->
                <!-- <div class="card" id='printMe' v-show="awd">
                  <barcode :value="receipt.barcode" tag="img"></barcode>
                  <barcode :value="receipt.barcode" tag="svg"></barcode>
                  <p>Event name : {{events.event_name}}</p>
                  <p>Bet ID : {{receipt.id}}</p>
                  <p>Pick : {{controls.pick}}</p>
                  <p>Date : {{new Date().toLocaleString()}}</p>
                  <p>Cashier : {{user.name}}</p>
                  <p>Amount : {{Number(receipt.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</p>
                  <p v-for="t in receipt.selection">Fight # :{{t.fightnumber}} = {{t.selection}}</p>
                </div> -->
                <!-- Customize PICK20 -->
                <div class="card" v-if='selected.length'>
                  <div class="card-header bg-dark text-white">
                  Customize  Your <b class="text-warning" v-if="!single">Single Pick</b><b class="text-warning" v-if="single"> Multiple picks</b>
                  </div>
                  <div class="card-body table-responsive-sm" style="max-height:60vh; overflow:auto; padding:0;">
                    <table class="table table-sm  table-striped table-borderless table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th class="text-center">Fight #</th>
                          <th class="text-center">Meron</th>
                          <th class="text-center">Wala</th>
                          <th class="text-center">Draw</th>
                        </tr>
                      </thead>

                      <tbody class="" style="width:100%">
                        <tr v-for='(bet,index) in selected' :index="index" v-if="!single">
                          <td class="text-center"><b>{{bet.fightnumber}}</b></td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" :name="index" value="" :id="m+index" v-on:change="picksm1(index)" v-model='bet.selection.meron' value='true'>
                              <label class="form-check-label text-danger font-weight-bold" v-if="bet.selection.meron">
                                <a class="btn btn-sm btn-danger" @click.prevent='picksm1(index)'>Meron</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.meron" :for="m+index" >
                                <a class="btn btn-sm" @click.prevent='picksm1(index)'>Meron</a>
                              </label>
                            </div>
                          </td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" :name="index" value="" :id="w+index"  v-on:change="picksw1(index)" v-model="bet.selection.wala" value='true'>
                              <label class="form-check-label text-info font-weight-bold" v-if="bet.selection.wala">
                                <a class="btn btn-sm btn-info text-white" @click.prevent='picksw1(index)'>Wala</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.wala" :for="w+index">
                                <a class="btn btn-sm" @click.prevent='picksw1(index)'>Wala</a>
                              </label>
                            </div>
                          </td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" :name="index" value="" :id="d+index" v-on:change="picksd1(index)" v-model="bet.selection.draw" value='true'>
                              <label class="form-check-label text-success font-weight-bold" v-if="bet.selection.draw">
                                <a class="btn btn-sm btn-success" :for="index+1" @click.prevent='picksd1(index)'>Draw</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.draw" :for="d+index">
                                <a class="btn btn-sm" @click.prevent='picksd1(index)'>Draw</a>
                              </label>
                            </div>
                          </td>
                        </tr>
                        <tr v-for='(bet,index) in selected' :index="index" v-if="single">
                          <td class="text-center"><b>{{bet.fightnumber}}</b></td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" :name="index" value="" :id="index+1" v-on:change="pickm1(index)" v-model='bet.selection.meron'>
                              <label class="form-check-label text-danger font-weight-bold" v-if="bet.selection.meron" for="index">
                                <a class="btn btn-sm btn-danger" @click.prevent='pickm(index)'>Meron</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.meron">
                                <a class="btn btn-sm" @click.prevent='pickm(index)'>Meron</a>
                              </label>
                            </div>
                          </td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" :name="index" value="" id="defaultCheck2"  v-on:change="pickw1(index)" v-model="bet.selection.wala">
                              <label class="form-check-label text-info font-weight-bold" v-if="bet.selection.wala">
                                <a class="btn btn-sm btn-info text-white" @click.prevent='pickw(index)'>Wala</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.wala" for="index">
                                <a class="btn btn-sm" @click.prevent='pickw(index)'>Wala</a>
                              </label>
                            </div>
                          </td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" :name="index" value="" id="defaultCheck1" v-on:change="pickd1(index)" v-model="bet.selection.draw">
                              <label class="form-check-label text-success font-weight-bold" v-if="bet.selection.draw">
                                <a class="btn btn-sm btn-success" :for="index+1" @click.prevent='pickd(index)'>Draw</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.draw" for="index">
                                <a class="btn btn-sm" @click.prevent='pickd(index)'>Draw</a>
                              </label>
                            </div>
                          </td>

                      <!-- <td>
                          <input :checked="bet.selection === 'Meron'" class="" type="radio" @click.prevent='switchbet(bet)' :name="bet.id" id="flexRadioDefault1" > <b class="text-danger">Meron</b>
                          <input :checked="bet.selection ==='Wala'" class="" type="radio" @click.prevent='switchbet(bet)' :name="bet.id" id="flexRadioDefault2" > <b class="text-primary">Wala</b>
                          </td> -->
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-footer" style="background:white">
                <a class="btn btn-success form-control" @click.prevent='showconfirm()' v-if='selected.length'>Place Bet</a>
                  <button type="button" class="btn btn-success form-control btn-sm" @click.prevent='betagainplacebet' name="button" v-if='rebet'><i class="fa fa-refresh" aria-hidden="true"></i> Bet Again</button> <br  v-if='rebet'>
                  <button type="button" class="btn btn-danger form-control btn-sm" style="margin-top:0.5rem" @click.prevent='goback' name="button" v-if='rebet'><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Go Back to Startingfights</button>
                <!-- <a class="btn btn-success form-control" @click.prevent='insertbet()'>Place Bet</a> -->
              </div>
            </div><br>
            <button type="button" class="btn btn-secondary form-control" name="button" @click.prevent='viewpendingbets'>View Current Pending Bets</button>
            <!-- <div class="card table-responsive-sm" id="containerx" style="" v-if="bets.length">
              <div class="card-header bg-dark text-white" v-if="user.role===0">
            Pending <b class="text-warning">Pick {{events.pick}}</b> bets
              </div>
              <div class="card-header bg-dark text-white" v-if="user.role===3">
            Your Current <b class="text-warning">Pick {{events.pick}}</b> picks on startingfight # {{events.startingfight}}
              </div>
              <div class="card-body" style="padding:0px;max-height:50vh; overflow:auto">



                  </div>
            </div> -->
          </div>
          <div class="modal fade" id="pendingbets" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content" style="border:none !important;">
                <div class="modal-header bg-dark text-white">
                  <h5 class="modal-title text-warning" id="exampleModalLabel">Current Pending Bets for {{events.event_name}} [{{events.fights}} Fights]</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body table-responsive" style="padding:0">
                  <table class="table tabl-sm table-striped table-borderless table-hover">
                    <thead class="thead-dark">
                      <tr>
                        <th>#</th>
                        <th>Starting Fight</th>
                        <th>Bet</th>
                        <!-- <th>Fight#</th> -->
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(t,index) in bets.data" :index='index' v-if="bets.data">
                      <!-- <tr v-for="(t,index) in bets.slice().reverse()" :index='index'> -->
                        <th>{{index+pages}}</th>
                        <td>{{t.startingfight}}</td>
                        <td>{{t.bet}} <br><a class="btn btn-success btn-sm" @click.prevent='reprint(t)' v-if="user.role===0">Reprint</a </td>
                        <!-- <td v-if="events.pick===20 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+19}}</b></td>
                        <td v-if="events.pick===15 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+14}}</b></td>
                        <td v-if="events.pick===24 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+23}}</b></td> -->
                        <td>{{t.created_at|datebet}}</td>
                        <tr>
                          <td v-if="!bets.data" colspan="4">You have no pending bets..</td>
                        </tr>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- <div class="modal-body font-weight-bold" v-if="!bets">
                  You have no pending bets yet..
                </div> -->
                <div class="modal-footer justify-content-center" >
                  <pagination :data="bets" :limit='5' @pagination-change-page="geteventbetss">
                    <!-- <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span> -->
                  </pagination><hr>
                  <button type="button" class="btn btn-secondary col-md-12" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="width:100%;">
                <center><tile></tile>
                <h6 class="text-muted">Please Wait...</h6></center>
            </div>
          </div>
          </div>
          <div class="modal fade" id="confirmation2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
              <div class="modal-content"  style="border:none !important;">
                <div class="modal-header bg-dark text-white">
                  <h5 class="modal-title text-warning" id="exampleModalCenterTitle">Confirmation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" style=" border:none !important;">
                  <center>Are you sure you want to bet this <br> <b>PICK 20?</b>
                  <br> Bet amount : {{Number(totalamount).toLocaleString()}} </center>
                </div>
                <div class="modal-footer" >
                  <button type="button" class="btn btn-primary form-control" :disabled='isDisabled' @click.prevent='insert2'>Confirm</button>
                  <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Go Back</button>
                </div>
              </div>
            </div>
          </div>
          <!-- pansamantala -->
          <div class="modal fade" id="validator" data-ba tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content" style="border:none !important;">
                <div class="modal-header bg-dark">
                  <h5 class="modal-title text-warning" id="exampleModalLabel">Account Details</h5>
                  <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button> -->
                </div>
                <div class="modal-body">
                  <label for="oldss">Name</label>
                  <input type="text" id="oldss" class="form-control" v-model="user.name" disabled>
                  <label for="newss">Email</label>
                  <input type="email" id="newss" class="form-control" v-model="user.email" disabled>
                  <label for="confirmss">Phone(Gcash)</label>
                  <input type="text" id="confirmss" class="form-control" v-model="form2.pnumber">
                </div>
                <div class="modal-footer">
                  <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                  <button type="button" class="btn btn-primary" @click.prevent="Updatedetails">Update Details</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
      props:['user','data'],
      data(){
        return{
          single:null,
          live:null,
          select:null,
          jackpot:null,
          loading:false,
          odds:null,
          randombets:null,
          awd:null,
          totalamount:1,
          customize:null,
          rebet:null,
          hasbet:'wala',
          refreshmoney:true,
          bets:{},
          events:[],
          selected:[],
          users:[],
          receipt:[],
          control:'',
          controls:[],
          disabled:false,
          pages:0,
          confirm:[],
          pageOfItems: [],
          startings: [],
          // bets:new Form({
          //   b1:[{
          //     fightnumber:'',
          //     selection1:'',
          //     selection2:'',
          //     selection3:'',
          //   }],
          // }),
          fight:new Form({
            id:'',
            start:null,
            selection:'Meron',
            amount:null,
            user_id:this.user.id,
          }),
          confirm:new Form({
            id:'',
            fightnumber:null,
            selection:'Meron',
            amount:null,
            user_id:this.user.id,
          }),
          start:new Form({
            startingfight:null,
            event_id:null,
            id:null
          }),
          form2:new Form({
            id:this.user.id,
            name:this.user.name,
            email:this.user.email,
            pnumber:this.user.pnumber
          }),
        }
      },
      computed: {
  	     isDisabled: function(){
        	return this.disabled;
        },
  	     jackpotfinal: function(){
           if (this.events.addtojackpot) {

             return  parseFloat(this.events.addtojackpot)+parseFloat(this.events.jackpot);
           }else {
             return parseFloat(this.events.jackpot);
           }
        },
      },
      methods:{
        switchmultiple(){
          this.single=1;
          this.randombets=null;
        },
        switchsingle(){
          this.randombets=null;
          this.single=null;
          this.selected = [],
          this.fight.post('/pick20/selection').then(response=>{
            this.selected = response.data;
          })
        },
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.controls=response.data;
            document.title = "Pick "+this.controls.pick;
          });
        },
        picksm1(index){
          // if (this.selected[index].selection.meron) {
          this.selected[index].bet='M';
          this.selected[index].amount=1;
          this.selected[index].selection.meron=true;
          this.selected[index].selection.wala=false;
          this.selected[index].selection.draw=false;
          // }
        },
        picksw1(index){
          // if (this.selected[index].selection.wala) {
          this.selected[index].bet='w';
          this.selected[index].amount=1;
          this.selected[index].selection.meron=false;
          this.selected[index].selection.wala=true;
          this.selected[index].selection.draw=false;
          // }
        },
        picksd1(index){
          // if (this.selected[index].selection.draw) {
          this.selected[index].bet='D';
          this.selected[index].amount=1;
          this.selected[index].selection.meron=false;
          this.selected[index].selection.wala=false;
          this.selected[index].selection.draw=true;
          // }
        },
        pickm1(index){
          if (this.selected[index].selection.meron) {
            this.selected[index].selection.meron = true;
            if (!this.selected[index].bet) {
              this.selected[index].bet='M';
              this.selected[index].amount=this.selected[index].amount+1;
            }else {
              this.selected[index].bet=this.selected[index].bet+'M';
              this.selected[index].amount=this.selected[index].amount+1;
            }
          }else {
            this.selected[index].bet=this.selected[index].bet.replace('M', '');
            this.selected[index].selection.meron = false;
            this.selected[index].amount=this.selected[index].amount-1;
          }
        },
        pickw1(index){
          if (this.selected[index].selection.wala) {
            this.selected[index].selection.wala = true;
            if (!this.selected[index].bet) {
              this.selected[index].bet='w';
              this.selected[index].amount=this.selected[index].amount+1;
            }else {
              this.selected[index].bet=this.selected[index].bet+'w';
              this.selected[index].amount=this.selected[index].amount+1;
            }
          }else {
            this.selected[index].bet=this.selected[index].bet.replace('w', '');
            this.selected[index].selection.wala = false;
            this.selected[index].amount=this.selected[index].amount-1;
          }
        },
        pickd1(index){
          if (this.selected[index].selection.draw) {
            this.selected[index].selection.draw = true;
            if (!this.selected[index].bet) {
              this.selected[index].bet='D';
              this.selected[index].amount=this.selected[index].amount+1;
            }else {
              this.selected[index].bet=this.selected[index].bet+'D';
              this.selected[index].amount=this.selected[index].amount+1;
            }
          }else {
            this.selected[index].bet=this.selected[index].bet.replace('D', '');
            this.selected[index].selection.draw = false;
            this.selected[index].amount=this.selected[index].amount-1;
          }
        },
        pickm(index){
          if (this.selected[index].selection.meron) {
            this.selected[index].selection.meron = false;
            this.selected[index].bet=this.selected[index].bet.replace('M', '');
            this.selected[index].amount=this.selected[index].amount-1;
          }else {
            this.selected[index].selection.meron = true;
            if (!this.selected[index].bet) {
              this.selected[index].bet='M';
              this.selected[index].amount=this.selected[index].amount+1;
            }else {
              this.selected[index].bet=this.selected[index].bet+'M';
              this.selected[index].amount=this.selected[index].amount+1;
            }
          }
        },
        pickw(index){
          if (this.selected[index].selection.wala) {
            this.selected[index].selection.wala = false;
            this.selected[index].bet=this.selected[index].bet.replace('w', '');
            this.selected[index].amount=this.selected[index].amount-1;
          }else {
            this.selected[index].selection.wala = true;
            if (!this.selected[index].bet) {
              this.selected[index].bet='w';
              this.selected[index].amount=this.selected[index].amount+1;
            }else {
              this.selected[index].bet=this.selected[index].bet+'w';
              this.selected[index].amount=this.selected[index].amount+1;
            }
          }
        },
        pickd(index){
          if (this.selected[index].selection.draw) {
            this.selected[index].selection.draw = false;
            this.selected[index].bet=this.selected[index].bet.replace('D', '');
            this.selected[index].amount=this.selected[index].amount-1;
          }else {
            this.selected[index].selection.draw = true;
            if (!this.selected[index].bet) {
              this.selected[index].bet='D';
              this.selected[index].amount=this.selected[index].amount+1;
            }else {
              this.selected[index].bet=this.selected[index].bet+'D';
              this.selected[index].amount=this.selected[index].amount+1;
            }
          }
        },
        goback(){
          this.select=null;
          this.live=null;
          this.randombets=false;
          this.control=null;
          this.Getallstartingfights();
        },
        Getallstartingfights(){
          this.start.event_id = this.events.id;
          axios.get('/pick20/startingfights').then(response=>{
            this.startings = response.data;
            this.select=null;
          })
        },
        geteventbetss(page = 1) {
          this.loading=true;
          // this.bets={};
            axios.get('/pick20/pendingbets?page=' + page)
                .then(response => {
                  if (page===1) {
                    this.pages=page;
                  }else {
                    this.pages=page*10-9;
                  }
                  this.loading=false;
                    this.bets = response.data;
                    // if (this.bets) {
                    //   this.hasbet='meron';
                    // }else {
                    //   this.hasbet='wala';
                    // }
                });
        },
        viewpendingbets(){
          this.geteventbetss();
          $('#pendingbets').modal('show');
        },
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        reprint(t){
          Swal.fire({
            title: 'Please Confirm',
            text: "Do you really want to reprint this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.fight.id=t.id;
              this.fight.post('/pick20/reprint').then(response=>{
                this.receipt=response.data;
              }).then(()=>{
                this.$htmlToPaper('printMe');
                this.loading=false;
              }).catch(()=>{
                Swal.fire(
                  'error',
                  'Invalid Reprint.',
                  'error'
                )
              })
            }
          })

        },
        geteventbets(){
          // $('#confirmation2').modal('show');
          this.loading=true;
          this.bets=[];
          axios.get('/pick20/getbets').then(response=>{
            this.loading=false;
            this.bets=response.data;
            if (this.bets) {
              this.hasbet='meron';
            }else {
              this.hasbet='wala';
            }
            $('#confirmation2').modal('hide');
          }).catch(()=>{
            this.loading=false;
          });
        },
        switchm(index){
          this.selected[index].selection = 'Meron';
         // Toast.fire({
         //         icon: 'warning',
         //         title: 'Successfully switched !'
         //       });
        },
        switchw(index){
          this.selected[index].selection = 'Wala';
         // Toast.fire({
         //         icon: 'warning',
         //         title: 'Successfully switched !'
         //       });
        },
        switchd(index){
          this.selected[index].selection = 'Draw';
         // Toast.fire({
         //         icon: 'warning',
         //         title: 'Successfully switched !'
         //       });
        },
        showconfirm(){
          this.totalamount = 1;
          this.disabled=false;
          // this.totalamount = this.selected.forEach(this.printArray);
          this.selected.forEach((val)=>{
            this.totalamount = parseInt(this.totalamount) * parseInt(val.amount) ;
          });
          if (this.totalamount) {
            this.totalamount = this.totalamount * parseInt(this.controls.amount);
            this.selected.forEach((val)=>{
              val.finalamount = this.totalamount;
            });
            if (this.totalamount<=parseInt(this.users.cash.slice(0,-4))) {
              $('#confirmation2').modal('show');
            }else {
              Swal.fire(
                'Not enough balance',
                'Bet amount : '+this.totalamount.toLocaleString()+'<br>Your cash : '+parseInt(this.users.cash.slice(0,-4).toLocaleString()),
                'error'
              )
            }
          }else {
            Swal.fire(
              'Error',
              'Please double check your bet.',
              'error'
            )
          }
        },
        insert2(){
          this.loading=true;
          this.disabled=true;
          this.refreshmoney=false;
          axios({
            method: 'post',
            url: '/pick20/insertbet',
            // url: '/pick20/testpost',
            data: {
              data : this.selected
            }
          }).then(response=>{
            if (response.data.error) {
              Swal.fire(
                'Ooops!',
                response.data.error,
                'error'
              );
              this.disabled=false;
                $('#confirmation2').modal('hide');
              this.goback();
            }else {
              this.disabled=false;
              this.receipt=response.data;

              this.customize=null;
              this.fight.start=null;
              this.randombets=false;
              this.rebet=1;
              Swal.fire(
                'Success!',
                'Your Pick has been entered.',
                'success'
              )
              this.refreshmoney=true;
              this.getuser();
              $('#confirmation2').modal('hide')
            }
            this.selected=[];
          })
          .then(()=>{
            if (this.user.role===0) {
              this.$htmlToPaper('printMe');
            }
            this.loading=false;
          }).catch(error =>{
            $('#confirmation2').modal('hide');
            this.loading=false;
            if (this.user.role===3) {
              Swal.fire(
                'error',
                'Please make sure that all fightnumbers has a bet',
                'error'
              )
            }else {
              Swal.fire(
                'error',
                'Your Pick has not been entered.',
                'error'
              )
            }
          });
        },
        insertbet(){
          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Go back'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              axios({
                method: 'post',
                url: '/pick20/testpost',
                data: {
                  data : this.selected
                }
              }).then(response=>{

                this.loading=true;
                this.receipt=response.data;
                this.selected=[];
                this.customize=null;
                this.fight.start=null;
                this.randombets=false;
                this.rebet=1;
                Swal.fire(
                  'Success!',
                  'Your Pick has been entered.',
                  'success'
                )
              })
              .then(()=>{
                if (this.user.role==0) {
                  this.loading=false;
                  this.$htmlToPaper('printMe');
                }
              }).catch(()=>{
                Swal.fire(
                  'error',
                  'Your Pick has not been entered.',
                  'error'
                )
              });
            }
          })
        },
        deleteprebets(){
          axios.post('/pick20/deleteprebets');
        },
        switchbet(bet){
          this.confirm.fill(bet);
          this.loading=true;
          this.confirm.post('/pick20/switchbet').then(response=>{
            this.loading=false;
            this.selected=response.data;
            // Toast.fire({
            //         icon: 'warning',
            //         title: 'Successfully switched !'
            //       });
          // if (bet.selection==="Meron") {
          //   Swal.fire({
          //     title: 'Are you sure?',
          //     text: "You want to switch from "+bet.selection+" to Wala?",
          //     icon: 'warning',
          //     showCancelButton: true,
          //     confirmButtonColor: '#3085d6',
          //     cancelButtonColor: '#d33',
          //     confirmButtonText: 'Yes!'
          //   }).then((result) => {
          //     if (result.isConfirmed) {
          //       this.confirm.post('/switchbetw').then(response=>{
          //         this.selected=response.data;
          //         Toast.fire({
          //                 icon: 'warning',
          //                 title: 'Successfully switched !'
          //               });
          //       }).catch(()=>{
          //         Toast.fire({
          //                 icon: 'error',
          //                 title: 'Please refresh !'
          //               });
          //       });
          //     }
          //   })
          // }
          // else {
          //     Swal.fire({
          //       title: 'Are you sure?',
          //       text: "You want to switch from "+bet.selection+" to Meron?",
          //       icon: 'warning',
          //       showCancelButton: true,
          //       confirmButtonColor: '#3085d6',
          //       cancelButtonColor: '#d33',
          //       confirmButtonText: 'Yes!'
          //     }).then((result) => {
          //       if (result.isConfirmed) {
          //         this.confirm.post('/switchbetm').then(response=>{
          //           this.selected=response.data;
          //           Toast.fire({
          //                   icon: 'warning',
          //                   title: 'Successfully switched !'
          //                 });
          //         }).catch(()=>{
          //           Toast.fire({
          //                   icon: 'error',
          //                   title: 'Please refresh !'
          //                 });
          //         });
          //       }
          //     })
        })
        },
        betagainplacebet(){
          this.jackpot=this.events.jackpot+this.events.addtojackpot;
          this.fight.start=this.select;
          this.live=true;
          this.rebet=null;
          if (this.randombets) {
            // ito random
            this.fight.post('/pick20/randompick').then(response=>{
              // this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Please select fightnumber first'
              });
            });
          }else {
            // ito hnd random
            this.fight.post('/pick20/selection').then(response=>{
              this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Theres no current event'
              });
            });
          }
        },
        placebet(t){
          this.rebet=null;
          this.select=t.startingfight;
          this.fight.start=t.startingfight;
          this.fight.id=t.id;
          this.getodds();
          this.live=true;
          this.control=t.control;
          // this.jackpot=parseFloat(this.events.addtojackpot).toFixed(2)+parseFloat(this.events.jackpot).toFixed(2);
          console.log(t.startingfight)
          if (this.randombets) {
            // ito random
            this.fight.start=t.startingfight;
            this.fight.post('/pick20/randompick').then(response=>{
              // this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Please select fightnumber first'
              });
            });
          }else {
            // ito hnd random
              this.fight.start=t.startingfight;
            this.fight.post('/pick20/selection').then(response=>{
              this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Theres no current event'
              });
            });
          }
        },
        randomplacebet(){
          this.jackpot=this.events.jackpot+this.events.addtojackpot;
          this.rebet=null;
          this.fight.start=this.select;
          if (this.randombets) {
            // ito random
            this.fight.start=this.select;
            this.fight.post('/pick20/randompick').then(response=>{
              // this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Please select fightnumber first'
              });
            });
          }else {
            // ito hnd random
              this.fight.start=this.select;
            this.fight.post('/pick20/selection').then(response=>{
              this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Theres no current event'
              });
            });
          }
        },
        wala(){
          this.fight.selection='Wala';
          Toast.fire({
                  icon: 'warning',
                  title: 'You selected WALA and please confirm'
                });
                this.fight.post('/pick20/allwala').then(response=>{
                  this.selected=response.data;});
        },
        meron(){
          this.fight.selection='Meron';
          Toast.fire({
                  icon: 'warning',
                  title: 'You selected MERON and please confirm'
                });
                this.fight.post('/pick20/allmeron').then(response=>{
                  this.selected=response.data;});
        },
        getevents(){
          axios.get('/pick20/getevents').then(response=>{
            this.selected=[];
            this.events=[];
            this.events=response.data;
            this.control=this.events.control;
            this.fight.amount=this.events.amount;
            this.confirm.amount=this.events.amount;
              this.fight.start=this.events.startingfight;
              // this.placebet();
              this.rebet=null;


          });
        },
        getevent(){
          axios.get('/pick20/geteventx').then(response=>{
            this.confirm=response.data;
              this.control=this.confirm.control;
              this.fight.amount=this.events.amount;
              this.confirm.amount=this.events.amount;

              // this.rebet=null;
          });
        },
        getodds(){

          this.fight.post('/pick20/getliveodds').then(response=>{
          this.odds=response.data;
          });
        },
        getuser(){
          axios.get('pick20/getuser').then(response=>{
          this.users=response.data;
          });
        },
        valid(){
          if (this.user.pnumber!='0') {

          }else{
            Swal.fire(
              'Please be informed',
              'Please make sure that you register you phone number in your account.<br>step 1 : Click menu and click your username <br>step 2 : Click account details (above logout button)<br>step 3 : Put your gcash number<br>step 4 : Click update account.',
              'info'
            ).then(()=>{
              $('#validator').modal('show');
            });
            // this.valid();
          }
        },
        Updatedetails(){
          this.loading=true;
          this.form2.post('/pick20/updateaccount').then(()=>{
            // this.getauthdetails();
            $('#validator').modal('hide');
            this.loading=false;
            Toast.fire({
              icon: 'success',
              title: 'Account has been successfully updated'
            });
          }).catch(()=>{
            this.loading=false;
            Toast.fire({
              icon: 'error',
              title: 'Account not updated, and please make sure the phone number is 11 digits and not duplicated.'
            });
          })
        },
      },
        created() {
          this.getcontrol();
          this.getuser();
          this.getevents();
          this.Getallstartingfights();
          this.valid();
          Echo.private('insert_bet')
          .listen('betevent',(event)=>{
            if (event.startingfight===this.select) {
              this.odds = this.odds + event.bet;
              console.log(event)
            }
          });
          Echo.private('eventupdate')
          .listen('eventlistener',(event)=>{
            console.log(event)
            //

            if (event.events.control==='Last Call' && this.events.event_name===event.events.event_name && this.select===event.events.startingfight ) {
              this.control='Last Call';
              Toast.fire({
                      icon: 'warning',
                      title: 'Last Call Please Place Your Bet !'
                    });
                    // this.Getallstartingfights();
            }
              else if (event.events.control==="Open" && this.events.event_name===event.events.event_name && this.select===event.events.startingfight ){
                this.control='Open';
                // this.getevents();
              }else if(event.events.control==='Closed'||event.events.control==='Finished' && this.events.id===event.events.id && this.select===event.events.startingfight ) {
                // this.control='Close';

                  this.goback();
                this.selected=[];
                  // this.closing=1;
                  this.bets=[];
                  // this.getevents();
                }else if(this.live===null) {
                    // $('#confirmation2').modal('hide')
                    // this.Swal.close()
                    this.Getallstartingfights();
                    this.getevents();
                    Toast.fire({
                            icon: 'warning',
                            title: 'List of starting fights is now updated'
                          });
                }

          })
        }
    }
</script>
