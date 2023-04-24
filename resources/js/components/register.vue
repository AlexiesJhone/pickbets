<template>
<div class="container-fluid" id="app" style="height: auto;">
  <div class="row align-items-center">
    <div id="overlay" v-if="loading">
      <tile style="color:white"></tile>
      <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">

      <div class="card card-login card-hidden mb-3" v-if="form.group_id==10">
        <div class="card-header card-header-primary text-center bg-dark">
          <h4 class="text-warning"><strong>How To Register as a Mobile Player</strong></h4>
        </div>
        <div class="card-body ">
          <b>1st Option</b>
          <ul>
            <li>Ask Cashier or Teller on site for the registration link</li>
          </ul>
          <b>2nd Option</b>
          <ul>
            <li>Contact : 0999999992</li>
            <li>Contact : 0999999993</li>
            <li>Contact : 0999999994</li>
            <li>Contact : 0999999995</li>
            <li>Contact : 0999999996</li>
          </ul>

        </div>
      </div>

        <div class="card card-login card-hidden mb-3" v-if="form.group_id!=10">
          <div class="card-header card-header-primary text-center bg-dark">
            <h4 class="text-warning"><strong>Registers</strong></h4>
          </div>
          <form v-on:submit.prevent="register">
          <div class="card-body ">

            <AlertError :form="form"  />
            <div class="">
              <div class="input-group">
                <input type="text" v-model="form.name" class="form-control" placeholder="Name.." value="" required>
              </div>
              <div v-if="form.errors.has('name')" v-html="form.errors.get('name')"  class="text-danger" />
            </div>
            <div class="mt-3">
              <div class="input-group">
                <input type="text" v-model="form.username"class="form-control" placeholder="Username" value="" required>
              </div>
              <div v-if="form.errors.has('username')" v-html="form.errors.get('username')"  class="text-danger" />
            </div>
            <div class="mt-3">
              <div class="input-group">
                <input type="number" v-model="form.pnumber" class="form-control" placeholder="Phone number" value="" required>
              </div>
              <div v-if="form.errors.has('pnumber')" v-html="form.errors.get('pnumber')"  class="text-danger" />
            </div>
            <div class="mt-3">
              <div class="input-group">
                <input type="email" v-model="form.email" class="form-control" placeholder="Email" value="" required>
              </div>
              <div v-if="form.errors.has('email')" v-html="form.errors.get('email')"  class="text-danger" />
            </div>
            <div class="mt-3">
              <div class="input-group">
                <input type="text" v-model="form.preferred_by" class="form-control" placeholder="Referred By" value="" required>
              </div>
              <div v-if="form.errors.has('preferred_by')" v-html="form.errors.get('preferred_by')" class="text-danger"/>
            </div>
            <div class=" mt-3">
              <div class="input-group">
                <input type="password" v-model="form.password" id="password_confirmation" class="form-control" placeholder="Password.." required>
              </div>
              <div v-if="form.errors.has('password')" v-html="form.errors.get('password')"  class="text-danger"/>
            </div>
            <div class="mt-3">
              <div class="input-group">
                <input type="password" v-model="form.password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password.." required>
              </div>
              <div v-if="form.errors.has('password_confirmation')" v-html="form.errors.get('password_confirmation')"  class="text-danger"/>
            </div>
          </div>
          <div class="card-footer justify-content-center">
            <button type="submit" class="btn btn-success" :disabled="form.busy">Create Account</button>
          </div>
        </form>
        </div>
    </div>
  </div>
</div>
</template>
<script>
    export default {
      props:['id'],
        data(){
          return{
            disable:false,
            loading:false,
            form:new Form({
              name:'',
              username:'',
              pnumber:'',
              email:'',
              preferred_by:'',
              password_confirmation:'',
              password:'',
              group_id:this.id,
            }),
          }
        },
        computed:{
          total: function(){
           return Number(this.pages);
         },
          totals: function(){
           return Number(this.pagex);
         },
        },
        methods:{
          register(){
            this.loading=true;
            this.form.post('/register').then(()=>{
              Toast.fire({
                icon: 'success',
                title: 'Successfully registered !'
              });
              window.location.reload();
            }).catch(()=>{
              this.loading=false;
            });
          }
        },
        mounted() {
          // alert('testing')
        }
    }
</script>
