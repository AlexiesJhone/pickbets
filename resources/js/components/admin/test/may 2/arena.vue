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
                        <a class="nav-link" data-toggle="tab">
                          <i class="material-icons">place</i> Active Arena
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item" >
                        <a class="nav-link" @click.prevent="showaddarena">
                          <i class="material-icons text-success">add_box</i> Add new arena
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
                          <th><b>ID</b></th>
                          <th><b>Name</b></th>
                          <th><b>Code</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td> <input type="text" name="" value="" placeholder="Id" class="form-control" v-model="form.id"></td>
                          <td>
                          <input type="text" name="" value="" placeholder="Name" class="form-control" v-model="form.name">
                          </td>
                          <td><input type="text" name="" value="" placeholder="Location" class="form-control" v-model="form.code"></td>
                        </tr>
                        <tr>
                          <td colspan="4"> <button type="button" name="button" class="btn btn-success btn-sm col-md-12" @click.prevent='getallarena'>Search</button>
                                            <button type="button" name="button" class="btn btn-default btn-sm col-md-12"  @click.prevent='clearsearch'>Clear Search</button>  </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="table tabl-sm table-stripped table-hover">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">#</th>
                          <th class="font-weight-bold">Name</th>
                          <th class="font-weight-bold">Code</th>
                          <th class="font-weight-bold">Date Created</th>
                          <th class="font-weight-bold">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="t in arenas.data">
                          <td>{{t.id}}</td>
                          <td>{{t.name}}</td>
                          <td>{{t.code}}</td>
                          <td>{{t.created_at | datec}}</td>
                          <td> <a class="btn btn-info btn-sm text-white" @click.prevent='editarena(t)'>Edit</a> </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer justify-content-center">
                  <pagination :data="arenas" :show-disabled=true :limit='5' @pagination-change-page="getallarena">
                      <span slot="prev-nav">&lt; Previous</span>
                      <span slot="next-nav">Next &gt;</span>
                  </pagination>
                </div>
              </div>
            </div>
            <div class="modal fade" id="arena" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content" style="border:none !important;">
                  <div class="modal-header bg-dark">
                    <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel" v-if="modal==='add'">Add Arena</h5>
                    <h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel" v-if="modal==='edit'">Edit Arena</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <label for="name">Arena</label>
                    <input type="text" id="name" class="form-control" v-model='form.name'>
                    <label for="code">Arena Code</label>
                    <input type="text" id="code" class="form-control" maxlength="3" v-model='form.code'>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success btn-sm" @click.prevent='addarena'  :disabled='disablethis' v-if="modal==='add'">Add Arena</button>
                    <button type="button" class="btn btn-success btn-sm" @click.prevent='updatearena' :disabled='disablethis' v-if="modal==='edit'" >Update Arena</button>
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
          modal:'',
          disabled:false,
          disabled1:false,
          searchQuery:'',
          pageOfItems: [],
          arenas:{},
          loading:false,
          form:new Form({
            id:'',
            name:'',
            code:'',
          })
        }
      },
      computed:{
        disablethis: function(){
          if (this.disabled1===false) {
            return this.disabled=false;
          }else {
            return this.disabled=true;
          }
       },
       resultQuery(){
          if(this.searchQuery){
          return this.pageOfItems.filter((item)=>{
            return this.searchQuery.toLowerCase().split(' ').every(v => item.name.toLowerCase().includes(v)||item.code.toLowerCase().includes(v))
          })
          }else{
            return this.pageOfItems;
          }
        }
      },
      methods:{
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        updatearena(){
          $('#arena').modal('hide');
          Swal.fire({
            title: 'Please Confirm',
            text: "Do you really want to update this arena?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.form.post('/pick20/updatearena').then(()=>{
                this.loading=false;
                this.getallarena();
                Swal.fire(
                  'Success',
                  'Arena has successfully added',
                  'success'
                )
              }).catch(()=>{
                this.loading=false;
                Swal.fire(
                  'Error',
                  'Arena has not been updated <br> Please double check all fields',
                  'error'
                )
              })
            }
          })
        },
        editarena(t){
          this.disabled1=false;
          this.modal='edit';
            this.form.fill(t);
            $('#arena').modal('show');
        },
        getallarena(page = 1){
          this.loading=true;
          this.form.post('/pick20/getallarena?page='+page).then(response=>{
            this.arenas = response.data;
            this.loading=false;
          }).catch(()=>{
            this.loading=false;
            Swal.fire(
              'Error',
              'No arena records',
              'error'
            )
          });
        },
        clearsearch(page = 1){
          this.loading=true;
          this.form.reset();
          this.form.post('/pick20/getallarena?page='+page).then(response=>{
            this.arenas = response.data;
            this.loading=false;
          }).catch(()=>{
            this.loading=false;
            Swal.fire(
              'Error',
              'No arena records',
              'error'
            )
          });
        },
        addarena(){
          Swal.fire({
            title: 'Please Confirm',
            text: "Do you really want to add this arena?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.disabled1=true;
              this.form.post('/pick20/addarena').then(()=>{
                $('#arena').modal('hide');
                this.loading=false;
                this.getallarena();
                Swal.fire(
                  'Success',
                  'Arena has been successfully added',
                  'success'
                )
              }).catch(()=>{
                this.disabled1=false;
                $('#arena').modal('show');
                this.loading=false;
                Swal.fire(
                  'Error',
                  'Arena has not been successfully added  <br> Please double check all fields',
                  'error'
                )
              })
            }
          })

        },
        showaddarena(){
          this.disabled1=false;
          this.form.reset();
          this.modal = 'add'
          $('#arena').modal('show');
          this.disabled= true;
        }
      },
        created() {
            this.getallarena();
        }
    }
</script>
