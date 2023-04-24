<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
            <div class="col-md-12">
              <!-- <div class="card">
                <button type="button" class="btn btn-success" @click.prevent='getallpossiblepayout' name="button">Add Combination</button>
                <table class="table">
                  <thead>
                    <th>Combinations</th>
                  </thead>
                  <tbody>
                    <tr v-for="t in Events">
                      <td>{{t.bet}}</td>
                    </tr>
                  </tbody>
                </table>
              </div> -->
              <div class="card table-responsive">
                <table class="table table-sm" v-if="Events.length">
                  <thead class="bg-dark text-warning font-weight-bold" >
                    <tr >
                      <th colspan="29"><b>Pick 4 Payout Monitoring SET 1</b></th>
                    </tr>
                    <tr >
                      <th class="sizengtext">Starting Fight</th>
                      <th class="sizengtext">Event Name</th>
                      <!-- <th class="sizengtext">MDDD</th>
                      <th class="sizengtext">MDDM</th>
                      <th class="sizengtext">MDDw</th>
                      <th class="sizengtext">MDMD</th>
                      <th class="sizengtext">MDMM</th>
                      <th class="sizengtext">MDMw</th>
                      <th class="sizengtext">MDwD</th>
                      <th class="sizengtext">MDwM</th>
                      <th class="sizengtext">MDww</th>
                      <th class="sizengtext">MMDD</th>
                      <th class="sizengtext">MMDM</th>
                      <th class="sizengtext">MMDw</th>
                      <th class="sizengtext">MMMD</th> -->
                      <th class="sizengtext">RRRR</th>
                      <th class="sizengtext">RRRw</th>
                      <!-- <th class="sizengtext">MMwD</th> -->
                      <th class="sizengtext">RRwR</th>
                      <th class="sizengtext">RRww</th>
                      <!-- <th class="sizengtext">MwDD</th> -->
                      <!-- <th class="sizengtext">MwDM</th>
                      <th class="sizengtext">MwDw</th>
                      <th class="sizengtext">MwMD</th> -->
                      <th class="sizengtext">RwRR</th>
                      <th class="sizengtext">RwRw</th>
                      <!-- <th class="sizengtext">MwwD</th> -->
                      <th class="sizengtext">RwwR</th>
                      <th class="sizengtext">Rwww</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="t in Events" v-if="t.set==1" style="font-size: 12px !important;">
                      <td>{{t.startingfight}}</td>
                      <td>{{t.eventname}}</td>
                      <!-- <td><a class="text-danger" v-if="t.MDDD<=129">{{Number(t.MDDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDDD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MDDM<=129">{{Number(t.MDDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MDDw<=129">{{Number(t.MDDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MDMD<=129">{{Number(t.MDMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDMD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MDMM<=129">{{Number(t.MDMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MDMw<=129">{{Number(t.MDMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDMw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MDwD<=129">{{Number(t.MDwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDwD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MDwM<=129">{{Number(t.MDwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MDww<=129">{{Number(t.MDww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MDww).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MMDD<=129">{{Number(t.MMDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMDD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MMDM<=129">{{Number(t.MMDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MMDw<=129">{{Number(t.MMDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MMMD<=129">{{Number(t.MMMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMMD).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.MMMM<=129">{{Number(t.MMMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MMMw<=129">{{Number(t.MMMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMMw).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.MMwD<=129">{{Number(t.MMwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMwD).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.MMwM<=129">{{Number(t.MMwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MMww<=129">{{Number(t.MMww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MMww).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.MwDD<=129">{{Number(t.MwDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwDD).toLocaleString()}}</a></td> -->
                      <!-- <td><a class="text-danger" v-if="t.MwDM<=129">{{Number(t.MwDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MwDw<=129">{{Number(t.MwDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MwMD<=129">{{Number(t.MwMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwMD).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.MwMM<=129">{{Number(t.MwMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.MwMw<=129">{{Number(t.MwMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwMw).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.MwwD<=129">{{Number(t.MwwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwwD).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.MwwM<=129">{{Number(t.MwwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.Mwww<=129">{{Number(t.Mwww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.Mwww).toLocaleString()}}</a></td>
                    </tr>
                  </tbody>
                  <thead class="bg-dark text-warning font-weight-bold">
                    <tr >
                      <th colspan="29"><b>Pick 4 Payout Monitoring SET 2</b></th>
                    </tr>
                    <tr >
                      <th class="sizengtext">Starting Fight</th>
                      <th class="sizengtext">Event Name</th>
                      <!-- <th class="sizengtext">wDDD</th>
                      <th class="sizengtext">wDDM</th>
                      <th class="sizengtext">wDDw</th>
                      <th class="sizengtext">wDMD</th>
                      <th class="sizengtext">wDMM</th>
                      <th class="sizengtext">wDMw</th>
                      <th class="sizengtext">wDwD</th>
                      <th class="sizengtext">wDwM</th>
                      <th class="sizengtext">wDww</th>
                      <th class="sizengtext">wMDD</th>
                      <th class="sizengtext">wMDM</th>
                      <th class="sizengtext">wMDw</th>
                      <th class="sizengtext">wMMD</th> -->
                      <th class="sizengtext">wRRR</th>
                      <th class="sizengtext">wRRw</th>
                      <!-- <th class="sizengtext">wMwD</th> -->
                      <th class="sizengtext">wRwR</th>
                      <th class="sizengtext">wRww</th>
                      <!-- <th class="sizengtext">wwDD</th>
                      <th class="sizengtext">wwDM</th>
                      <th class="sizengtext">wwDw</th>
                      <th class="sizengtext">wwMD</th> -->
                      <th class="sizengtext">wwRR</th>
                      <th class="sizengtext">wwRw</th>
                      <!-- <th class="sizengtext">wwwD</th> -->
                      <th class="sizengtext">wwwR</th>
                      <th class="sizengtext">wwww</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="t in Events" v-if="t.set==2" style="font-size: 12px !important;">
                      <td>{{t.startingfight}}</td>
                      <td>{{t.eventname}}</td>
                      <!-- <td><a class="text-danger" v-if="t.wDDD<=129">{{Number(t.wDDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDDD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wDDM<=129">{{Number(t.wDDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wDDw<=129">{{Number(t.wDDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wDMD<=129">{{Number(t.wDMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDMD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wDMM<=129">{{Number(t.wDMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wDMw<=129">{{Number(t.wDMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDMw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wDwD<=129">{{Number(t.wDwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDwD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wDwM<=129">{{Number(t.wDwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wDww<=129">{{Number(t.wDww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wDww).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wMDD<=129">{{Number(t.wMDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMDD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wMDM<=129">{{Number(t.wMDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wMDw<=129">{{Number(t.wMDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wMMD<=129">{{Number(t.wMMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMMD).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.wMMM<=129">{{Number(t.wMMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wMMw<=129">{{Number(t.wMMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMMw).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.wMwD<=129">{{Number(t.wMwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMwD).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.wMwM<=129">{{Number(t.wMwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wMww<=129">{{Number(t.wMww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wMww).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.wwDD<=129">{{Number(t.wwDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwDD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wwDM<=129">{{Number(t.wwDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wwDw<=129">{{Number(t.wwDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wwMD<=129">{{Number(t.wwMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwMD).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.wwMM<=129">{{Number(t.wwMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wwMw<=129">{{Number(t.wwMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwMw).toLocaleString()}}</a></td>
                      <!-- <td><a class="text-danger" v-if="t.wwwD<=129">{{Number(t.wwwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwwD).toLocaleString()}}</a></td> -->
                      <td><a class="text-danger" v-if="t.wwwM<=129">{{Number(t.wwwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.wwww<=129">{{Number(t.wwww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwww).toLocaleString()}}</a></td>
                    </tr>
                  </tbody>
                  <!-- <thead class="bg-dark text-warning font-weight-bold">
                    <tr >
                      <th colspan="29"><b>Pick 4 Payout Monitoring SET 3</b></th>
                    </tr>
                    <tr >
                      <th class="sizengtext">Starting Fight</th>
                      <th class="sizengtext">Event Name</th>
                      <th class="sizengtext">DDDD</th>
                      <th class="sizengtext">DDDM</th>
                      <th class="sizengtext">DDDw</th>
                      <th class="sizengtext">DDMD</th>
                      <th class="sizengtext">DDMM</th>
                      <th class="sizengtext">DDMw</th>
                      <th class="sizengtext">DDwD</th>
                      <th class="sizengtext">DDwM</th>
                      <th class="sizengtext">DDww</th>
                      <th class="sizengtext">DMDD</th>
                      <th class="sizengtext">DMDM</th>
                      <th class="sizengtext">DMDw</th>
                      <th class="sizengtext">DMMD</th>
                      <th class="sizengtext">DMMM</th>
                      <th class="sizengtext">DMMw</th>
                      <th class="sizengtext">DMwD</th>
                      <th class="sizengtext">DMwM</th>
                      <th class="sizengtext">DMww</th>
                      <th class="sizengtext">DwDD</th>
                      <th class="sizengtext">DwDM</th>
                      <th class="sizengtext">DwDw</th>
                      <th class="sizengtext">DwMD</th>
                      <th class="sizengtext">DwMM</th>
                      <th class="sizengtext">DwMw</th>
                      <th class="sizengtext">DwwD</th>
                      <th class="sizengtext">DwwM</th>
                      <th class="sizengtext">Dwww</th>
                    </tr>
                  </thead> -->
                  <!-- <tbody>
                    <tr v-for="t in Events" v-if="t.set==3" style="font-size: 12px !important;">
                      <td>{{t.startingfight}}</td>
                      <td>{{t.eventname}}</td>
                      <td><a class="text-danger" v-if="t.DDDD<=129">{{Number(t.DDDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDDD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DDDM<=129">{{Number(t.DDDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DDDw<=129">{{Number(t.DDDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DDMD<=129">{{Number(t.DDMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDMD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DDMM<=129">{{Number(t.DDMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DDMw<=129">{{Number(t.DDMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDMw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DDwD<=129">{{Number(t.DDwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDwD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DDwM<=129">{{Number(t.DDwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DDww<=129">{{Number(t.DDww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DDww).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMDD<=129">{{Number(t.DMDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMDD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMDM<=129">{{Number(t.DMDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMDw<=129">{{Number(t.DMDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMMD<=129">{{Number(t.DMMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMMD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMMM<=129">{{Number(t.DMMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMMw<=129">{{Number(t.DMMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMMw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMwD<=129">{{Number(t.DMwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMwD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMwM<=129">{{Number(t.DMwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DMww<=129">{{Number(t.DMww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DMww).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DwDD<=129">{{Number(t.DwDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DwDD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DwDM<=129">{{Number(t.DwDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DwDM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DwDw<=129">{{Number(t.DwDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DwDw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DwMD<=129">{{Number(t.DwMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DwMD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DwMM<=129">{{Number(t.DwMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DwMM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DwMw<=129">{{Number(t.DwMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DwMw).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DwwD<=129">{{Number(t.DwwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DwwD).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.DwwM<=129">{{Number(t.DwwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.DwwM).toLocaleString()}}</a></td>
                      <td><a class="text-danger" v-if="t.Dwww<=129">{{Number(t.Dwww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.Dwww).toLocaleString()}}</a></td>
                    </tr>
                  </tbody> -->
                </table>
                <a v-if="!Events.length">There are no current active event for now..</a>
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
          Events:[],
          pin:new Form({
            id:'',
            username:'',
            pin:'',
          }),
        }
      },
      methods:{
        getallpossiblepayout(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutallpick4').then(response=>{
            this.Events = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          }).catch(()=>{
            this.loading = false;
          })
        }
      },
        created() {
          this.getallpossiblepayout();
          Echo.private('insert_bet')
          .listen('betevent',(event)=>{
            console.log(event);
            // if (event.startingfight===this.select&&this.fight.id===event.id) {
            //   this.odds = this.odds + event.bet;
            //   console.log(event)
            // }

            this.Events.forEach((val)=>{
              console.log(event.alldata);
              if (val.id==event.id) {

                val.MDDD = event.alldata[0].MDDD;
                val.MDDM = event.alldata[0].MDDM;
                val.MDDw = event.alldata[0].MDDw;
                val.MDMD = event.alldata[0].MDMD;
                val.MDMM = event.alldata[0].MDMM;
                val.MDMw = event.alldata[0].MDMw;
                val.MDwD = event.alldata[0].MDwD;
                val.MDwM = event.alldata[0].MDwM;
                val.MDww = event.alldata[0].MDww;
                val.MMDD = event.alldata[0].MMDD;
                val.MMDM = event.alldata[0].MMDM;
                val.MMDw = event.alldata[0].MMDw;
                val.MMMD = event.alldata[0].MMMD;
                val.MMMM = event.alldata[0].MMMM;
                val.MMMw = event.alldata[0].MMMw;
                val.MMwD = event.alldata[0].MMwD;
                val.MMwM = event.alldata[0].MMwM;
                val.MMww = event.alldata[0].MMww;
                val.MwDD = event.alldata[0].MwDD;
                val.MwDM = event.alldata[0].MwDM;
                val.MwDw = event.alldata[0].MwDw;
                val.MwMD = event.alldata[0].MwMD;
                val.MwMM = event.alldata[0].MwMM;
                val.MwMw = event.alldata[0].MwMw;
                val.MwwD = event.alldata[0].MwwD;
                val.MwwM = event.alldata[0].MwwM;
                val.Mwww = event.alldata[0].Mwww;

                val.wDDD = event.alldata[0].wDDD;
                val.wDDM = event.alldata[0].wDDM;
                val.wDDw = event.alldata[0].wDDw;
                val.wDMD = event.alldata[0].wDMD;
                val.wDMM = event.alldata[0].wDMM;
                val.wDMw = event.alldata[0].wDMw;
                val.wDwD = event.alldata[0].wDwD;
                val.wDwM = event.alldata[0].wDwM;
                val.wDww = event.alldata[0].wDww;
                val.wMDD = event.alldata[0].wMDD;
                val.wMDM = event.alldata[0].wMDM;
                val.wMDw = event.alldata[0].wMDw;
                val.wMMD = event.alldata[0].wMMD;
                val.wMMM = event.alldata[0].wMMM;
                val.wMMw = event.alldata[0].wMMw;
                val.wMwD = event.alldata[0].wMwD;
                val.wMwM = event.alldata[0].wMwM;
                val.wMww = event.alldata[0].wMww;
                val.wwDD = event.alldata[0].wwDD;
                val.wwDM = event.alldata[0].wwDM;
                val.wwDw = event.alldata[0].wwDw;
                val.wwMD = event.alldata[0].wwMD;
                val.wwMM = event.alldata[0].wwMM;
                val.wwMw = event.alldata[0].wwMw;
                val.wwwD = event.alldata[0].wwwD;
                val.wwwM = event.alldata[0].wwwM;
                val.wwww = event.alldata[0].wwww;

                val.DDDD = event.alldata[0].DDDD;
                val.DDDM = event.alldata[0].DDDM;
                val.DDDw = event.alldata[0].DDDw;
                val.DDMD = event.alldata[0].DDMD;
                val.DDMM = event.alldata[0].DDMM;
                val.DDMw = event.alldata[0].DDMw;
                val.DDwD = event.alldata[0].DDwD;
                val.DDwM = event.alldata[0].DDwM;
                val.DDww = event.alldata[0].DDww;
                val.DMDD = event.alldata[0].DMDD;
                val.DMDM = event.alldata[0].DMDM;
                val.DMDw = event.alldata[0].DMDw;
                val.DMMD = event.alldata[0].DMMD;
                val.DMMM = event.alldata[0].DMMM;
                val.DMMw = event.alldata[0].DMMw;
                val.DMwD = event.alldata[0].DMwD;
                val.DMwM = event.alldata[0].DMwM;
                val.DMww = event.alldata[0].DMww;
                val.DwDD = event.alldata[0].DwDD;
                val.DwDM = event.alldata[0].DwDM;
                val.DwDw = event.alldata[0].DwDw;
                val.DwMD = event.alldata[0].DwMD;
                val.DwMM = event.alldata[0].DwMM;
                val.DwMw = event.alldata[0].DwMw;
                val.DwwD = event.alldata[0].DwwD;
                val.DwwM = event.alldata[0].DwwM;
                val.Dwww = event.alldata[0].Dwww;
                // this.possible = event.alldata;
                // alexlang
              }
            });
          });
    }
    }
</script>
