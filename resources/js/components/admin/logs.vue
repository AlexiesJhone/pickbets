<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-tabs card-header-dark bg-dark">
                  <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                      <ul class="nav nav-tabs" data-tabs="tabs">
                        <li class="nav-item">
                          <a class="nav-link" href="#profile" data-toggle="tab">
                            <i class="material-icons">search</i> Search
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="card-body" style="padding:0.5rem">
                  <table class="table table-sm">
                    <thead>
                      <tr class="text-muted font-weight-bold">
                        <th><b>Log ID</b></th>
                        <th><b>Username</b></th>
                        <th><b>Type</b></th>
                        <th><b>Group</b></th>
                        <th><b>Date</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td> <input type="text" name="" value="" placeholder="log id" class="form-control" v-model="search.logid"> </td>
                        <td>
                        <v-select v-model="search.username" class="col-sm-12" :options="users" placeholder="Choose User" :reduce="username => username.id" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></center>
                        </td>
                        <td><v-select v-model='search.type'  class="col-sm-12" :options="items" placeholder="Choose Action Type" :reduce="type => type.type" id="user" label="type" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></center></td>
                        <td><v-select v-model='search.group'  class="col-sm-12" :options="groupsname" placeholder="Choose Group" :reduce="name => name.id" id="user" label="name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></center></td>
                        <td> <input type="date" name="" value="" class="form-control" v-model="search.date"> </td>
                      </tr>
                    </tbody>
                  </table>

                </div>
                <div class="card-footer">
                  <a class="btn btn-success btn-sm col-md-12 text-white" @click.prevent='searchs'>Search</a>
                </div>
                <div class="card-footer">
                  <a class="btn btn-default btn-sm col-md-12 text-white" @click.prevent='clearsearch'>Clear Search</a>
                </div>
              </div>
              <div class="card">
              <div class="card-header card-header-tabs card-header-dark bg-dark">
                <div class="nav-tabs-navigation">
                  <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item">
                        <a class="nav-link" href="#profile" data-toggle="tab">
                          <i class="material-icons">history</i> Logs
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active table-responsive" id="profile">
                    <!-- <input type="text" class="form-control" placeholder="Search username, type, description on this page.." v-model="searchQuery"> -->
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">Log Id</th>
                          <th class="font-weight-bold">Date/Time</th>
                          <th class="font-weight-bold">Username</th>
                          <th class="font-weight-bold">Type</th>
                          <th class="font-weight-bold">Log</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="t in laravelData.data" :key='t.id'>
                          <td class="">{{t.id}}</td>
                          <td>{{t.created_at | datetime}}</td>
                          <td v-if='t.user'>{{t.user.username}}</td>
                          <td>{{t.type}}</td>
                          <td><pre>{{t.message}}</pre></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer justify-content-center table-responsive">
                  <center>
                    <pagination :data="laravelData" :show-disabled=true :limit='5' @pagination-change-page="getResults">
                        <span slot="prev-nav">&lt; Previous</span>
                        <span slot="next-nav">Next &gt;</span>
                    </pagination>
                    </center>
                  <!-- <jw-pagination :items="groups" @changePage="onChangePage" :maxPages='5' :pageSize='10' v-if="groups.length"></jw-pagination> -->
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
          searchQuery:'',
          logs:[],
          users:[],
          groupsname:[],
          laravelData:{},
          items: [
            { type: 'Login' },
            { type: 'Logout' },
            { type: 'Insert_Bet' },
            { type: 'Mismatch_Results' },
            { type: 'Requested_Grade' },
            { type: 'Confirmed_Grade' },
            { type: 'Change_Status_Event' },
            { type: 'Abono' },
            { type: 'Reprint_Receipt' },
            { type: 'No_Winners' },
            { type: 'Confirmed_Close_Claiming_Bets' },
            { type: 'Winners' },
            { type: 'Open_Startingfight' },
            { type: 'Close_Startingfight' },
            { type: 'Withdrawal' },
            { type: 'Confirmed_Withdrawal' },
            { type: 'Request_Withdrawal' },
            { type: 'Deposit' },
            { type: 'Create_New_User' },
            { type: 'Cash_In' },
            { type: 'Forced_Cash-Out' },
            { type: 'Cash_Out' },
            { type: 'Update_User_Details' },
            { type: 'Update_Group_Details' },
            { type: 'Derby_Program' },
            { type: 'Updated_Pin' },
            { type: 'Incorrect_Pin' },
            { type: 'Set_Announcement' },
            { type: 'Removed_Announcement' },
            { type: 'Open_Claiming' },
            // { type: 'Withdrawal_Cancelled' },
          ],
          search:new Form({
            logid:'',
            username:'',
            type:'',
            date:'',
            group:'',
          }),
        }
      },
      methods:{
        searchs(){
          this.loading = true;
          this.search.post('/pick20/searchuser').then(response=>{
            // this.search.reset();
            this.loading=false;
            this.laravelData=response.data;
          }).catch(()=>{
            this.loading=false;
            // this.search.reset();
          })
        },
        getallgroupname(){
          this.loading=true;
          axios.get('/pick20/getallgroupname').then(response=>{
            this.loading=false;
            this.groupsname = response.data;
          })
        },
        getallusers(){
          this.loading=true;
          axios.get('/pick20/allusers2').then(response=>{
            this.loading=false;
            this.users=response.data;
          })
        },
        getResults(page = 1) {
            this.loading=true;
            this.search.post('/pick20/searchuser?page=' + page)
                .then(response => {
                    this.loading=false;
                    this.laravelData = response.data;
                }).catch(()=>{
                  this.loading=false;
                });
        },
        clearsearch(page = 1) {
          this.search.reset();
          this.loading=true;
            this.search.post('/pick20/searchuser?page=' + page)
                .then(response => {
                    this.laravelData = response.data;
                    this.loading=false;
                }).catch(()=>{
                  this.loading=false;
                });
        },
      },
      mounted() {
        this.getallgroupname();
        this.getallusers();
        this.getResults();
      }
    }
</script>
