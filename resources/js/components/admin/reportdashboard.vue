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
                      <li class="nav-item">
                        <a class="nav-link" href="#monitoringpick2" data-toggle="tab" @click.prevent='monitoringpick2'>
                          <i class="material-icons">monitor</i> Pick 2 Monitoring
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#monitoringpick3" data-toggle="tab" @click.prevent='monitoringpick3'>
                          <i class="material-icons">monitor</i> Pick 3 Monitoring
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#monitoringpick4" data-toggle="tab" @click.prevent='monitoringpick4'>
                          <i class="material-icons">monitor</i> Pick 4 Monitoring
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#monitoringpick5" data-toggle="tab" @click.prevent='monitoringpick5'>
                          <i class="material-icons">monitor</i> Pick 5 Monitoring
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#monitoringpick6" data-toggle="tab" @click.prevent='monitoringpick6'>
                          <i class="material-icons">monitor</i> Pick 6 Monitoring
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#monitoringpick8" data-toggle="tab" @click.prevent='monitoringpick8'>
                          <i class="material-icons">monitor</i> Pick 8 Monitoring
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#monitoringpick14" data-toggle="tab" @click.prevent='monitoringpick14'>
                          <i class="material-icons">monitor</i> Pick 14 Monitoring
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
                  <div class="tab-pane container-fluid" id="monitoringpick2">
                    <monitoringpick2 v-if="page==='monitoringpick2'" :user='user'></monitoringpick2>
                  </div>
                  <div class="tab-pane container-fluid" id="monitoringpick3">
                    <monitoringpick3 v-if="page==='monitoringpick3'" :user='user'></monitoringpick3>
                  </div>
                  <div class="tab-pane container-fluid" id="monitoringpick4">
                    <monitoringpick4 v-if="page==='monitoringpick4'" :user='user'></monitoringpick4>
                  </div>
                  <div class="tab-pane container-fluid" id="monitoringpick5">
                    <monitoringpick5 v-if="page==='monitoringpick5'" :user='user'></monitoringpick5>
                  </div>
                  <div class="tab-pane container-fluid" id="monitoringpick6">
                    <monitoringpick6 v-if="page==='monitoringpick6'" :user='user'></monitoringpick6>
                  </div>
                  <div class="tab-pane container-fluid" id="monitoringpick8">
                    <monitoringpick8 v-if="page==='monitoringpick8'" :user='user'></monitoringpick8>
                  </div>
                  <div class="tab-pane container-fluid" id="monitoringpick14">
                    <monitoringpick14 v-if="page==='monitoringpick14'" :user='user'></monitoringpick14>
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
        monitoringpick14(){
          this.page='monitoringpick14';
        },
        monitoringpick8(){
          this.page='monitoringpick8';
        },
        monitoringpick6(){
          this.page='monitoringpick6';
        },
        monitoringpick5(){
          this.page='monitoringpick5';
        },
        monitoringpick4(){
          this.page='monitoringpick4';
        },
        monitoringpick3(){
          this.page='monitoringpick3';
        },
        monitoringpick2(){
          this.page='monitoringpick2';
        },
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
