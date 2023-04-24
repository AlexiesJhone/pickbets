<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12 row">
              <div class="col-md-12">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Total Sales Per Event
                </div>
                <div class="card-body">
                  <!-- <h4 class="card-title">Total Sales</h4> -->
                  <apexchart type="bar" height='200' :options="options" :series="series"></apexchart>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Total Bets Per Event
                </div>
                <div class="card-body">
                  <!-- <h4 class="card-title">Total Sales</h4> -->
                  <apexchart type="bar" height='200' :options="options2" :series="series2"></apexchart>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card card-stats">
                <div class="card-header card-header-dark card-header-icon">
                  <div class="card-icon bg-dark">
                    <i class="material-icons">credit_card</i>
                  </div>
                  <p class="card-category">Total Current Pending Bets</p>
                  <h3 class="card-title">{{Number(totalpendingbets).toLocaleString()}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card card-stats">
                <div class="card-header card-header-dark card-header-icon">
                  <div class="card-icon bg-dark">
                    <i class="material-icons">point_of_sale</i>
                  </div>
                  <p class="card-category">Total Current Rake</p>
                  <h3 class="card-title">{{Number(totalcurrentrake).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card card-stats">
                <div class="card-header card-header-dark card-header-icon">
                  <div class="card-icon bg-dark">
                    <i class="material-icons">confirmation_number</i>
                  </div>
                  <p class="card-category">Total Current Bets</p>
                  <h3 class="card-title">{{Number(totalcurrentbets).toLocaleString()}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card card-stats">
                <div class="card-header card-header-dark card-header-icon">
                  <div class="card-icon bg-dark">
                    <i class="material-icons">account_balance_wallet</i>
                  </div>
                  <p class="card-category">Total Funds</p>
                  <h3 class="card-title">{{Number(this.control.addtojackpot).toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Total Sales Per Hour
                </div>
                <div class="card-body">
                  <!-- <h4 class="card-title">Total Sales</h4> -->
                  <apexchart type="area" height='200' :options="options3" :series="series3"></apexchart>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-chart card-header-dark bg-dark font-weight-bold">
                  Total Sales Per Day
                </div>
                <div class="card-body">
                  <!-- <h4 class="card-title">Total Sales</h4> -->
                  <apexchart type="area" height='200' :options="options4" :series="series4"></apexchart>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card card-stats">
                <div class="card-header card-header-dark card-header-icon">
                  <div class="card-icon bg-dark">
                    <i class="material-icons">money</i>
                  </div>
                  <p class="card-category">This Month Income</p>
                  <h3 class="card-title">{{Number(thismonth).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i>  As of {{new Date().toLocaleString()}}.
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
          datako:[],
          datako2:[],
          perhour:[],
          control:[],
          perhourdata:[],
          totalpendingbets:[],
          totalcurrentrake:[],
          totalcurrentbets:[],
          thismonth:[],
          options: {
            // tooltip: {
            //   theme: 'dark',
            // },
            chart: {
              id: 'vuechart-example'
            },
            xaxis: {
              categories: []
            },
            plotOptions: {
            bar: {
                distributed: true
            }
          }
          },
          options2: {
            chart: {
              id: 'vuechart-example'
            },
            xaxis: {
              categories: []
            },
            plotOptions: {
            bar: {
                distributed: true
            }
          }

          },
          options3: {
            chart: {
              type: 'area',
              zoom: {
                enabled: true
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              curve: 'smooth'
            },
            title: {
              align: 'left'
            },
            subtitle: {
              align: 'left'
            },
            // labels: [
            // ],
            xaxis: {
              type: 'fill'
            },
            yaxis: {
              opposite: true
            },
            legend: {
              horizontalAlign: 'left'
            }
          },
          options4: {
            chart: {
              type: 'area',
              zoom: {
                enabled: true
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              curve: 'smooth'
            },
            title: {
              align: 'left'
            },
            subtitle: {
              align: 'left'
            },
            // labels: [
            // ],
            xaxis: {
              type: 'fill'
            },
            yaxis: {
              opposite: true
            },
            legend: {
              horizontalAlign: 'left'
            }
          },
          series4: [{
            name: 'Daily Sales',
            data: [],
            xaxis: {
              type: 'datetime'
            },
          }],
          series2: [{
            name: 'Total Bets',
            data: [],
            colors: [
              "#008FFB", "#00E396", "#FEB019", "#FF4560", "#775DD0",
              "#3F51B5", "#546E7A", "#D4526E", "#8D5B4C", "#F86624",
              "#D7263D", "#1B998B", "#2E294E", "#F46036", "#E2C044"
            ],
            fill: {
              type: 'solid'
            },
          }],
          series3: [{
            name: 'Total Sales per hour',
            data: [],
            colors: [
              "#008FFB", "#00E396", "#FEB019", "#FF4560", "#775DD0",
              "#3F51B5", "#546E7A", "#D4526E", "#8D5B4C", "#F86624",
              "#D7263D", "#1B998B", "#2E294E", "#F46036", "#E2C044"
            ],
            fill: {
              type: 'solid'
            },
          }],
          series: [{
            name: 'Total Sales per hour',
            data: [],
            colors: [
              "#008FFB", "#00E396", "#FEB019", "#FF4560", "#775DD0",
              "#3F51B5", "#546E7A", "#D4526E", "#8D5B4C", "#F86624",
              "#D7263D", "#1B998B", "#2E294E", "#F46036", "#E2C044"
            ],
            fill: {
              type: 'solid'
            },
          }],

      }
    },
      methods:{
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.control=response.data;
          });
        },
        getthismonth(){
          this.loading = true;
          axios.get('/pick20/thismonthsale').then(response=>{
            this.loading = false;
            this.thismonth=response.data;
          })
        },
        getdailysales(){
          this.loading = true;
          axios.get('/pick20/getdailysales').then(response=>{
            // this.loading = false;
            this.options4 = {
              label : response.data
            };
            axios.get('/pick20/getdailysalesdata').then(response=>{
              this.loading = false;
              this.series4= [{
                data: response.data
              }]
            })
          }).catch(()=>{
            this.loading = false;
          })
        },
        totalcurrentbetx(){
          this.loading = true;
          axios.get('/pick20/totalcurrentpendingbets').then(response=>{
            this.loading = false;
            this.totalcurrentbets = response.data;
          }).catch(()=>{
            this.loading = false;
          })
        },
        gettotalcurrentrake(){
          this.loading = true;
          axios.get('/pick20/totalcurrentrake').then(response=>{
            // this.loading = false;
            this.totalcurrentrake = response.data;
          }).catch(()=>{
            this.loading = false;
          })
        },
        gettotalpendingbets(){
          // this.loading = true;
          axios.get('/pick20/totalpendingbetsreport').then(response=>{
            this.loading = false;
            this.totalpendingbets = response.data;
          }).catch(()=>{
            this.loading = false;
          })
        },
        getperhour(){
          this.loading = true;
          axios.get('/pick20/perhourbets').then(response=>{
            // this.loading = false;
            this.perhour = response.data;
            this.options3 = {
              xaxis:{
                categories:response.data
              }
            };
            // this.loading = true;
            axios.get('/pick20/perhourdata').then(response=>{
              this.loading = false;
              this.perhourdata = response.data;
              this.series3 = [{
                data: response.data
              }];
            }).catch(()=>{
              this.loading = false;

            });
          }).catch(()=>{
            this.loading = false;

          })
        },
        totalbets(){
          this.loading = true;
          axios.get('/pick20/totalbets').then(response=>{
            // this.loading = false;
            this.datako2 = response.data;
            // nasa baba
            this.options2 = {
              xaxis:{
                categories:response.data
              }
            };
            // this.loading = true;
            axios.get('/pick20/totalbetsdata').then(response=>{
              this.loading = false;
              this.series2 = [{
                data: response.data
              }];
            })
          }).catch(()=>{
            this.loading = false;
          })
        },
        getallrake(){
          this.loading = true;
          axios.get('/pick20/getallrake').then(response=>{
            // this.loading = false;
            this.datako = response.data;
            // nasa baba
            this.options = {
              xaxis:{
                categories:response.data
              }
            };
          }).then(()=>{
            // this.loading = true;
            axios.get('/pick20/getrakedata').then(response=>{
              this.loading = false;
              console.log(response.data)
              this.series = [{
                data: response.data
              }];
            })
          }).catch(()=>{
            this.loading = false;
          })
        }
      },
      mounted() {
        this.getcontrol()
        this.getthismonth();
        this.getdailysales();
        this.gettotalpendingbets();
        this.getperhour();
        this.totalbets();
        this.getallrake();
        this.totalcurrentbetx();
        this.gettotalcurrentrake();
      }
    }
</script>
<style media="screen">
.apexcharts-menu.apexcharts-menu-open {
  opacity: 1;
  pointer-events: all;
  transition: 0.15s ease all;
  color: black;
},
.apexcharts-tooltip {
    background: #f3f3f3;
    color: orange;
  }

</style>
