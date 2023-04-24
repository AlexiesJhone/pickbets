<style media="screen">
.modal1 {
padding: 0 !important;
}
.modal1 .modal-dialog1 {
width: 100%;
max-width: none;
height: 100%;
margin: 0;
}
.modal1 .modal-content1 {
height: 100%;
border: 0;
border-radius: 0;
}
.modal1 .modal-body1 {
overflow-y: auto;
}
</style>
<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
              <div class="card">
              <div class="card-header card-header-tabs card-header-dark bg-dark">
                <div class="nav-tabs-navigation">
                  <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item">
                        <a class="nav-link active" href="#profile" data-toggle="tab" @click.prevent='statistics'>
                          <i class="material-icons">analytics</i> Statistics
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#messages" data-toggle="tab" @click.prevent='accountreports'>
                          <i class="material-icons">assessment</i> Account Reports
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#transactions" data-toggle="tab" @click.prevent='transactionadminreport'>
                          <i class="material-icons">receipt_long</i> Transactions
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#spotcheck" data-toggle="tab" @click.prevent='spotcheck'>
                          <i class="material-icons">check_circle_outline</i> Spotcheck
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#monitoring" data-toggle="tab" @click.prevent='monitoring'>
                          <i class="material-icons">bar_chart</i> Cashier/Teller Monitoring
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                    </ul>

                  </div>
                </div>
              </div>
              <div class="card-body"style="padding:0">
                <div class="tab-content">
                  <div class="tab-pane active" id="profile">
                    <reports v-if="page==='statistics'"></reports>
                  </div>
                  <div class="tab-pane container-fluid" id="messages">
                    <accountreports v-if="page==='accountreports'"></accountreports>
                  </div>
                  <div class="tab-pane container-fluid" id="transactions">
                    <transactionadminreport v-if="page==='transactionadminreport'"></transactionadminreport>
                  </div>
                  <div class="tab-pane container-fluid" id="spotcheck">
                    <spotcheck v-if="page==='spotcheck'"></spotcheck>
                  </div>
                  <div class="tab-pane container-fluid" id="monitoring">
                    <monitoring v-if="page==='monitoring'" :user='user'></monitoring>
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
          page:'statistics'
        }
      },
      methods:{
        monitoring(){
          this.page='monitoring';
        },
        spotcheck(){
          this.page='spotcheck';
        },
        transactionadminreport(){
          this.page='transactionadminreport';
        },
        statistics(){
          this.page='statistics';
        },
        accountreports(){
          this.page='accountreports';
        }
      },
        created() {

        }
    }
</script>
