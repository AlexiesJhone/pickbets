<style media="screen">
  input#swal2-input.swal2-input{
    color: black !important;
    width: auto !important;
    text-align: center;
  }
</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>

          <div class="col-md-6">

            <div class="alert alert-success" role="alert" v-if="controls.announcement&&!live"><center>
            <h4 class="alert-heading">Announcement</h4><hr>
            <p>{{this.controls.announcement}}</p>
          </center>
            </div>
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
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">All Starting Fights</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead class="thead-dark " v-if="!select&&startings.length&&events.pick20">
                  <tr>
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">PICK 20</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead v-if="!select&&startings.length&&events.pick20">
                  <tr>
                    <th>Starting Fight</th>
                    <!-- <th>Pick</th> -->
                    <th>Total Net Fees</th>
                    <th>Total Wins</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody v-if="!select&&startings.length&&events.pick20">
                  <tr v-for="t in orderedUsers" v-if="t.pick==20&&t.control==='Last Call'">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==20&&t.control==='Open'">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==20&&t.control==='Closed'">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==20&&t.control==='Finished'">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <!-- <tr v-if="!starting.length">
                    <th colspan="3">There`s no available starting fight yet...</th>
                  </tr> -->
                </tbody>
              </table>
              <table class="table table-sm table-striped" v-if="!select&&startings.length&&events.pick2">
                <thead class="thead-dark ">
                  <tr>
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">PICK 2</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead>
                  <tr>
                    <th>Starting Fight</th>
                    <!-- <th>Pick</th> -->
                    <th>Total Net Fees</th>
                    <th>Total Wins</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in orderedUsers" v-if="t.pick==2">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==2&&t.control==='Open'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==2&&t.control==='Closed'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==2&&t.control==='Finished'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-if="!starting.length">
                    <th colspan="3">There`s no available starting fight yet...</th>
                  </tr> -->
                </tbody>
              </table>
              <!-- START OF PICK 3  -->
              <table class="table table-sm table-striped" v-if="!select&&startings.length&&events.pick3">
                <thead class="thead-dark ">
                  <tr>
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">PICK 3</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead>
                  <tr>
                    <th>Starting Fight</th>
                    <!-- <th>Pick</th> -->
                    <th>Total Net Fees</th>
                    <th>Total Wins</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in orderedUsers" v-if="t.pick==3">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==3&&t.control==='Open'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==3&&t.control==='Closed'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==3&&t.control==='Finished'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-if="!starting.length">
                    <th colspan="3">There`s no available starting fight yet...</th>
                  </tr> -->
                </tbody>
              </table>
              <!-- END OF PICK 3  -->

              <!-- START OF PICK 4  -->
              <table class="table table-sm table-striped" v-if="!select&&startings.length&&events.pick4">
                <thead class="thead-dark ">
                  <tr>
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">PICK 4</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead>
                  <tr>
                    <th>Starting Fight</th>
                    <!-- <th>Pick</th> -->
                    <th>Total Net Fees</th>
                    <th>Total Wins</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in orderedUsers" v-if="t.pick==4">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==4&&t.control==='Open'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==4&&t.control==='Closed'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <tr v-for="t in orderedUsers" v-if="t.pick==4&&t.control==='Finished'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-if="!starting.length">
                    <th colspan="3">There`s no available starting fight yet...</th>
                  </tr> -->
                </tbody>
              </table>
              <!-- END OF PICK 4  -->

              <!-- START OF PICK 5  -->
              <table class="table table-sm table-striped" v-if="!select&&startings.length&&events.pick5">
                <thead class="thead-dark ">
                  <tr>
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">PICK 5</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead>
                  <tr>
                    <th>Starting Fight</th>
                    <!-- <th>Pick</th> -->
                    <th>Total Net Fees</th>
                    <th>Total Wins</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in orderedUsers" v-if="t.pick==5">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==5&&t.control==='Open'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==5&&t.control==='Closed'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==5&&t.control==='Finished'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-if="!starting.length">
                    <th colspan="3">There`s no available starting fight yet...</th>
                  </tr> -->
                </tbody>
              </table>
              <!-- END OF PICK 5  -->

              <!-- START OF PICK 6  -->
              <table class="table table-sm table-striped" v-if="!select&&startings.length&&events.pick6">
                <thead class="thead-dark ">
                  <tr>
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">PICK 6</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead>
                  <tr>
                    <th>Starting Fight</th>
                    <!-- <th>Pick</th> -->
                    <th>Total Net Fees</th>
                    <th>Total Wins</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in orderedUsers" v-if="t.pick==6">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Open'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Closed'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Finished'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-if="!starting.length">
                    <th colspan="3">There`s no available starting fight yet...</th>
                  </tr> -->
                </tbody>
              </table>
              <!-- END OF PICK 6  -->
              <!-- START OF PICK 8  -->
              <table class="table table-sm table-striped" v-if="!select&&startings.length&&events.pick8">
                <thead class="thead-dark ">
                  <tr>
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">PICK 8</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead>
                  <tr>
                    <th>Starting Fight</th>
                    <!-- <th>Pick</th> -->
                    <th>Total Net Fees</th>
                    <th>Total Wins</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in orderedUsers" v-if="t.pick==8">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Open'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Closed'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Finished'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-if="!starting.length">
                    <th colspan="3">There`s no available starting fight yet...</th>
                  </tr> -->
                </tbody>
              </table>
              <!-- END OF PICK 8  -->
              <!-- START OF PICK 14  -->
              <table class="table table-sm table-striped" v-if="!select&&startings.length&&events.pick14">
                <thead class="thead-dark ">
                  <tr>
                    <th colspan="5" class="" style="text-align: center;" ><b class="text-warning">PICK 14</b>
                      <!-- <br><b class="text-warning">PICK {{events.pick}} - {{events.event_name}} [{{events.fights}} Fights]</b> -->
                    </th>
                  </tr>
                </thead>
                <thead>
                  <tr>
                    <th>Starting Fight</th>
                    <!-- <th>Pick</th> -->
                    <th>Total Net Fees</th>
                    <th>Total Wins</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="t in orderedUsers" v-if="t.pick==14">
                  <!-- <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'"> -->
                    <td><b>{{t.startingfight}}</b></td>
                    <!-- <td><b>{{t.pick}}</b></td> -->
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr>
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Open'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Closed'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-for="t in orderedUsers" v-if="t.pick==6&&t.control==='Finished'">
                  <tr v-for="t in startings" v-if="t.control==='Open'||t.control==='Last Call'">
                    <td><b>{{t.startingfight}}</b></td>
                    <td><b>{{t.pick}}</b></td>
                    <td>{{Number(t.potmoney_sum_amount).toLocaleString()}}</td>
                    <td> <a href="#" v-if="t.winnings" @click.prevent='getwinnings(t)' class="link-success text-success">{{Number(t.winnings).toLocaleString()}}</a> <a v-if="!t.winnings">-</a> </td>
                    <td><a class="text-white font-weight-bold btn btn-sm btn-danger" v-if="t.control==='Closed'">Closed Betting</a><a class="text-white font-weight-bold btn btn-sm btn-success" v-if="t.control==='Open'">Open Betting</a><a class="font-weight-bold btn btn-sm btn-warning" v-if="t.control==='Last Call'">Last Call</a>
                      <a class="text-white font-weight-bold btn btn-sm btn-secondary" v-if="t.control==='Finished'">Finished</a></td>
                    <td>
                      <a class="btn btn-outline-dark btn-sm btn-game" @click.prevent='placebet(t)' v-if="t.control==='Open'||t.control==='Last Call'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                      <a class="btn btn-outline-dark btn-sm disabled btn-game" v-if="t.control==='Closed'"><i class="fa fa-arrow-left" aria-hidden="true"></i> Play Pick <b>{{t.pick}}</b> </a>
                     </td>
                  </tr> -->
                  <!-- <tr v-if="!starting.length">
                    <th colspan="3">There`s no available starting fight yet...</th>
                  </tr> -->
                </tbody>
              </table>
              <!-- END OF PICK 14  -->
              <!-- reciept 2 -->

            </div>

            <div class="modal fade" id="confirmprint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-dark">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Print Reciept</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" id='printMe'>
                    <div class="card" v-for="t in receipt">
                      <!-- <barcode :value="receipt.barcode" tag="img"></barcode> -->

                      <p style="font-size: 14px;"><center>
                      <barcode v-if="t.barcode" :value="t.barcode" tag="svg"></barcode><br>
                      <b>Event name :</b> {{events.event_name}}<br>
                      <b>Bet ID :</b> {{t.id}}<br>
                      <b>Pick :</b> {{fight.pick}}<br>
                      <b>Arena :</b> {{events.venue}}<br>
                      <b>Date :</b> {{new Date().toLocaleString()}}<br>
                      <b>Teller :</b> {{user.name}}<br>
                      <b>Amount :</b> {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>

                      <a v-if="fight.pick==20"><b>Bet Range : </b>{{t.startingfight}} - {{t.endingfight}}<br>
                      <b>Additional fight : </b> {{t.cancelled-2}} - {{t.cancelled}}<br></a>
                      <a v-if="fight.pick==2"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+1}}<br></a>
                      <a v-if="fight.pick==3"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+2}}<br></a>
                      <a v-if="fight.pick==4"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+3}}<br></a>
                      <a v-if="fight.pick==5"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+4}}<br></a>
                      <a v-if="fight.pick==6"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+5}}<br></a>
                      <a v-if="fight.pick==8"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+7}}<br></a>
                      <a v-if="fight.pick==14"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+13}}<br></a>

                      <b>Bet :</b> <a v-if="fight.pick==2||fight.pick==3||fight.pick==4||fight.pick==5||fight.pick==6||fight.pick==8||fight.pick==14">{{t.selection}}</a> <a v-if="fight.pick==20">{{t.selection}}</a> <br>
                    <b>Paalala</b> : Ang napanalunang Ticket <br>
                    ay dito lamang pwede i claim sa <br>
                      arenang ito, at panatilihing malinis ang ticket <br>
                      at wag iwawala. at sa araw lamang ng event <br> pwede iwithdraw ang payout.<br>
                      <b>Strictly no ticket no claim.</b></center></p>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent='printme'>ConfirmPrint</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="confirmprint2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-dark">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Print Reciept</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" id='printMe2'>
                    <div class="card" v-for="t in receipt">
                      <!-- <barcode :value="receipt.barcode" tag="img"></barcode> -->

                      <p style="font-size: 14px;"><center>
                      <b>Reprint Reciept</b><br>
                      <b>Reprint Count : </b> {{t.reprint}}<br>
                      <barcode v-if="t.barcode" :value="t.barcode" tag="svg"></barcode><br>
                      <b>Event name :</b> {{events.event_name}}<br>
                      <b>Bet ID :</b> {{t.id}}<br>
                      <b>Pick :</b> {{t.turn}}<br>
                      <b>Arena :</b> {{events.venue}}<br>
                      <b>Date :</b> {{new Date().toLocaleString()}}<br>
                      <b>Teller :</b> {{user.name}}<br>
                      <b>Amount :</b> {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>

                      <a v-if="fight.pick==20||t.turn==20"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+19}}<br>
                      <b>Additional fight : </b> {{t.startingfight+20}} - {{t.startingfight+22}}<br></a>
                      <a v-if="fight.pick==2||t.turn==2"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+1}}<br></a>
                      <a v-if="fight.pick==3||t.turn==3"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+2}}<br></a>
                      <a v-if="fight.pick==4||t.turn==4"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+3}}<br></a>
                      <a v-if="fight.pick==5||t.turn==5"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+4}}<br></a>
                      <a v-if="fight.pick==6||t.turn==6"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+5}}<br></a>
                      <a v-if="fight.pick==8||t.turn==8"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+7}}<br></a>
                      <a v-if="fight.pick==14||t.turn==14"><b>Bet Range : </b>{{t.startingfight}} - {{t.startingfight+13}}<br></a>

                      <b>Bet :</b><a v-if="fight.pick==2||t.turn==2||fight.pick==3||t.turn==3||fight.pick==4||t.turn==4||fight.pick==5||t.turn==5||fight.pick==6||t.turn==8||t.turn==14">{{t.bet}}</a>
                      <a v-if="fight.pick==20||t.turn==20">{{t.bet}}</a> <br>
                    <b>Paalala</b> : Ang napanalunang Ticket <br>
                    ay dito lamang pwede i claim sa <br>
                      arenang ito, at panatilihing malinis ang ticket <br>
                      at wag iwawala. at sa araw lamang ng event <br> pwede iwithdraw ang payout.<br>
                      <b>Strictly no ticket no claim.</b></center></p>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent='printme'>ConfirmPrint</button>
                  </div>
                </div>
              </div>
            </div>



            <div class="card" v-if="select" style="background-color: transparent;">
              <a class="float-right btn btn-sm btn-danger" @click.prevent='goback' style="margin-bottom:0.5rem;background:rgb(221 92 86); border-color:rgb(221 92 86);" v-if="!rebet"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Go Back To Starting Fights</a>
              <!-- <div class="card-header bg-dark text-white">
                <barcode value="1234567890"></barcode>
              </div> -->
              <div class="card-header bg-dark text-white">
                <center>
                <b class="text-warning">PICK {{fight.pick}} - {{actualevent.event_name}} [{{actualevent.fights}} Fights] <br>{{actualevent.venue}} </b>
                <hr style="margin-top:0.5rem;margin-bottom:0.5rem">

                <cash v-if="refreshmoney" :user="user"></cash>
                <b class="text-muted" >Total Net Fees : <b class="text-success">{{odds.toLocaleString()}}</b></b>
                <!-- <b class="text-muted" v-if="odds>=10000">Total Payout : <b class="text-success">{{odds.toLocaleString()}}</b></b>  -->
              </center>
              </div>
              <div class="card-body" style="padding: 0.25rem;padding-top:0; background-color: white"><center>

                <!-- <div class="alert alert-success" role="alert">
                  <b>Highest Combination Prize {{Number(events.jackpot).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} </b>
                </div> -->
                <!-- <h4>Starting Fight Number : <b class="text-danger">{{fight.start}}</b></h4> -->
                <p class="h5" style="margin-top:0.5rem">Starting Fight Number : <b class="text-danger">{{select}}</b></p>
                <p v-if="fight.pick==2 && !rebet && possible" class="text-success">Possible Payout for {{possiblepayout2}} :
                  <a v-for="t in possible" v-if="possible">
                    <a v-if="possiblepayout2==='RR'" class="text-success font-weight-bold">{{Number(t.mm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='Rw'" class="text-success font-weight-bold">{{Number(t.mw).toLocaleString()}} per 100</a>
                    <!-- <a v-if="possiblepayout2==='MD'" class="text-success font-weight-bold">{{Number(t.md).toLocaleString()}} per 100</a> -->
                    <a v-if="possiblepayout2==='wR'" class="text-success font-weight-bold">{{Number(t.wm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='ww'" class="text-success font-weight-bold">{{Number(t.ww).toLocaleString()}} per 100</a>
                    <!-- <a v-if="possiblepayout2==='wD'" class="text-success font-weight-bold">{{Number(t.wd).toLocaleString()}} per 100</a> -->
                    <!-- <a v-if="possiblepayout2==='DM'" class="text-success font-weight-bold">{{Number(t.dm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='Dw'" class="text-success font-weight-bold">{{Number(t.dw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DD'" class="text-success font-weight-bold">{{Number(t.dd).toLocaleString()}} per 100</a> -->
                  </a>
                  <!-- <a v-for="t in possible" v-if="possiblepayout2==='Mw'">{{t.mw}}</a> -->
                </p>
                <p v-if="fight.pick==3&& !rebet && possible" class="text-success">Possible Payout for {{possiblepayout2}} :
                  <a v-for="t in possible" v-if="possible">
                    <a v-if="possiblepayout2==='MMM'" class="text-success font-weight-bold">{{Number(t.mmm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMw'" class="text-success font-weight-bold">{{Number(t.mmw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMD'" class="text-success font-weight-bold">{{Number(t.mmd).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwD'" class="text-success font-weight-bold">{{Number(t.mwd).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='Mww'" class="text-success font-weight-bold">{{Number(t.mww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwM'" class="text-success font-weight-bold">{{Number(t.mwm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDM'" class="text-success font-weight-bold">{{Number(t.mdm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDw'" class="text-success font-weight-bold">{{Number(t.mdw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDD'" class="text-success font-weight-bold">{{Number(t.mdd).toLocaleString()}} per 100</a>

                    <a v-if="possiblepayout2==='wwM'" class="text-success font-weight-bold">{{Number(t.wwm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='www'" class="text-success font-weight-bold">{{Number(t.www).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwD'" class="text-success font-weight-bold">{{Number(t.wwd).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDD'" class="text-success font-weight-bold">{{Number(t.wdd).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDw'" class="text-success font-weight-bold">{{Number(t.wdw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDM'" class="text-success font-weight-bold">{{Number(t.wdm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMM'" class="text-success font-weight-bold">{{Number(t.wmm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMw'" class="text-success font-weight-bold">{{Number(t.wmw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMD'" class="text-success font-weight-bold">{{Number(t.wmd).toLocaleString()}} per 100</a>

                    <a v-if="possiblepayout2==='DDD'" class="text-success font-weight-bold">{{Number(t.ddd).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDM'" class="text-success font-weight-bold">{{Number(t.ddm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDw'" class="text-success font-weight-bold">{{Number(t.ddw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='Dww'" class="text-success font-weight-bold">{{Number(t.dww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwD'" class="text-success font-weight-bold">{{Number(t.dwd).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwM'" class="text-success font-weight-bold">{{Number(t.dwm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMM'" class="text-success font-weight-bold">{{Number(t.dmm).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMw'" class="text-success font-weight-bold">{{Number(t.dmw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMD'" class="text-success font-weight-bold">{{Number(t.dmd).toLocaleString()}} per 100</a>
                  </a>
                  <!-- <a v-for="t in possible" v-if="possiblepayout2==='Mw'">{{t.mw}}</a> -->
                </p>
                <p v-if="fight.pick==4&& !rebet && possible" class="text-success">Possible Payout for {{possiblepayout2}} :
                  <a v-for="t in possible" v-if="possible">
                    <a v-if="possiblepayout2==='DDDD'" class="text-success font-weight-bold">{{Number(t.DDDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDDM'" class="text-success font-weight-bold">{{Number(t.DDDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDDw'" class="text-success font-weight-bold">{{Number(t.DDDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDMD'" class="text-success font-weight-bold">{{Number(t.DDMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDMM'" class="text-success font-weight-bold">{{Number(t.DDMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDMw'" class="text-success font-weight-bold">{{Number(t.DDMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDwD'" class="text-success font-weight-bold">{{Number(t.DDwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDwM'" class="text-success font-weight-bold">{{Number(t.DDwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DDww'" class="text-success font-weight-bold">{{Number(t.DDww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMDD'" class="text-success font-weight-bold">{{Number(t.DMDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMDM'" class="text-success font-weight-bold">{{Number(t.DMDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMDw'" class="text-success font-weight-bold">{{Number(t.DMDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMMD'" class="text-success font-weight-bold">{{Number(t.DMMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMMM'" class="text-success font-weight-bold">{{Number(t.DMMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMMw'" class="text-success font-weight-bold">{{Number(t.DMMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMwD'" class="text-success font-weight-bold">{{Number(t.DMwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMwM'" class="text-success font-weight-bold">{{Number(t.DMwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DMww'" class="text-success font-weight-bold">{{Number(t.DMww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwDD'" class="text-success font-weight-bold">{{Number(t.DwDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwDM'" class="text-success font-weight-bold">{{Number(t.DwDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwDw'" class="text-success font-weight-bold">{{Number(t.DwDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwMD'" class="text-success font-weight-bold">{{Number(t.DwMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwMM'" class="text-success font-weight-bold">{{Number(t.DwMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwMw'" class="text-success font-weight-bold">{{Number(t.DwMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwwD'" class="text-success font-weight-bold">{{Number(t.DwwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='DwwM'" class="text-success font-weight-bold">{{Number(t.DwwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='Dwww'" class="text-success font-weight-bold">{{Number(t.Dwww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDDD'" class="text-success font-weight-bold">{{Number(t.MDDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDDM'" class="text-success font-weight-bold">{{Number(t.MDDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDDw'" class="text-success font-weight-bold">{{Number(t.MDDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDMD'" class="text-success font-weight-bold">{{Number(t.MDMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDMM'" class="text-success font-weight-bold">{{Number(t.MDMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDMw'" class="text-success font-weight-bold">{{Number(t.MDMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDwD'" class="text-success font-weight-bold">{{Number(t.MDwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDwM'" class="text-success font-weight-bold">{{Number(t.MDwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MDww'" class="text-success font-weight-bold">{{Number(t.MDww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMDD'" class="text-success font-weight-bold">{{Number(t.MMDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMDM'" class="text-success font-weight-bold">{{Number(t.MMDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMDw'" class="text-success font-weight-bold">{{Number(t.MMDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMMD'" class="text-success font-weight-bold">{{Number(t.MMMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMMM'" class="text-success font-weight-bold">{{Number(t.MMMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMMw'" class="text-success font-weight-bold">{{Number(t.MMMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMwD'" class="text-success font-weight-bold">{{Number(t.MMwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMwM'" class="text-success font-weight-bold">{{Number(t.MMwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MMww'" class="text-success font-weight-bold">{{Number(t.MMww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwDD'" class="text-success font-weight-bold">{{Number(t.MwDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwDM'" class="text-success font-weight-bold">{{Number(t.MwDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwDw'" class="text-success font-weight-bold">{{Number(t.MwDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwMD'" class="text-success font-weight-bold">{{Number(t.MwMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwMM'" class="text-success font-weight-bold">{{Number(t.MwMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwMw'" class="text-success font-weight-bold">{{Number(t.MwMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwwD'" class="text-success font-weight-bold">{{Number(t.MwwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='MwwM'" class="text-success font-weight-bold">{{Number(t.MwwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='Mwww'" class="text-success font-weight-bold">{{Number(t.Mwww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDDD'" class="text-success font-weight-bold">{{Number(t.wDDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDDM'" class="text-success font-weight-bold">{{Number(t.wDDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDDw'" class="text-success font-weight-bold">{{Number(t.wDDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDMD'" class="text-success font-weight-bold">{{Number(t.wDMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDMM'" class="text-success font-weight-bold">{{Number(t.wDMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDMw'" class="text-success font-weight-bold">{{Number(t.wDMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDwD'" class="text-success font-weight-bold">{{Number(t.wDwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDwM'" class="text-success font-weight-bold">{{Number(t.wDwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wDww'" class="text-success font-weight-bold">{{Number(t.wDww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMDD'" class="text-success font-weight-bold">{{Number(t.wMDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMDM'" class="text-success font-weight-bold">{{Number(t.wMDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMDw'" class="text-success font-weight-bold">{{Number(t.wMDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMMD'" class="text-success font-weight-bold">{{Number(t.wMMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMMM'" class="text-success font-weight-bold">{{Number(t.wMMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMMw'" class="text-success font-weight-bold">{{Number(t.wMMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMwD'" class="text-success font-weight-bold">{{Number(t.wMwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMwM'" class="text-success font-weight-bold">{{Number(t.wMwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wMww'" class="text-success font-weight-bold">{{Number(t.wMww).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwDD'" class="text-success font-weight-bold">{{Number(t.wwDD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwDM'" class="text-success font-weight-bold">{{Number(t.wwDM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwDw'" class="text-success font-weight-bold">{{Number(t.wwDw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwMD'" class="text-success font-weight-bold">{{Number(t.wwMD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwMM'" class="text-success font-weight-bold">{{Number(t.wwMM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwMw'" class="text-success font-weight-bold">{{Number(t.wwMw).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwwD'" class="text-success font-weight-bold">{{Number(t.wwwD).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwwM'" class="text-success font-weight-bold">{{Number(t.wwwM).toLocaleString()}} per 100</a>
                    <a v-if="possiblepayout2==='wwww'" class="text-success font-weight-bold">{{Number(t.wwww).toLocaleString()}} per 100</a>

                  </a>
                  <!-- <a v-for="t in possible" v-if="possiblepayout2==='Mw'">{{t.mw}}</a> -->
                </p>
                <p v-if="fight.pick==5&& !rebet && possible" class="text-success">Possible Payout for {{possiblepayout2}}
                  <a v-for="t in possible" v-if="possible">
                    <a v-for="a in t.combination" v-if="possiblepayout2===a.bet&&possible" class="text-success font-weight-bold">{{Number(a.total).toLocaleString()}} per 100</a>
                  </a>
                  <!-- <a v-for="t in possible" v-if="possiblepayout2==='Mw'">{{t.mw}}</a> -->
                <!-- <br>  <i class="text-secondary" v-if="possible">if there`s no possible payout, {{odds.toLocaleString()}} will be the possible payout</i> -->
                </p>
                <p v-if="fight.pick==6&& !rebet && possible" class="text-success">Possible Payout for {{possiblepayout2}}
                  <a v-for="t in possible" v-if="possible">
                    <a v-for="a in t.combination" v-if="possiblepayout2===a.bet&&possible" class="text-success font-weight-bold">{{Number(a.total).toLocaleString()}} per 100</a>
                  </a>
                  <!-- <a v-for="t in possible" v-if="possiblepayout2==='Mw'">{{t.mw}}</a> -->
                <!-- <br>  <i class="text-secondary" v-if="possible">if there`s no possible payout, {{odds.toLocaleString()}} will be the possible payout</i> -->
                </p>
                <p v-if="fight.pick==8&& !rebet && possible" class="text-success">Possible Payout for {{possiblepayout2}}
                  <a v-for="t in possible" v-if="possible">
                    <a v-for="a in t.combination" v-if="possiblepayout2===a.bet&&possible" class="text-success font-weight-bold">{{Number(a.total).toLocaleString()}} per 100</a>
                  </a>
                  <!-- <a v-for="t in possible" v-if="possiblepayout2==='Mw'">{{t.mw}}</a> -->
                <!-- <br>  <i class="text-secondary" v-if="possible">if there`s no possible payout, {{odds.toLocaleString()}} will be the possible payout</i> -->
                </p>
                <p v-if="fight.pick==14&& !rebet && possible" class="text-success">Possible Payout for {{possiblepayout2}}
                  <a v-for="t in possible" v-if="possible">
                    <a v-for="a in t.combination" v-if="possiblepayout2===a.bet&&possible" class="text-success font-weight-bold">{{Number(a.total).toLocaleString()}} per 100</a>
                  </a>
                  <!-- <a v-for="t in possible" v-if="possiblepayout2==='Mw'">{{t.mw}}</a> -->
                <!-- <br>  <i class="text-secondary" v-if="possible">if there`s no possible payout, {{odds.toLocaleString()}} will be the possible payout</i> -->
                </p>
                <p class="h6" v-if="fight.pick==2||fight.pick==3||fight.pick==4||fight.pick==5||fight.pick==5||fight.pick==6||fight.pick==8||fight.pick==14 && !rebet">  Choose Bet Amount :
                  <select class="form-control" v-model="fight.amount">
                    <option value="1" selected>100</option>
                    <option value="2">200</option>
                    <option value="3">300</option>
                    <option value="4">400</option>
                    <option value="5">500</option>
                  </select> </p>
                <p class="h6" v-else>Bet Amount[per single bet] : <b class="text-success">{{Number(controls.amount).toLocaleString()}}</b>
                </p>
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
                  <div class="form-check"  v-if="fight.pick==20||fight.pick==5||fight.pick==6||fight.pick==8||fight.pick==14">
                    <input class="form-check-input" v-on:change="randomplacebet" type="checkbox"  id="defaultCheck1" v-model="randombets">
                    <label class="form-check-label" for="defaultCheck1">
                      <b class="">Random Picks</b>
                    </label>&nbsp&nbsp&nbsp&nbsp&nbsp
                    <button type="button" name="button" class="btn btn-success btn-sm" @click.prevent='switchmultiple' v-if="!single&&fight.pick==20">Switch to multiple bets</button>
                    <button type="button" name="button" class="btn btn-success btn-sm" @click.prevent='switchsingle' v-if="single && fight.pick==20">Switch to Single bet</button>
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
                          <th class="text-center text-danger btn-title-button" style="cursor: pointer;" @click.prevent='allmeron' title="Select all meron"  v-title data-placement="top"><a >Red Apple</a></th>
                          <th class="text-center text-info btn-title-button" style="cursor: pointer;" @click.prevent='allwala' title="Select all wala" v-title data-placement="top"><a >White Apple</a></th>
                          <!-- <th class="text-center text-success" style="cursor: pointer;">Draw</th> -->
                        </tr>
                      </thead>

                      <tbody class="" style="width:100%">
                        <tr v-for='(bet,index) in selected' :index="index" v-if="!single">
                          <td class="text-center"><b>{{bet.fightnumber}}</b></td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" :name="index" value="" :id="m+index" v-on:change="picksm1(index)" v-model='bet.selection.meron' value='true'>
                              <label class="form-check-label text-danger font-weight-bold" v-if="bet.selection.meron">
                                <!-- <a class="btn btn-sm btn-danger" @click.prevent='picksm1(index)'><a style="font-size:0.6rem;" v-for="names in derby" v-if="bet.fightnumber==names.fightnumber">{{names.entryname1}}</a></a> -->
                                <!-- <a class="btn btn-sm btn-info" :for="index+1" @click.prevent='picksm1(index)'>Meron</a> -->
                                <a class="btn btn-sm btn-danger" :for="index+1" @click.prevent='picksm1(index)'>Red Apple</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.meron" :for="m+index" >
                                <!-- <a class="btn btn-sm" @click.prevent='picksm1(index)'><a style="font-size:0.6rem;" v-for="names in derby" v-if="bet.fightnumber==names.fightnumber">{{names.entryname1}}</a></a> -->
                                <a class="btn btn-sm" :for="index+1" @click.prevent='picksm1(index)'>Red Apple</a>
                              </label>
                            </div>
                          </td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" :name="index" value="" :id="w+index"  v-on:change="picksw1(index)" v-model="bet.selection.wala" value='true'>
                              <label class="form-check-label text-info font-weight-bold" v-if="bet.selection.wala">
                                <!-- <a class="btn btn-sm btn-info text-white" @click.prevent='picksw1(index)'>
                                  <a style="font-size:0.6rem;"v-for="names in derby" v-if="bet.fightnumber==names.fightnumber">{{names.entryname2}}</a>
                                 </a> -->
                                 <a class="btn btn-sm btn-info text-white" :for="index+1" @click.prevent='picksw1(index)'>White Apple</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.wala" :for="w+index">
                                <!-- <a class="btn btn-sm" @click.prevent='picksw1(index)'>
                                  <a style="font-size:0.6rem;" v-for="names in derby" v-if="bet.fightnumber==names.fightnumber">{{names.entryname2}}</a>
                                </a> -->
                                <a class="btn btn-sm" :for="index+1" @click.prevent='picksw1(index)'>White Apple</a>
                              </label>
                            </div>
                          </td>
                          <!-- <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" :name="index" value="" :id="d+index" v-on:change="picksd1(index)" v-model="bet.selection.draw" value='true'>
                              <label class="form-check-label text-success font-weight-bold" v-if="bet.selection.draw">
                                <a class="btn btn-sm btn-success" :for="index+1" @click.prevent='picksd1(index)'>Draw</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.draw" :for="d+index">
                                <a class="btn btn-sm" @click.prevent='picksd1(index)'>Draw</a>
                              </label>
                            </div>
                          </td> -->
                        </tr>
                        <tr v-for='(bet,index) in selected' :index="index" v-if="single">
                          <td class="text-center"><b>{{bet.fightnumber}}</b></td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" :name="index" value="" :id="index+1" v-on:change="pickm1(index)" v-model='bet.selection.meron'>
                              <label class="form-check-label text-danger font-weight-bold" v-if="bet.selection.meron" for="index">
                                <a class="btn btn-sm btn-danger" @click.prevent='pickm(index)'>Red Apple</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.meron">
                                <a class="btn btn-sm" @click.prevent='pickm(index)'>Red Apple</a>
                              </label>
                            </div>
                          </td>
                          <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" :name="index" value="" id="defaultCheck2"  v-on:change="pickw1(index)" v-model="bet.selection.wala">
                              <label class="form-check-label text-info font-weight-bold" v-if="bet.selection.wala">
                                <a class="btn btn-sm btn-info text-white" @click.prevent='pickw(index)'>White Apple</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.wala" for="index">
                                <a class="btn btn-sm" @click.prevent='pickw(index)'>White Apple</a>
                              </label>
                            </div>
                          </td>
                          <!-- <td class="">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" :name="index" value="" id="defaultCheck1" v-on:change="pickd1(index)" v-model="bet.selection.draw">
                              <label class="form-check-label text-success font-weight-bold" v-if="bet.selection.draw">
                                <a class="btn btn-sm btn-success" :for="index+1" @click.prevent='pickd(index)'>Draw</a>
                              </label>
                              <label class="form-check-label font-weight-bold" v-if="!bet.selection.draw" for="index">
                                <a class="btn btn-sm" @click.prevent='pickd(index)'>Draw</a>
                              </label>
                            </div>
                          </td> -->

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
	     <button class="btn btn-primary form-control" @click.prevent='randommultiple()' v-if='selected.length&&fight.pick==20'>Place Multiple Random Picks</button>
                <button class="btn btn-success form-control" @click.prevent='showconfirm()' style='margin-top: 0.25rem;' v-if='selected.length'>Place Bet</button><br v-if='!rebet'>

                  <button type="button" class="btn btn-success form-control btn-sm" @click.prevent='betagainplacebet' name="button" v-if='rebet'><i class="fa fa-refresh" aria-hidden="true"></i> Bet Again</button> <br  v-if='rebet'>
                  <button type="button" class="btn btn-danger form-control btn-sm" style="margin-top:0.5rem" @click.prevent='goback' name="button" v-if='rebet'><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Go Back to Startingfights</button>
                <!-- <a class="btn btn-success form-control" @click.prevent='insertbet()'>Place Bet</a> -->
              </div>
            </div><br>
            <button type="button" class="btn btn-dark text-warning form-control" name="button" @click.prevent='viewpendingbets'>View Current Pending Bets</button><br><br>
            <button type="button" class="btn btn-dark text-warning form-control" name="button" @click.prevent='viewentrynames'>View Current Entry Names</button>
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
                  <h5 class="modal-title text-warning" id="exampleModalLabel">Current Pending Bets</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body table-responsive" style="padding:0">
                  <table class="table tabl-sm table-striped table-borderless table-hover">
                    <tbody>
                      <tr v-for="(t,index) in bets.data" :index='index' v-if="bets.data">
                        <div class="card">
                            <div class="card-header bg-dark text-warning text-center">Fightdate : {{t.fightdate|datec}} - Starting Fight {{t.startingfight}}</div>
                            <div class="card-body table-responsive" style="padding:0">
                              <table class="table table-sm table-striped" >
                                <thead>
                                  <th class="text-center">#</th>
                                  <th class="text-center">Starting Fight</th>
                                  <th class="text-center">Wins</th>
                                  <th class="text-center">Bet</th>
                                  <th class="text-center">Time</th>
                                </thead>
                                <tbody>
                                  <tr v-for="(a,indexx) in t.expertbet" :index='indexx'>
                                    <th class="text-center">{{indexx+1}}</th>
                                     <td class=" text-center">{{Number(a.startingfight).toLocaleString()}}</td>
                                    <td class="text-center">{{a.wins}}</td>
                                    <td class="text-center">{{a.bet}}<br v-if="user.role===9"><a class="btn btn-success btn-sm" @click.prevent='reprint(a)' v-if="user.role===9">Reprint</a></td>
                                    <td class="text-center">{{a.created_at|datetime}}</td>
                                    <!-- <td class="text-center"> <a v-if="a.winner===0" class="text-danger">Pending</a> </td> -->
                                    <!-- <td class="text-center"> <a class="btn btn-success btn-sm" @click.prevent='showdetailedbets(a.id)'>View bets</a> </td> -->
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                        </div>
                        <tr>
                          <td v-if="!bets.data" colspan="4">You have no pending bets..</td>
                        </tr>
                      </tr>
                    </tbody>
                  </table>
                </div>
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
          <!-- //original -->
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
                  <center>Are you sure you want to bet this <br>
                     <b v-if="fight.pick==2">PICK 2?</b>
                     <b v-if="fight.pick==3">PICK 3?</b>
                     <b v-if="fight.pick==4">PICK 4?</b>
                     <b v-if="fight.pick==5">PICK 5?</b>
                     <b v-if="fight.pick==6">PICK 6?</b>
                     <b v-if="fight.pick==8">PICK 8?</b>
                     <b v-if="fight.pick==14">PICK 14?</b>
                     <b v-if="fight.pick==20">PICK 20?</b>
                  <br> Bet amount : {{Number(totalamount).toLocaleString()}}<br>
                 </center>
                </div>
                <div class="modal-footer" >
                  <button type="button" class="btn btn-primary form-control" :disabled='isDisabled' @click.prevent='insert2'>Confirm</button>
                  <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Go Back</button>
                </div>
              </div>
            </div>
          </div>
          <!-- //random multiple -->
          <div class="modal fade" id="randommultiple" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
              <div class="modal-content"  style="border:none !important;">
                <div class="modal-header bg-dark text-white">
                  <h5 class="modal-title text-warning" id="exampleModalCenterTitle">Multiple Random picks</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" style=" border:none !important;">
                  <center>Choose the number of how many random picks you want :  </center>
                    <!-- <input type="number" min="1" class="form-control" name="" v-model="fight.numberofrandompicks"> -->
                    <select class="form-control" id="exampleFormControlSelect2" v-model="selectedValue">
                      <option>2</option>
                      <option >3</option>
                      <option >4</option>
                      <option>5</option>
                      <!-- <option >6</option>
                      <option >7</option>
                      <option >8</option>
                      <option >9</option>
                      <option >10</option> -->
                    </select>
                </div>
                <div class="modal-footer" >
                  <button type="button" class="btn btn-primary form-control" :disabled='isDisabled' @click.prevent='showconfirmrandompicks'>Confirm</button>
                  <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Go Back</button>
                </div>
              </div>
            </div>
          </div>
          <!-- //random multiple confirm-->
          <div class="modal fade" id="randommultipleconfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
              <div class="modal-content"  style="border:none !important;">
                <div class="modal-header bg-dark text-white">
                  <h5 class="modal-title text-warning" id="exampleModalCenterTitle">Multiple Random picks</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" style=" border:none !important;">
                  <center>
                  Are you sure you want to bet multiple random pick?<br>
                  Number of picks : <b>{{this.fight.numberofrandompicks}}</b><br>
                  amount : <b>{{this.fight.numberofrandompicks*this.controls.amount}}</b>
                </center>
                </div>
                <div class="modal-footer" >
                  <button type="button" class="btn btn-primary form-control" :disabled='isDisabled' @click.prevent='insertmultiplerandompicks'>Confirm</button>
                  <button type="button" class="btn btn-danger form-control" @click.prevent='randommultiple'>Go Back</button>
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
          <!-- derbynames -->
          <div class="modal fade" id="derbynames" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header bg-dark">
                  <h5 class="modal-title text-warning" id="exampleModalLabel">Entry Names <br><small class="text-warning">Entries may not be accurate (due to unexpected changes)</small></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Fightnumber</th>
                        <th class="text-danger">MERON</th>
                        <th class="text-info">WALA</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in derby.data">
                        <td style="font-size: 8.3px">{{t.fightnumber}}</td>
                        <td style="font-size: 8.3px">{{t.entryname1}}</td>
                        <td style="font-size: 8.3px">{{t.entryname2}}</td>
                      </tr>
                    </tbody>
                  </table>

                <div class="modal-footer">
                  <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                  <div class="w-100">
                  <pagination :data="derby" :show-disabled=true :limit='2' @pagination-change-page="getderby" class="justify-content-center">
                  </pagination>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- end of derbynames -->
          <!-- winnings -->
          <div class="modal fade" id="winnings" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header bg-dark">
                  <h5 class="modal-title text-warning" id="exampleModalLabel">Winnings of Pick {{winnings.pick}}<br><small class="text-warning">Starting Fight {{winnings.startingfight}}</small></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Score</th>
                        <th>Winnings</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in winbets">
                        <td>{{t.wins}}</td>
                        <td class="text-success">{{Number(t.result).toLocaleString()}}</td>
                      </tr>
                    </tbody>
                  </table>

                <div class="modal-footer">
                  <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                  <div class="w-100">
                  <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">Close</button>
                  </pagination>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- end of winnings -->
        </div>
    </div>
</template>
<script>

    export default {
      props:['user','data'],
      data(){
        return{
          d:null,
          m:null,
          w:null,
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
          currentbet:'',
          refreshmoney:true,
          bets:{},
          events:[],
          selected:[],
          users:[],
          receipt:[],
          derby:{},
          control:'',
          checkforavailability:'',
          possiblepayoutpick5:0,
          totalpick5:0,
          controls:[],
          winbets:[],
          disabled:false,
          pages:0,
          drawcount:0,
          confirm:[],
          pageOfItems: [],
          startings: [],
          possible: [],
          // bets:new Form({
          //   b1:[{
          //     fightnumber:'',
          //     selection1:'',
          //     selection2:'',
          //     selection3:'',
          //   }],
          // }),
          selectedValue: 2,
          fight:new Form({
            id:'',
            pick:'',
            start:null,
            selection:'Meron',
            amount:null,
            user_id:this.user.id,
            numberofrandompicks:this.selectedValue,
            fightnumber:'',
            pin:'',
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
          actualevent:new Form({
            startingfight:'',
            id:'',
            event_name:'',
            fights:'',
            venue:'',
          }),
          form2:new Form({
            id:this.user.id,
            name:this.user.name,
            email:this.user.email,
            pnumber:this.user.pnumber
          }),
          winnings:new Form({
            id:'',
            pick:'',
            startingfight:''
          }),
        }
      },
      computed: {
        orderedUsers: function () {
          return _.orderBy(this.startings, 'startingfight')
        },
  	     possiblepayout2: function(){
           if (this.selected&&this.fight.pick==2) {
             return this.selected[0].bet+''+this.selected[1].bet;
           }
           else if (this.selected&&this.fight.pick==3) {
             return this.selected[0].bet+''+this.selected[1].bet+''+this.selected[2].bet;
           }
           else if (this.selected&&this.fight.pick==4) {
             return this.selected[0].bet+''+this.selected[1].bet+''+this.selected[2].bet+''+this.selected[3].bet;
           }
           else if (this.selected&&this.fight.pick==5) {
             this.possiblepayoutpick5 = 0;
             this.totalpick5 = 0;
             this.checkforavailability =  this.selected[0].bet+''+this.selected[1].bet+''+this.selected[2].bet+''+this.selected[3].bet+''+this.selected[4].bet;
             this.possible.forEach((val)=>{
               this.totalpick5 = val.totalamountfinal;
               val.combination.forEach((val2)=>{
               if (val2.bet===this.checkforavailability) {
                 this.possiblepayoutpick5 = val2.total;
               }
             });
             });
             if (this.possiblepayoutpick5) {
               return this.checkforavailability+' : '+this.possiblepayoutpick5.toLocaleString()+' Per 100';
             }else {
               return this.checkforavailability+' : '+this.totalpick5.toLocaleString()+' Per 100';
             }
           }
           else if (this.selected&&this.fight.pick==6) {
             this.possiblepayoutpick5 = 0;
             this.totalpick5 = 0;
             this.checkforavailability =  this.selected[0].bet+''+this.selected[1].bet+''+this.selected[2].bet+''+this.selected[3].bet+''+this.selected[4].bet+''+this.selected[5].bet;
             this.possible.forEach((val)=>{
               this.totalpick5 = val.totalamountfinal;
               val.combination.forEach((val2)=>{
               if (val2.bet===this.checkforavailability) {
                 this.possiblepayoutpick5 = val2.total;
               }
             });
             });
             if (this.possiblepayoutpick5) {
               return this.checkforavailability+' : '+this.possiblepayoutpick5.toLocaleString()+' Per 100';
             }else {
               return this.checkforavailability+' : '+this.totalpick5.toLocaleString()+' Per 100';
             }
           }
           else if (this.selected&&this.fight.pick==8) {
             this.possiblepayoutpick5 = 0;
             this.totalpick5 = 0;
             this.checkforavailability =  this.selected[0].bet+''+this.selected[1].bet+''+this.selected[2].bet+''+this.selected[3].bet+''+this.selected[4].bet+''+this.selected[5].bet+''+this.selected[6].bet+''+this.selected[7].bet;
             this.possible.forEach((val)=>{
               this.totalpick5 = val.totalamountfinal;
               val.combination.forEach((val2)=>{
               if (val2.bet===this.checkforavailability) {
                 this.possiblepayoutpick5 = val2.total;
               }
             });
             });
             if (this.possiblepayoutpick5) {
               return this.checkforavailability+' : '+this.possiblepayoutpick5.toLocaleString()+' Per 100';
             }else {
               return this.checkforavailability+' : '+this.totalpick5.toLocaleString()+' Per 100';
             }
           }
           else if (this.selected&&this.fight.pick==14) {
             this.possiblepayoutpick5 = 0;
             this.totalpick5 = 0;
             this.checkforavailability ="";
             this.checkforavailability =  this.selected[0].bet+''+this.selected[1].bet+''+this.selected[2].bet+''+this.selected[3].bet+''+this.selected[4].bet+''+this.selected[5].bet+''+this.selected[6].bet+''+this.selected[7].bet+''+this.selected[8].bet+''+this.selected[9].bet+''+this.selected[10].bet+''+this.selected[11].bet+''+this.selected[12].bet+''+this.selected[13].bet;
             this.possible.forEach((val)=>{
               this.totalpick5 = val.totalamountfinal;
               val.combination.forEach((val2)=>{
               if (val2.bet===this.checkforavailability) {
                 this.possiblepayoutpick5 = val2.total;
               }
             });
             });
             if (this.possiblepayoutpick5) {
               return this.checkforavailability+' : '+this.possiblepayoutpick5.toLocaleString()+' Per 100';
             }else {
               return this.checkforavailability+' : '+this.totalpick5.toLocaleString()+' Per 100';
             }
           }
        },
  	     raker: function(){
        	this.controls.rakepick2/100;
        },
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
        tooltip(){
            $('[data-toggle="tooltip"]').tooltip()
        },
        allmeron(){
          // alert('hi')
          this.selected.forEach((val)=>{
             val.bet = 'R';
             val.selection.draw = false;
             val.selection.wala = false;
             val.selection.meron = true;
          });
        },
        allwala(){
          this.selected.forEach((val)=>{
             val.bet = 'w';
             val.selection.draw = false;
             val.selection.wala = true;
             val.selection.meron = false;
          });
        },
        possiblepayout(){
          this.fight.post('/pick20/possiblepayout').then(response=>{
            this.possible = response.data;
          });
        },
        getwinnings(t){
          this.winnings.fill(t);
          this.loading = true;
          this.winnings.post('/pick20/getwinningbets').then(response=>{
            this.loading = false;
            this.winbets = response.data;
            $('#winnings').modal('show');
          }).catch(()=>{
            this.loading = false;
          });
        },
        viewentrynames(){
          this.getderby();
          $('#derbynames').modal('show');
        },
        getderby(page = 1){
          // console.log(raker);
          this.fight.fightnumber = this.startings[0].startingfight;
          this.fight.post('/pick20/derbynames?page='+page).then(response=>{
            this.derby = response.data;
          })
        },
        printme(){
          this.$htmlToPaper('printMe');
          $('#confirmprint').modal('hide');
        },
        insertmultiplerandompicks(){
          this.refreshmoney=false;
          this.loading=true;
          this.disabled=true;
          this.fight.post('/pick20/insertmultiplerandompicks').then(response=>{
            if (response.data.error) {
              this.loading=false;
              this.disabled=false;
              Swal.fire(
                'Success!',
                response.data.error,
                'error'
              )
            }else {
              this.disabled=false;
            this.receipt=response.data;
            this.loading=false;
            this.customize=null;
            this.fight.start=null;
            this.randombets=false;
            this.rebet=1;
            this.refreshmoney=true;
            this.selectedValue=2;
            this.selected=[];
            $('#randommultipleconfirm').modal('hide');
            Swal.fire(
              'Success!',
              'Your Picks has been entered.',
              'success'
            )
            }
          }).then(()=>{

              if (this.user.role===9) {
                // $('#confirmprint').modal('show');
                // axios.get('/pick20/control').then(response=>{
                  // this.controls=response.data;
                  // document.title = "Pick "+this.controls.pick;
                  this.$htmlToPaper('printMe');
                // });
              }
          }).catch(()=>{
            this.loading=false;
            this.disabled=false;
            $('#randommultipleconfirm').modal('hide');
            Swal.fire(
              'error',
              'Error.',
              'error'
            )
          });
        },
        showconfirmrandompicks(){
            this.getuser();
            this.fight.numberofrandompicks = parseInt(this.selectedValue);
            this.fight.amount = this.fight.numberofrandompicks*this.controls.amount;
          if (this.fight.amount <= parseInt(this.users.cash.slice(0,-4)) && this.fight.amount>=100 || this.users.role===9) {
            $('#randommultiple').modal('hide');
            $('#randommultipleconfirm').modal('show');
          }else {
            if (!this.fight.numberofrandompicks||this.fight.numberofrandompicks<=0) {
              Swal.fire(
                'error',
                'Please type the number of how many random picks you want.',
                'error'
              )
            }else {
              Swal.fire(
                'error',
                'Not enough balance.',
                'error'
              )
            }

          }
        },
        randommultiple(){
          this.selectedValue = 2;
          $('#randommultipleconfirm').modal('hide');
          this.fight.numberofrandompicks=null;
          $('#randommultiple').modal('show');
        },
        switchmultiple(){
          // pick 2
          if (this.fight.pick==2) {
            this.single=1;
            this.randombets=null;
            this.selected = [],
            this.loading=true;
            this.fight.post('/pick20/selectionpick2').then(response=>{
              this.selected = response.data;
              this.loading=false;
            })
          }
          // end of pick 2
          // pick 3
          else if (this.fight.pick==3) {
            this.single=1;
            this.randombets=null;
            this.selected = [],
            this.loading=true;
            this.fight.post('/pick20/selectionpick3').then(response=>{
              this.selected = response.data;
              this.loading=false;
            })
          }
          // end of pick 3
          else {
            this.single=1;
            this.randombets=null;
            this.selected = [],
            this.loading=true;
            this.fight.post('/pick20/selection').then(response=>{
              this.selected = response.data;
              this.loading=false;
            })
          }

        },
        switchsingle(){
          // pick 2
          if (this.fight.pick==2) {
            this.randombets=null;
            this.single=null;
            this.selected = [],
            this.loading=true;
            this.fight.post('/pick20/selectionpick2').then(response=>{
              this.selected = response.data;
              this.loading=false;
            })
          }
          // end of pick 2
          else if (this.fight.pick==3) {
            this.randombets=null;
            this.single=null;
            this.selected = [],
            this.loading=true;
            this.fight.post('/pick20/selectionpick3').then(response=>{
              this.selected = response.data;
              this.loading=false;
            })
          }
          else {
            this.randombets=null;
            this.single=null;
            this.selected = [],
            this.loading=true;
            this.fight.post('/pick20/selection').then(response=>{
              this.selected = response.data;
              this.loading=false;
            })
          }

        },
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.controls=response.data;
            document.title = "Pick "+this.controls.pick;
          });
        },
        picksm1(index){
          // if (this.selected[index].selection.meron) {
          this.drawcount = 0;
          this.selected[index].bet='R';
          this.selected[index].amount=1;
          this.selected[index].selection.meron=true;
          this.selected[index].selection.wala=false;
          this.selected[index].selection.draw=false;
          this.selected.forEach((val)=>{
           if (val.bet==='D') {
             this.drawcount = this.drawcount+1;
           }
          });
          // }
        },
        picksw1(index){
          // if (this.selected[index].selection.wala) {
          this.drawcount = 0;
          this.selected[index].bet='w';
          this.selected[index].amount=1;
          this.selected[index].selection.meron=false;
          this.selected[index].selection.wala=true;
          this.selected[index].selection.draw=false;
          this.selected.forEach((val)=>{
           if (val.bet==='D') {
             this.drawcount = this.drawcount+1;
           }
          });
          // }
        },
        picksd1(index){
          // if (this.selected[index].selection.draw) {
          // alexmuna
          this.drawcount = 0;
          this.selected[index].bet='D';
          this.selected[index].amount=1;
          this.selected[index].selection.meron=false;
          this.selected[index].selection.wala=false;
          this.selected[index].selection.draw=true;
          this.selected.forEach((val)=>{
           if (val.bet==='D') {
             this.drawcount = this.drawcount+1;
           }
          });
          if (this.drawcount>2 && this.fight.pick==20) {
            Swal.fire(
              'Ooops',
              "You have selected more than 2 draws!\nPlease make sure you only have maximum 2 draws in one bet.",
              'warning'
            )
          }
          // }
        },
        pickm1(index){
          if (this.selected[index].selection.meron) {
            this.selected[index].selection.meron = true;
            if (!this.selected[index].bet) {
              this.selected[index].bet='R';
              this.selected[index].amount=this.selected[index].amount+1;
            }else {
              this.selected[index].bet=this.selected[index].bet+'R';
              this.selected[index].amount=this.selected[index].amount+1;
            }
          }else {
            this.selected[index].bet=this.selected[index].bet.replace('R', '');
            this.selected[index].selection.meron = false;
            this.selected[index].amount=this.selected[index].amount-1;
          }
          this.selected.forEach((val)=>{
           if (val.bet==='D') {
             this.drawcount = this.drawcount+1;
           }
          });
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
          this.selected.forEach((val)=>{
           if (val.bet==='D') {
             this.drawcount = this.drawcount+1;
           }
          });
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
          this.selected.forEach((val)=>{
           if (val.bet==='D') {
             this.drawcount = this.drawcount+1;
           }
          });
          if (this.drawcount>2 && this.fight.pick==20) {
            Swal.fire(
              'Ooops',
              "You have selected more than 2 draws!\nPlease make sure you only have maximum 2 draws in one bet.",
              'warning'
            )
          }
        },
        pickm(index){
          this.drawcount = 0;
          if (this.selected[index].selection.meron) {
            this.selected[index].selection.meron = false;
            this.selected[index].bet=this.selected[index].bet.replace('R', '');
            this.selected[index].amount=this.selected[index].amount-1;
          }else {
            this.selected[index].selection.meron = true;
            if (!this.selected[index].bet) {
              this.selected[index].bet='R';
              this.selected[index].amount=this.selected[index].amount+1;
            }else {
              this.selected[index].bet=this.selected[index].bet+'R';
              this.selected[index].amount=this.selected[index].amount+1;
            }
          }
          this.selected.forEach((val)=>{
            if (!val.bet) {
              Swal.fire(
                'Ooops',
                "You dont have any bet on fight number "+val.fightnumber,
                'warning'
              )
            }
           if (val.selection.draw) {
             this.drawcount = this.drawcount+1;
           }
          });
        },
        pickw(index){
          this.drawcount = 0;
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
          this.selected.forEach((val)=>{
            if (!val.bet) {
              Swal.fire(
                'Ooops',
                "You dont have any bet on fight number "+val.fightnumber,
                'warning'
              )
            }
           if (val.selection.draw) {
             this.drawcount = this.drawcount+1;
           }
          });
        },
        pickd(index){
          // alert('hi')
          this.drawcount = 0;
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
          this.selected.forEach((val)=>{
            if (!val.bet) {
              Swal.fire(
                'Ooops',
                "You dont have any bet on fight number "+val.fightnumber,
                'warning'
              )
            }
           if (val.selection.draw) {
             this.drawcount = this.drawcount+1;
           }
          });
          if (this.drawcount>2) {
            Swal.fire(
              'Ooops',
              "You have selected more than 2 draws!\nPlease make sure you only have maximum 2 draws in one bet.",
              'warning'
            )
          }
        },
        goback(){
          this.Getallstartingfights();
          this.select=null;
          this.live=null;
          this.randombets=false;
          this.control=null;
          this.single=null;
        },
        Getallstartingfights(){
          this.start.event_id = this.events.id;
          axios.get('/pick20/startingfightshome').then(response=>{
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
        reprint(a){
            $('#pendingbets').modal('hide');
          Swal.fire({
            title: 'Please Confirm',
            text: "Do you really want to reprint this?",
            icon: 'warning',
            showCancelButton: true,
            color:'black',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            input: 'password',
            inputLabel: 'Pin of supervisor',
            inputAttributes: {
                maxlength: 4,
            },
            inputValidator: (value) => {
              if (!value) {
                return 'You need the pin of your supervisor!';
              }
            }
          }).then((result) => {
            if (result.isConfirmed) {
              // this.loading=true;
              this.goback();
              this.fight.id=a.id;
              this.fight.pin=result.value;
              $('#pendingbets').modal('hide');
              this.fight.post('/pick20/reprint').then(response=>{
                if (response.data.error) {
                  Swal.fire(
                    'error',
                    response.data.error,
                    'error'
                  )
                  this.receipt=null;
                }else {
                  this.receipt=response.data;

                }

              }).then(()=>{
                $('#pendingbets').modal('show');
                if (this.receipt) {
                  this.$htmlToPaper('printMe2');
                }
              }).catch(()=>{
                this.loading=false;
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
          this.getuser();
          this.totalamount = 1;
          this.disabled=true;
          // this.totalamount = this.selected.forEach(this.printArray);
          //alexies.com
          if (this.fight.pick==2||this.fight.pick==3||this.fight.pick==4||this.fight.pick==5||this.fight.pick==6||this.fight.pick==8||this.fight.pick==14) {
            this.selected.forEach((val)=>{
              this.totalamount = parseInt(this.fight.amount) ;
              val.amount = parseInt(this.fight.amount) ;
            });
          }else {
            this.selected.forEach((val)=>{
              this.totalamount = parseInt(this.totalamount) * parseInt(val.amount) ;
            });
          }

          console.log(this.totalamount);
          if (this.totalamount) {
            this.totalamount = this.totalamount * parseInt(this.controls.amount);
            this.selected.forEach((val)=>{
              val.finalamount = this.totalamount;
            });
            this.disabled=false;
            if (this.totalamount<=parseInt(this.users.cash.slice(0,-4)) || this.users.role===9) {
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
	           this.getuser();
            if (response.data.error) {
              this.receipt=response.data;
	             this.refreshmoney=true;
              Swal.fire(
                'Ooops!',
                response.data.error,
                'error'
              );
              this.disabled=false;
                $('#confirmation2').modal('hide');
              // this.goback();
            }else {
              if (this.fight.pick==2||this.fight.pick==3||this.fight.pick==4||this.fight.pick==5||this.fight.pick==6||this.fight.pick==8||this.fight.pick==14) {
                this.goback();
              }
              this.disabled=false;
              this.receipt=response.data;

              this.customize=null;
              this.fight.start=null;
              this.randombets=false;
              this.rebet=1;

              this.refreshmoney=true;

              $('#confirmation2').modal('hide');
              if (this.receipt[0].cash<100) {
	      this.refreshmoney=true;
                Swal.fire(
                  'Success!',
                  'Your Pick has been entered. but you have now 0 balance so you cannot bet further.',
                  'success'
                )
              }else if(this.receipt[0].cash>=100) {
	      this.refreshmoney=true;
                Swal.fire(
                  'Success!',
                  'Your Pick has been entered.',
                  'success'
                )
              }

              this.selected=[];
            }
          })
          .then(()=>{
            if (this.user.role===9) {
                  if (!this.receipt.error) {
                    this.$htmlToPaper('printMe');
                  }
                }
            this.loading=false;
          }).catch(error =>{
            $('#confirmation2').modal('hide');
            this.loading=false;
            if (this.user.role===3) {
              Swal.fire(
                'error',
                'Please make sure that all fightnumbers has a bet!',
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
                if (this.user.role==9) {
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
          if (this.fight.pick==2) {
            this.fight.amount=1;
            this.jackpot=this.events.jackpot+this.events.addtojackpot;
            this.fight.start=this.select;
            this.live=true;
            this.rebet=null;
            if (this.randombets) {
              // ito random
              this.loading=true;
              this.fight.post('/pick20/randompick2').then(response=>{
                // this.customize=1;
                if (response.data.error) {
                  this.goback();
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                }else {

                  this.selected=response.data;
                }
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Please select fightnumber first'
                });
              });
            }else {
              // ito hnd random
              this.loading=true;
              this.fight.post('/pick20/selectionpick2').then(response=>{
                if (response.data.error) {
                  this.goback();
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                }else {
                  this.customize=1;
                  this.selected=response.data;
                }
                this.loading=false;
                // this.selected=response.data;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }else {
            this.jackpot=this.events.jackpot+this.events.addtojackpot;
            this.fight.start=this.select;
            this.live=true;
            this.rebet=null;
            if (this.randombets) {
              // ito random
              this.loading=true;
              this.fight.post('/pick20/randompick').then(response=>{
                // this.customize=1;
                if (response.data.error) {
                  this.goback();
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                }else {

                  this.selected=response.data;
                }
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Please select fightnumber first'
                });
              });
            }else {
              // ito hnd random
              this.loading=true;
              this.fight.post('/pick20/selection').then(response=>{
                if (response.data.error) {
                  this.goback();
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                }else {
                  this.customize=1;
                  this.selected=response.data;
                }
                this.loading=false;
                // this.selected=response.data;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }

        },
        placebet(t){
          this.getuser();
          this.getderby();
          this.rebet=null;
          this.select=t.startingfight;
          this.fight.start=t.startingfight;
          this.fight.id=t.id;
          this.fight.pick = t.pick;
          this.actualevent.fill(t);
          this.getodds();
          this.live=true;
          this.control=t.control;
          // this.jackpot=parseFloat(this.events.addtojackpot).toFixed(2)+parseFloat(this.events.jackpot).toFixed(2);
          console.log(t.startingfight)
          // pick 2
          if (t.pick==2) {
            this.fight.amount=1;
            if (this.randombets) {
              // ito random

              this.fight.start=t.startingfight;
                this.loading=true;
              this.fight.post('/pick20/randompick2').then(response=>{
                Toast.fire({
                  icon: 'info',
                  title: 'Choose Red Apple or White Apple for each draw'
                });
                this.possiblepayout();
                // this.customize=1;
                if (response.data.error) {
                  this.goback();
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                }else {
                  this.selected=response.data;
                }
                this.loading=false;
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Please select fightnumber first'
                });
              });
            }else {
              // ito hnd random
                this.fight.start=t.startingfight;
                this.loading=true;
              this.fight.post('/pick20/selectionpick2').then(response=>{
                Toast.fire({
                  icon: 'info',
                  title: 'Choose Red Apple or White Apple for each draw'
                });

                if (response.data.error) {
                  this.goback();
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                }else {
                  this.customize=1;
                  this.selected=response.data;
                    this.possiblepayout();
                }
                this.loading=false;
                // this.selected=response.data;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            } //ito
          }
          // end of pick2
          // pick 3
          else if (t.pick==3) {
              this.fight.amount=1;
              if (this.randombets) {
                // ito random
                this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/randompick3').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });
                  this.possiblepayout();
                  // this.customize=1;
                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.selected=response.data;
                  }
                  this.loading=false;
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Please select fightnumber first'
                  });
                });
              }else {
                // ito hnd random
                  this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/selectionpick3').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });
                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.customize=1;
                    this.selected=response.data;
                      this.possiblepayout();
                  }
                  this.loading=false;
                  // this.selected=response.data;
                  // Toast.fire({
                  //         icon: 'warning',
                  //         title: 'Please Confirm'
                  //       });
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Theres no current event'
                  });
                });
              }
          }
          // end of pick 3
          // pick 4
          else if (t.pick==4) {
              this.fight.amount=1;
              if (this.randombets) {
                // ito random
                this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/randompick4').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });
                  this.possiblepayout();
                  // this.customize=1;
                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.selected=response.data;
                  }
                  this.loading=false;
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Please select fightnumber first'
                  });
                });
              }else {
                // ito hnd random
                  this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/selectionpick4').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });

                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.customize=1;
                    this.selected=response.data;
                      this.possiblepayout();
                  }
                  this.loading=false;
                  // this.selected=response.data;
                  // Toast.fire({
                  //         icon: 'warning',
                  //         title: 'Please Confirm'
                  //       });
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Theres no current event'
                  });
                });
              }
          }
          // end of pick 4
          // pick 5
          else if (t.pick==5) {
              this.fight.amount=1;
              if (this.randombets) {
                // ito random
                this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/randompick5').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });
                  this.possiblepayout();
                  // this.customize=1;
                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.selected=response.data;
                  }
                  this.loading=false;
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Please select fightnumber first'
                  });
                });
              }else {
                // ito hnd random
                  this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/selectionpick5').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });

                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.customize=1;
                    this.selected=response.data;
                      this.possiblepayout();
                  }
                  this.loading=false;
                  // this.selected=response.data;
                  // Toast.fire({
                  //         icon: 'warning',
                  //         title: 'Please Confirm'
                  //       });
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Theres no current event'
                  });
                });
              }
          }
          // end of pick 5
          // pick 6
          else if (t.pick==6) {
              this.fight.amount=1;
              if (this.randombets) {
                // ito random
                this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/randompick6').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });
                  this.possiblepayout();
                  // this.customize=1;
                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.selected=response.data;
                  }
                  this.loading=false;
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Please select fightnumber first'
                  });
                });
              }else {
                // ito hnd random
                  this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/selectionpick6').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });

                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.customize=1;
                    this.selected=response.data;
                      this.possiblepayout();
                  }
                  this.loading=false;
                  // this.selected=response.data;
                  // Toast.fire({
                  //         icon: 'warning',
                  //         title: 'Please Confirm'
                  //       });
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Theres no current event'
                  });
                });
              }
          }
          // end of pick 6
          // pick 8
          else if (t.pick==8) {
              this.fight.amount=1;
              if (this.randombets) {
                // ito random
                this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/randompick8').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });
                  this.possiblepayout();
                  // this.customize=1;
                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.selected=response.data;
                  }
                  this.loading=false;
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Please select fightnumber first'
                  });
                });
              }else {
                // ito hnd random
                  this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/selectionpick8').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });

                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.customize=1;
                    this.selected=response.data;
                      this.possiblepayout();
                  }
                  this.loading=false;
                  // this.selected=response.data;
                  // Toast.fire({
                  //         icon: 'warning',
                  //         title: 'Please Confirm'
                  //       });
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Theres no current event'
                  });
                });
              }
          }
          // end of pick 8
          // pick 14
          else if (t.pick==14) {
              this.fight.amount=1;
              if (this.randombets) {
                // ito random
                this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/randompick14').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });
                  this.possiblepayout();
                  // this.customize=1;
                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.selected=response.data;
                  }
                  this.loading=false;
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Please select fightnumber first'
                  });
                });
              }else {
                // ito hnd random
                  this.fight.start=t.startingfight;
                  this.loading=true;
                this.fight.post('/pick20/selectionpick14').then(response=>{
                  Toast.fire({
                    icon: 'info',
                    title: 'Choose Red Apple or White Apple for each draw'
                  });

                  if (response.data.error) {
                    this.goback();
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.customize=1;
                    this.selected=response.data;
                      this.possiblepayout();
                  }
                  this.loading=false;
                  // this.selected=response.data;
                  // Toast.fire({
                  //         icon: 'warning',
                  //         title: 'Please Confirm'
                  //       });
                }).catch(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'warning',
                    title: 'Theres no current event'
                  });
                });
              }
          }
          // end of pick 14
          // pick20
          else {
            if (this.randombets) {
              // ito random
              this.fight.start=t.startingfight;
                this.loading=true;
              this.fight.post('/pick20/randompick').then(response=>{
                Toast.fire({
                  icon: 'info',
                  title: 'Choose Red Apple or White Apple for each draw'
                });
                // this.customize=1;
                if (response.data.error) {
                  this.goback();
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                }else {
                  this.selected=response.data;
                }
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Please select fightnumber first'
                });
              });
            }else {
              // ito hnd random
                this.fight.start=t.startingfight;
                this.loading=true;
              this.fight.post('/pick20/selection').then(response=>{
                Toast.fire({
                  icon: 'info',
                  title: 'Choose Red Apple or White Apple for each draw'
                });
                if (response.data.error) {
                  this.goback();
                  Swal.fire(
                    'Ooops!',
                    response.data.error,
                    'error'
                  );
                }else {
                  this.customize=1;
                  this.selected=response.data;
                }
                this.loading=false;
                // this.selected=response.data;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }
          // end of pick 20
        },
        randomplacebet(){
          this.jackpot=this.events.jackpot+this.events.addtojackpot;
          this.rebet=null;
          this.fight.start=this.select;
          // pick2
          if (this.fight.pick==2) {
            if (this.randombets) {
              // ito random
              this.fight.start=this.select;
              this.loading=true;
              this.fight.post('/pick20/randompick2').then(response=>{
                // this.customize=1;
                this.selected=response.data;
                this.loading=false;
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
                this.loading=true;
              this.fight.post('/pick20/selectionpick2').then(response=>{
                this.customize=1;
                this.selected=response.data;
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }
          // end of pick 2
          // pick 3
          else if (this.fight.pick==3) {
            if (this.randombets) {
              // ito random
              this.fight.start=this.select;
              this.loading=true;
              this.fight.post('/pick20/randompick3').then(response=>{
                // this.customize=1;
                this.selected=response.data;
                this.loading=false;
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
                this.loading=true;
              this.fight.post('/pick20/selectionpick3').then(response=>{
                this.customize=1;
                this.selected=response.data;
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }
          // end of pick 3
          // pick 5
          else if (this.fight.pick==5) {
            if (this.randombets) {
              // ito random
              this.fight.start=this.select;
              this.loading=true;
              this.fight.post('/pick20/randompick5').then(response=>{
                // this.customize=1;
                this.selected=response.data;
                this.loading=false;
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
                this.loading=true;
              this.fight.post('/pick20/selectionpick5').then(response=>{
                this.customize=1;
                this.selected=response.data;
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }
          // end of pick 5
          // pick 6
          else if (this.fight.pick==6) {
            if (this.randombets) {
              // ito random
              this.fight.start=this.select;
              this.loading=true;
              this.fight.post('/pick20/randompick6').then(response=>{
                // this.customize=1;
                this.selected=response.data;
                this.loading=false;
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
                this.loading=true;
              this.fight.post('/pick20/selectionpick6').then(response=>{
                this.customize=1;
                this.selected=response.data;
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }
          // end of pick 6
          // pick 8
          else if (this.fight.pick==8) {
            if (this.randombets) {
              // ito random
              this.fight.start=this.select;
              this.loading=true;
              this.fight.post('/pick20/randompick8').then(response=>{
                // this.customize=1;
                this.selected=response.data;
                this.loading=false;
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
                this.loading=true;
              this.fight.post('/pick20/selectionpick8').then(response=>{
                this.customize=1;
                this.selected=response.data;
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }
          // end of pick 8
          // pick 14
          else if (this.fight.pick==14) {
            if (this.randombets) {
              // ito random
              this.fight.start=this.select;
              this.loading=true;
              this.fight.post('/pick20/randompick14').then(response=>{
                // this.customize=1;
                this.selected=response.data;
                this.loading=false;
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
                this.loading=true;
              this.fight.post('/pick20/selectionpick14').then(response=>{
                this.customize=1;
                this.selected=response.data;
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }
          // end of pick 14
          else {
            if (this.randombets) {
              // ito random
              this.fight.start=this.select;
              this.loading=true;
              this.fight.post('/pick20/randompick').then(response=>{
                // this.customize=1;
                this.selected=response.data;
                this.loading=false;
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
                this.loading=true;
              this.fight.post('/pick20/selection').then(response=>{
                this.customize=1;
                this.selected=response.data;
                this.loading=false;
                // Toast.fire({
                //         icon: 'warning',
                //         title: 'Please Confirm'
                //       });
              }).catch(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'warning',
                  title: 'Theres no current event'
                });
              });
            }
          }
          // end of pick 3
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
              Toast.fire({
                      icon: 'info',
                      html: "<center>Jackpot for pick20 is <br> <b>"+Number(this.events.jackpot).toLocaleString()+"</b> <br>You can check results/winners on the leaderboards tab.</center>",
                      showCloseButton: true,
                    });

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
            if (response.data.lock==1) {
              window.location.reload();
            }
          this.users=response.data;
          if (this.user.cash<100) {
            Toast.fire({
                    icon: 'warning',
                    title: 'You have 0 balance on your account. you cannot bet with 0 balance !',
		    showCloseButton: true,
                  });
          }
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
                Echo.private('announce')
                .listen('announcement',(event)=>{
                  // console.log('announce')
                  this.getcontrol();
                });
          Echo.private('insert_bet')
          .listen('betevent',(event)=>{
            console.log(event);
            if (event.startingfight===this.select&&this.fight.id===event.id) {
              if (this.fight.pick==5||this.fight.pick==6||this.fight.pick==8||this.fight.pick==14) {
                this.odds = this.odds + event.bet;
                this.possiblepayout();
              }else {
                this.odds = this.odds + event.bet;
              }
            }

            this.startings.forEach((val)=>{
              if (val.id==event.id && event.user!=this.user.id) {
                val.potmoney_sum_amount = val.potmoney_sum_amount  + parseInt(event.bet) ;
                if (this.fight.id==event.id) {
                  if (this.fight.pick==5||this.fight.pick==6||this.fight.pick==8||this.fight.pick==14) {
                    this.possiblepayout();
                  }else {
                    this.possible = event.alldata;
                  }
                }
                // alexlang
              }
            });
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
              }else if(event.events.control==='Closed' && this.fight.id===event.events.id && this.select===event.events.startingfight && this.fight.pick===event.events.pick) {
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
