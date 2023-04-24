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
                    <input type="text" class="form-control" placeholder="Search name, username, email, group name on this page.." v-model="searchQuery">
                        <table class="table table-sm" id="myTable">
                          <thead>
                            <tr>
                              <th class="font-weight-bold">User Id</th>
                              <th class="font-weight-bold">Name</th>
                              <th class="font-weight-bold">Username</th>
                              <th class="font-weight-bold">Email</th>
                              <th class="font-weight-bold">Active</th>
                              <th class="font-weight-bold">Cash</th>
                              <th class="font-weight-bold">Role</th>
                              <th class="font-weight-bold">Group</th>
                              <th class="font-weight-bold">Added last</th>
                              <th class="font-weight-bold">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="t in resultQuery">
                              <td>{{t.id}}</td>
                              <td>{{t.name}}</td>
                              <td>{{t.username}}</td>
                              <td>{{t.email}}</td>
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
                          <jw-pagination :items="users" @changePage="onChangePage" :maxPages='5' :pageSize='10' v-if="users.length"></jw-pagination>
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
                      <label for="name">Name</label>
                      <input class="form-control" type="text" id="name" v-model='form.name'>
                      <label for="username">Username</label>
                      <input class="form-control" type="text" id="username" v-model='form.username'>
                      <label for="email">Email</label>
                      <input class="form-control" type="email" id="email" v-model='form.email'>
                      <label for="role">Role</label>
                      <select class="form-control" id="role" v-model="form.role">
                        <option value="1">Admin</option>
                        <option value="0">teller</option>
                        <option value="3">Mobile Player</option>
                        <option value="4">Cashier</option>
                        <option value="5">Declarator</option>
                        <option value="6">CSR</option>
                        <option value="7">Boss/Manager</option>
                      </select>
                      <label for="role">Group</label>
                      <select class="form-control" id="role" v-model="form.group_id">
                        <option v-bind:value="t.id" v-for="t in groups">{{t.name}}</option>
                      </select>
                      <label for="password">Password</label>
                      <input class="form-control" type="password" id="password" v-model='form.password'>
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
                            <th class="font-weight-bold">Group Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in userdetailed"  :key="">
                            <td>{{t.username}}</td>
                            <td>{{t.name}}</td>
                            <td>{{t.groupname}}</td>
                          </tr>
                          <tr v-for="t in userdetailed"  :key="">
                            <td class="font-weight-bold">Current Cash</td>
                            <td colspan="2">{{Number(t.cash).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                          </tr>
                        </tbody>
                        <thead class="bg-dark text-warning text-center">
                          <tr>
                            <th  class="font-weight-bold" colspan="3">Select Events</th>
                          </tr>
                        </thead>
                        <thead>
                          <tr>
                            <th colspan="3">  <v-select v-model='form.event_id' :options="eventsdetailed" placeholder="Select event and click search.." :reduce="event_name => event_name.id" id="user" label="event_name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></th>
                            </tr>
                            <tr>
                              <th colspan="3">
                                <a class="btn btn-info col-md-12 text-white" @click.prevent='checkeventdetails'>Search Event</a>
                              </th>
                            </tr>
                        </thead>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
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
                        <tbody v-for="t in eventsdetailedmodal">
                          <tr v-for="s in t.bets">
                            <td class="font-weight-bold">{{s.startingfight}}</td>
                            <td>{{s.id}}</td>
                            <td>{{s.bet}}</td>
                            <td>{{s.created_at|datef}}</td>
                            <td>{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                            <td> <a v-if="s.winner===1||s.winner===2" class="text-success">Winner</a><a v-else-if="s.winner===3" class="text-danger">Lose</a><a v-else class="text-danger">Pending</a> </td>
                            <td><a v-if="s.winner===1||s.winner===2" class="text-success">+{{Number(s.result).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else-if="s.winner===3" class="text-danger">
                              -{{Number(s.amount).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</a><a v-else>-</a> </td>
                            <td>{{Number(s.startingbalance).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
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
                        <tbody v-for="t in eventsdetailedmodal">
                          <tr v-for="s in t.transactions">
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
          users:[],
          groups:[],
          usermodalx:'',
          disabled:false,
          searchQuery:'',
          userdetailed:[],
          eventsdetailed:[],
          eventsdetailedmodal:[],
          pageOfItems: [],
          groupname:'',
          form: new Form({
            event_id:'',
            id:'',
            name:'',
            username:'',
            email:'',
            password:'',
            role:'',
            group_id:'',
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
        checkeventdetails(){
          this.loading=true;
          this.form.post('/pick20/checkeventdetails').then(response=>{
            this.loading=false;
            this.eventsdetailedmodal = [];
            this.eventsdetailedmodal = response.data;
            $('#userdetailsmodal2').modal('show');
          })
        },
        showuserdetails(t){
          this.form.id = t.id;
          this.form.role = t.role;
          this.loading=true;
          this.form.post('/pick20/getuserdatailed').then(response=>{
            this.userdetailed = response.data;
            this.form.post('/pick20/geteventsdatailed').then(response=>{
              this.loading=false;
              this.eventsdetailed = response.data;
              $('#userdetailsmodal').modal('show');
            })
          })
        },
        getallgroup(){
          this.loading=false;
          axios.get('/pick20/getallgroups').then(response=>{
            this.loading=false;
            this.groups = response.data;
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
                this.getallusers();
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
                this.getallusers();
                Swal.fire({
                  title: 'Success',
                  text: "User has been added",
                  icon: 'success',
                });
                this.form.reset();
              }).catch(()=>{
                this.disabled=false;
                this.loading=false;
                $('#usermodal').modal('show');
                Toast.fire({
                  icon: 'error',
                  title: 'User has not been added <br> please double check all fields'
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
        getallusers(){
          this.loading=true;
          axios.get('/pick20/allusers').then(response=>{
            this.loading=false;
            this.users=response.data;
          })
        }
      },
        mounted() {
          this.getallgroup();
          this.getallusers();
        }
    }
</script>
