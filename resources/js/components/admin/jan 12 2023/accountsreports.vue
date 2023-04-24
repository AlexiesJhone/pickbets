
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
                          <a class="btn btn-sm btn-success text-white" @click.prevent='accountreportsmodal(t)'>Account Reports</a><a class="btn btn-sm btn-success text-white" @click.prevent='groupreportsmodal(t)'>Group Reports</a><br>
                          <a class="btn btn-sm btn-info text-white" @click.prevent='arenareportsmodal(t)'>Arena Reports of Pick 20</a>
                          <a class="btn btn-sm btn-info text-white" @click.prevent='arenareportsmodal2(t)'>Arena Reports of Pick 2</a>
                          <a class="btn btn-sm btn-info text-white" @click.prevent='arenareportsmodal3(t)'>Arena Reports overall</a>
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
                            <th class="font-weight-bold" colspan="2">Date Closed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>{{form.event_name}}</td>
                            <td>{{form.venue}}</td>
                            <td>{{form.created_at|datef}}</td>
                            <td>{{form.fightopened|datef}}</td>
                            <td colspan="2"><a v-if="form.fightclosed">{{form.fightclosed|datef}}</a><a v-else>-</a> </td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Total Number of Bets</th>
                            <th class="font-weight-bold">Total Amount of Bets</th>
                            <!-- <th class="font-weight-bold">Total Income</th> -->
                            <th colspan="2" class="font-weight-bold">Total Payout</th>
                            <th class="font-weight-bold" colspan="2">Total Payout Unclaimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td>{{t.numberofbets}}</td>
                            <td class="text-success">{{Number(t.totalbetsamountfinal).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <!-- <td class="text-success">{{Number(t.rakefinal).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td> -->
                            <td colspan="2" class="text-danger">{{Number(t.totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td class="text-success" colspan="2">{{Number(t.totalpayoutunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Office Rake</th>
                            <th class="font-weight-bold">Total Contingency funds</th>
                            <th class="font-weight-bold">Total Current Contingency Funds</th>
                            <th class="font-weight-bold">Breakage</th>
                            <th class="font-weight-bold" colspan="2">Total Payout Paid</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td class="text-success">{{Number(t.officerake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td class="text-success">
                              <a v-if="t.totalcontingencyfunds<0" class="text-danger">{{Number(t.totalcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.totalcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success" >
                              <a v-if="t.currentcontingencyfunds<0" class="text-danger">{{Number(t.currentcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.currentcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success">
                              <a v-if="t.breakage<0" class="text-danger">{{Number(t.breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td colspan="2" class="text-danger">{{Number(t.totalpayoutpaid).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold"  >Total Cancelled Bets</th>
                            <th class="font-weight-bold" >Total Cancelled Bets Payout</th>
                            <th class="font-weight-bold"colspan="2">Total Cancelled Bets Unclaimed</th>
                            <th class="font-weight-bold" colspan="2">Total Cancelled Bets Claimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td class="text-success">
                              <a v-if="t.cancelledbetsamount<0" class="text-danger">{{Number(t.cancelledbetsamount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.cancelledbetsamount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success">
                              <a v-if="t.cancelledbetsamountpayout<0" class="text-danger">{{Number(t.cancelledbetsamountpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.cancelledbetsamountpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success" colspan="2" >
                              <a v-if="t.totalcancelledunclaimed<0" class="text-danger">{{Number(t.totalcancelledunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.totalcancelledunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success"  colspan="2">
                              <a v-if="t.claimedcancelled<0" class="text-danger">{{Number(t.claimedcancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-danger">{{Number(t.claimedcancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr>
                            <th class="font-weight-bold text-center text-warning bg-dark" colspan="6">List of Users</th>
                          </tr>
                          <tr>
                            <th colspan="6">
                                <v-select v-model="search.username" class="col-sm-12" :options="searchusers" placeholder="Choose Username" :reduce="username => username.username" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                                 <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='searchuser'>Search</a><a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='acountsreportsmodalpage'>Clear Search</a> </center>
                            </th>
                          </tr>
                          <tr class="">
                            <th class="font-weight-bold">Teller/Mobile</th>
                            <th class="font-weight-bold">Role</th>
                            <th class="font-weight-bold">Bet Total</th>
                            <th class="font-weight-bold">Rake</th>
                            <th class="font-weight-bold">Payout Paid</th>
                            <th class="font-weight-bold">Unclaimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in users.data">
                            <td><a href="#" @click.prevent='showdetailedbet(t)' class="font-weight-bold text-info">{{t.username}}</a></td>
                            <td> <a v-if="t.role===1">Admin</a><a v-if="t.role===9">Teller</a><a v-if="t.role===3">Mobile Player</a><a v-if="t.role===4">Cashier</a><a v-if="t.role===5">Declarator</a>
                              <a v-if="t.role===6">CSR</a><a v-if="t.role===7">Boss/Manager</a><a v-if="t.role===2">Supervisor</a><a v-if="t.role===8">Confirm Declarator</a></td>
                            <td>{{Number(t.totalbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number((Number(t.totalpick20)*0.10)+(Number(t.totalpick2)*0.05)).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
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
                        :data="dataForExcel"
                        worksheet="My Worksheet"
                        name="arena_reports.xls"
                        v-if="form.event_name"
                      >
                        Download Overall
                      </download-excel>
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="excel"
                        :fields="usersfields"
                        worksheet="My Worksheet"
                        name="list_of_Users.xls"
                        v-if="excel.length"
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
                            <td colspan="2"><a class="text-success" v-if="detailedbets.totalpick20||detailedbets.totalpick2">{{((Number(detailedbets.totalpick20)*0.10)+(Number(detailedbets.totalpick2)*0.05))}}</a>
                              <a class="text-danger" v-else>{{((Number(detailedbets.totalpick20)*0.10)+(Number(detailedbets.totalpick2)*0.05))}}</a> </td>
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
                            <th class="font-weight-bold">Starting Fight #</th>
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
                            <td><p v-if="t.role===9">Teller</p><p v-if="t.role===3">Mobile Player</p></td>
                            <td>{{t.startingfight}}</td>
                            <td>{{t.bet}}</td>
                            <td>{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>
                              <!-- {{Number(t.income).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} -->
                              <a v-if="t.turn==2">{{Number(t.amount*0.05).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-if="t.turn==20">{{Number(t.amount*0.10).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td><a v-if="t.winner===1||t.winner===2||t.winner===4" class="text-success font-weight-bold">{{Number(t.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-if="t.winner===3||t.winner===5" class="text-danger font-weight-bold">Loss</a>
                              <a v-if="t.winner===0" class="text-danger font-weight-bold">Pending</a><a v-if="t.winner===4" class="text-warning font-weight-bold">Cancelled</a></td>
                            <!-- <td>{{Number(t.income).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td> -->
                            <td>{{t.wins}}</td>
                            <td> <p v-if="t.claimed===1">Claimed</p><p v-else-if="t.claimed===null&&t.winner===1||t.winner===2||t.winner===4">Not Claimed</p> <p v-if="t.winner===3">-</p> </td>
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
                      <h5 class="modal-title text-warning font-weight-bold">{{titleheader}}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1 table-responsive" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold" colspan="2">Event Name</th>
                            <th class="font-weight-bold">Arena</th>
                            <th class="font-weight-bold">Fights</th>
                            <th class="font-weight-bold">Event Date</th>
                            <th class="font-weight-bold">Fight Opened</th>
                            <th class="font-weight-bold" colspan="2">Fight Closed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="2">{{form.event_name}}</td>
                            <td>{{form.venue}}</td>
                            <td>{{form.fights}}</td>
                            <td>{{form.fightdate|datef}}</td>
                            <td>{{form.fightopened|datef}}</td>
                            <td colspan="2"><a v-if="form.fightclosed">{{form.fightclosed|datef}}</a> <a v-else>-</a> </td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold" colspan="2">Total Number of Bets</th>
                            <th class="font-weight-bold" colspan="2">Total Amount Bets</th>
                            <!-- <th class="font-weight-bold" colspan="2">Total Income</th> -->
                            <th class="font-weight-bold" colspan="2">Total Payout</th>
                            <th class="font-weight-bold" colspan="2">Total Payout Unclaimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td colspan="2">{{t.numberofbets}}</td>
                            <td class="text-success" colspan="2">{{Number(t.totalbetsamountfinal).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <!-- <td colspan="2" class="text-success">{{Number(t.rakefinal).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td> -->
                            <td colspan="2" class="text-danger">{{Number(t.totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="2" class="text-success">{{Number(t.totalpayoutunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold" v-if="pick==20||pick=='all'"colspan="2">Office Rake</th>
                            <th class="font-weight-bold" v-if="pick==2"  colspan="2">Office Rake</th>
                            <th class="font-weight-bold" v-if="pick==2" colspan="2">Contingency Funds (No Winners)</th>
                            <th class="font-weight-bold" v-if="pick==20||pick=='all'" >Total Contingency funds</th>
                            <th class="font-weight-bold" colspan="2" v-if="pick==20||pick=='all'">Total Current Contingency Funds</th>
                            <th class="font-weight-bold" v-if="pick==20||pick=='all'" >Breakage</th>
                            <th class="font-weight-bold" v-if="pick==2" colspan="2" >Breakage</th>
                            <th class="font-weight-bold" colspan="2">Total Payout Paid</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td class="text-success" v-if="pick==20||pick=='all'"colspan="2">{{Number(t.officerake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td class="text-success" v-if="pick==2"  colspan="2">{{Number(t.officerake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td class="text-success" v-if="pick==2"  colspan="2">{{Number(t.totalcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td class="text-success" v-if="pick==20||pick=='all'">
                              <a v-if="t.totalcontingencyfunds<0" class="text-danger">{{Number(t.totalcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.totalcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td colspan="2" class="text-success" v-if="pick==20||pick=='all'">
                              <a v-if="t.currentcontingencyfunds<0" class="text-danger">{{Number(t.currentcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.currentcontingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success" v-if="pick==20||pick=='all'">
                              <a v-if="t.breakage<0" class="text-danger">{{Number(t.breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success" v-if="pick==2" colspan="2" >
                              <a v-if="t.breakage<0" class="text-danger">{{Number(t.breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td colspan="2" class="text-danger">{{Number(t.totalpayoutpaid).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold" v-if="pick==2||pick=='all'" colspan="2">Total Cancelled Bets</th>
                            <th class="font-weight-bold" v-if="pick==2||pick=='all'" colspan="2">Total Cancelled Bets Payout</th>
                            <th class="font-weight-bold" v-if="pick==2||pick=='all'" colspan="2">Total Cancelled Bets Unclaimed</th>
                            <th class="font-weight-bold" v-if="pick==2||pick=='all'" colspan="2">Total Cancelled Bets Claimed</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in arenareportsmodaltotal">
                            <td class="text-success" v-if="pick==2||pick=='all'" colspan="2">
                              <a v-if="t.cancelledbetsamount<0" class="text-danger">{{Number(t.cancelledbetsamount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.cancelledbetsamount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success" v-if="pick==2||pick=='all'" colspan="2">
                              <a v-if="t.cancelledbetsamountpayout<0" class="text-danger">{{Number(t.cancelledbetsamountpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.cancelledbetsamountpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success" v-if="pick==2||pick=='all'" colspan="2">
                              <a v-if="t.totalcancelledunclaimed<0" class="text-danger">{{Number(t.totalcancelledunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-success">{{Number(t.totalcancelledunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                            <td class="text-success" v-if="pick==2||pick=='all'" colspan="2">
                              <a v-if="t.claimedcancelled<0" class="text-danger">{{Number(t.claimedcancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else class="text-danger">{{Number(t.claimedcancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                            </td>
                          </tr>
                        </tbody>
                        <thead>
                          <tr class="bg-dark text-warning ">
                            <th colspan="8" class="text-center font-weight-bold">Starting Fights</th>
                          </tr>
                        </thead>
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Starting Fight Number</th>
                            <th class="font-weight-bold">Results</th>
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
                            <!-- alexmuna -->
                            <td class="font-weight-bold">{{t.startingfight}}</td>
                            <td >{{t.results}}</td>
                            <td>{{t.winners}}</td>
                            <td>{{t.bet}}</td>
                            <td>{{t.totalplayers}}</td>
                            <td><p v-if="t.claim===0" class="text-danger">Pending</p><p v-if="t.claim===2||t.claim===1" class="text-info">Finished</p></td>
                            <td>{{t.created_at|datef}}</td>
                            <td>
                              <a class="btn btn-sm btn-success text-white col-sm-12" @click.prevent='betsofarenareports(t)'>View Bets</a>
                            <a class="btn btn-sm btn-success text-white col-sm-12" @click.prevent='downloadallbets(t)'>Download All Bets</a>
                          </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <!-- :fields="arenareportsfields" -->
                      <!-- :header="dataForExcel" -->
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="dataForExcel"
                        worksheet="My Worksheet"
                        name="arena_reports.xls"
                        v-if="form.event_name"
                      >
                        Download Overall
                      </download-excel>
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="arenareports"
                        :fields="arenareportsfields"
                        :header="header"
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
                            <th colspan="4" class="font-weight-bold" v-if="pick==20">Total Office Rake</th>
                            <th colspan="2" class="font-weight-bold" v-if="pick==20">Total Contingency Funds</th>
                            <th colspan="2" class="font-weight-bold" v-if="pick==20">Total Net Fees</th>
                            <th colspan="4" class="font-weight-bold" v-if="pick==2">Total Office Rake</th>
                            <th colspan="4" class="font-weight-bold" v-if="pick==2">Total Net Fees</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in totalarenareports">
                            <td colspan="4">{{Number(t.totalamount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="4" v-if="pick==20">{{Number(t.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="2" v-if="pick==20">{{Number(t.cleanfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="2" v-if="pick==20">{{Number(t.netfees20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="4" v-if="pick==2">{{Number(t.rakepick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td colspan="4" v-if="pick==2">{{Number(t.netfees2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                      </tbody>
                        <thead>
                          <tr class="bg-dark text-warning">
                            <th class="font-weight-bold text-center" colspan="12">List of Bets</th>
                          </tr>
                          <tr>
                            <th colspan="12">
                              <v-select v-model="form.username" class="col-sm-12" :options="searchusers" placeholder="Choose Username" :reduce="username => username.username" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                              <v-select v-model="form.statuss" class="col-sm-12" :options="['Pending','Winner','Loss']" placeholder="Choose Status"  :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                              <!-- <select class="form-control" placeholder="Select Status" v-model="form.status">
                                <option value="1">awd</option>
                                <option value="2">dwa</option>
                              </select> -->
                                <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='betsofarenareportspage'>Search</a>
                                <a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='betsofarenareportspageclear'>Clear Search</a>
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
                            <th class="font-weight-bold">View Bets</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in betsarenareports.data">
                            <td>{{t.id}}</td>
                            <td><a v-if="t.role===3">Mobile Player</a><a v-else>{{t.barcode}}</a></td>
                            <td><a v-if="t.role===3">Mobile Player</a><a v-if="t.role===9">Teller</a></td>
                            <td>{{t.username}}</td>
                            <td>{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td><a v-if="t.winner===0" class="text-danger">Pending</a><a v-if="t.winner===1||t.winner===2" class="text-success">Winner</a><a v-if="t.winner===3||t.winner===5" class="text-danger">Loss</a>
                            <a v-if="t.winner===4" class="text-warning">Cancelled</a></td>
                            <td>{{Number(t.income).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td>{{Number(t.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td> <p v-if="t.claimed===1">Claimed</p><p v-else-if="t.claimed===null&&t.winner===1||t.winner===2">Not Claimed</p> <p v-if="t.winner===3||t.winner===0||t.winner===5">-</p></td>
                            <td>{{t.wins}}</td>
                            <td>{{t.created_at|datef}}</td>
                            <td><a class="btn btn-success btn-sm text-white" @click.prevent='showdetailedbets(t.id)'>View bets</a> </td>
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
                        :data="betsarenareports.data"
                        :header="header"
                        :fields="betsarenareportsfields"
                        worksheet="My Worksheet"
                        name="arena_reports.xls"
                      >
                        Download This Page
                      </download-excel>
                      <!-- <button type="button" class="btn btn-success btn-sm" name="button">Download All</button> -->
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- bets columns -->
        <div class="modal fade" id="detailed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content" style="border:none !important;">
              <div class="modal-header bg-dark text-white">
                <h5 class="modal-title text-warning" id="exampleModalLabel" v-if="detailed.length">Bets for starting fight number {{detailed[0].fightnumber}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body table-responsive" style="padding:0">
                <table class="table tabl-sm table-striped table-borderless table-hover">
                  <thead>
                    <tr>
                      <th><b>Fight #</b></th>
                      <th><b>Bet</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="betx in detailed" :index='index'>
                    <!-- <tr v-for="(t,index) in bets.slice().reverse()" :index='index'> -->
                      <th style="padding: 0.20rem">{{betx.fightnumber}}</th>
                      <td style="padding: 0.20rem"><b v-if="betx.selection==='Meron'" class="text-danger">{{betx.selection}}</b><b v-if="betx.selection==='Wala'" class="text-info">{{betx.selection}}</b>
                      <b v-if="betx.selection==='Draw'" class="text-success">{{betx.selection}}</b></td>
                      <!-- <td v-if="events.pick===20 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+19}}</b></td>
                      <td v-if="events.pick===15 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+14}}</b></td>
                      <td v-if="events.pick===24 "><b>{{t.selection[0].fightnumber}}</b> to <b>{{t.selection[0].fightnumber+23}}</b></td> -->
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- <div class="modal-body font-weight-bold" v-if="!bets">
                You have no pending bets yet..
              </div> -->
              <div class="modal-footer justify-content-center" >
                <button type="button" class="btn btn-secondary col-md-12" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- download all -->
        <div class="modal fade" id="downloadmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-dark text-warning ">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">{{form.event_name}} Starting Fight : {{form.startingfight}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <!-- <div class="modal-body">
                Click To Download
              </div> -->
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <download-excel
                  class="btn btn-success btn-sm"
                  :data="downloadall"
                  :fields="betsarenareportsfields"
                  worksheet="My Worksheet"
                  name="arena_reports.xls"
                >
                  Download All Bets
                </download-excel>
              </div>
            </div>
          </div>
        </div>
          <!-- end of download all -->
          <!-- group reports -->
          <div class="modal modal1 fade" id="groupmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog1" role="document">
              <div class="modal-content modal-content1">
                <div class="modal-header bg-dark">
                  <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Group Reports</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body modal-body1 table-responsive" style="padding:0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th colspan="10"><b>Event Name :</b> {{form.event_name}}</th>
                      </tr>
                      <tr>
                        <th colspan="10"><b>Arena :</b> {{form.venue}}</th>
                      </tr>
                      <tr>
                        <th colspan="10"><b>Created Date :</b> {{form.created_at|datef}}</th>
                      </tr>
                      <tr>
                        <th colspan="10"><b>Date Open :</b> {{form.fightopened|datef}}</th>
                      </tr>
                      <tr>
                        <th colspan="10"><b>Date Closed :</b> <a v-if="form.fightclosed">{{form.fightclosed|datef}}</a><a v-else> - </a> </th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <th colspan="10" class="bg-dark text-warning text-center font-weight-bold">List of Groups</th>
                      </tr>
                      <tr>
                        <th colspan="10">
                          <v-select v-model="form.searchgroup" class="col-sm-12" :options="groupssearch.data" placeholder="Choose Group" :reduce="name => name.name" id="user" label="name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                            <a class="btn btn-sm btn-success col-sm-12 text-white" @click.prevent='searchgroup'>Search</a><a class="btn btn-sm btn-default col-sm-12 text-white" @click.prevent='clearsearchgroup'>Clear Search</a> </center>
                          </th>
                        </tr>
                      <tr>
                        <th class="font-weight-bold">ID</th>
                        <th class="font-weight-bold">Group Name</th>
                        <th class="font-weight-bold">Pick 20 Bets</th>
                        <th class="font-weight-bold">Pick 20 Rake</th>
                        <th class="font-weight-bold">Pick 2 Bets</th>
                        <th class="font-weight-bold">Pick 2 Rake</th>
                        <th class="font-weight-bold">Total Bets</th>
                        <th class="font-weight-bold">Total Rake</th>
                        <th class="font-weight-bold">Payout Paid</th>
                        <th class="font-weight-bold">Payout Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in groups.data" >
                        <td>{{t.id}}</td>
                        <td><a @click.prevent='getallusergroup(t)' class="btn btn-sm btn-success text-white">{{t.name}}</a></td>
                        <td><a v-if="t.totalpick20">{{Number(t.totalpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a> </td>
                        <td><a v-if="t.totalrakepick20">{{Number(t.totalrakepick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a></td>
                        <td><a v-if="t.totalpick2">{{Number(t.totalpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a></td>
                        <td><a v-if="t.totalrakepick2">{{Number(t.totalrakepick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a></td>
                        <td><a v-if="t.totalbets">{{Number(t.totalbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a></td>
                        <td><a v-if="t.totalrake">{{Number(t.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a></td>
                        <td><a v-if="t.payoutpaid">{{Number(t.payoutpaid).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a></td>
                        <td><a v-if="t.payoutunclaimed">{{Number(t.payoutunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <download-excel
                  class="btn btn-success"
                  :data="groups.data"
                  :header="myArr"
                  :fields="transfields"
                  worksheet="My Worksheet"
                  name="Pickbetmonitoring.xls"
                  >
                  Download Group Reports
                </download-excel>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
              </div>
            </div>
          </div>
          <!-- end of group reports -->
          <!-- group users -->
          <div class="modal modal1 fade" id="groupmodalusers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog1" role="document">
              <div class="modal-content modal-content1">
                <div class="modal-header bg-dark">
                  <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Group Reports</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body modal-body1 table-responsive" style="padding:0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th colspan="10"><b>Event Name :</b> {{form.event_name}}</th>
                      </tr>
                      <tr>
                        <th colspan="10"><b>Arena :</b> {{form.venue}}</th>
                      </tr>
                      <tr>
                        <th colspan="10"><b>Created Date :</b> {{form.created_at|datef}}</th>
                      </tr>
                      <tr>
                        <th colspan="10"><b>Date Open :</b> {{form.fightopened|datef}}</th>
                      </tr>
                      <tr>
                        <th colspan="10"><b>Date Closed :</b> <a v-if="form.fightclosed">{{form.fightclosed|datef}}</a><a v-else> - </a> </th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <th colspan="10" class="bg-dark text-warning text-center font-weight-bold">List of Users</th>
                      </tr>
                      <tr>
                        <th class="font-weight-bold">ID</th>
                        <th class="font-weight-bold">Teller/Mobile</th>
                        <th class="font-weight-bold">Pick 20 Bets</th>
                        <th class="font-weight-bold">Pick 20 Rake</th>
                        <th class="font-weight-bold">Pick 2 Bets</th>
                        <th class="font-weight-bold">Pick 2 Rake</th>
                        <th class="font-weight-bold">Total Bets</th>
                        <th class="font-weight-bold">Total Rake</th>
                        <th class="font-weight-bold">Payout Paid</th>
                        <th class="font-weight-bold">Payout Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in groupsuser" >
                        <td><a v-if="t.name==='Mobile Players'">#</a><a v-else>{{t.id}}</a></a></td>
                        <td><a><a v-if="t.name==='Mobile Players'">Mobile Players</a><a v-else><a @click.prevent='viewreport(t)' class="btn btn-sm btn-success text-white">{{t.name}}</a></a></a></td>
                        <td><a v-if="t.totalpick20||t.role==9"><a v-if="t.totalpick20">{{Number(t.totalpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>0</a></a><a v-else>n/a</a> </td>
                        <td><a v-if="t.totalrakepick20||t.role==9"><a v-if="t.totalrakepick20">{{Number(t.totalrakepick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>0</a></a><a v-else>n/a</a></td>
                        <td><a v-if="t.totalpick2||t.role==9"><a v-if="t.totalpick2">{{Number(t.totalpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a> </a><a v-else>n/a</a></td>
                        <td><a v-if="t.totalrakepick2||t.role==9"><a v-if="t.totalrakepick2">{{Number(t.totalrakepick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a> </a><a v-else>n/a</a></td>
                        <td><a v-if="t.totalbets||t.role==9"><a v-if="t.totalbets">{{Number(t.totalbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a> </a><a v-else>n/a</a></td>
                        <td><a v-if="t.totalrake||t.role==9"><a v-if="t.totalrake">{{Number(t.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else>0</a> </a><a v-else>n/a</a></td>
                        <td><a v-if="t.payoutpaid&&t.role==4"><a v-if="t.payoutpaid">{{Number(t.payoutpaid).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>0</a></a><a v-else>
                          <a v-if="t.payoutpaid&&t.role==4">{{Number(parseInt(t.payoutpaid)).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>n/a</a> </a></td>
                        <td><a v-if="t.role==4"><a v-if="form.unclaimed">{{Number(form.unclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>0</a></a></a>
                          <a v-else><a v-if="t.payoutunclaimed">{{Number(parseInt(t.payoutunclaimed)).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>0</a></a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <download-excel
                  class="btn btn-success"
                  :data="groupsuser"
                  :header="myArr"
                  :fields="transfields"
                  worksheet="My Worksheet"
                  name="Pickbetmonitoring.xls"
                  >
                  Download Group Reports
                </download-excel>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
              </div>
            </div>
          </div>
          <!-- end of group users -->
          <!-- group users teller cashier -->
          <div class="modal modal1 fade" id="reportmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog1">
              <div class="modal-content modal-content1">
                <div class="modal-header bg-dark">
                  <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">{{form.event_name}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body modal-body1" style="padding:0">
                  <table class="table table-hover table-stripped table-bordered">
                    <thead>
                      <tr class="bg-dark">
                        <th colspan="3" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==9">Total Bet Amount</th>
                        <th colspan="4" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==9">Total Cash In</th>
                        <th colspan="3" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==9">Total Cash Out</th>

                        <th colspan="2" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==4">Money on Hand</th>
                        <th colspan="2" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==4">Total Cash In</th>
                        <th colspan="2" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==4">Total Cash Out</th>
                        <th colspan="2" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==4">Total Pay Out</th>
                        <th colspan="1" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==4">Total Deposit</th>
                        <th colspan="1" class="font-weight-bold text-warning" v-for="t in transactions.data" v-if="t.role==4">Total Withdraw</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="3" class="" v-for="t in transactions.data" v-if="t.role==9">{{Number(t.totalbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td colspan="4" class="" v-for="t in transactions.data" v-if="t.role==9">{{Number(t.cashin).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td colspan="3" class="" v-for="t in transactions.data" v-if="t.role==9">{{Number(t.cashout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>

                        <td colspan="2" class="" v-for="t in transactions.data" v-if="t.role==4">{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td colspan="2" class="" v-for="t in transactions.data" v-if="t.role==4">{{Number(t.cashin).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td colspan="2" class="" v-for="t in transactions.data" v-if="t.role==4">{{Number(t.cashout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td colspan="2" class="" v-for="t in transactions.data" v-if="t.role==4">{{Number(t.totalw).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td colspan="1" class="" v-for="t in transactions.data" v-if="t.role==4">{{Number(t.totald).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td colspan="1" class="" v-for="t in transactions.data" v-if="t.role==4">{{Number(t.totalwithdrawmobile).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr class="bg-dark">
                        <th colspan="10" class="font-weight-bold text-center text-warning">TRANSACTIONS</th>
                      </tr>
                      <tr class="">
                        <th class="font-weight-bold">Id</th>
                        <th class="font-weight-bold">Type</th>
                        <th class="font-weight-bold">Barcode</th>
                        <th class="font-weight-bold">Transacted To</th>
                        <th class="font-weight-bold">Starting Balance</th>
                        <th class="font-weight-bold">Amount</th>
                        <th class="font-weight-bold">Ending Balance</th>
                        <th class="font-weight-bold" colspan="3">Created Date</th>
                      </tr>
                    </thead>
                    <tbody v-for="t in transactions.data">
                      <tr v-for="s in t.transactions" class="">
                        <td>{{s.id}}</td>
                        <td >{{s.type}}</td>
                        <td> <a v-if="s.type==='Cash Out'||s.type==='Cash In'||s.type==='Deposit'||s.type==='Withdrawal'">-</a><a v-else>{{s.barcode}}</a></td>
                        <td>{{s.user.name}}</td>
                        <td>{{Number(s.startingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td>{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td>{{Number(s.endingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td colspan="3">{{s.created_at|datef}}</td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr class="bg-dark">
                        <th colspan="10" class="font-weight-bold text-center text-warning">BET HISTORY</th>
                      </tr>
                      <tr class="">
                        <th class="font-weight-bold">Id</th>
                        <th class="font-weight-bold">Barcode</th>
                        <th class="font-weight-bold">Starting Fight</th>
                        <th class="font-weight-bold" colspan="2">Bet</th>
                        <th class="font-weight-bold">Amount</th>
                        <th class="font-weight-bold">Result</th>
                        <th class="font-weight-bold" colspan="3">Created Date</th>
                      </tr>
                    </thead>
                    <tbody v-for="t in transactions.data">
                      <tr v-for="s in t.bets">
                        <td>{{s.id}}</td>
                        <td>{{s.barcode}}</td>
                        <td>{{s.startingfight}}</td>
                        <td colspan="2">{{s.bet}}</td>
                        <td>{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td><a v-if="s.winner===0" class="text-danger font-weight-bold">Pending</a> <a v-else-if="s.winner === 3" class="text-danger font-weight-bold">Loss -{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                          <a v-else class="text-success font-weight-bold">{{Number(s.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td colspan="3">{{s.created_at|datef}}</td>
                      </tr>
                    </tbody>
                  </table>

                </div>
                <!-- <div class="modal-footer">
                  <pagination :data="transactions" :show-disabled=true :limit='5' @pagination-change-page="viewreportpage" class="justify-content-center">
                    <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span>
                  </pagination>
                </div> -->
                <div class="modal-footer">
                  <!-- <download-excel
                    class="btn btn-success"
                    :data="transactionsexcel"
                    :fields="transfields"
                    worksheet="My Worksheet"
                    name="Pickbetmonitoring.xls"
                  >
                    Download Excel
                  </download-excel> -->
                  <a :href="url" class="btn btn-success">Download All Bets</a>
                  <a :href="url2" class="btn btn-success">Download All Transaction</a>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- group users teller cashier -->
    </div>
</template>

<script>
    export default {
      props:['user'],
      data(){
        return{
          header:'alex',
          headernggroup:"",
          pick:'',
          loading:false,
          events:{},
          users:{},
          groups:{},
          groupssearch:{},
          groupsuser:{},
          transactions:{},
          bets:[],
          excel:[],
          detailed:[],
          searchusers:[],
          allevents:[],
          arenareports:[],
          betsarenareports:{},
          totalarenareports:[],
          downloadall:[],
          arenareportsmodaltotal:[],
          detailedbets:[],
          titleheader:'',

          transfields: {
             'id': 'id',
             'Group Name': "name",
             'Pick 20 Bets': 'totalpick20',
             'Pick 20 Rake': 'totalrakepick20',
             'Pick 2 Bets': 'totalpick2',
             'Pick 2 Rake': 'totalrakepick2',
             'Total Bets': 'totalbets',
             'Total Rake': 'totalrake',
             'Payout Paid': 'payoutpaid',
             'Payout Unclaimed': 'payoutunclaimed',
           },
          // header:new Form({
          //   name:'alex',
          //   id:'alex',
          //   event_name:'alex'
          // }),
          search:new Form({
            name:'',
            id:'',
            event_name:''
          }),
          form:new Form({
            id:'',
            group_id:'',
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
            username:'',
            statuss:'',
            payoutunclaimed:'',
            group_name:'',
            searchgroup:''
          }),
          myArr : [


        ],
          betsarenareportsfields: {
             'Id': 'id',
             'Barcode': 'barcode',
             'Bet': 'bet',
             'Username': 'username',
             'Amount': 'amount',
             'Status': 'winner',
             'Income': 'income',
             'Payout': 'result',
             'Wins': 'wins',
             'Claimed': 'claimed',
             'Date Processed': 'updated_at',
             'Date': 'created_at',
           },
           dataForExcel: [
          { a: "Event Name", b: "" ,c:"",d:"",e:"",f:"",g:"",h:"",i:"",j:"",k:"",l:""},
          { a: "Arena", b: "" ,c:"",d:"",e:"",f:"",g:"",h:"",i:"",j:"",k:"",l:""},
          { a: "Date Created", b: "" ,c:"",d:"",e:"",f:"",g:"",h:"",i:"",j:"",k:"",l:""},
          { a: "Date Close", b: "" ,c:"",d:"",e:"",f:"",g:"",h:"",i:"",j:"",k:"",l:""},
          { a: "", b: "" ,c:"",d:"",e:"",f:"",g:"",h:"",i:"",j:"",k:"",l:""},
          { a: "Total Number of Bets", b: "Total Amount Bets",c:"Total Cancelled Bets Payout",d:"Total Payout",e:"Total Payout Unclaimed",f:"Office Rake",g:"Total Contingency Funds",h:"Total Current Contingency Funds",i:"Breakage",j:"Total Payout Paid",k:"Total Cancelled Bets",l:"Total Income"},
          { a: "", b: "" ,c:"",d:"",e:"",f:"",g:"",h:"",i:"",j:"",k:"",l:""},
        ],
          arenareportsfields: {
             'Startingfight': 'startingfight',
             'Results': 'results',
             'Number of Winners': 'winners',
             'Number of Bets': 'bet',
             'Status': 'status',
             'Date': 'created_at_format',
           },
          betsfields: {
             'Bet ID': 'id',
             'Username': 'username',
             'Startingfight': 'startingfight',
             'Bet': 'bet',
             'Income': 'income',
             'Claimed': 'claimed',
             'Date': 'created_at',
           },
          usersfields: {
             'Teller/Mobile': 'username',
             'Role': 'role',
             'Bet Total': 'totalbets',
             'Rake': 'income',
             'Payout Paid': 'totalpayoutpaid',
             'Unclaimed': 'totalunclaimed',
           },
        }
      },
      computed: {
        url: function(){
          return "/pick20/downloadusermonitoring/"+this.form.id+"/"+this.form.event_name;
        },
        url2: function(){
          return "/pick20/downloadusertransaction/"+this.form.id+"/"+this.form.event_name;
        },
        eventname: function(){
          if (this.form.event_name) {
            this.dataForExcel[0].b = this.form.event_name;
            this.dataForExcel[1].b = this.form.venue;
            this.dataForExcel[2].b = moment(String(this.form.created_at)).format('MM/DD/YYYY hh:mm');
            if (this.form.fightclosed) {
              this.dataForExcel[3].b = moment(String(this.form.fightclosed)).format('MM/DD/YYYY hh:mm');
            }else {
              this.dataForExcel[3].b = 'n/a';
            }
            this.dataForExcel[6].a = this.arenareportsmodaltotal[0].numberofbets;
            this.dataForExcel[6].b = this.arenareportsmodaltotal[0].totalbetsamountfinal;
            this.dataForExcel[6].l = this.arenareportsmodaltotal[0].rakefinal;
            this.dataForExcel[6].d = this.arenareportsmodaltotal[0].totalpayout;
            this.dataForExcel[6].e = this.arenareportsmodaltotal[0].totalpayoutunclaimed;
            this.dataForExcel[6].f = this.arenareportsmodaltotal[0].officerake;
            if (this.arenareportsmodaltotal[0].totalcontingencyfunds) {
              this.dataForExcel[6].g = this.arenareportsmodaltotal[0].totalcontingencyfunds;
            }else {
              this.dataForExcel[6].g = 0;

            }
            if (this.arenareportsmodaltotal[0].currentcontingencyfunds) {
              this.dataForExcel[6].h = this.arenareportsmodaltotal[0].currentcontingencyfunds;
            }else {
              this.dataForExcel[6].h =0;
            }
            this.dataForExcel[6].i = this.arenareportsmodaltotal[0].breakage;
            this.dataForExcel[6].j = this.arenareportsmodaltotal[0].totalpayoutpaid;
            if (this.arenareportsmodaltotal[0].cancelledbetsamount) {
              this.dataForExcel[6].k = this.arenareportsmodaltotal[0].cancelledbetsamount;
            }
            if (this.arenareportsmodaltotal[0].cancelledbetsamountpayout) {
              this.dataForExcel[6].c = this.arenareportsmodaltotal[0].cancelledbetsamountpayout;
            }


          }else {
            return 'alex';

          }
       },
      },
      methods:{
        viewreport(t,page=1){
          this.form.id = t.id;
          // this.form.event_id = this.eventdatailed.id;
          this.loading=true;
          $('#reportmodal').modal('show');
          this.form.post('/pick20/gettransactions?page='+page).then(response=>{
            this.loading=false;
            this.transactions = response.data;
            this.transactionsexcel = this.transactions[0].transactions;
          })
        },
        getallusergroup(t,page=1){
          this.form.group_id = t.id;
          this.form.id = this.form.event_id;
          this.form.unclaimed = t.payoutunclaimed;
          this.form.group_name = t.name;
          this.loading = true;
          this.form.post('/pick20/eventgetusersgroupreport?page='+page).then(response=>{
            this.groupsuser = response.data;
            this.groupsuser.forEach((val)=>{
              if (val.role==4) {
                val.payoutunclaimed = t.payoutunclaimed;
                // alexlang
              }
            });
              $('#groupmodalusers').modal('show');
              this.loading=false;
          }).catch(()=>{
            this.loading=false;
          })
        },
        groupreportsmodal(t,page=1){
          this.form.fill(t);
          this.myArr = [];
          this.form.event_id = t.id;
          this.search.id = t.id;
          this.loading = true;
          if (this.form.fightclosed) {
            this.form.fightclosed = moment(String(this.form.fightclosed)).format('MM/DD/YYYY hh:mm')
          }else {
            this.form.fightclosed = '';
          }
          this.myArr.push('Event Name : '+this.form.event_name,'Arena : '+this.form.venue,'Created Date : '+moment(String(this.form.created_at)).format('MM/DD/YYYY hh:mm'),'Fight Opened : '+moment(String(this.form.fightopened)).format('MM/DD/YYYY hh:mm'),'Fight Closed : '+this.form.fightclosed);
          this.headernggroup = "Event Name : "+this.form.event_name+"\nArena : "+this.form.venue;
          this.form.post('/pick20/eventgetgroupreport?page='+page).then(response=>{
            this.groups = response.data;
            this.groupssearch = response.data;
              $('#groupmodal').modal('show');
              this.loading=false;
          }).catch(()=>{
            this.loading=false;
          })
        },
        clearsearchgroup(t,page=1){
          // this.form.fill(t);
          this.form.searchgroup='';
          this.search.id = t.id;
          this.loading = true;
          this.form.post('/pick20/eventgetgroupreport?page='+page).then(response=>{
            this.groups = response.data;
              $('#groupmodal').modal('show');
              this.loading=false;
          }).catch(()=>{
            this.loading=false;
          })
        },
        searchgroup(t,page=1){
          // this.form.fill(t);
          // this.form.searchgroup = t.name;
          this.form.post('/pick20/eventgetgroupreport?page='+page).then(response=>{
            this.groups = response.data;
              $('#groupmodal').modal('show');
              this.loading=false;
          }).catch(()=>{
            this.loading=false;
          })
        },
        showdetailedbets(id){
          this.loading=true,
            axios.get('/pick20/showdetailedbets/' + id).then(response=>{
              this.loading=false,
              this.detailed = response.data;
              $('#detailed').modal('show');
            })
        },
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
          this.form.statuss='',
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
        downloadallbets(t){
          this.form.id=t.id;
          this.form.username=null;
          this.search.id = t.event_id;
          this.form.startingfight = t.startingfight;
          this.form.event_id = t.event_id;
          this.loading = true;
          this.form.post('/pick20/downloadall').then(response=>{
            this.downloadall = response.data;
            $('#downloadmodal').modal('show');
            this.loading = false;
          }).catch(()=>{
            this.loading = false;
          });
        },
        arenareportsmodal(t){
          this.dataForExcel[6].a = 0;
          this.dataForExcel[6].b = 0;
          this.dataForExcel[6].l = 0;
          this.dataForExcel[6].d = 0;
          this.dataForExcel[6].e = 0;
          this.dataForExcel[6].f = 0;
            this.dataForExcel[6].g = 0;

            this.dataForExcel[6].h = 0;
          this.dataForExcel[6].i = 0;
          this.dataForExcel[6].j = 0;
            this.dataForExcel[6].k = 0;
            this.dataForExcel[6].c = 0;
            this.pick = 20;
            this.titleheader = 'Arena Reports of Pick 20';
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
            if (this.form.event_name) {
              this.dataForExcel[0].b = this.form.event_name;
              this.dataForExcel[1].b = this.form.venue;
              this.dataForExcel[2].b = moment(String(this.form.created_at)).format('MM/DD/YYYY hh:mm');
              if (this.form.fightclosed) {
                this.dataForExcel[3].b = moment(String(this.form.fightclosed)).format('MM/DD/YYYY hh:mm');
              }else {
                this.dataForExcel[3].b = 'n/a';
              }
              this.dataForExcel[6].a = this.arenareportsmodaltotal[0].numberofbets;
              this.dataForExcel[6].b = this.arenareportsmodaltotal[0].totalbetsamountfinal;
              this.dataForExcel[6].l = this.arenareportsmodaltotal[0].rakefinal;
              this.dataForExcel[6].d = this.arenareportsmodaltotal[0].totalpayout;
              this.dataForExcel[6].e = this.arenareportsmodaltotal[0].totalpayoutunclaimed;
              this.dataForExcel[6].f = this.arenareportsmodaltotal[0].officerake;
              if (this.arenareportsmodaltotal[0].totalcontingencyfunds) {
                this.dataForExcel[6].g = this.arenareportsmodaltotal[0].totalcontingencyfunds;
              }else {
                this.dataForExcel[6].g = 0;

              }
              if (this.arenareportsmodaltotal[0].currentcontingencyfunds) {
                this.dataForExcel[6].h = this.arenareportsmodaltotal[0].currentcontingencyfunds;
              }else {
                this.dataForExcel[6].h = 0;
              }
              this.dataForExcel[6].i = this.arenareportsmodaltotal[0].breakage;
              this.dataForExcel[6].j = this.arenareportsmodaltotal[0].totalpayoutpaid;
              if (this.arenareportsmodaltotal[0].cancelledbetsamount) {
                this.dataForExcel[6].k = this.arenareportsmodaltotal[0].cancelledbetsamount;
              }
              if (this.arenareportsmodaltotal[0].cancelledbetsamountpayout) {
                this.dataForExcel[6].c = this.arenareportsmodaltotal[0].cancelledbetsamountpayout;
              }


            }else {
              return 'alex';

            }
          })
          })
        },
        arenareportsmodal2(t){
          this.dataForExcel[6].a = 0;
          this.dataForExcel[6].b = 0;
          this.dataForExcel[6].l = 0;
          this.dataForExcel[6].d = 0;
          this.dataForExcel[6].e = 0;
          this.dataForExcel[6].f = 0;
            this.dataForExcel[6].g = 0;

            this.dataForExcel[6].h = 0;
          this.dataForExcel[6].i = 0;
          this.dataForExcel[6].j = 0;
            this.dataForExcel[6].k = 0;
            this.dataForExcel[6].c = 0;
          this.pick = 2;
          this.titleheader = 'Arena Reports of Pick 2';
            this.bets=[];
            this.loading=true;
            this.form.fill(t);
          this.form.post('/pick20/arenareportsmodal2').then(response=>{
            this.loading=false;
            this.arenareports = response.data;
          this.form.fill(t);
          this.form.post('/pick20/arenareportsmodaltotal2').then(response=>{
            this.arenareportsmodaltotal=response.data;
            $('#arenareportsmodal').modal('show');
            if (this.form.event_name) {
              this.dataForExcel[0].b = this.form.event_name;
              this.dataForExcel[1].b = this.form.venue;
              this.dataForExcel[2].b = moment(String(this.form.created_at)).format('MM/DD/YYYY hh:mm');
              if (this.form.fightclosed) {
                this.dataForExcel[3].b = moment(String(this.form.fightclosed)).format('MM/DD/YYYY hh:mm');
              }else {
                this.dataForExcel[3].b = 'n/a';
              }
              this.dataForExcel[6].a = this.arenareportsmodaltotal[0].numberofbets;
              this.dataForExcel[6].b = this.arenareportsmodaltotal[0].totalbetsamountfinal;
              this.dataForExcel[6].l = this.arenareportsmodaltotal[0].rakefinal;
              this.dataForExcel[6].d = this.arenareportsmodaltotal[0].totalpayout;
              this.dataForExcel[6].e = this.arenareportsmodaltotal[0].totalpayoutunclaimed;
              this.dataForExcel[6].f = this.arenareportsmodaltotal[0].officerake;
              if (this.arenareportsmodaltotal[0].totalcontingencyfunds) {
                this.dataForExcel[6].g = this.arenareportsmodaltotal[0].totalcontingencyfunds;
              }
              if (this.arenareportsmodaltotal[0].currentcontingencyfunds) {
                this.dataForExcel[6].h = this.arenareportsmodaltotal[0].currentcontingencyfunds;
              }
              this.dataForExcel[6].i = this.arenareportsmodaltotal[0].breakage;
              this.dataForExcel[6].j = this.arenareportsmodaltotal[0].totalpayoutpaid;
              if (this.arenareportsmodaltotal[0].cancelledbetsamount) {
                this.dataForExcel[6].k = this.arenareportsmodaltotal[0].cancelledbetsamount;
              }
              if (this.arenareportsmodaltotal[0].cancelledbetsamountpayout) {
                this.dataForExcel[6].c = this.arenareportsmodaltotal[0].cancelledbetsamountpayout;
              }


            }else {
              return 'alex';

            }
          })
          })
        },
        arenareportsmodal3(t){
          this.dataForExcel[6].a = 0;
          this.dataForExcel[6].b = 0;
          this.dataForExcel[6].l = 0;
          this.dataForExcel[6].d = 0;
          this.dataForExcel[6].e = 0;
          this.dataForExcel[6].f = 0;
            this.dataForExcel[6].g = 0;

            this.dataForExcel[6].h = 0;
          this.dataForExcel[6].i = 0;
          this.dataForExcel[6].j = 0;
            this.dataForExcel[6].k = 0;
            this.dataForExcel[6].c = 0;
          this.pick = 'all';
          this.titleheader = 'Arena Reports Overall';
            this.bets=[];
            this.loading=true;
            this.form.fill(t);
          this.form.post('/pick20/arenareportsmodal3').then(response=>{
            this.loading=false;
            this.arenareports = response.data;
          this.form.fill(t);
          this.form.post('/pick20/arenareportsmodaltotal3').then(response=>{
            this.arenareportsmodaltotal=response.data;
            $('#arenareportsmodal').modal('show');
            if (this.form.event_name) {
              this.dataForExcel[0].b = this.form.event_name;
              this.dataForExcel[1].b = this.form.venue;
              this.dataForExcel[2].b = moment(String(this.form.created_at)).format('MM/DD/YYYY hh:mm');
              if (this.form.fightclosed) {
                this.dataForExcel[3].b = moment(String(this.form.fightclosed)).format('MM/DD/YYYY hh:mm');
              }else {
                this.dataForExcel[3].b = 'n/a';
              }
              this.dataForExcel[6].a = this.arenareportsmodaltotal[0].numberofbets;
              this.dataForExcel[6].b = this.arenareportsmodaltotal[0].totalbetsamountfinal;
              this.dataForExcel[6].l = this.arenareportsmodaltotal[0].rakefinal;
              this.dataForExcel[6].d = this.arenareportsmodaltotal[0].totalpayout;
              this.dataForExcel[6].e = this.arenareportsmodaltotal[0].totalpayoutunclaimed;
              this.dataForExcel[6].f = this.arenareportsmodaltotal[0].officerake;
              if (this.arenareportsmodaltotal[0].totalcontingencyfunds) {
                this.dataForExcel[6].g = this.arenareportsmodaltotal[0].totalcontingencyfunds;
              }
              if (this.arenareportsmodaltotal[0].currentcontingencyfunds) {
                this.dataForExcel[6].h = this.arenareportsmodaltotal[0].currentcontingencyfunds;
              }
              this.dataForExcel[6].i = this.arenareportsmodaltotal[0].breakage;
              this.dataForExcel[6].j = this.arenareportsmodaltotal[0].totalpayoutpaid;
              if (this.arenareportsmodaltotal[0].cancelledbetsamount) {
                this.dataForExcel[6].k = this.arenareportsmodaltotal[0].cancelledbetsamount;
              }
              if (this.arenareportsmodaltotal[0].cancelledbetsamountpayout) {
                this.dataForExcel[6].c = this.arenareportsmodaltotal[0].cancelledbetsamountpayout;
              }


            }else {
              return 'alex';

            }
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
            this.form.post('/pick20/arenareportsmodaltotal3').then(response=>{

                this.loading=false;
              this.arenareportsmodaltotal=response.data;
              $('#reportsmodal').modal('show');
              this.form.post('/pick20/eventgetusersreportexcel').then(response=>{
                this.excel = response.data;
              });
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
            this.form.post('/pick20/eventgetusersreportexcel').then(response=>{
              this.excel = response.data;
            });
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
