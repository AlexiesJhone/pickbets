<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-dark text-white font-weight-bold">
                  {{selectedDate}}
                </div>
                <div class='card-body' style="padding:0">
                  <date-picker  mode="date" is-expanded v-model="selectedDate"></date-picker>
                </div>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
          return{
            selectedDate:new Date(),
          }
        }
    }
</script>
