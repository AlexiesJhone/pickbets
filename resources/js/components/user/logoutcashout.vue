<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
              <changepassword :user='user'></changepassword>
              <modalcash :userx='user'></modalcash>
              <session :userx='user'></session>
              <div class="card">
                <div class="card-header bg-dark text-warning font-weight-bold">
                  Attention
                </div>
                <div class="card-body">
                  Please make sure you <b>Cash Out</b>, before logout.<br>
                  <!-- <button class="btn btn-danger" onclick="history.back()">Go Back</button> -->
                  <button class="btn btn-danger" @click.prevent='gohome'>Go Back</button>
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

          }
        },
        methods:{
          gohome(){
            window.location.href = "/home"
          }
        },
        mounted() {
        }
    }
</script>
