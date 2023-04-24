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
                          <a class="nav-link" >
                            <i class="material-icons">group</i> Users
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item" v-if="user.role===1">
                          <a class="nav-link" @click.prevent="showaddusermodal">
                            <i class="material-icons text-success">add_box</i> Add new user
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>

                    </div>
                  </div>
                </div>
                  <div class="card-body table-responsive">
                    <!-- <input type="text" class="form-control" placeholder="Search Username" id="myInput" onkeyup="myFunction()"> -->
                    <table class="table table-sm">
                      <thead>
                        <tr class="text-muted font-weight-bold">
                          <th><b>User ID</b></th>
                          <th><b>Username</b></th>
                          <th><b>Name</b></th>
                          <th><b>group</b></th>
                          <th><b>Phone</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td> <input type="text" name="" value="" placeholder="User id" class="form-control" v-model="search.id"> </td>
                          <td>
                          <v-select v-model="search.username" class="col-sm-12" :options="searchusers" placeholder="Choose User" :reduce="username => username.username" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></center>
                          </td>
                          <td><v-select v-model="search.name" class="col-sm-12" :options="searchusers" placeholder="Choose Name" :reduce="name => name.name" id="user" label="name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></center></td>
                          <td><v-select v-model="search.group" class="col-sm-12" :options="groupsname" placeholder="Choose Group" :reduce="name => name.id" id="user" label="name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></td>
                          <td><input type="number" name="" value="" placeholder="Phone Number" class="form-control" v-model="search.phone"></td>
                        </tr>
                      </tbody>
                    </table>
                    <!-- <input type="text" class="form-control" placeholder="username" v-model="search.name"> -->
                    <!-- table -->
                    <a class="btn btn-success btn-sm text-white col-md-12" @click.prevent="getallusers">Search</a>
                    <a class="btn btn-default text-white btn-sm col-md-12" @click.prevent="getallusersclear">Clear</a>
                        <table class="table table-sm" id="myTable">
                          <thead>
                            <tr>
                              <th class="font-weight-bold">User Id</th>
                              <th class="font-weight-bold">Name</th>
                              <th class="font-weight-bold">Username</th>
                              <th class="font-weight-bold">Email</th>
                              <th class="font-weight-bold">Phone</th>
                              <th class="font-weight-bold">Status</th>
                              <th class="font-weight-bold">Cash</th>
                              <th class="font-weight-bold">Role</th>
                              <th class="font-weight-bold">Group</th>
                              <th class="font-weight-bold">Added last</th>
                              <th class="font-weight-bold">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="t in users.data">
                              <td>{{t.id}}</td>
                              <td>{{t.name}}</td>
                              <td>{{t.username}}</td>
                              <td>{{t.email}}</td>
                              <td>{{t.pnumber}}</td>
                              <td><a class="text-success" v-if="t.active===1">Active</a><a class="text-danger" v-if="t.active===0">Deactivated</a></td>
                              <td>{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                              <td> <a v-if="t.role===1">Admin</a><a v-if="t.role===0">Teller</a><a v-if="t.role===3">Mobile Player</a><a v-if="t.role===4">Cashier</a><a v-if="t.role===5">Declarator</a>
                                <a v-if="t.role===6">CSR</a><a v-if="t.role===7">Boss/Manager</a> </td>
                              <td><a v-if="t.group.name">{{t.group.name}}</a><a v-else>-</a> </td>
                              <td> {{t.created_at | datef}} </td>
                              <td> <a class="btn btn-info bg-info btn-sm text-white"@click.prevent='showeditusermodal(t)' v-if="user.role===1">Edit</a>
                              <a class="btn btn-success btn-sm text-white"@click.prevent='showuserdetails(t)' v-if="t.role===0||t.role===4||t.role===3">View User Details</a>
                              <a v-else class="btn btn-success btn-sm text-white disabled">View User Details</a> </td>
                            </tr>
                          </tbody>
                        </table>
                        <div class="card-footer justify-content-center">
                          <!-- <jw-pagination :items="users" @changePage="onChangePage" :maxPages='5' :pageSize='10' v-if="users.length"></jw-pagination> -->
                          <pagination class="justify-content-center" :data="users" :limit='5' @pagination-change-page="getallusers">
                            <!-- <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span> -->
                          </pagination>
                        </div>
                      </div>
              </div>
              <!-- Modal -->
              <div class="modal fade" id="usermodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content" style="border:none !important;">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel" v-if="usermodalx==='add'">Add User</h5>
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel" v-if="usermodalx==='edit'">Edit User</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="mt-3">
                        <label for="name">Name</label>
                        <div class="input-group">
                          <input type="text" v-model="form.name"class="form-control" value="" required>
                        </div>
                        <div v-if="form.errors.has('name')" v-html="form.errors.get('name')"  class="text-danger font-weight-bold" />
                      </div>
                      <div class="mt-3">
                        <label for="name">Username</label>
                        <div class="input-group">
                          <input type="text" v-model="form.username"class="form-control" value="" required>
                        </div>
                        <div v-if="form.errors.has('username')" v-html="form.errors.get('username')"  class="text-danger font-weight-bold" />
                      </div>
                      <div class="mt-3">
                        <label for="name">Email</label>
                        <div class="input-group">
                          <input class="form-control" type="email" id="email" v-model='form.email' required>
                        </div>
                        <div v-if="form.errors.has('email')" v-html="form.errors.get('email')"  class="text-danger font-weight-bold" />
                      </div>
                      <div class="mt-3">
                        <label for="phone">Phone Number</label>
                        <div class="input-group">
                          <input class="form-control" type="number" id="phone" v-model='form.pnumber' required>
                        </div>
                        <div v-if="form.errors.has('pnumber')" v-html="form.errors.get('pnumber')"  class="text-danger font-weight-bold" />
                      </div>
                      <label for="role">Role</label>
                      <select class="form-control" id="role" v-model="form.role" required>
                        <option value="1">Admin</option>
                        <option value="0">teller</option>
                        <option value="3">Mobile Player</option>
                        <option value="4">Cashier</option>
                        <option value="5">Declarator</option>
                        <option value="6">CSR</option>
                        <option value="7">Boss/Manager</option>
                      </select>
                      <div v-if="form.errors.has('role')" v-html="form.errors.get('role')"  class="text-danger font-weight-bold" />
                      <label for="role">Status</label>
                      <select class="form-control" id="role" v-model="form.active" required>
                        <option value="1">Active</option>
                        <option value="0">Deactivate</option>
                      </select>
                      <div v-if="form.errors.has('active')" v-html="form.errors.get('active')"  class="text-danger font-weight-bold" />
                      <div class="mt-3">
                        <label for="group">Group</label>
                        <div class="input-group">
                          <select class="form-control" id="role" v-model="form.group_id" required>
                           <option v-bind:value="t.id" v-for="t in groupsname">{{t.name}}</option>
                          </select>
                        </div>
                        <div v-if="form.errors.has('group_id')" v-html="form.errors.get('group_id')"  class="text-danger font-weight-bold" />
                      </div>
                      <div class="mt-3">
                        <label for="password">Password</label>
                        <div class="input-group">
                          <input class="form-control" type="password" id="password" v-model='form.password' required>
                        </div>
                        <div v-if="form.errors.has('password')" v-html="form.errors.get('password')"  class="text-danger font-weight-bold" />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                      <button type="button" class="btn btn-success btn-sm" @click.prevent='adduser' v-if="usermodalx==='add'" :disabled='disablethis'>Add User</button>
                      <button type="button" class="btn btn-success btn-sm"  @click.prevent='edituser' v-if="usermodalx==='edit'" :disabled='disablethis'>Edit User</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- User details -->
              <div class="modal modal1 fade" id="userdetailsmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="userdetailed">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1" style="border:none !important;">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">User Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="">
                            <th class="font-weight-bold">Username</th>
                            <th class="font-weight-bold">Name</th>
                            <th class="font-weight-bold" colspan="3">Group Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in userdetailed"  :key="">
                            <td>{{t.username}}</td>
                            <td>{{t.name}}</td>
                            <td colspan="3">{{t.groupname}}</td>
                          </tr>
                          <tr v-for="t in userdetailed"  :key="">
                            <td class="font-weight-bold">Current Cash</td>
                            <td colspan="4">{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead class="bg-dark text-warning text-center">
                          <tr>
                            <th  class="font-weight-bold" colspan="5">Select Events</th>
                          </tr>
                        </thead>
                        <thead>
                          <tr>
                            <th colspan="5"><v-select v-model='form.event_id' :options="eventsdetailed" placeholder="Select event and click search.." :reduce="event_name => event_name.id" id="user" label="event_name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></th>
                            </tr>
                            <tr>
                              <th colspan="5">
                                <a class="btn btn-success col-md-12 text-white" @click.prevent='showeventspager2'>Search Event</a>
                                <a class="btn btn-default col-md-12 text-white" @click.prevent='clearshoweventspager2'>Clear Search Event</a>
                              </th>
                            </tr>
                        </thead>
                        <thead>
                          <tr>
                          <th class="font-weight-bold">Event Id</th>
                          <th class="font-weight-bold">Event Name</th>
                          <th class="font-weight-bold">Fight Date</th>
                          <th class="font-weight-bold">Status</th>
                          <th class="font-weight-bold">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in eventsdetailedpage.data" >
                            <th>{{t.id}}</th>
                            <td>{{t.event_name}}</td>
                            <td>{{t.fightdate|datec}}</td>
                            <td> <a class="font-weight-bold text-success" v-if="t.status==1">Active</a><a class="font-weight-bold text-info" v-if="t.status==2">Finished</a><a class="font-weight-bold text-danger" v-if="t.status==3">Pending</a> </td>
                            <td> <a class="btn btn-sm btn-success text-white" @click.prevent='checkeventdetails(t)'>View Bets</a></td>
                          </tr>
                          <tr>
                            <th colspan="5">
                              <pagination class="justify-content-center" :data="eventsdetailedpage" :limit='5' @pagination-change-page="showeventspager">
                              </pagination>
                            </th>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- User details2 -->
              <div class="modal modal1 fade" id="userdetailsmodal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="userdetailed">
                <div class="modal-dialog modal-dialog1">
                  <div class="modal-content modal-content1" style="border:none !important;">
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">User Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body modal-body1" style="padding:0">
                      <table class="table table-hover table-stripped table-bordered">
                        <thead>
                          <tr class="">
                            <th colspan="3" class="font-weight-bold">Username</th>
                            <th colspan="2" class="font-weight-bold">Name</th>
                            <th colspan="3" class="font-weight-bold">Group Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in userdetailed"  :key="">
                            <td colspan="3">{{t.username}}</td>
                            <td colspan="2">{{t.name}}</td>
                            <td colspan="3">{{t.groupname}}</td>
                          </tr>
                          <tr v-for="t in userdetailed"  :key="">
                            <td class="font-weight-bold"colspan="3">Current Cash</td>
                            <td colspan="5">{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead class="bg-dark text-warning text-center">
                          <tr>
                            <th  class="font-weight-bold" colspan="8">List of Bets</th>
                          </tr>
                        </thead>
                        <thead>
                          <tr>
                            <th class="font-weight-bold">startingfight</th>
                            <th class="font-weight-bold">bet Id</th>
                            <th class="font-weight-bold">bet</th>
                            <th class="font-weight-bold">Date</th>
                            <th class="font-weight-bold">Amount</th>
                            <th class="font-weight-bold">Status</th>
                            <th class="font-weight-bold">Payout</th>
                            <th class="font-weight-bold">Starting Balance</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="s in eventsdetailedmodal.data">
                            <td class="font-weight-bold">{{s.startingfight}}</td>
                            <td>{{s.id}}</td>
                            <td>{{s.bet}}</td>
                            <td>{{s.created_at|datef}}</td>
                            <td>{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td> <a v-if="s.winner===1||s.winner===2" class="text-success">Winner</a><a v-else-if="s.winner===3" class="text-danger">Loss</a><a v-else class="text-danger">Pending</a> </td>
                            <td><a v-if="s.winner===1||s.winner===2" class="text-success">+{{Number(s.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else-if="s.winner===3" class="text-danger">
                              -{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>-</a> </td>
                            <td>{{Number(s.startingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                          <tr>
                            <th colspan="8">
                              <pagination class="justify-content-center" :data="eventsdetailedmodal" :limit='5' @pagination-change-page="checkeventdetailspage">
                                <!-- <span slot="prev-nav">&lt; Previous</span>
                                <span slot="next-nav">Next &gt;</span> -->
                              </pagination>
                            </th>
                          </tr>
                        </tbody>
                        <thead class="bg-dark text-warning text-center">
                          <tr>
                            <th  class="font-weight-bold" colspan="8">List of Transactions</th>
                          </tr>
                        </thead>
                        <thead>
                          <tr>
                            <th class="font-weight-bold">Processed at</th>
                            <th class="font-weight-bold">Transaction</th>
                            <th class="font-weight-bold">Starting Balance</th>
                            <th class="font-weight-bold">Amount</th>
                            <th class="font-weight-bold">Ending Balance</th>
                            <th class="font-weight-bold">Transacted By</th>
                            <th class="font-weight-bold">Transacted To/Barcode</th>
                            <th colspan="1" class="font-weight-bold">Group</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="s in eventsdetailedmodal2.data">
                            <td>{{s.created_at|datef}}</td>
                            <td>{{s.type}}</td>
                            <td><a v-if="form.role===0||form.role===4">{{Number(s.startingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else-if="form.role===3">{{Number(s.startingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                            <td class="text-success">{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td><a v-if="form.role===0||form.role===4">{{Number(s.endingbalancecashier).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a>
                              <a v-else-if="form.role===3">{{Number(s.endingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a></td>
                            <td>{{s.cashier}}</td>
                            <td><a v-if="form.role===0||form.role===4">{{s.user}}/{{s.barcode}}</a><a v-else-if="s.userrole===3">{{s.user}}</a> </td>
                            <td colspan="1">{{s.group}}</td>
                          </tr>
                          <tr>
                            <th colspan="8">
                              <pagination class="justify-content-center" :data="eventsdetailedmodal2" :limit='5' @pagination-change-page="checkeventdetailstrans">
                                <!-- <span slot="prev-nav">&lt; Previous</span>
                                <span slot="next-nav">Next &gt;</span> -->
                              </pagination>
                            </th>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                    </div>
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
          users:{},
          groups:{},
          usermodalx:'',
          disabled:false,
          searchQuery:'',
          userdetailed:[],
          searchusers:[],
          eventsdetailed:[],
          eventsdetailedpage:{},
          eventsdetailedmodal:{},
          eventsdetailedmodal2:{},
          pageOfItems: [],
          groupsname: [],
          errors: null,
          groupname:'',
          search:new Form({
            id:'',
            name:'',
            username:'',
            group:'',
            phone:'',
          }),
          form: new Form({
            event_id:'',
            id:'',
            name:'',
            username:'',
            email:'',
            password:'',
            role:'',
            group_id:'',
            active:'',
            pnumber:'',
          })
        }
      },
      computed:{
        disablethis: function(){
          if (this.disabled===false) {
            return this.disabled=false;
          }else {
            return this.disabled=true;
          }
       },
       resultQuery(){
          if(this.searchQuery){
          return this.pageOfItems.filter((item)=>{
            return this.searchQuery.toLowerCase().split(' ').every(v => item.username.toLowerCase().includes(v)||item.name.toLowerCase().includes(v)||item.email.toLowerCase().includes(v)||item.group.name.toLowerCase().includes(v))
          })
          }else{
            return this.pageOfItems;
          }
        }
      },
      methods:{
        searching(){
          this.loading=true;
          this.search.post('/pick20/search').then(response=>{
            this.loading=false;
            this.users=response.data;
          }).catch(()=>{
            this.loading=false;
          })
        },
        checkeventdetailstrans(page=1){
          this.loading=true;
              this.form.post('/pick20/checkeventdetails2?page='+page).then(response=>{
                this.loading=false;
                this.eventsdetailedmodal = {};
                this.eventsdetailedmodal2 = response.data;
                $('#userdetailsmodal2').modal('show');
            });
        },
        checkeventdetailspage(page=1){
          this.loading=true;
          this.form.post('/pick20/checkeventdetails?page='+page).then(response=>{
            this.eventsdetailedmodal = {};
            this.eventsdetailedmodal = response.data;
            this.loading=false;
            //   this.form.post('/pick20/checkeventdetails2?page='+page).then(response=>{
            //     this.eventsdetailedmodal2 = response.data;
            // });
            $('#userdetailsmodal2').modal('show');
          })
        },
        checkeventdetails(t,page=1){
          this.loading=true;
          this.form.event_id=t.id;
          this.form.post('/pick20/checkeventdetails?page='+page).then(response=>{
            this.eventsdetailedmodal = {};
            this.eventsdetailedmodal = response.data;
            this.loading=false;
              this.form.post('/pick20/checkeventdetails2?page='+page).then(response=>{
                this.eventsdetailedmodal2 = response.data;
            });
            $('#userdetailsmodal2').modal('show');
          })
        },
        showeventspager2(page=1){
          this.loading = true;
          this.form.post('/pick20/geteventsdatailedpage?page='+page).then(response=>{
            this.loading=false;
            this.eventsdetailedpage = response.data;
            // $('#userdetailsmodal').modal('show');
          })
        },
        clearshoweventspager2(page=1){
          this.loading = true;
          this.form.event_id='';
          this.form.post('/pick20/geteventsdatailedpage?page='+page).then(response=>{
            this.loading=false;
            this.eventsdetailedpage = response.data;
            // $('#userdetailsmodal').modal('show');
          })
        },
        showuserdetails(t,page=1){
          this.form.id = t.id;
          this.form.role = t.role;
          this.loading=true;
          this.form.post('/pick20/getuserdatailed').then(response=>{
            this.userdetailed = response.data;
            this.form.post('/pick20/geteventsdatailed').then(response=>{
              this.loading=false;
              this.eventsdetailed = response.data;
              $('#userdetailsmodal').modal('show');
              this.form.post('/pick20/geteventsdatailedpage?page='+page).then(response=>{
                this.loading=false;
                this.eventsdetailedpage = response.data;
                $('#userdetailsmodal').modal('show');
              })
            })
          })
        },
        showeventspager(page=1){
          this.loading = true;
          this.form.post('/pick20/geteventsdatailedpage?page='+page).then(response=>{
            this.loading=false;
            this.eventsdetailedpage = response.data;
            $('#userdetailsmodal').modal('show');
          })
        },
        getallgroupname(){
          this.loading=true;
          axios.get('/pick20/getallgroupname').then(response=>{
            this.loading=false;
            this.groupsname = response.data;
          })
        },
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        edituser(){
          $('#usermodal').modal('hide');
          Swal.fire({
            title: 'Please Confirm',
            text: "Are you sure that you really want to add this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.form.post('/pick20/edituser').then(()=>{
                this.loading=false;
                this.getallusersclear();
                Swal.fire(
                  'Success',
                  'User has been updated',
                  'success'
                );
              }).catch(()=>{
                $('#usermodal').modal('show');
                this.loading=false;
                Toast.fire({
                  icon: 'error',
                  title: 'User has not been updated <br> please double check all fields'
                });
              })
            }
          })

        },
        showeditusermodal(t){
          this.usermodalx='edit';
          this.form.fill(t);
          $('#usermodal').modal('show');
        },
        adduser(){
          this.disabled=true;
          $('#usermodal').modal('hide');
          Swal.fire({
            title: 'Please Confirm',
            text: "Are you sure that you really want to add this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.form.post('/pick20/adduser').then(()=>{
                this.loading=false;
                this.disabled=false;
                this.getallusersclear();
                Swal.fire({
                  title: 'Success',
                  text: "User has been added",
                  icon: 'success',
                });
                this.form.reset();
              }).catch(e=>{
                this.errors = e.data;
                this.disabled=false;
                this.loading=false;
                $('#usermodal').modal('show');
                Toast.fire({
                  icon: 'error',
                  title: errors['name']
                });
              })
            }
          })
        },
        showaddusermodal(){
          this.disabled=false;
          this.form.reset();
          this.usermodalx='add';
          $('#usermodal').modal('show');
        },
        getallusers(page=1){
          this.loading=true;
          this.search.post('/pick20/allusers?page=' + page).then(response=>{
            this.loading=false;
            this.users=response.data;
          })
        },
        getallusersclear(page=1){
          this.search.reset();
          this.loading=true;
          this.search.post('/pick20/allusers?page=' + page).then(response=>{
            this.loading=false;
            this.users=response.data;
          })
        },
        getallusers2(){
          this.loading=true;
          axios.get('/pick20/allusers2').then(response=>{
            this.loading=false;
            this.searchusers=response.data;
          })
        }
      },
        mounted() {
          // this.getallgroup();
          this.getallgroupname();
          this.getallusers();
          this.getallusers2();
        }
    }
</script>
