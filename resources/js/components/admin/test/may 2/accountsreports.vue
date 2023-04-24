
<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  List of Events
                </div>
                <div class="card-body table-responsive" style="">
                  <v-select v-model="search.event_name" class="col-sm-12" :options="allevents" placeholder="Choose Event" :reduce="event_name => event_name.event_name" id="user" label="event_name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                  <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='searchevent'>Search Event</a><a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='geteventsreports'>Clear Search</a>
                  <table class="table table-hover table-stripped table-bordered">
                    <thead>
                      <tr class="bg-dark text-white">
                        <th class="font-weight-bold">ID</th>
                        <th class="font-weight-bold">Event Name</th>
                        <th class="font-weight-bold">Fights</th>
                        <!-- <th class="font-weight-bold">Rake</th> -->
                        <th class="font-weight-bold">Status</th>
                        <th class="font-weight-bold">Created Date</th>
                        <th class="font-weight-bold">Date Opened</th>
                        <th class="font-weight-bold">Date Closed</th>
                        <th class="font-weight-bold">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in events.data">
                        <td>{{t.id}}</td>
                        <td>{{t.event_name}}</td>
                        <td>{{t.fights}}</td>
                        <!-- <td>{{t.rake}}</td> -->
                        <td> <a v-if="t.status===1" class="text-success">On Going</a><a v-if="t.status===2" class="text-info">Finished</a><a v-if="t.status===0" class="text-danger">Pending</a> </td>
                        <td>{{t.created_at|datef}}</td>
                        <td><a v-if="t.fightopened">{{t.fightopened|datef}}</a> <a v-else> - </a> </td>
                        <td><a v-if="t.fightclosed">{{t.fightclosed|datef}}</a><a v-else> - </a> </td>
                        <td>
                          <a class="btn btn-sm btn-success text-white" @click.prevent='accountreportsmodal(t)'>Account Reports</a><br>
                          <a class="btn btn-sm btn-info text-white" @click.prevent='arenareportsmodal(t)'>Arena Reports</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> refresh the page to update
                  </div>
                  <center>
                  <pagination :data="events" :show-disabled=true :limit='5' @pagination-change-page="geteventsreports" class="justify-content-center">
                      <span slot="prev-nav">&lt; Previous</span>
                      <span slot="next-nav">Next &gt;</span>
                  </pagination>  </center>
                </div>
              </div>
              <!-- ACCOUNT REPORTS -->
              <div class="modal modal1 fade" id="reportsmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Account Reports</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1 table-responsive" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Event Name</th>
                            <th class="font-weight-bold">Arena</th>
                            <th class="font-weight-bold">Date Created</th>
                            <th class="font-weight-bold">Date Opened</th>
                            <th class="font-weight-bold">Date Closed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>{{form.event_name}}</td>
                            <td>{{form.venue}}</td>
                            <td>{{form.created_at|datef}}</td>
                            <td>{{form.fightopened|datef}}</td>
                            <td><a v-if="form.fightclosed">{{form.fightclosed|datef}}</a><a v-else>-</a> </td>
                          </tr>
                        </tbody>
                        <thead>

                          <tr class="">
                            <th class="font-weight-bold">Total Number of Bets</th>
                            <th class="font-weight-bold">Total Amount of Bets</th>
                            <th class="font-weight-bold">Total Income</th>
                            <th class="font-weight-bold">Total Payout</th>
                            <th class="font-weight-bold">Total Payout Unclaimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td>{{t.numberofbets}}</td>
                            <td>{{Number(t.totalbetsamountfinal).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.rakefinal).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.totalpayoutunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr>
                            <th class="font-weight-bold text-center text-warning bg-dark" colspan="5">List of Users</th>
                          </tr>
                          <tr>
                            <th colspan="5">
                                <v-select v-model="search.username" class="col-sm-12" :options="searchusers" placeholder="Choose Username" :reduce="username => username.username" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                                 <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='searchuser'>Search</a><a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='acountsreportsmodalpage'>Clear Search</a> </center>
                            </th>
                          </tr>
                          <tr class="">
                            <th class="font-weight-bold">Teller/Mobile</th>
                            <th class="font-weight-bold">Bet Total</th>
                            <th class="font-weight-bold">Rake</th>
                            <th class="font-weight-bold">Payout Paid</th>
                            <th class="font-weight-bold">Unclaimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in users.data">
                            <td><a href="#" @click.prevent='showdetailedbet(t)' class="font-weight-bold text-info">{{t.username}}</a></td>
                            <td>{{Number(t.totalbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.totalpayoutpaid).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.totalunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                      </table>
                      <pagination :data="users" :show-disabled=true :limit='5' @pagination-change-page="acountsreportsmodalpage" class="justify-content-center">
                        <span slot="prev-nav">&lt; Previous</span>
                        <span slot="next-nav">Next &gt;</span>
                      </pagination>
                    </div>
                    <div class="modal-footer">
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="users.data"
                        :fields="usersfields"
                        worksheet="My Worksheet"
                        name="list_of_Users.xls"
                      >
                        Download Excel
                      </download-excel>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ACCOUNT DETAILED REPORTS -->
              <div class="modal modal1 fade" id="detailedbets" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold">Detailed Bets Report</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1 justify-content-center table-responsive" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="bg-white text-dark">
                            <th class="font-weight-bold" colspan="4">Arena Name</th>
                            <th class="font-weight-bold" colspan="4">Event Name</th>
                            <th class="font-weight-bold" colspan="4">Event Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="4">{{form.venue}}</td>
                            <td colspan="4">{{form.event_name}}</td>
                            <td colspan="4">{{form.fightdate|datef}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="bg-white text-dark">
                            <th colspan="2" class="font-weight-bold">Total Bets</th>
                            <th colspan="3" class="font-weight-bold">Total Amount Bets</th>
                            <th colspan="2" class="font-weight-bold">Total Income</th>
                            <th colspan="2" class="font-weight-bold">Total Payout</th>
                            <th colspan="3" class="font-weight-bold">Total Unclaimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="2"><a class="text-success" v-if="bets.total>0">{{bets.total}}</a> <a v-else class="text-danger">{{bets.total}}</a> </td>
                            <td colspan="3"><a v-if="detailedbets.totalbets>0" class="text-success">{{Number(detailedbets.totalbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                             <a v-else class="text-danger">{{Number(detailedbets.totalbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> </td>
                            <td colspan="2"><a class="text-success" v-if="detailedbets.totalrake>0">{{Number(detailedbets.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a class="text-danger" v-else>{{Number(detailedbets.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> </td>
                            <td colspan="2"><a v-if="detailedbets.totalpayoutpaid>0" class="text-success">{{Number(detailedbets.totalpayoutpaid).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                             <a v-else class="text-danger">{{Number(detailedbets.totalpayoutpaid).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> </td>
                            <td colspan="3"><a v-if="detailedbets.totalunclaimed" class="text-success">{{Number(detailedbets.totalunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                             <a v-else class="text-danger">{{Number(detailedbets.totalunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> </td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="bg-dark text-warning">
                            <th colspan="12" class="font-weight-bold text-center">List of bets</th>
                          </tr>
                          <tr>
                            <th class="font-weight-bold">Bet ID</th>
                            <th class="font-weight-bold">Username</th>
                            <th class="font-weight-bold">Media</th>
                            <th class="font-weight-bold">Starting fight #</th>
                            <th class="font-weight-bold">Bet</th>
                            <th class="font-weight-bold">Amount</th>
                            <th class="font-weight-bold">Rake</th>
                            <th class="font-weight-bold">Result</th>
                            <th class="font-weight-bold">Wins</th>
                            <th class="font-weight-bold">Claimed</th>
                            <th class="font-weight-bold">Date Processed</th>
                            <th class="font-weight-bold">Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in bets.data">
                            <td>{{t.id}}</td>
                            <td>{{t.username}}</td>
                            <td><p v-if="t.role===0">Teller</p><p v-if="t.role===3">Mobile Player</p></td>
                            <td>{{t.startingfight}}</td>
                            <td>{{t.bet}}</td>
                            <td>{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.income).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td><a v-if="t.winner===1||t.winner===2" class="text-success font-weight-bold">{{Number(t.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-if="t.winner===3" class="text-danger font-weight-bold">Loss</a>
                              <a v-if="t.winner===0" class="text-danger font-weight-bold">Pending</a></td>
                            <!-- <td>{{Number(t.income).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td> -->
                            <td>{{t.wins}}</td>
                            <td> <p v-if="t.claimed===1">Claimed</p><p v-else-if="t.claimed===null&&t.winner===1||t.winner===2">Not Claimed</p> <p v-if="t.winner===3">-</p> </td>
                            <td>{{t.created|datef}}</td>
                            <td>{{t.created |datef}}</td>
                          </tr>
                        </tbody>
                      </table>
                      <pagination :data="bets" :show-disabled=true :limit='5' @pagination-change-page="showdetailedbetpage" class="justify-content-center">
                        <span slot="prev-nav">&lt; Previous</span>
                        <span slot="next-nav">Next &gt;</span>
                      </pagination>
                    </div>
                    <div class="modal-footer">
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="bets.data"
                        :fields="betsfields"
                        worksheet="My Worksheet"
                        name="list_of_Bets.xls"
                      >
                        Download Excel
                      </download-excel>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ARENA REPORTS -->
              <div class="modal modal1 fade" id="arenareportsmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold">Fight List</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1 table-responsive" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Event Name</th>
                            <th class="font-weight-bold">Arena</th>
                            <th class="font-weight-bold">Fights</th>
                            <th class="font-weight-bold">Event Date</th>
                            <th class="font-weight-bold">Fight Opened</th>
                            <th class="font-weight-bold" colspan="2">Fight Closed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>{{form.event_name}}</td>
                            <td>{{form.venue}}</td>
                            <td>{{form.fights}}</td>
                            <td>{{form.fightdate|datef}}</td>
                            <td>{{form.fightopened|datef}}</td>
                            <td colspan="2"><a v-if="form.fightclosed">{{form.fightclosed|datef}}</a> <a v-else>-</a> </td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Total Number of Bets</th>
                            <th class="font-weight-bold">Total Amount Bets</th>
                            <th class="font-weight-bold" colspan="2">Total Income</th>
                            <th class="font-weight-bold">Total Payout</th>
                            <th class="font-weight-bold" colspan="2">Total Payout Unclaimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td >{{t.numberofbets}}</td>
                            <td class="text-success">{{Number(t.totalbetsamountfinal).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="2" class="text-success">{{Number(t.rakefinal).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td class="text-danger">{{Number(t.totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="2" class="text-success">{{Number(t.totalpayoutunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Office Rake</th>
                            <th class="font-weight-bold">Total Contingency funds</th>
                            <th class="font-weight-bold" colspan="2">Total Current Contingency Funds</th>
                            <th class="font-weight-bold">Breakage</th>
                            <th class="font-weight-bold" colspan="2">Total Payout Paid</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td class="text-success">{{Number(t.officerake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td class="text-success">{{Number(t.totalcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="2" class="text-success">{{Number(t.currentcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td class="text-success">{{Number(t.breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="2" class="text-danger">{{Number(t.totalpayoutpaid).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="bg-dark text-warning ">
                            <th colspan="7" class="text-center font-weight-bold">Starting fights</th>
                          </tr>
                        </thead>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Starting fight number</th>
                            <th class="font-weight-bold">No. of Winners</th>
                            <th class="font-weight-bold">No. of Bets</th>
                            <th class="font-weight-bold">No. of Players</th>
                            <th class="font-weight-bold">Status</th>
                            <th class="font-weight-bold">Date Created</th>
                            <th class="font-weight-bold">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareports">
                            <td class="font-weight-bold">{{t.startingfight}}</td>
                            <td>{{t.winners}}</td>
                            <td>{{t.bet}}</td>
                            <td>{{t.totalplayers}}</td>
                            <td><p v-if="t.claim===0">Pending</p><p v-if="t.claim===2||t.claim===1">Finished</p></td>
                            <td>{{t.created_at|datef}}</td>
                            <td><a class="btn btn-sm btn-success text-white col-sm-12" @click.prevent='betsofarenareports(t)'>View Bets</a></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="arenareports"
                        :fields="arenareportsfields"
                        worksheet="My Worksheet"
                        name="arena_reports.xls"
                      >
                        Download Excel
                      </download-excel>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ARENA BETS REPORTS -->
              <div class="modal modal1 fade" id="arenabetsreportsmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold">Bet List</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1 table-responsive" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <!-- <thead>
                          <tr class="">
                            <th colspan="3" class="font-weight-bold">Event Name</th>
                            <th colspan="2" class="font-weight-bold">Arena</th>
                            <th colspan="2" class="font-weight-bold">Event Date</th>
                            <th colspan="2" class="font-weight-bold">Fight Opened</th>
                            <th colspan="2" class="font-weight-bold">Fight Closed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="3">{{form.event_name}}</td>
                            <td colspan="2">{{form.venue}}</td>
                            <td colspan="2">{{form.fightdate|datef}}</td>
                            <td colspan="2">{{form.fightopened|datef}}</td>
                            <td colspan="2"><a v-if="form.fightclosed">{{form.fightclosed|datef}}</a> <a v-else>-</a> </td>
                          </tr>
                        </tbody> -->
                        <thead>
                          <tr class="">
                            <th colspan="4" class="font-weight-bold">Total Bet Amount</th>
                            <th colspan="4" class="font-weight-bold">Total Office Rake</th>
                            <th colspan="4" class="font-weight-bold">Total Contingency Funds</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in totalarenareports">
                            <td colspan="4">{{Number(t.totalamount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="4">{{Number(t.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="4">{{Number(t.cleanfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                      </tbody>
                        <thead>
                          <tr class="bg-dark text-warning">
                            <th class="font-weight-bold text-center" colspan="11">List of Bets</th>
                          </tr>
                          <tr>
                            <th colspan="11">
                              <v-select v-model="form.username" class="col-sm-12" :options="searchusers" placeholder="Choose Username" :reduce="username => username.username" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                                <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='betsofarenareportspage'>Search User</a><a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='betsofarenareportspageclear'>Clear Search</a>
                            </th>
                          </tr>
                          <tr class="">
                            <th class="font-weight-bold">Bet ID</th>
                            <th class="font-weight-bold">Barcode</th>
                            <th class="font-weight-bold">Media</th>
                            <th class="font-weight-bold">Cashier/Mobile Player</th>
                            <th class="font-weight-bold">Amount</th>
                            <th class="font-weight-bold">Status</th>
                            <th class="font-weight-bold">Income</th>
                            <th class="font-weight-bold">Payout</th>
                            <th class="font-weight-bold">Claimed</th>
                            <th class="font-weight-bold">Wins</th>
                            <th class="font-weight-bold">Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in betsarenareports.data">
                            <td>{{t.id}}</td>
                            <td><a v-if="t.role===3">Mobile Player</a><a v-else>{{t.barcode}}</a></td>
                            <td><a v-if="t.role===3">Mobile Player</a><a v-if="t.role===0">Teller</a></td>
                            <td>{{t.username}}</td>
                            <td>{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td><a v-if="t.winner===0" class="text-danger">Pending</a><a v-if="t.winner===1||t.winner===2" class="text-success">Winner</a><a v-if="t.winner===3" class="text-danger">Loss</a></td>
                            <td>{{Number(t.income).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td> <p v-if="t.claimed===1">Claimed</p><p v-else-if="t.claimed===null&&t.winner===1||t.winner===2">Not Claimed</p> <p v-if="t.winner===3||t.winner===0">-</p></td>
                            <td>{{t.wins}}</td>
                            <td>{{t.created_at|datef}}</td>
                          </tr>
                        </tbody>

                      </table>
                      <center>
                      <pagination :data="betsarenareports" :show-disabled=true :limit='5' @pagination-change-page="betsofarenareportspage" class="justify-content-center">
                          <span slot="prev-nav">&lt; Previous</span>
                          <span slot="next-nav">Next &gt;</span>
                      </pagination>
                    </center>
                    </div>
                    <div class="modal-footer">
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="betsarenareports"
                        :fields="betsarenareportsfields"
                        worksheet="My Worksheet"
                        name="arena_reports.xls"
                      >
                        Download Excel
                      </download-excel>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
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
          events:{},
          users:{},
          bets:[],
          searchusers:[],
          allevents:[],
          arenareports:[],
          betsarenareports:{},
          totalarenareports:[],
          arenareportsmodaltotal:[],
          detailedbets:[],
          search:new Form({
            name:'',
            id:'',
            event_name:''
          }),
          form:new Form({
            id:'',
            event_name:'',
            fights:'',
            venue:'',
            startingfight:'',
            event_id:'',
            rake:'',
            fightdate:'',
            created_at:'',
            fightopened:'',
            fightclosed:'',
            user_id:'',
            username:''
          }),
          betsarenareportsfields: {
             'Id': 'id',
             'Barcode': 'barcode',
             'Username': 'user.username',
             'Amount': 'amount',
             'Status': 'winner',
             'Income': 'income',
             'Payout': 'result',
             'Claimed': 'calimed',
             'Date Processed': 'updated_at',
             'Date': 'created_at',
           },
          arenareportsfields: {
             'Startingfight': 'startingfight',
             'Number of Winners': 'winners',
             'Number of Bets': 'bet',
             'Status': 'Finished',
             'Date': 'created_at',
           },
          betsfields: {
             'Bet ID': 'id',
             'Username': 'user.username',
             'Startingfight': 'startingfight',
             'Bet': 'bet',
             'Income': 'income',
             'Claimed': 'claimed',
             'Date': 'created_at',
           },
          usersfields: {
             'Teller/Mobile': 'username',
             'Bet Total': 'totalbets',
             'Rake': 'income',
             'Payout Paid': 'totalpayoutpaid',
             'Unclaimed': 'totalunclaimed',
           },
        }
      },
      methods:{
        searchuser(page = 1){
          this.search.post('/pick20/searchusertransaction?page='+page).then(response=>{
            this.users = response.data;
          });
        },
        getallusers(){
          axios.get('/pick20/searchusers').then(response=>{
            this.searchusers = response.data;
          });
        },
        betsofarenareportspageclear(page = 1){
          this.form.username='',
          this.form.post('/pick20/betsofarenareports?page='+page).then(response=>{
            this.betsarenareports = response.data;
          });
        },
        betsofarenareportspage(page = 1){
          this.form.post('/pick20/betsofarenareports?page='+page).then(response=>{
            this.betsarenareports = response.data;
          });
        },
        betsofarenareports(t,page = 1){
          this.form.id=t.id;
          this.form.username=null;
          this.search.id = t.event_id;
          this.form.startingfight = t.startingfight;
          this.form.event_id = t.event_id;
          this.form.post('/pick20/betsofarenareports?page='+page).then(response=>{
            this.betsarenareports = response.data;
            this.form.post('/pick20/totalarenareports').then(response=>{
              this.totalarenareports = response.data;
              $('#arenabetsreportsmodal').modal('show');
            })
          })
        },
        arenareportsmodal(t){
            this.bets=[];
            this.loading=true;
            this.form.fill(t);
          this.form.post('/pick20/arenareportsmodal').then(response=>{
            this.loading=false;
            this.arenareports = response.data;
          this.form.fill(t);
          this.form.post('/pick20/arenareportsmodaltotal').then(response=>{
            this.arenareportsmodaltotal=response.data;
            $('#arenareportsmodal').modal('show');
          })
          })
        },
        showdetailedbet(t,page = 1){
            this.bets=[];
            this.loading=true;
            this.form.user_id = t.username;
            this.detailedbets =t;
          this.form.post('/pick20/betdetailereport?page='+page).then(response=>{
            this.loading=false;
            this.bets = response.data;
            $('#detailedbets').modal('show');

          }).catch(()=>{
            this.loading=false;
          })
        },
        showdetailedbetpage(page = 1){
            this.bets=[];
            this.loading=true;
            // this.form.user_id = t.username;
            // this.detailedbets =t;
          this.form.post('/pick20/betdetailereport?page='+page).then(response=>{
            this.loading=false;
            this.bets = response.data;
            $('#detailedbets').modal('show');

          })
        },
        accountreportsmodal(t,page = 1){
          this.form.fill(t);
          this.search.id = t.id;
          // this.search.username = '';
          this.loading=true;
          this.form.post('/pick20/eventgetusersreport?page='+page).then(response=>{
            this.loading=false;
            this.users = response.data;
            this.form.post('/pick20/arenareportsmodaltotal').then(response=>{
                this.loading=false;
              this.arenareportsmodaltotal=response.data;
              $('#reportsmodal').modal('show');
            })
          }).catch(()=>{
            this.loading=false;
          })
        },
        acountsreportsmodalpage(page = 1){
          // this.form.fill(t);
          this.loading=true;
          this.form.post('/pick20/eventgetusersreport?page='+page).then(response=>{
            this.loading=false;
            this.users = response.data;
          }).catch(()=>{
            this.loading=false;
          })
        },
        searchevent(page = 1){
          this.search.post('/pick20/searchgeteventsreports?page='+page).then(response=>{
            this.events = response.data;
          });
        },
        geteventsreports(page = 1){
          this.loading=true;
          this.search.event_name = '';
          axios.get('/pick20/geteventsreports?page='+page).then(response=>{
            this.events = response.data;
            axios.get('/pick20/getalleventsreports').then(response=>{
              this.allevents = response.data;
              this.loading=false;
            })
          })
        }
      },
      created() {
        this.geteventsreports();
        this.getallusers();
      }
    }
</script>
