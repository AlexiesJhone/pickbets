<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
          <!-- <session :userx='user'></session>
          <modalcash :userx='user'></modalcash> -->
            <div class="col-md-12 row justify-content-center">
              <div class="card table-responsive">
                <!-- <div class="card-header bg-dark text-warning">
                  <a class="btn btn-success text-white" @click.prevent='cashin'>Click To Cash In</a>
                  <a class="btn btn-danger text-white" @click.prevent='logout'>Click To Logout</a>
                </div> -->
                <div class="card-header bg-dark text-warning">
                  <center><a class="font-weight-bold text-warning" v-if="totalsummary[0]">Summary Report of {{totalsummary[0].Event}}</a></center>
                  <center><a class="font-weight-bold text-warning">Summary Report</a></center>
                </div>
                <div class="card-body">
                  <table class="table-bordered table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <td>{{user.name}}</td>
                      </tr>
                      <tr>
                        <th>Cash In</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].CashIn).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Total Bets</th>
                        <td><a  v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 2 Bets</th>
                        <td><a  v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 2 Cancelled Bets</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick2cancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 3 Bets</th>
                        <td><a  v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 3 Cancelled Bets</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick3cancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 4 Bets</th>
                        <td><a  v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 4 Cancelled Bets</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick4cancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 5 Bets</th>
                        <td><a  v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 5 Cancelled Bets</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick5cancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 6 Bets</th>
                        <td><a  v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 6 Cancelled Bets</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick6cancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 8 Bets</th>
                        <td><a  v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 8 Cancelled Bets</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick8cancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 14 Bets</th>
                        <td><a  v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 14 Cancelled Bets</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick14cancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==9">
                        <th>Pick 20 Bets</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].pick20count).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <!-- <tr v-if="user.role==4">
                        <th>Total Payout</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr> -->
                      <tr v-if="user.role==4">
                        <th>Payout Paid</th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==4">
                        <th>Payout Unclaimed : </th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].payoutunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==4">
                        <th>Cancelled Payout Paid : </th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].totalpayoutcancelled).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr v-if="user.role==4">
                        <th>Cancelled Unclaimed : </th>
                        <td><a v-if="totalsummary[0]">{{Number(totalsummary[0].cancelledunclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <!-- <tr>
                        <th>Total Contingency Funds</th>
                        <td><a <a v-if="totalsummary[0]">{{Number(totalsummary[0].contingency).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                      <tr>
                        <th>Breakage</th>
                        <td><a <a v-if="totalsummary[0]">{{Number(totalsummary[0].breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr> -->
                      <tr>
                        <th>Money On Hand</th>
                        <td><a <a v-if="totalsummary[0]">{{Number(this.user.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </thead>
                  </table>
                  <center><button type="button" class="btn btn-primary" @click.prevent='printme'>Print Summary</button> <button type="button" class="btn btn-success" @click.prevent='restoresession'>Restore Session</button> <button type="button" class="btn btn-danger" @click.prevent='cashout1'>Cash Out</button></center>
                </div>
                  <table class="table" v-if="bet.data.length">
                    <thead>
                      <tr class="bg-dark">
                        <th colspan="8" class="text-warning text-center font-weight-bold">List of Bets</th>
                      </tr>
                      <tr>
                        <th>Starting Fight</th>
                        <th>Bet ID</th>
                        <th>Bet</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payout</th>
                        <th>Starting Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in bet.data"  >
                        <td>{{t.startingfight}}</td>
                        <td>{{t.id}}</td>
                        <td>{{t.bet}}</td>
                        <td>{{t.date|datef}}</td>
                        <td>{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td> <a v-if="t.winner===1||t.winner===2" class="text-success">Winner</a><a v-else-if="t.winner===3" class="text-danger">Loss</a><a v-else-if="t.winner===4" class="text-warning font-weight-bold">Cancelled</a>
                          <a v-else-if="t.winner===5" class="text-danger">No Winner</a>
                          <a v-else class="text-danger">Pending</a> </td>
                        <td><a v-if="t.winner===1||t.winner===2" class="text-success">+{{Number(t.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else-if="t.winner===3" class="text-danger">
                          -{{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>-</a></td>
                        <td>{{Number(t.startingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination class="justify-content-center" :data="bet" :limit='5' @pagination-change-page="getmysummary">
                    <!-- <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span> -->
                  </pagination>
                  <table class="table" v-if="transactions.length">
                    <thead>
                      <tr class="bg-dark">
                        <th colspan="8" class="text-warning text-center font-weight-bold">List of Transactions</th>
                      </tr>
                      <tr>
                        <th>Processed at</th>
                        <th>Transaction</th>
                        <th>Starting Balance</th>
                        <th>Amount</th>
                        <th>Ending Balance</th>
                        <th>Transacted By</th>
                        <th>Transacted To/Barcode</th>
                        <!-- <th>Group</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="s in pageOfItems">
                        <td>{{s.created_at|datef}}</td>
                        <td>{{s.type}}</td>
                        <td><a v-if="user.role===9||user.role===4">{{Number(s.startingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                          <a v-else-if="user.role===3">{{Number(s.startingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td class="text-success">{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        <td><a v-if="user.role===9||user.role===4">{{Number(s.endingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                          <a v-else-if="user.role===3">{{Number(s.endingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td>{{s.cashier}}</td>
                        <td><a v-if="s.userrole===9||s.userrole===4"><a v-if="s.type==='Cash Out'||s.type==='Cash In'||s.type==='Deposit'">{{s.user}}</a><a v-if="s.type==='Withdrawal'">{{s.barcode}}</a>
                        </a><a v-if="s.userrole===3">{{s.user}}</a> </td>
                        <!-- <td colspan="1">{{s.group}}</td> -->
                      </tr>
                    </tbody>
                  </table>
                  <center>
                  <jw-pagination :items="transactions" @changePage="onChangePage" :maxPages='5' :pageSize='10'></jw-pagination>
                </center>
                  <!-- <pagination class="justify-content-center" :data="transactions" :limit='5' @pagination-change-page="getmysummary2"> -->
                    <!-- <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span> -->
                  <!-- </pagination> -->
              </div>
              <div class="modal fade" id="restore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog" role="document">
                  <div class="modal-content" style="border:none">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Restore Session</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <!-- <label style="color:gray">Amount</label>
                      <input :disabled='isDisabled' type="number" class="form-control"  oninput="this.value = Math.round(this.value);" min="1"  v-model="forms.amount"/> -->
                      <label style="color:gray">Pin of Supervisor</label>
                      <input :disabled='isDisabled' type="password" class="form-control" maxlength="4" v-model="forms.pin"/>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success" @click.prevent='restore'>Restore</button>
                    </div>
                  </div>
                </div>
              </div>
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
                  <div class="modal-body" id='printMe' >
                    <div class="card" style="page-break-after: always !important;">
                      <!-- <barcode :value="receipt.barcode" tag="img"></barcode> -->

                      <!-- <center> -->
                      <!-- <p style="font-size: 14px;">
                      <b>{{totalsummary[0].Event}}</b> [Cash Out] <br>
                      <b>Cash In :</b> {{Number(totalsummary[0].CashIn).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                      <b>Total Bet Amount :</b> {{Number(totalsummary[0].totalamountbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                      <b>Total Contingency Funds :</b> {{Number(totalsummary[0].contingency).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                      <b>Total Breakage :</b> {{Number(totalsummary[0].breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                      <b>Money On Hand :</b> {{Number(totalsummary[0].moh).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                    </p> -->
                      <table>
                        <thead>
                          <tr>
                            <th colspan="2" v-if="totalsummary[0]">{{totalsummary[0].Event}}</b> [Cash Out] </th>
                          </tr>
                          <tr>
                            <td  style="font-size: 12px;">Arena : <a  v-if="totalsummary[0]">{{totalsummary[0].arena}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Date : <a  v-if="totalsummary[0]">{{date|datef}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Name : <a   v-if="totalsummary[0]">{{user.name}}</a> </td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Cash In : <a v-if="totalsummary[0]">{{Number(totalsummary[0].CashIn).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Total Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbets).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 20 Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].pick20count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 2 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick2count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 2 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick2cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 3 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick3count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 3 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick3cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 4 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick4count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 4 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick4cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 5 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick5count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 5 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick5cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 6 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick6count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 6 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick6cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 8 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick8count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 8 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick8cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 14 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick14count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 14 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick14cancelled).toLocaleString()}}</a></td>

                          </tr>

                          <!-- <tr>
                            <td style="text-align: right" v-if="user.role==9&&totalsummary[0]">Total Bet Amount : </td>
                            <td style="text-align: left" v-if="user.role==9&&totalsummary[0]">{{Number(totalsummary[0].totalamountbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                          <tr>
                            <td style="text-align: right">Total Contingency Funds : </td>
                            <td style="text-align: left" v-if="totalsummary[0]">{{Number(totalsummary[0].contingency).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                          <tr>
                            <td style="text-align: right">Total Breakage :  <br><br></td>
                            <td style="text-align: left" v-if="totalsummary[0]">{{Number(totalsummary[0].breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} <br><br> </td>
                          </tr> -->
                          <tr>
                            <td style="font-size: 12px;">Money on Hand : <a  v-if="totalsummary[0]">{{Number(user.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                          </tr>
                          <tr>
                            <th style="font-size: 12px;">List of Transactions</th>
                          </tr>
                          <tr v-for="t in transactions">
                            <td style="font-size: 12px;">{{t.type}} | {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} |
                              <a v-if="t.type==='Deposit'||t.type==='Withdraw'"><a v-if="t.userrole==3">{{t.user}}</a> <a v-else>{{t.barcode}}</a> </a> <a v-else>--</a>
                              <a v-if="t.userrole===9||t.userrole===4"><a v-if="t.type==='Cash Out'||t.type==='Cash In'||t.type==='Deposit'">{{t.user}}</a><a v-if="t.type==='Withdrawal'">{{t.barcode}}</a>
                            </a><a v-if="t.userrole===3">{{t.user}}</a>
                             </td>
                          </tr>
                          <!-- <tr v-if="user.role==9&&betonly.length">
                            <th style="text-align: right" colspan="2">List of Bets</th>
                          </tr> -->
                          <!-- <tr v-for="t in betonly" v-if="user.role==9">
                            <td style="text-align: right" colspan="2">{{t.barcode}} | {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}
                           </td>
                          </tr> -->
                        </thead>
                      </table>
                    <center><br>  ==============  <br><br> </center>
                    <table>
                      <thead>
                        <tr>
                          <th colspan="2" v-if="totalsummary[0]">{{totalsummary[0].Event}}</b> [Cash Out] </th>
                        </tr>
                        <tr>
                          <td  style="font-size: 12px;">Arena : <a  v-if="totalsummary[0]">{{totalsummary[0].arena}}</a></td>

                        </tr>
                        <tr>
                          <td style="font-size: 12px;">Date : <a  v-if="totalsummary[0]">{{date|datef}}</a></td>

                        </tr>
                        <tr>
                          <td style="font-size: 12px;">Name : <a   v-if="totalsummary[0]">{{user.name}}</a> </td>

                        </tr>
                        <tr>
                          <td style="font-size: 12px;">Cash In : <a v-if="totalsummary[0]">{{Number(totalsummary[0].CashIn).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                        </tr>
                        <tr>
                          <td style="font-size: 12px;">Total Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbets).toLocaleString()}}</a></td>

                        </tr>
                        <tr>
                          <td style="font-size: 12px;">Pick 20 Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].pick20count).toLocaleString()}}</a></td>

                        </tr>
                        <tr>
                          <td style="font-size: 12px;">Pick 2 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick2count).toLocaleString()}}</a></td>

                        </tr>
                        <tr>
                          <td style="font-size: 12px;">Pick 2 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick2cancelled).toLocaleString()}}</a></td>
                          <tr>
                            <td style="font-size: 12px;">Pick 3 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick3count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 3 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick3cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 4 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick4count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 4 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick5cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 5 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick5count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 5 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick5cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 6 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick6count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 6 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick6cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 8 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick8count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 8 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick8cancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 14 Bets : <a v-if="totalsummary[0]">{{Number(totalsummary[0].pick14count).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Pick 14 Cancelled Bets : <a   v-if="totalsummary[0]">{{Number(totalsummary[0].totalamountbetspick14cancelled).toLocaleString()}}</a></td>

                          </tr>

                        </tr>

                        <!-- <tr>
                          <td style="text-align: right" v-if="user.role==9&&totalsummary[0]">Total Bet Amount : </td>
                          <td style="text-align: left" v-if="user.role==9&&totalsummary[0]">{{Number(totalsummary[0].totalamountbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        </tr>
                        <tr>
                          <td style="text-align: right">Total Contingency Funds : </td>
                          <td style="text-align: left" v-if="totalsummary[0]">{{Number(totalsummary[0].contingency).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                        </tr>
                        <tr>
                          <td style="text-align: right">Total Breakage :  <br><br></td>
                          <td style="text-align: left" v-if="totalsummary[0]">{{Number(totalsummary[0].breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} <br><br> </td>
                        </tr> -->
                        <tr>
                          <td style="font-size: 12px;">Money on Hand : <a  v-if="totalsummary[0]">{{Number(user.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                        </tr>
                        <tr>
                          <th style="font-size: 12px;">List of Transactions</th>
                        </tr>
                        <tr v-for="t in transactions">
                          <td style="font-size: 12px;">{{t.type}} | {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} |
                            <a v-if="t.type==='Deposit'||t.type==='Withdraw'"><a v-if="t.userrole==3">{{t.user}}</a> <a v-else>{{t.barcode}}</a> </a> <a v-else>--</a>
                            <a v-if="t.userrole===9||t.userrole===4"><a v-if="t.type==='Cash Out'||t.type==='Cash In'||t.type==='Deposit'">{{t.user}}</a><a v-if="t.type==='Withdrawal'">{{t.barcode}}</a>
                          </a><a v-if="t.userrole===3">{{t.user}}</a>
                           </td>
                        </tr>
                        <!-- <tr v-if="user.role==9&&betonly.length">
                          <th style="text-align: right" colspan="2">List of Bets</th>
                        </tr> -->
                        <!-- <tr v-for="t in betonly" v-if="user.role==9">
                          <td style="text-align: right" colspan="2">{{t.barcode}} | {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}
                         </td>
                        </tr> -->
                      </thead>
                    </table>
                    <!-- </center> -->
                    </div>
                  </div>
                  <div class="modal-body" id='printMe2' >
                    <div class="card" style="page-break-after: always !important;">
                      <!-- <barcode :value="receipt.barcode" tag="img"></barcode> -->

                      <!-- <center> -->
                      <!-- <p style="font-size: 14px;">
                      <b>{{totalsummary[0].Event}}</b> [Cash Out] <br>
                      <b>Cash In :</b> {{Number(totalsummary[0].CashIn).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                      <b>Total Bet Amount :</b> {{Number(totalsummary[0].totalamountbets).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                      <b>Total Contingency Funds :</b> {{Number(totalsummary[0].contingency).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                      <b>Total Breakage :</b> {{Number(totalsummary[0].breakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                      <b>Money On Hand :</b> {{Number(totalsummary[0].moh).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}<br>
                    </p> -->
                      <table>
                        <thead>
                          <tr>
                            <th colspan="2" v-if="totalsummary[0]">{{totalsummary[0].Event}}</b> [Cash Out] </th>
                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Arena : <a v-if="totalsummary[0]">{{totalsummary[0].arena}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;" >Date : <a  v-if="totalsummary[0]">{{date|datef}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Name : <a  v-if="totalsummary[0]">{{user.name}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;">Cash In : <a  v-if="totalsummary[0]">{{Number(totalsummary[0].CashIn).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;" v-if="user.role===4">Payout Paid :  <a  v-if="totalsummary[0]||user.role===4"><a v-if="totalsummary[0].totalpayout"></a>{{Number(totalsummary[0].totalpayout).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;" v-if="user.role===4">Payout Unclaimed :  <a  v-if="totalsummary[0]||user.role===4"><a v-if="totalsummary[0].payoutunclaimed"></a>{{Number(totalsummary[0].payoutunclaimed).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;" v-if="user.role===4">Cancelled Payout Paid : <a  v-if="totalsummary[0]||user.role===4"><a v-if="totalsummary[0].totalpayoutcancelled"></a>{{Number(totalsummary[0].totalpayoutcancelled).toLocaleString()}}</a></td>

                          </tr>
                          <tr>
                            <td style="font-size: 12px;" v-if="user.role===4">Cancelled Payout Unclaimed : <a  v-if="totalsummary[0]||user.role===4"><a v-if="totalsummary[0].payoutunclaimed"></a>{{Number(totalsummary[0].cancelledunclaimed).toLocaleString()}}</a></td>

                          </tr>

                          <tr>
                            <td style="font-size: 12px;">Money on Hand : <a v-if="totalsummary[0]">{{Number(user.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                          </tr>
                          <tr>
                            <th style="font-size: 12px;">List of Transactions</th>
                          </tr>
                          <tr v-for="t in transactions">
                            <td style="font-size: 12px;" >{{t.type}} | {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} |
                              <a v-if="t.type==='Deposit'||t.type==='Withdraw'"><a v-if="t.userrole==3">{{t.user}}</a> <a v-else>{{t.barcode}}</a> </a> <a v-else>--</a>
                              <a v-if="t.userrole===9||t.userrole===4"><a v-if="t.type==='Cash Out'||t.type==='Cash In'||t.type==='Deposit'">{{t.user}}</a><a v-if="t.type==='Withdrawal'">{{t.barcode}}</a>
                            </a><a v-if="t.userrole===3">{{t.user}}</a>
                             </td>
                          </tr>
                          <!-- <tr v-if="user.role==9&&betonly.length">
                            <th style="text-align: right" colspan="2">List of Bets</th>
                          </tr> -->
                          <!-- <tr v-for="t in betonly" v-if="user.role==9">
                            <td style="text-align: right" colspan="2">{{t.barcode}} | {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}
                           </td>
                          </tr> -->
                        </thead>
                      </table>
                  <br>  <center>============== </center> <br> <br>
                  <table>
                    <thead>
                      <tr>
                        <th colspan="2" v-if="totalsummary[0]">{{totalsummary[0].Event}}</b> [Cash Out] </th>
                      </tr>
                      <tr>
                        <td style="font-size: 12px;">Arena : <a v-if="totalsummary[0]">{{totalsummary[0].arena}}</a></td>

                      </tr>
                      <tr>
                        <td style="font-size: 12px;">Date : <a  v-if="totalsummary[0]">{{date|datef}}</a></td>

                      </tr>
                      <tr>
                        <td style="font-size: 12px;">Name : <a  v-if="totalsummary[0]">{{user.name}}</a></td>

                      </tr>
                      <tr>
                        <td style="font-size: 12px;">Cash In : <a  v-if="totalsummary[0]">{{Number(totalsummary[0].CashIn).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                      </tr>
                      <tr>
                        <td style="font-size: 12px;" v-if="user.role===4">Payout Paid :  <a  v-if="totalsummary[0]||user.role===4"><a v-if="totalsummary[0].totalpayout"></a>{{Number(totalsummary[0].totalpayout).toLocaleString()}}</a></td>

                      </tr>
                      <tr>
                        <td style="font-size: 12px;" v-if="user.role===4">Payout Unclaimed :  <a  v-if="totalsummary[0]||user.role===4"><a v-if="totalsummary[0].payoutunclaimed"></a>{{Number(totalsummary[0].payoutunclaimed).toLocaleString()}}</a></td>

                      </tr>
                      <tr>
                        <td style="font-size: 12px;" v-if="user.role===4">Cancelled Payout Paid : <a  v-if="totalsummary[0]||user.role===4"><a v-if="totalsummary[0].totalpayoutcancelled"></a>{{Number(totalsummary[0].totalpayoutcancelled).toLocaleString()}}</a></td>

                      </tr>
                      <tr>
                        <td style="font-size: 12px;" v-if="user.role===4">Cancelled Payout Unclaimed : <a  v-if="totalsummary[0]||user.role===4"><a v-if="totalsummary[0].payoutunclaimed"></a>{{Number(totalsummary[0].cancelledunclaimed).toLocaleString()}}</a></td>

                      </tr>

                      <tr>
                        <td style="font-size: 12px;">Money on Hand : <a v-if="totalsummary[0]">{{Number(user.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                      </tr>
                      <tr>
                        <th  style="font-size: 12px;">List of Transactions</th>
                      </tr>
                      <tr v-for="t in transactions">
                        <td style="font-size: 12px;" >{{t.type}} | {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} |
                          <a v-if="t.type==='Deposit'||t.type==='Withdraw'"><a v-if="t.userrole==3">{{t.user}}</a> <a v-else>{{t.barcode}}</a> </a> <a v-else>--</a>
                          <a v-if="t.userrole===9||t.userrole===4"><a v-if="t.type==='Cash Out'||t.type==='Cash In'||t.type==='Deposit'">{{t.user}}</a><a v-if="t.type==='Withdrawal'">{{t.barcode}}</a>
                        </a><a v-if="t.userrole===3">{{t.user}}</a>
                         </td>
                      </tr>
                      <!-- <tr v-if="user.role==9&&betonly.length">
                        <th style="text-align: right" colspan="2">List of Bets</th>
                      </tr> -->
                      <!-- <tr v-for="t in betonly" v-if="user.role==9">
                        <td style="text-align: right" colspan="2">{{t.barcode}} | {{Number(t.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}
                       </td>
                      </tr> -->
                    </thead>
                  </table>

                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent='printme'>ConfirmPrint</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="cashout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content" style="border:none">
                  <div class="modal-header bg-dark">
                    <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Please Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" style="color:gray">
                    That you want to cash out {{Number(user.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}} ?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click.prevent='cashout'>Cash Out</button>
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
          disabled:false,
          date:'',
          forms: new Form({
            id:'',
            amount:'',
            user_id:this.user.id,
            pin:'',
          }),
          pageOfItems: [],
          bet:{},
          transactions:[],
          totalsummary:[],
          betonly:[],
        }
      },
      computed:{
        isDisabled: function(){
        return this.disabled;
       },
      },
      methods:{
        cashout(){
          this.loading = true;
              axios.get('/pick20/cashout').then(response=>{
                if (response.data.error) {
                  Swal.fire(
                    'Success!',
                      response.data.error,
                    'success'
                  )
                }else {
                  this.loading = false;
                  Swal.fire(
                    'Success!',
                    'All cash has been removed from your account.',
                    'success'
                  )
                }
              }).then(()=>{
                window.location.href = "/home"
              });
        },
        cashout1(){
          $('#cashout').modal('show');
        },
        restore(){
          if (!this.forms.pin) {
            Swal.fire(
              'Ooops!',
              'Pin Required.',
              'error'
            );
          }else
           {
            this.forms.post('/pick20/restoresession').then(response=>{
              if (response.data.error) {
                this.disabled=false;
                Swal.fire(
                  'Ooops!',
                  response.data.error,
                  'error'
                );
              }else {
                window.location.href = "/home"
              }
            })
          }

        },
        restoresession(){
          $('#restore').modal('show');
        },
        printme(){
          this.date = new Date();
          if (this.user.role == 4) {

            this.$htmlToPaper('printMe2');
          }
          if (this.user.role == 9) {

            this.$htmlToPaper('printMe');
          }
          // $('#confirmprint').modal('hide');
        },
        logout(){
          document.getElementById('logout-form').submit();
        },
        cashinconfirmed(){

          if (!this.forms.pin) {
            Swal.fire(
              'Ooops!',
              'Pin Required.',
              'error'
            );
          }else {
          if (this.forms.amount>=100) {
            this.disabled=true;
            Swal.fire({
              title: 'Please Confirm',
              text: "That you want to cash in "+this.forms.amount+' ?',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {

              if (result.isConfirmed) {
                this.forms.post('/pick20/cashin').then(response=>{
                  if (response.data.error) {
                    this.disabled=false;
                    Swal.fire(
                      'Ooops!',
                      response.data.error,
                      'error'
                    );
                  }else {
                    this.disabled=false;
                    this.forms.amount='';
                    this.forms.pin='';
                    Swal.fire(
                      'Success!',
                      'Cash in added to your account.',
                      'success'
                    );
                    window.location.href = "https://pickbets.pitlive.ph/"
                  }
                }).then(()=>{

                });
              }else {
                this.disabled=false;
              }
            })
          }else {
            this.disabled=false;
            Swal.fire(
              'Ooops!',
              'Please make sure that the amount is 100 minimum.',
              'error'
            );
          }
        }
        },
        cashin(){
          $('#cashin').modal('show');
        },
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        totalsummaryreciept(){
          this.loading = true;
          axios.get('/pick20/totalsummary').then(response=>{
            this.loading = false;
            this.totalsummary=response.data;
            this.date = new Date();
          }).catch(()=>{
            this.loading = false;
          })
        },
        getmysummary(page = 1){
          this.loading = true;
          axios.get('/pick20/getmysummary?page='+page).then(response=>{
            this.loading = false;
            this.bet = response.data;
            this.getmysummary2();
            this.totalsummaryreciept();
            this.getbetonly();
          }).catch(()=>{
            this.loading = false;
          })
        },
        getbetonly(){
          axios.get('/pick20/getbetonly').then(response=>{
            this.betonly = response.data;
          })
        },
        getmysummary2(page = 1){
          this.loading = true;
          axios.get('/pick20/getmysummary2?page='+page).then(response=>{
            this.loading = false;
            this.transactions = response.data;
            // this.getmysummary2();
          })
        }
      },
        mounted() {

          document.addEventListener("backbutton", this.yourCallBackFunction, false);
          this.getmysummary();
        }
    }
</script>
