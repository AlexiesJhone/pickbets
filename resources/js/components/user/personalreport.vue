<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              Reports
            </div>
          <table class="table">
            <tr>
              <th>Wins</th>
              <th>Totals</th>
            </tr>
            <tr v-for="t in report">
              <td>{{t.wins}}</td>
              <td>{{t.total}}</td>
            </tr>
          </table>
          </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
      // props:['user'],
      data(){
        return{
          report:[]
        }
      },
      computed:{

      },
      methods:{
        getalldata(){
          axios.get('/personalreport').then(response=>{
            this.report = response.data;
          })
        }
      },
        created() {
          this.getalldata();
        }
    }
</script>
