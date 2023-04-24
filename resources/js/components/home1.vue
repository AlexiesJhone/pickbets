<template>
    <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-5">
            <!-- <div class="card">
              <div class="card-header bg-dark text-white">
                awd
              </div>
              <div class="card-body">
                wad
              </div>
            </div> -->
            <div class="card">
              <div class="card-header bg-dark text-white">
                <b class="text-warning">PICK 20</b> - {{events.event_name}} <br>
              </div>
              <div class="card-body" >

                <div class="form-group">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-sm" >Starting fight number</span>
                    </div>
                    <input type="text" class="form-control" v-model="fight.start" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
                  </div>
                  <button type="button" class="btn btn-success form-control btn-sm" @click.prevent='getevents' name="button" v-if='rebet'>Bet Again</button>
                  <!-- <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Starting fight number</label>
                    </div>
                    <input type="text" class="custom-input" name="" value="">
                    <select class="custom-select" id="inputGroupSelect01" v-on:change="placebet" v-model="fight.start" placeholder='select fight number.'>
                      <option selected disabled>Choose...</option>
                      <option  v-for='n in events.fights' :value="n" v-if="n = events.startingfight" selected>{{n}}</b></option>
                    </select>
                  </div> -->
                  <div class="" v-if='customize'>
                    <!-- <div v-if='fight.selection==="Meron"' class="alert alert-danger" role="alert">
                      Selected Side : <b>Meron</b>
                    </div>
                    <div v-if='fight.selection==="Wala"' class="alert alert-primary" role="alert">
                      Selected Side : <b>Wala</b>
                    </div> -->
                  <div class="row" v-if='awd'>
                    <div class="col-md-6 col-md-3">
                      <a @click.prevent='meron' class="btn btn-danger btn-lg form-control"><b>MERON</b></a>
                    </div>
                    <div class="col-md-6 col-md-3">
                      <a @click.prevent='wala' class="btn btn-info btn-lg form-control text-white"><b>WALA</b></a>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="inputGroupSelect01">Fixed bet amount</label>
                    </div>
                    <input type="text" name=""class="form-control" value="100" v-model='fight.amount' disabled>
                  </div>
                  <center>
                  <div class="form-check">
                    <input class="form-check-input" v-on:change="placebet" type="checkbox"  id="defaultCheck1" v-model="randombets">
                    <label class="form-check-label" for="defaultCheck1">
                      <b>Random Picks</b>
                    </label>
                  </div></center>
                </div>
                </div>

                <div class="card" v-if='customize'>
                  <div class="card-header bg-dark text-white">
                  Customize  Your <b class="text-warning">PICK 20</b>
                  </div>
                  <div class="card-body table-responsive-sm" style="max-height:65vh; overflow:auto; padding:0;">
                    <table class="table table-sm  table-striped table-borderless table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th class="text-center">Fight #</th>
                          <!-- <th>Amount</th> -->
                          <th class="text-center">Pick</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>

                      <tbody class="" style="width:100%">
                        <tr v-for='(bet,index) in selected'>
                          <td class="text-center"><b>{{bet.fightnumber}}</b></td>
                          <!-- <td>{{bet.amount}}</td> -->
                          <td class="text-center"><b class="text-danger" v-if='bet.selection==="Meron"'>{{bet.selection}}</b> <b class="text-info" v-if='bet.selection==="Wala"'>{{bet.selection}}</b> </td>
                          <td class="text-center"> <a @click.prevent='switchw(index)' v-if='bet.selection==="Meron"' class="btn btn-primary btn-sm">
                          Switch to WALA
                        </a>
                        <a class="btn btn-danger btn-sm" @click.prevent='switchm(index)' v-if='bet.selection==="Wala"'>Switch to Meron </a>
                      </td>
                      <!-- <td>
                          <input :checked="bet.selection === 'Meron'" class="" type="radio" @click.prevent='switchbet(bet)' :name="bet.id" id="flexRadioDefault1" > <b class="text-danger">Meron</b>
                          <input :checked="bet.selection ==='Wala'" class="" type="radio" @click.prevent='switchbet(bet)' :name="bet.id" id="flexRadioDefault2" > <b class="text-primary">Wala</b>
                          </td> -->
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-footer" v-if='customize'>
                <a class="btn btn-success form-control" @click.prevent='insertbet()'>Place Bet</a>
              </div>
            </div>
            <div class="card" v-if="bets">
              <div class="card-header bg-dark text-white">
                Pending Bets
              </div>
              <div class="card-body">
                <a v-for="t in bets" class="btn btn-primary btn-sm">{{t.id}}</a>
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
          randombets:null,
          awd:null,
          customize:null,
          rebet:null,
          pending:false,
          events:[],
          bets:[],
          selected:[],
          fight:new Form({
            id:'',
            start:null,
            selection:'Meron',
            amount:100,
            user_id:this.user.id,
          }),
          confirm:new Form({
            id:'',
            fightnumber:null,
            selection:'Meron',
            amount:100,
            user_id:this.user.id,
          }),
        }
      },
      methods:{
        getbets(){
          axios.get('/pick20/getbets').then(response=>{
            this.bets=response.data;
          });
        },
        switchm(index){
          this.selected[index].selection = 'Meron';
         Toast.fire({
                 icon: 'warning',
                 title: 'Successfully switched !'
               });
        },
        switchw(index){
          this.selected[index].selection = 'Wala';
         Toast.fire({
                 icon: 'warning',
                 title: 'Successfully switched !'
               });
        },
        insertbet(){
          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Check Again'
          }).then((result) => {
            if (result.isConfirmed) {
              axios({
                method: 'post',
                url: '/pick20/testpost',
                data: {
                  data : this.selected
                }
              }).then(()=>{
                Toast.fire({
                  icon: 'success',
                  title: 'Picks Successfully Entered !'
                });
                this.selected=[];
                this.customize=null;
                this.fight.start=null;
                this.randombets=false;
                this.rebet=1;
                Swal.fire(
                  'Success!',
                  'Your Pick has been entered.',
                  'success'
                )
              })
            }
          })
        },
        deleteprebets(){
          axios.post('/pick20/deleteprebets');
        },
        switchbet(bet){
          this.confirm.fill(bet);
          this.confirm.post('/pick20/switchbet').then(response=>{
            this.selected=response.data;
            // Toast.fire({
            //         icon: 'warning',
            //         title: 'Successfully switched !'
            //       });
          // if (bet.selection==="Meron") {
          //   Swal.fire({
          //     title: 'Are you sure?',
          //     text: "You want to switch from "+bet.selection+" to Wala?",
          //     icon: 'warning',
          //     showCancelButton: true,
          //     confirmButtonColor: '#3085d6',
          //     cancelButtonColor: '#d33',
          //     confirmButtonText: 'Yes!'
          //   }).then((result) => {
          //     if (result.isConfirmed) {
          //       this.confirm.post('/switchbetw').then(response=>{
          //         this.selected=response.data;
          //         Toast.fire({
          //                 icon: 'warning',
          //                 title: 'Successfully switched !'
          //               });
          //       }).catch(()=>{
          //         Toast.fire({
          //                 icon: 'error',
          //                 title: 'Please refresh !'
          //               });
          //       });
          //     }
          //   })
          // }
          // else {
          //     Swal.fire({
          //       title: 'Are you sure?',
          //       text: "You want to switch from "+bet.selection+" to Meron?",
          //       icon: 'warning',
          //       showCancelButton: true,
          //       confirmButtonColor: '#3085d6',
          //       cancelButtonColor: '#d33',
          //       confirmButtonText: 'Yes!'
          //     }).then((result) => {
          //       if (result.isConfirmed) {
          //         this.confirm.post('/switchbetm').then(response=>{
          //           this.selected=response.data;
          //           Toast.fire({
          //                   icon: 'warning',
          //                   title: 'Successfully switched !'
          //                 });
          //         }).catch(()=>{
          //           Toast.fire({
          //                   icon: 'error',
          //                   title: 'Please refresh !'
          //                 });
          //         });
          //       }
          //     })
        })
        },
        placebet(){

          if (this.randombets) {
            // ito random
            this.fight.start=this.events.startingfight;
            this.fight.post('/pick20/randompick').then(response=>{
              this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Please select fightnumber first'
              });
            });
          }else {
            // ito hnd random
              this.fight.start=this.events.startingfight;
            this.fight.post('/pick20/selection').then(response=>{
              this.customize=1;
              this.selected=response.data;
              // Toast.fire({
              //         icon: 'warning',
              //         title: 'Please Confirm'
              //       });
            }).catch(()=>{
              Toast.fire({
                icon: 'warning',
                title: 'Please select fight first'
              });
            });
          }
        },
        wala(){
          this.fight.selection='Wala';
          Toast.fire({
                  icon: 'warning',
                  title: 'You selected WALA and please confirm'
                });
                this.fight.post('/pick20/allwala').then(response=>{
                  this.selected=response.data;});
        },
        meron(){
          this.fight.selection='Meron';
          Toast.fire({
                  icon: 'warning',
                  title: 'You selected MERON and please confirm'
                });
                this.fight.post('/pick20/allmeron').then(response=>{
                  this.selected=response.data;});
        },
        getevents(){
          axios.get('/pick20/getevents').then(response=>{
            this.events=response.data;
              this.fight.start=this.events.startingfight;
              this.placebet();
              this.rebet=null;
          });
        }
      },
        created() {
            this.getevents();
            // this.deleteprebets();
            this.getbets();
          Echo.join(`chat`)
          .here((users) => {
              console.log(users)
          })
          .joining((user) => {
              console.log(`${user.name} joined`);
          })
          .leaving((user) => {
              console.log(`${user.name} leaved`);
          });
        }
    }
</script>
