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
                  Spotcheck Arena Overview
                </div>
                <div class="card-body table-responsive" style="">
                  <table class="table table-hover table-stripped">
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Initial Account Cash</th>
                        <th class="font-weight-bold">Total Deposit</th>
                        <th class="font-weight-bold">Total Withdraw</th>
                        <th class="font-weight-bold">Total Rake</th>
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
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Current Player Cash</th>
                        <th class="font-weight-bold">Cashier/Teller Cash</th>
                        <th class="font-weight-bold">Cashier/Teller Cashin</th>
                        <th class="font-weight-bold">Total Payout Paid</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in allspotcheck">
                        <td><a v-if="t.totalplayercash>0" class="text-success font-weight-bold">{{Number(t.totalplayercash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalplayercash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalcashiercash>0" class="text-success font-weight-bold">{{Number(t.totalcashiercash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalcashiercash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalcashin>0" class="text-success font-weight-bold">{{Number(t.totalcashin).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalcashin).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalpayout>0" class="text-danger font-weight-bold">{{Number(t.totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalpayout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Total Bets of Pick 20</th>
                        <th class="font-weight-bold">Total Bets of Pick 2</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 20</th>
                        <th class="font-weight-bold">Total Unclaimed of Pick 2</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in allspotcheck">
                        <td><a v-if="t.totalbetspick20>0" class="text-success font-weight-bold">{{Number(t.totalbetspick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalbetspick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalbetspick2>0" class="text-success font-weight-bold">{{Number(t.totalbetspick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalbetspick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalunclaimedpick20>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalunclaimedpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalunclaimedpick2>0" class="text-success font-weight-bold">{{Number(t.totalunclaimedpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalunclaimedpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Breakage of Pick 20</th>
                        <th class="font-weight-bold">Breakage of Pick 2</th>
                        <th class="font-weight-bold">Total Breakage</th>
                        <th class="font-weight-bold" colspan="2">Total Contingency Funds</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in allspotcheck">
                        <td><a v-if="t.breakageofpick20>0" class="text-success font-weight-bold">{{Number(t.breakageofpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.breakageofpick20).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.breakageofpick2>0" class="text-success font-weight-bold">{{Number(t.breakageofpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.breakageofpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.totalbreakage>0" class="text-success font-weight-bold">{{Number(t.totalbreakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                          {{Number(t.totalbreakage).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                        <td><a v-if="t.contingencyfunds>0" class="text-success font-weight-bold">{{Number(t.contingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else class="text-danger font-weight-bold">
                          {{Number(t.contingencyfunds).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Total Cancelled Bets Pick 2</th>
                        <th class="font-weight-bold">Total Cancelled Bets Payout Pick 2</th>
                        <th class="font-weight-bold">Total Cancelled Bets Unclaimed</th>
                        <th class="font-weight-bold">Total Cancelled Bets Claimed</th>
                      </tr>
                    </thead>
                    <tr v-for="t in allspotcheck">
                      <td><a v-if="t.totalcancelledpick2>0" class="text-success font-weight-bold">{{Number(t.totalcancelledpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                        {{Number(t.totalcancelledpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      <td><a v-if="t.cancelledpayoutpick2>0" class="text-success font-weight-bold">{{Number(t.cancelledpayoutpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                        {{Number(t.cancelledpayoutpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      <td><a v-if="t.uncalimedcancelledpick2>0" class="text-success font-weight-bold">{{Number(t.uncalimedcancelledpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                        {{Number(t.uncalimedcancelledpick2).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                      <td><a v-if="t.totalcancelledpayoutclaimed>0" class="text-danger font-weight-bold">{{Number(t.totalcancelledpayoutclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a> <a v-else class="text-danger font-weight-bold">
                        {{Number(t.totalcancelledpayoutclaimed).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                    </tr>
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
