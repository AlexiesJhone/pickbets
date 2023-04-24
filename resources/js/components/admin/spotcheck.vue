<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12 row">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Spotcheck
                </div>
                <div class="card-body table-responsive" style="">
                  <table class="table table-hover table-stripped">
                    <thead>
                      <tr>
                        <th colspan="6" class="font-weight-bold text-center bg-dark text-warning">Spotcheck Arena Overview</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Initial Account Cash</th>
                        <th class="font-weight-bold">Total Deposit</th>
                        <th class="font-weight-bold">Total Withdraw</th>
                        <th class="font-weight-bold">Total Rake</th>
                        <th class="font-weight-bold" colspan="2">Current Player Cash</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in allspotcheck">
                        <td><a v-if="t.totalinitial>0" class="text-success font-weight-bold">{{Number(t.totalinitial).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalinitial).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totaldeposit>0" class="text-success font-weight-bold">{{Number(t.totaldeposit).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totaldeposit).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalwithdraw>0" class="text-danger font-weight-bold">{{Number(t.totalwithdraw).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalwithdraw).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalrake>0" class="text-success font-weight-bold">{{Number(t.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalrake).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> </td>
                          <td colspan="2"><a v-if="t.totalplayercash>0" class="text-success font-weight-bold">{{Number(t.totalplayercash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                            {{Number(t.totalplayercash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Cashier/Teller Cash</th>
                        <th class="font-weight-bold">Cashier/Teller Cashin</th>
                        <th class="font-weight-bold">Total Payout Paid</th>
                        <th class="font-weight-bold">Total Breakage</th>
                        <th class="font-weight-bold" colspan="2">Total Contingency Funds</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in allspotcheck">
                        <td><a v-if="t.totalcashiercash>0" class="text-success font-weight-bold">{{Number(t.totalcashiercash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalcashiercash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalcashin>0" class="text-success font-weight-bold">{{Number(t.totalcashin).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalcashin).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalpayout>0" class="text-danger font-weight-bold">{{Number(t.totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                          <td><a v-if="t.totalbreakage>0" class="text-success font-weight-bold">{{Number(t.totalbreakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                            {{Number(t.totalbreakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                            <td colspan="2"><a v-if="t.contingencyfunds>0" class="text-success font-weight-bold">{{Number(t.contingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else class="text-danger font-weight-bold">
                              {{Number(t.contingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                    <!-- <thead>
                      <tr>

                      </tr>
                    </thead> -->
                    <thead>
                      <tr>
                        <th colspan="6" class="font-weight-bold text-center bg-dark text-warning">Pick 20</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Total Bets of Pick 20</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 20</th>
                        <th class="font-weight-bold" colspan="4">Breakage of Pick 20</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in allspotcheck">
                        <td><a v-if="t.totalbetspick20>0" class="text-success font-weight-bold">{{Number(t.totalbetspick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalbetspick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalunclaimedpick20>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalunclaimedpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                          <td colspan="4"><a v-if="t.breakageofpick20>0" class="text-success font-weight-bold">{{Number(t.breakageofpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                            {{Number(t.breakageofpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>

                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th class="font-weight-bold text-center bg-dark text-warning" colspan="6">Pick 2</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Total Bets of Pick 2</th>
                        <th class="font-weight-bold">Breakage of Pick 2</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 2</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 2</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 2 Payout</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 2 Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in allspotcheck">
                        <td><a v-if="t.totalbetspick2>0" class="text-success font-weight-bold">{{Number(t.totalbetspick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalbetspick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.breakageofpick2>0" class="text-success font-weight-bold">{{Number(t.breakageofpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                            {{Number(t.breakageofpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalunclaimedpick2>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                            {{Number(t.totalunclaimedpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalcancelledpick2>0" class="text-success font-weight-bold">{{Number(t.totalcancelledpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                              {{Number(t.totalcancelledpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.cancelledpayoutpick2>0" class="text-success font-weight-bold">{{Number(t.cancelledpayoutpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                              {{Number(t.cancelledpayoutpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td colspan="2"><a v-if="t.uncalimedcancelledpick2>0" class="text-success font-weight-bold">{{Number(t.uncalimedcancelledpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                {{Number(t.uncalimedcancelledpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th class="font-weight-bold text-center bg-dark text-warning" colspan="6">Pick 3</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Total Bets of Pick 3</th>
                        <th class="font-weight-bold">Breakage of Pick 3</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 3</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 3</th>
                        <th class="font-weight-bold" >Total Cancelled Bets Payout Pick 3</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 3 Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>


                    <tr v-for="t in allspotcheck">
                      <td><a v-if="t.totalbetspick3>0" class="text-success font-weight-bold">{{Number(t.totalbetspick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                        {{Number(t.totalbetspick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.breakageofpick3>0" class="text-success font-weight-bold">{{Number(t.breakageofpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.breakageofpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                          <td><a v-if="t.totalunclaimedpick3>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                            {{Number(t.totalunclaimedpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                            <td><a v-if="t.totalcancelledpick3>0" class="text-danger font-weight-bold">{{Number(t.totalcancelledpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                              {{Number(t.totalcancelledpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                              <td><a v-if="t.cancelledpayoutpick3>0" class="text-success font-weight-bold">{{Number(t.cancelledpayoutpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                {{Number(t.cancelledpayoutpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                <td><a v-if="t.uncalimedcancelledpick3>0" class="text-success font-weight-bold">{{Number(t.uncalimedcancelledpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                        {{Number(t.uncalimedcancelledpick3).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                    </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th class="font-weight-bold text-center bg-dark text-warning" colspan="6">Pick 4</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr >
                        <th class="font-weight-bold">Total Bets of Pick 4</th>
                        <th class="font-weight-bold">Breakage of Pick 4</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 4</th>
                        <th class="font-weight-bold">	Total Cancelled Bets Pick 4</th>
                        <th class="font-weight-bold">Total Cancelled Bets Payout Pick 4</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 4 Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="t in allspotcheck" >
                      <td ><a v-if="t.totalbetspick4>0" class="text-success font-weight-bold">{{Number(t.totalbetspick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                        {{Number(t.totalbetspick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.breakageofpick4>0" class="text-success font-weight-bold">{{Number(t.breakageofpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.breakageofpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalunclaimedpick4>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalunclaimedpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                          <td ><a v-if="t.totalcancelledpick4>0" class="text-success font-weight-bold">{{Number(t.totalcancelledpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                            {{Number(t.totalcancelledpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                            <td><a v-if="t.cancelledpayoutpick4>0" class="text-success font-weight-bold">{{Number(t.cancelledpayoutpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                              {{Number(t.cancelledpayoutpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                              <td><a v-if="t.uncalimedcancelledpick4>0" class="text-success font-weight-bold">{{Number(t.uncalimedcancelledpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                      {{Number(t.uncalimedcancelledpick4).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                    </tr>
                  </tbody>
                  <thead>
                    <tr>
                      <th class="font-weight-bold text-center bg-dark text-warning" colspan="6">Pick 5</th>
                    </tr>
                  </thead>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Total Bets of Pick 5</th>
                        <th class="font-weight-bold">Breakage of Pick 5</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 5</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 5</th>
                        <th class="font-weight-bold">Total Cancelled Bets Payout Pick 5</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 5 Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="t in allspotcheck" >
                      <td ><a v-if="t.totalbetspick5>0" class="text-success font-weight-bold">{{Number(t.totalbetspick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                        {{Number(t.totalbetspick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.breakageofpick5>0" class="text-success font-weight-bold">{{Number(t.breakageofpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.breakageofpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                          <td><a v-if="t.totalunclaimedpick5>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                            {{Number(t.totalunclaimedpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                            <td ><a v-if="t.totalcancelledpick5>0" class="text-success font-weight-bold">{{Number(t.totalcancelledpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                              {{Number(t.totalcancelledpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                              <td><a v-if="t.cancelledpayoutpick5>0" class="text-success font-weight-bold">{{Number(t.cancelledpayoutpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                {{Number(t.cancelledpayoutpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                <td><a v-if="t.uncalimedcancelledpick5>0" class="text-success font-weight-bold">{{Number(t.uncalimedcancelledpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                        {{Number(t.uncalimedcancelledpick5).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                    </tr>
                  </tbody>
                  <thead>
                    <tr>
                      <th class="font-weight-bold text-center bg-dark text-warning" colspan="6">Pick 6</th>
                    </tr>
                  </thead>
                    <thead>
                      <tr >
                        <th class="font-weight-bold">Total Bets of Pick 6</th>
                        <th class="font-weight-bold">Breakage of Pick 6</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 6</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 6</th>
                        <th class="font-weight-bold">Total Cancelled Bets Payout Pick 6</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 6 Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>

                      <tr v-for="t in allspotcheck" >
                              <td ><a v-if="t.totalbetspick6>0" class="text-success font-weight-bold">{{Number(t.totalbetspick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                {{Number(t.totalbetspick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                <td><a v-if="t.breakageofpick6>0" class="text-success font-weight-bold">{{Number(t.breakageofpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                  {{Number(t.breakageofpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                  <td><a v-if="t.totalunclaimedpick6>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                    {{Number(t.totalunclaimedpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                    <td ><a v-if="t.totalcancelledpick6>0" class="text-success font-weight-bold">{{Number(t.totalcancelledpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                      {{Number(t.totalcancelledpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                      <td><a v-if="t.cancelledpayoutpick6>0" class="text-success font-weight-bold">{{Number(t.cancelledpayoutpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                        {{Number(t.cancelledpayoutpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                        <td><a v-if="t.uncalimedcancelledpick6>0" class="text-success font-weight-bold">{{Number(t.uncalimedcancelledpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                                {{Number(t.uncalimedcancelledpick6).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                  <thead>
                    <tr>
                      <th class="font-weight-bold text-center bg-dark text-warning" colspan="6">Pick 8</th>
                    </tr>
                  </thead>
                    <thead>
                      <tr >
                        <th class="font-weight-bold">Total Bets of Pick 8</th>
                        <th class="font-weight-bold">Breakage of Pick 8</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 8</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 8</th>
                        <th class="font-weight-bold">Total Cancelled Bets Payout Pick 8</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 8 Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>

                      <tr v-for="t in allspotcheck" >
                              <td ><a v-if="t.totalbetspick8>0" class="text-success font-weight-bold">{{Number(t.totalbetspick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                {{Number(t.totalbetspick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                <td><a v-if="t.breakageofpick8>0" class="text-success font-weight-bold">{{Number(t.breakageofpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                  {{Number(t.breakageofpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                  <td><a v-if="t.totalunclaimedpick8>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                    {{Number(t.totalunclaimedpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                    <td ><a v-if="t.totalcancelledpick8>0" class="text-success font-weight-bold">{{Number(t.totalcancelledpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                      {{Number(t.totalcancelledpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                      <td><a v-if="t.cancelledpayoutpick8>0" class="text-success font-weight-bold">{{Number(t.cancelledpayoutpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                        {{Number(t.cancelledpayoutpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                        <td><a v-if="t.uncalimedcancelledpick8>0" class="text-success font-weight-bold">{{Number(t.uncalimedcancelledpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                                {{Number(t.uncalimedcancelledpick8).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                  <thead>
                    <tr>
                      <th class="font-weight-bold text-center bg-dark text-warning" colspan="6">Pick 14</th>
                    </tr>
                  </thead>
                    <thead>
                      <tr >
                        <th class="font-weight-bold">Total Bets of Pick 14</th>
                        <th class="font-weight-bold">Breakage of Pick 14</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 14</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 14</th>
                        <th class="font-weight-bold">Total Cancelled Bets Payout Pick 14</th>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 14 Unclaimed</th>
                      </tr>
                    </thead>
                    <tbody>

                      <tr v-for="t in allspotcheck" >
                              <td ><a v-if="t.totalbetspick14>0" class="text-success font-weight-bold">{{Number(t.totalbetspick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                {{Number(t.totalbetspick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                <td><a v-if="t.breakageofpick14>0" class="text-success font-weight-bold">{{Number(t.breakageofpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                  {{Number(t.breakageofpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                  <td><a v-if="t.totalunclaimedpick14>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                    {{Number(t.totalunclaimedpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                    <td ><a v-if="t.totalcancelledpick14>0" class="text-success font-weight-bold">{{Number(t.totalcancelledpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                      {{Number(t.totalcancelledpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                      <td><a v-if="t.cancelledpayoutpick14>0" class="text-success font-weight-bold">{{Number(t.cancelledpayoutpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                        {{Number(t.cancelledpayoutpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                                        <td><a v-if="t.uncalimedcancelledpick14>0" class="text-success font-weight-bold">{{Number(t.uncalimedcancelledpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                                                {{Number(t.uncalimedcancelledpick14).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                      <!-- <thead>
                        <tr>
                          <th class="font-weight-bold">Total Cancelled Bets Claimed</th>



                        </tr>
                      </thead>
                      <tbody>

                      <tr v-for="t in allspotcheck">
                        <td><a v-if="t.totalcancelledpayoutclaimed>0" class="text-danger font-weight-bold">{{Number(t.totalcancelledpayoutclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalcancelledpayoutclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody> -->
                  </table>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.

                  </div>
                  <download-excel
                    class="btn btn-success btn-sm"
                    :data="allspotcheck"
                    :fields="Arenaoverviewfield"
                    worksheet="My Worksheet"
                    name="Spot_Check_Arena_Overview.xls"
                  >
                    Download Excel
                  </download-excel>
                  <a @click.prevent='refreshspotcheck' class="btn btn-success btn-sm text-white">Refresh Spotcheck</a>
                </div>
              </div>
              <div class="col-sm-6">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Top Player
                </div>
                  <div class="card-body table-responsive" style="height:500px">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">#</th>
                          <th class="font-weight-bold">Group</th>
                          <th class="font-weight-bold">Name/Username</th>
                          <th class="font-weight-bold">Current Balance</th>
                        </tr>
                      </thead>
                        <tbody>
                          <tr v-for="(t,index) in topplayerspotcheck" :index='index'>
                            <td>{{index+1}}</td>
                            <td>{{t.group.name}}</td>
                            <td>{{t.name}}/{{t.username}}</td>
                            <td class="text-success font-weight-bold">{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="card-footer">
                      <div class="stats">
                        <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.

                      </div>
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="topplayerspotcheck"
                        :fields="topplayerspotcheckfields"
                        worksheet="My Worksheet"
                        name="Top_Players.xls"
                      >
                        Download Excel
                      </download-excel>
                    </div>
                </div>
              </div>
              <div class="col-sm-6">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Current Cashier/Teller above 50,000 Cash
                </div>
                  <div class="card-body table-responsive"  style="height:500px">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">#</th>
                          <th class="font-weight-bold">Group</th>
                          <th class="font-weight-bold">Name/Username</th>
                          <th class="font-weight-bold">Cash on hand</th>
                        </tr>
                      </thead>
                        <tbody>
                          <tr v-for="(t,index) in toptellercashier" :index='index'>
                            <td>{{index+1}}</td>
                            <td>{{t.group.name}}</td>
                            <td>{{t.name}}/{{t.username}}</td>
                            <td class="text-success font-weight-bold">{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="card-footer">
                      <div class="stats">
                        <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.

                      </div>
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="toptellercashier"
                        :fields="toptellercashierfield"
                        worksheet="My Worksheet"
                        name="Top_Teller_Cashier.xls"
                      >
                        Download Excel
                      </download-excel>
                    </div>
                </div>
              </div>
              <div class="col-sm-6">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Top Loaders
                </div>
                  <div class="card-body table-responsive" style="height:500px">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">#</th>
                          <th class="font-weight-bold">Group</th>
                          <th class="font-weight-bold">Name/Username</th>
                          <th class="font-weight-bold">Total Deposit</th>
                        </tr>
                      </thead>
                        <tbody>
                          <tr v-for="(t,index) in orderedUsers" :index='index'>
                            <td>{{index+1}}</td>
                            <td>{{t.group.name}}</td>
                            <td>{{t.name}}/{{t.username}}</td>
                            <td class="text-success font-weight-bold">{{Number(t.total).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="card-footer">
                      <div class="stats">
                        <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.

                      </div>
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="orderedUsers"
                        :fields="orderedUsersfields"
                        worksheet="My Worksheet"
                        name="Top_Loaders.xls"
                      >
                        Download Excel
                      </download-excel>
                    </div>
                </div>
              </div>
              <div class="col-sm-6">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Top Withdrawals
                </div>
                  <div class="card-body table-responsive"  style="height:500px">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">#</th>
                          <th class="font-weight-bold">Group</th>
                          <th class="font-weight-bold">Name/Username</th>
                          <th class="font-weight-bold">Total Withdrawals</th>
                        </tr>
                      </thead>
                        <tbody>
                          <tr v-for="(t,index) in orderedUsers1" :index='index'>
                            <td>{{index+1}}</td>
                            <td>{{t.group.name}}</td>
                            <td>{{t.name}}/{{t.username}}</td>
                            <td class="text-success font-weight-bold">{{Number(t.total).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="card-footer">
                      <div class="stats">
                        <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.

                      </div>
                      <download-excel
                        class="btn btn-success btn-sm"
                        :data="orderedUsers1"
                        :fields="orderedUsers1fields"
                        worksheet="My Worksheet"
                        name="Top_Withdrawals.xls"
                      >
                        Download Excel
                      </download-excel>
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
            allspotcheck:[],
            topplayerspotcheck:[],
            toptellercashier:[],
            toploaders:[],
            topwithdrawal:[],
            orderedUsers1fields: {
               'Group': 'group.name',
               'Name': 'name',
               'Username': 'username',
               'Total Deposit': 'total',
             },
            orderedUsersfields: {
               'Group': 'group.name',
               'Name': 'name',
               'Username': 'username',
               'Total Deposit': 'total',
             },
            toptellercashierfield: {
               'ID': 'id',
               'Group': 'group.name',
               'Name': 'name',
               'Username': 'username',
               'Current Balance': 'cash',
             },
            topplayerspotcheckfields: {
               'ID': 'id',
               'Group': 'group.name',
               'Name': 'name',
               'Username': 'username',
               'Current Balance': 'cash',
             },
            Arenaoverviewfield: {
               'Initial Account': 'totalinitial',
               'Total Deposit': 'totaldeposit',
               'Total Withdraw': 'totalwithdraw',
               'Total Rake': 'totalrake',
               'Current Player Cash': 'totalplayercash',
               'Cashier/ Teller Cash': 'totalcashiercash',
               'Cashier/ Teller Cash In': 'totalcashin',
               'Total Payout': 'totalpayout',
               'Total Bets of Pick 20': 'totalbetspick20',
               'Total Bets of Pick 2': 'totalbetspick2',
               'Total Unclaimed of Pick 20': 'totalunclaimedpick20',
               'Total Unclaimed of Pick 2': 'totalunclaimedpick2',
               'Breakage of Pick 20': 'breakageofpick20',
               'Breakage of Pick 2': 'breakageofpick2',
               'Total Breakage': 'totalbreakage',
               'Total Contingency Funds': 'contingencyfunds',
               'Total Cancelled Bets Pick 2': 'totalcancelledpick2',
               'Total Cancelled Bets Pick 2 Payout': 'cancelledpayoutpick2',
               'Total Cancelled Bets Pick 2 Unclaimed': 'totalcancelledpayoutclaimed',
               'Total Bets of Pick 3': 'totalbetspick3',
               'Breakage of Pick 3': 'breakageofpick3',
               'Total Unclaimed of Pick 3': 'totalunclaimedpick3',
               'Total Cancelled Bets Pick 3': 'totalcancelledpick3',
               'Total Cancelled Bets Pick 3 Payout': 'cancelledpayoutpick3',
               'Total Bets of Pick 4': 'totalbetspick4',
               'Breakage of Pick 4': 'breakageofpick4',
               'Total Unclaimed of Pick 4': 'totalunclaimedpick4',
               'Total Cancelled Bets Pick 4': 'totalcancelledpick4',
               'Total Cancelled Bets Pick 4 Payout': 'cancelledpayoutpick4',
               'Total Bets of Pick 5': 'totalbetspick5',
               'Breakage of Pick 5': 'breakageofpick5',
               'Total Unclaimed of Pick 5': 'totalunclaimedpick5',
               'Total Cancelled Bets Pick 5': 'totalcancelledpick5',
               'Total Cancelled Bets Pick 5 Payout': 'cancelledpayoutpick5',
               'Total Bets of Pick 6': 'totalbetspick6',
               'Breakage of Pick 6': 'breakageofpick6',
               'Total Unclaimed of Pick 6': 'totalunclaimedpick6',
               'Total Cancelled Bets Pick 6': 'totalcancelledpick6',
               'Total Cancelled Bets Pick 6 Payout': 'cancelledpayoutpick6',
               'Total Bets of Pick 8': 'totalbetspick8',
               'Breakage of Pick 8': 'breakageofpick8',
               'Total Unclaimed of Pick 8': 'totalunclaimedpick8',
               'Total Cancelled Bets Pick 8': 'totalcancelledpick8',
               'Total Cancelled Bets Pick 8 Payout': 'cancelledpayoutpick8',
             },
          }
        },
        computed: {
          orderedUsers: function () {
            return _.orderBy(this.toploaders, 'total','desc');
          },
          orderedUsers1: function () {
            return _.orderBy(this.topwithdrawal, 'total','desc');
          }
        },
        methods:{
          refreshspotcheck(){
            this.getspotcheck();
          },
          gettopwithdrawals(){
            this.loading=true;
            axios.get('/pick20/topwithdrawals').then(response=>{
              this.loading=false;
              this.topwithdrawal = response.data;
            })
          },
          gettoploaders(){
            this.loading=true;
            axios.get('/pick20/toploaders').then(response=>{
              this.loading=false;
              this.toploaders = response.data;
            })
          },
          gettoptellercashier(){
            this.loading=true;
            axios.get('/pick20/toptellercashier').then(response=>{
              this.loading=false;
              this.toptellercashier = response.data;
            })
          },
          topplayers(){
            this.loading=true;
            axios.get('/pick20/topplayerss').then(response=>{
              this.loading=false;
              this.topplayerspotcheck = response.data;
            })
          },
          getspotcheck(){
            this.loading=true;
            axios.get('/pick20/spotcheck').then(response=>{
              this.loading=false;
              this.allspotcheck = response.data;
            })
          }
        },
        created() {
          this.gettoploaders();
          this.gettoptellercashier();
          this.topplayers();
          this.getspotcheck();
          this.gettopwithdrawals();
        }
    }
</script>
