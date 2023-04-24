<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
            <div class="col-md-12">
              <div id="overlay" v-if="loading">
                <tile style="color:white"></tile>
                <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
              </div>
              <div class="card">
              <div class="card-header card-header-tabs card-header-dark bg-dark">
                <div class="nav-tabs-navigation">
                  <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item">
                        <a class="nav-link" href="#profile" data-toggle="tab">
                          <i class="material-icons">group</i> Groups
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item" >
                        <a class="nav-link text-success" @click.prevent="showaddgroup" style="cursor: pointer;">
                          <i class="material-icons text-success">add_box</i> Create New Group
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
                    <table class="table table-sm">
                      <thead>
                        <tr class="text-muted font-weight-bold">
                          <th><b>Group ID</b></th>
                          <th><b>Name</b></th>
                          <th><b>Location</b></th>
                          <th><b>Description</b></th>
                          <th><b>Status</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td> <input type="text" name="" value="" placeholder="Id" class="form-control" v-model="search.id"></td>
                          <td>
                          <input type="text" name="" value="" placeholder="Name" class="form-control" v-model="search.name">
                          </td>
                          <td><input type="text" name="" value="" placeholder="Location" class="form-control" v-model="search.location"></td>
                          <td> <input type="text" name="" value="" class="form-control" v-model="search.description"> </td>
                          <td>   <select class="form-control" name="" v-model="search.active">
                              <option value="1">Active</option>
                              <option value="2">Deactivate</option>
                            </select></td>
                        </tr>
                        <tr>
                          <td colspan="5"> <button type="button" name="button" class="btn btn-success btn-sm col-md-12" @click.prevent='getallgroup'>Search</button>
                                            <button type="button" name="button" class="btn btn-default btn-sm col-md-12"  @click.prevent='clearsearch'>Clear Search</button>  </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">#</th>
                          <th class="font-weight-bold">Name</th>
                          <th class="font-weight-bold">Location</th>
                          <th class="font-weight-bold">Members</th>
                          <th class="font-weight-bold">Description</th>
                          <th class="font-weight-bold">Status</th>
                          <th class="font-weight-bold">Created</th>
                          <th class="font-weight-bold">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="t in groups.data">
                          <td>{{t.id}}</td>
                          <td>{{t.name}}</td>
                          <td>{{t.location}}</td>
                          <td>{{t.users.length}}</td>
                          <td><pre>{{t.description}}</pre></td>
                          <td class="text-success font-weight-bold"> <a class="text-success font-weight-bold" v-if="t.active===1">Active</a><a class="text-danger font-weight-bold" v-else>Deactivated</a> </td>
                          <td>{{t.created_at|datef}}</td>
                          <td> <a class="btn btn-info btn-sm text-white" @click.prevent='editgroup(t)'>Edit</a> <a class="btn btn-success btn-sm text-white" @click.prevent='showusers(t)'>View Users</a><a @click.prevent='copylink(t)' class="btn btn-primary btn-sm text-white">Copy Link Mobile Registration</a> </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer justify-content-center">
                  <pagination :data="groups" :show-disabled=true :limit='5' @pagination-change-page="getallgroup">
                      <span slot="prev-nav">&lt; Previous</span>
                      <span slot="next-nav">Next &gt;</span>
                  </pagination>
                </div>
              </div>
            </div>
            <div class="modal fade" id="group" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content noborder">
                  <div class="modal-header bg-dark">
                    <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel" v-if="modalgroup==='add'">Add Group</h5>
                    <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel" v-if="modalgroup==='edit'">Edit Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <label for="name">Name</label>
                    <input v-model="form.name" type="text" id="name" class="form-control">
                    <label for="location">Location</label>
                    <input v-model="form.location" type="text" id="location" class="form-control">
                    <label for="" v-if="modalgroup==='edit'">Status</label>
                    <select class="form-control" name="" v-model="form.active" v-if="modalgroup==='edit'">
                      <option value="1">Active</option>
                      <option value="2">Deactivate</option>
                    </select>
                    <label for="Description">Description</label>
                    <textarea v-model="form.description" id="Description" class="form-control" rows="3" cols="80"></textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success btn-sm"  v-if="modalgroup==='add'" @click.prevent='addgroup' :disabled='disablethis'>Add Group</button>
                    <button type="button" class="btn btn-success btn-sm"  v-if="modalgroup==='edit'" @click.prevent='updategroup' :disabled='disablethis'>Edit Group</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="users" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content noborder">
                  <div class="modal-header bg-dark">
                    <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">{{groupname}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body table-responsive">
                    <table class="table table-hover table-stripped">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">Name</th>
                          <th class="font-weight-bold">Username</th>
                          <th class="font-weight-bold">Role</th>
                          <th class="font-weight-bold">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><v-select v-model="search.name" class="col-sm-12" :options="groupusersall" placeholder="Choose user" :reduce="name => name.name" id="user" label="name" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></td>
                          <td><v-select v-model="search.username" class="col-sm-12" :options="groupusersall" placeholder="Choose username" :reduce="username => username.username" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></td>
                          <td>
                            <v-select v-model="search.role" class="col-sm-12"
                            :options="[{ role: 'Admin', id: 1 },{ role: 'teller', id: 9 },{ role: 'Mobile Player', id: 3 },{ role: 'Cashier', id: 4 },{ role: 'Declarator', id: 5 },{ role: 'CSR', id: 6 },
                            { role: 'Boss/Manager', id: 7 },{ role: 'Supervisor', id: 2 },{ role: 'Confirm Declarator', id: 8 },{ role: 'Board Admin', id: 10 },]"
                            placeholder="Choose role"
                            :reduce="role =>role.id" id="user" label="role" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                          </td>
                          <td>
                            <v-select v-model="search.status" class="col-sm-12"
                            :options="[{ role: 'Active', id: 1 },{ role: 'Deactivated', id: 2 },]"
                            placeholder="Choose status"
                            :reduce="role =>role.id" id="user" label="role" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                      </td>
                        </tr>
                        <tr>
                          <td colspan="4">
                            <a class="btn btn-sm btn-success col-md-12 text-white" @click.prevent='searchusers'>Search</a>
                            <a class="btn btn-sm btn-default col-md-12 text-white" @click.prevent='searchusersclear'>Clear Search</a>
                          </td>
                        </tr>
                      </tbody>
                      <thead>
                        <tr>
                          <th class="font-weight-bold">Name</th>
                          <th class="font-weight-bold">Username</th>
                          <th class="font-weight-bold">Role</th>
                          <th class="font-weight-bold">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="t in groupusers.data">
                          <td>{{t.name}}</td>
                          <td>{{t.username}}</td>
                          <td><a v-if="t.role===3">Mobile</a><a v-if="t.role===9">Teller</a> <a v-if="t.role===7">Boss</a> <a v-if="t.role===6">CSR</a><a v-if="t.role===4">Cashier</a>
                            <a v-if="t.role===5">Declarator</a><a v-if="t.role===1">Admin</a><a v-if="t.role===2">Supervisor</a> <a v-if="t.role===8">Confirm Declarator</a><a v-if="t.role===10">Board Admin</a>   </td>
                          <td><a v-if="t.active===1" class="text-success">Active</a> <a href="#" v-else class="text-danger">Deactivated</a> </td>
                        </tr>
                        <tr>
                          <th colspan="4">
                            <pagination :data="groupusers" class="justify-content-center" :show-disabled=true :limit='5' @pagination-change-page="showuserspage">
                              <span slot="prev-nav">&lt; Previous</span>
                              <span slot="next-nav">Next &gt;</span>
                          </pagination>
                        </th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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
            groups:{},
            loading:false,
            disabled:false,
            searchQuery:'',
            url:'',
            modalgroup:'',
            groupname:'',
            pageOfItems: [],
            users:[],
            groupusers:{},
            groupusersall:[],
            form: new Form({
              id:'',
              name:'',
              location:'',
              description:'',
              active:'',
              group_id:'',
            }),
            search: new Form({
              username:'',
              name:'',
              role:'',
              status:'',
              group_id:'',
              active:'',
            }),
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
              return this.searchQuery.toLowerCase().split(' ').every(v => item.location.toLowerCase().includes(v)||item.name.toLowerCase().includes(v)||item.description.toLowerCase().includes(v))
            })
            }else{
              return this.pageOfItems;
            }
          }
        },
        methods:{
          searchusersclear(page=1){
            this.loading=true;
            this.search.reset();
            this.form.post('/pick20/getgroupusers?page='+page).then(response=>{
              this.groupusers = response.data;
              this.loading=false;
            });
          },
          searchusers(page=1){
            this.loading=true;
            this.search.group_id=this.form.group_id;
            this.search.post('/pick20/getgroupusers?page='+page).then(response=>{
              this.groupusers = response.data;
              this.loading=false;
            });
          },
          showuserspage(page=1){
            this.loading=true;
            this.form.post('/pick20/getgroupusers?page='+page).then(response=>{
            this.groupusers = response.data;
            $('#users').modal('show');
            this.form.post('/pick20/getgroupusersall').then(response=>{
              this.groupusersall = response.data;
              this.loading=false;
            });
          });
          },
          copylink(t){
            this.url = window.location.origin;
            this.url = this.url+'/registers/'+t.id;
            // console.log(Url.innerHTML)
            // Url.select();
            // document.execCommand("copy");
            navigator.clipboard.writeText(this.url);
            this.url = window.location.origin;
            Toast.fire({
              icon: 'success',
              title: 'Copied to Clipboard'
            });
          },
          showusers(t,page=1){
            this.loading=true;
            this.form.group_id = t.id;
            this.groupname = t.name;
            this.form.post('/pick20/getgroupusers?page='+page).then(response=>{
            this.groupusers = response.data;
            $('#users').modal('show');
            this.form.post('/pick20/getgroupusersall').then(response=>{
              this.groupusersall = response.data;
              this.loading=false;
            });
          });
          },
          onChangePage(pageOfItems) {
              // update page of items
              this.pageOfItems = pageOfItems;
          },
          updategroup(){
            this.disabled=true;
            $('#group').modal('hide');
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {
              if (result.isConfirmed) {
                this.loading=true;
                this.form.post('/pick20/updategroup').then(()=>{
                this.loading=false;
                this.getallgroup();
                Swal.fire(
                  'Success',
                  'Group has been updated',
                  'success'
                );
                }).catch(()=>{
                  this.loading=false;
                  this.disabled=false;
                  Toast.fire({
                    icon: 'error',
                    title: 'Group has not been updated <br> please double check all fields'
                  });
                })
              }
            })

          },
          editgroup(t){
            this.disabled=false;
            this.form.fill(t);
            this.modalgroup='edit';
            $('#group').modal('show');
          },
          getallgroup(page = 1){
            this.loading=false;
            this.search.post('/pick20/getallgroups?page='+page).then(response=>{
              this.loading=false;
              this.groups = response.data;
            })
          },
          clearsearch(page = 1){
            this.search.reset();
            this.loading=false;
            this.search.post('/pick20/getallgroups?page='+page).then(response=>{
              this.loading=false;
              this.groups = response.data;
            })
          },
          addgroup(){
            this.disabled=true;
            $('#group').modal('hide');
            Swal.fire({
              title: 'Please Confirm',
              text: "Do you really want to add this group?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {
              if (result.isConfirmed) {
                this.loading=true;
                this.form.post('/pick20/addgroup').then(()=>{
                  this.loading=false;
                  this.getallgroup();
                  Swal.fire(
                    'Success',
                    'Group has been added',
                    'success'
                  );
                }).catch(response=>{
                  this.loading=false;
                  this.disabled=false;
                  $('#group').modal('show');
                  Toast.fire({
                    icon: 'error',
                    title: 'Group has not been added <br> please double check all fields'
                  });
                })
              }
            })
          },
          showaddgroup(){
            this.disabled=false;
            this.form.reset();
            this.modalgroup='add';
            $('#group').modal('show')
          },
        },
        mounted() {
          this.getallgroup();
        }
    }
</script>
