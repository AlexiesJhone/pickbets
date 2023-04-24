<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div id="overlay" v-if="loading">
            <tile style="color:white"></tile>
            <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
          </div>
          <changepassword v-if="user" :user='user'></changepassword>
          <session :userx='user' v-if="user"></session>
	    <modalcash :userx='user' v-if="user"></modalcash>
          <div class="col-md-12 row">
            <div class="col-md-12">

                <div class="alert alert-success" role="alert" v-if="this.control.announcement"><center>
              <h4 class="alert-heading">Announcement</h4><hr>
              <p>{{this.control.announcement}}</p>
            </center>
              </div>
              <div class="card">
                <div class="card-header bg-dark text-warning font-weight-bold text-center">
                  {{events.event_name}} [{{events.fights}} Fights]<br>{{events.created_at|datec}} <br> <b class="text-success">JACKPOT FOR TODAY : {{Number(control.jackpot).toLocaleString()}}</b>
                  <!-- {{events.event_name}} - {{events.event_name}} [{{events.fights}} Fights]<br> <b class="text-success">JACKPOT FOR TODAY : {{Number(jackpotfinal).toLocaleString()}}</b> -->
                </div>
              </div><hr>
            </div>
            <div class="col-md-1">
            <div class="card" style="">
              <div class="card-header bg-dark text-warning font-weight-bold text-center">
                Results
              </div>
              <div class="card-body table-responsive" style="padding:0px;overflow-x:auto;height:480px">
              <table class="table table-sm table-bordered table-hover" >
                <thead>
                  <tr v-for="t in resultstotal" class="bg-danger font-weight-normal text-center text-white">
                    <th class="font-weight-normal" style="vertical-align:center">Meron</th>
                    <th class="font-weight-normal" v-if="t.meron">{{t.meron}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr>
                  <tr v-for="t in resultstotal" class="bg-primary font-weight-normal text-center text-white">
                    <th class="font-weight-normal">Wala</th>
                    <th class="font-weight-normal" v-if="t.wala">{{t.wala}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr>
                  <tr v-for="t in resultstotal"  class="bg-success font-weight-normal text-center text-white">
                    <th class="font-weight-normal">Draw</th>
                    <th class="font-weight-normal" v-if="t.draw">{{t.draw}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr v-for="t in resultstotal">
                  <tr v-for="t in resultstotal" class="bg-secondary font-weight-normal text-center text-white">
                    <th class="font-weight-normal">Cancelled</th>
                    <th class="font-weight-normal" v-if="t.cancelled">{{t.cancelled}}</th>
                    <th class="font-weight-normal" v-else>0</th>
                  </tr>
                </thead>
                <thead class="bg-dark">
                  <tr>
                    <th style="border: 1px solid #464748;" class="text-warning text-center">Fight#</th>
                    <th style="border: 1px solid #464748;" class="text-warning text-center">Winner</th>
                  </tr>
                </thead>
                  <tr v-for="t in results">
                    <td  v-if="t.result==='Meron'" class="bg-danger font-weight-normal text-center text-white" style="vertical-align: middle;">{{t.fightnumber}}</td>
                    <td class="text-center bg-danger"  v-if="t.result==='Meron'"><a class="text-white font-weight-normal" style="text-decoration:none;cursor: default">{{t.result}}</a></td>
                    <!-- <td class="text-center bg-danger"  v-if="t.result==='Meron'"><p class="text-white font-weight-normal">{{t.result.toUpperCase()}}</p></td> -->
                    <!-- <td class="text-center bg-danger"  v-if="t.result==='Meron'"><p class="text-white font-weight-normal">{{t.result.slice(0,-4)}}</p></td> -->
                    <td  v-if="t.result==='Wala'" class="bg-primary font-weight-normal text-center text-white" style="text-decoration:none;cursor: default">{{t.fightnumber}}</td>
                    <td class="text-center bg-primary"  v-if="t.result==='Wala'"><a class="text-white font-weight-normal text-center" style="text-decoration:none;cursor: default">{{t.result}}</a></td>
                    <td  v-if="t.result==='Cancelled'" class="bg-secondary font-weight-normal text-center text-white" style="text-decoration:none;cursor: default">{{t.fightnumber}}</td>
                    <td class="text-center bg-secondary"  v-if="t.result==='Cancelled'"><a class="text-white font-weight-normal text-center" style="text-decoration:none;cursor: default">{{t.result}}</a></td>
                    <td  v-if="t.result==='Draw'" class="bg-success font-weight-normal text-center text-white" style="text-decoration:none;cursor: default">{{t.fightnumber}}</td>
                    <td class="text-center bg-success"  v-if="t.result==='Draw'"><a class="text-white font-weight-normal text-center" style="text-decoration:none;cursor: default">{{t.result}}</a></td>
                  </tr>
              </table>
              </div>
            </div>
            </div>
            <div class="col-md-11" >
                <button type="button" @click.prevent='cpickall' class="btn btn-success">All</button>
                <button type="button" @click.prevent='cpick2' class="btn btn-success" :class="pick2 ? 'btn-success' : 'btn-danger'">Pick 2</button>
                <button type="button" @click.prevent='cpick3' class="btn btn-success" :class="pick3 ? 'btn-success' : 'btn-danger'">Pick 3</button>
                <button type="button" @click.prevent='cpick4' class="btn btn-success" :class="pick4 ? 'btn-success' : 'btn-danger'">Pick 4</button>
                <button type="button" @click.prevent='cpick5' class="btn btn-success" :class="pick5 ? 'btn-success' : 'btn-danger'">Pick 5</button>
                <button type="button" @click.prevent='cpick6' class="btn btn-success" :class="pick6 ? 'btn-success' : 'btn-danger'">Pick 6</button>
                <button type="button" @click.prevent='cpick8' class="btn btn-success" :class="pick8 ? 'btn-success' : 'btn-danger'">Pick 8</button>
                <button type="button" @click.prevent='cpick14' class="btn btn-success" :class="pick14 ? 'btn-success' : 'btn-danger'">Pick 14</button>
                <!-- <a class="btn btn-success" @click.prevent='cpick2'>Pick 2</a>
                <a class="btn btn-success" @click.prevent='cpick3'>Pick 3</a>
                <a class="btn btn-success" @click.prevent='cpick4'>Pick 4</a>
                <a class="btn btn-success" @click.prevent='cpick5'>Pick 5</a>
                <a class="btn btn-success" @click.prevent='cpick6'>Pick 6</a> -->


              <div class="card" v-if="pick2">
                  <table class="table" v-if="eventx.length">
                    <thead class="bg-dark text-warning font-weight-bold border-0">
                      <tr>
                        <th colspan="11" class="border-0 text-center"><b>PICK 2 PAYOUT MONITORING</b></th>
                      </tr>
                      <tr>
                        <th class="border-0">Starting Fight</th>
                        <!-- <th class="border-0">Event Name</th> -->
                        <th class="border-0">RR</th>
                        <th class="border-0">Rw</th>
                        <!-- <th class="border-0">MD</th> -->
                        <th class="border-0">wR</th>
                        <th class="border-0">ww</th>
                        <!-- <th class="border-0">wD</th> -->
                        <!-- <th class="border-0">DM</th>
                        <th class="border-0">Dw</th>
                        <th class="border-0">DD</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in eventx" style="font-size: 12px !important;">
                        <td class="text-center"><b>{{t.startingfight}}</b></td>
                        <!-- <td>{{t.eventname}}</td> -->
                        <td><a class="text-danger" v-if="t.mm<=129">{{Number(t.mm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.mw<=129">{{Number(t.mw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mw).toLocaleString()}}</a></td>
                        <!-- <td><a class="text-danger" v-if="t.md<=129">{{Number(t.md).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.md).toLocaleString()}}</a></td> -->
                        <td><a class="text-danger" v-if="t.wm<=129">{{Number(t.wm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.ww<=129">{{Number(t.ww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.ww).toLocaleString()}}</a></td>
                        <!-- <td><a class="text-danger" v-if="t.wd<=129">{{Number(t.wd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wd).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dm<=129">{{Number(t.dm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dw<=129">{{Number(t.dw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dw).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dd<=129">{{Number(t.dd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dd).toLocaleString()}}</a></td> -->
                      </tr>
                    </tbody>
                  </table>
                </div>
                <hr v-if="eventx2.length">
                  <div class="card" v-if="pick3">
                  <table class="table table-sm" v-if="eventx2.length">
                    <thead class="bg-dark text-warning font-weight-bold" >
                      <tr >
                        <th colspan="29" class="border-0 text-center"><b>PICK 3 PAYOUT MONITORING</b></th>
                      </tr>
                      <tr >
                        <th class="sizengtext">Starting Fight</th>
                        <!-- <th class="sizengtext">Event Name</th> -->
                        <th class="sizengtext">RRR</th>
                        <th class="sizengtext">RRw</th>
                        <!-- <th class="sizengtext">MMD</th>
                        <th class="sizengtext">MwD</th> -->
                        <th class="sizengtext">Rww</th>
                        <th class="sizengtext">RwR</th>
                        <!-- <th class="sizengtext">MDM</th>
                        <th class="sizengtext">MDw</th>
                        <th class="sizengtext">MDD</th> -->
                        <th class="sizengtext">wwR</th>
                        <th class="sizengtext">www</th>
                        <!-- <th class="sizengtext">wwD</th>
                        <th class="sizengtext">wDD</th>
                        <th class="sizengtext">wDw</th>
                        <th class="sizengtext">wDM</th> -->
                        <th class="sizengtext">wRR</th>
                        <th class="sizengtext">wRw</th>
                        <!-- <th class="sizengtext">wMD</th>
                        <th class="sizengtext">DDD</th>
                        <th class="sizengtext">DDM</th>
                        <th class="sizengtext">DDw</th>
                        <th class="sizengtext">Dww</th>
                        <th class="sizengtext">DwD</th>
                        <th class="sizengtext">DwM</th>
                        <th class="sizengtext">DMM</th>
                        <th class="sizengtext">DMw</th>
                        <th class="sizengtext">DMD</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in eventx2" style="font-size: 12px !important;">
                        <td class="text-center"><b>{{t.startingfight}}</b></td>
                        <!-- <td>{{t.eventname}}</td> -->
                        <td><a class="text-danger" v-if="t.mmm<=129">{{Number(t.mmm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mmm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.mmw<=129">{{Number(t.mmw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mmw).toLocaleString()}}</a></td>
                        <!-- <td><a class="text-danger" v-if="t.mmd<=129">{{Number(t.mmd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mmd).toLocaleString()}}</a></td> -->
                        <!-- <td><a class="text-danger" v-if="t.mwd<=129">{{Number(t.mwd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mwd).toLocaleString()}}</a></td> -->
                        <td><a class="text-danger" v-if="t.mww<=129">{{Number(t.mww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mww).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.mwm<=129">{{Number(t.mwm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mwm).toLocaleString()}}</a></td>
                        <!-- <td><a class="text-danger" v-if="t.mdm<=129">{{Number(t.mdm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mdm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.mdw<=129">{{Number(t.mdw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mdw).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.mdd<=129">{{Number(t.mdd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.mdd).toLocaleString()}}</a></td> -->
                        <td><a class="text-danger" v-if="t.wwm<=129">{{Number(t.wwm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.www<=129">{{Number(t.www).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.www).toLocaleString()}}</a></td>
                        <!-- <td><a class="text-danger" v-if="t.wwd<=129">{{Number(t.wwd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wwd).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.wdd<=129">{{Number(t.wdd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wdd).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.wdw<=129">{{Number(t.wdw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wdw).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.wdm<=129">{{Number(t.wdm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wdm).toLocaleString()}}</a></td> -->
                        <td><a class="text-danger" v-if="t.wmm<=129">{{Number(t.wmm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wmm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.wmw<=129">{{Number(t.wmw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wmw).toLocaleString()}}</a></td>
                        <!-- <td><a class="text-danger" v-if="t.wmd<=129">{{Number(t.wmd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.wmd).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.ddd<=129">{{Number(t.ddd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.ddd).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.ddm<=129">{{Number(t.ddm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.ddm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.ddw<=129">{{Number(t.ddw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.ddw).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dww<=129">{{Number(t.dww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dww).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dwd<=129">{{Number(t.dwd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dwd).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dwm<=129">{{Number(t.dwm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dwm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dmm<=129">{{Number(t.dmm).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dmm).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dmw<=129">{{Number(t.dmw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dmw).toLocaleString()}}</a></td>
                        <td><a class="text-danger" v-if="t.dmd<=129">{{Number(t.dmd).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.dmd).toLocaleString()}}</a></td> -->
                      </tr>
                    </tbody>
                  </table>
              </div>
              <hr v-if="eventx3.length">
              <div class="card" v-if="pick4">
              <table class="table table-sm" v-if="eventx3.length">
                <thead class="bg-dark text-warning font-weight-bold" >
                  <tr >
                    <th colspan="29" class="border-0 text-center"><b>PICK 4 PAYOUT MONITORING Set 1</b></th>
                  </tr>
                  <tr >
                    <th class="sizengtext">Starting Fight</th>
                    <!-- <th class="sizengtext">Event Name</th> -->
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
                    <!-- <th class="sizengtext">MwDD</th>
                    <th class="sizengtext">MwDM</th>
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
                  <tr v-for="t in eventx3" v-if="t.set==1" style="font-size: 12px !important;">
                    <td class="text-center"><b>{{t.startingfight}}</b></td>
                    <!-- <td>{{t.eventname}}</td> -->
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
                    <!-- <td><a class="text-danger" v-if="t.MwDD<=129">{{Number(t.MwDD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwDD).toLocaleString()}}</a></td>
                    <td><a class="text-danger" v-if="t.MwDM<=129">{{Number(t.MwDM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwDM).toLocaleString()}}</a></td>
                    <td><a class="text-danger" v-if="t.MwDw<=129">{{Number(t.MwDw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwDw).toLocaleString()}}</a></td>
                    <td><a class="text-danger" v-if="t.MwMD<=129">{{Number(t.MwMD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwMD).toLocaleString()}}</a></td> -->
                    <td><a class="text-danger" v-if="t.MwMM<=129">{{Number(t.MwMM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwMM).toLocaleString()}}</a></td>
                    <td><a class="text-danger" v-if="t.MwMw<=129">{{Number(t.MwMw).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwMw).toLocaleString()}}</a></td>
                    <!-- <td><a class="text-danger" v-if="t.MwwD<=129">{{Number(t.MwwD).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwwD).toLocaleString()}}</a></td> -->
                    <td><a class="text-danger" v-if="t.MwwM<=129">{{Number(t.MwwM).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.MwwM).toLocaleString()}}</a></td>
                    <td><a class="text-danger" v-if="t.Mwww<=129">{{Number(t.Mwww).toLocaleString()}}</a><a class="text-success" v-else>{{Number(t.Mwww).toLocaleString()}}</a></td>
                  </tr>
                </tbody>
              </table>
          </div>
          <hr v-if="eventx3.length">
          <div class="card" v-if="pick4">
          <table class="table table-sm" v-if="eventx3.length">
            <thead class="bg-dark text-warning font-weight-bold" >
              <tr >
                <th colspan="29" class="border-0 text-center"><b>PICK 4 PAYOUT MONITORING Set 2</b></th>
              </tr>
              <tr >
                <th class="sizengtext">Starting Fight</th>
                <!-- <th class="sizengtext">Event Name</th> -->
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
                <th class="sizengtext">wwwM</th>
                <th class="sizengtext">wwww</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="t in eventx3" v-if="t.set==2" style="font-size: 12px !important;">
                <td class="text-center"><b>{{t.startingfight}}</b></td>
                <!-- <td>{{t.eventname}}</td> -->
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
          </table>
      </div>
      <hr v-if="eventx3.length">
      <!-- <div class="card" v-if="pick4">
      <table class="table table-sm" v-if="eventx3.length">
        <thead class="bg-dark text-warning font-weight-bold" >
          <tr >
            <th colspan="29" class="border-0 text-center"><b>PICK 4 PAYOUT MONITORING Set 3</b></th>
          </tr>
          <tr >
            <th class="sizengtext">Starting Fight</th>
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
        </thead>
        <tbody>
          <tr v-for="t in eventx3" v-if="t.set==3" style="font-size: 12px !important;">
            <td class="text-center"><b>{{t.startingfight}}</b></td>
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
        </tbody>
      </table>
  </div> -->
    <hr v-if="eventx5.length">
    <div class="card table-responsive"  v-if="pick5">
      <table class="table table-sm" v-for="t in eventx5">
        <thead class="bg-dark text-warning font-weight-bold border-0 text-center">
          <tr >
            <th  colspan="2"><b>Pick 5 Payout Monitoring Available Combinations for {{t.startingfight}}</b></th>
          </tr>
          <tr v-if="t.combination.length">
            <th>Combination</th>
            <th>Possible Payout</th>
          </tr>
        </thead>
        <tbody class="border-0 text-center">
          <tr v-for="s in t.combination" v-if="t.combination.length">
            <td>{{s.bet}}</td>
            <td><a class="text-danger" v-if="s.total<=129">{{s.total}}</a> <a class="text-success" v-else>{{s.total}}</a> </td>
          </tr>
          <tr v-if="!t.combination.length">
            <td class="text-center">There are no current bets for <b>Starting Fight {{t.startingfight}}</b>..</td>
          </tr>
        </tbody>
      </table>
    </div>
    <hr v-if="eventx6.length">
    <div class="card table-responsive" v-if="pick6">
      <table class="table table-sm" v-for="t in eventx6">
        <thead class="bg-dark text-warning font-weight-bold border-0 text-center" >
          <tr >
            <th colspan="2"><b>Pick 6 Payout Monitoring Available Combinations for {{t.startingfight}}</b></th>
          </tr>
          <tr v-if="t.combination.length">
            <th>Combination</th>
            <th>Possible Payout</th>
          </tr>
        </thead>
        <tbody class="border-0 text-center">
          <tr v-for="s in t.combination" v-if="t.combination.length">
            <td>{{s.bet}}</td>
            <td><a class="text-danger" v-if="s.total<=129">{{s.total}}</a> <a class="text-success" v-else>{{s.total}}</a> </td>
          </tr>
          <tr v-if="!t.combination.length" >
            <td class=" text-center">There are no current bets for <b>Starting Fight {{t.startingfight}}</b>..</td>
          </tr>
        </tbody>
      </table>
    </div>
    <hr v-if="eventx8.length">
    <div class="card table-responsive" v-if="pick8">
      <table class="table table-sm" v-for="t in eventx8">
        <thead class="bg-dark text-warning font-weight-bold border-0 text-center" >
          <tr >
            <th colspan="2"><b>Pick 8 Payout Monitoring Available Combinations for {{t.startingfight}}</b></th>
          </tr>
          <tr v-if="t.combination.length">
            <th>Combination</th>
            <th>Possible Payout</th>
          </tr>
        </thead>
        <tbody class="border-0 text-center">
          <tr v-for="s in t.combination" v-if="t.combination.length">
            <td>{{s.bet}}</td>
            <td><a class="text-danger" v-if="s.total<=129">{{s.total}}</a> <a class="text-success" v-else>{{s.total}}</a> </td>
          </tr>
          <tr v-if="!t.combination.length" >
            <td class=" text-center">There are no current bets for <b>Starting Fight {{t.startingfight}}</b>..</td>
          </tr>
        </tbody>
      </table>
    </div>
    <hr v-if="eventx14.length">
    <div class="card table-responsive" v-if="pick14">
      <table class="table table-sm" v-for="t in eventx14">
        <thead class="bg-dark text-warning font-weight-bold border-0 text-center" >
          <tr >
            <th colspan="2"><b>Pick 8 Payout Monitoring Available Combinations for {{t.startingfight}}</b></th>
          </tr>
          <tr v-if="t.combination.length">
            <th>Combination</th>
            <th>Possible Payout</th>
          </tr>
        </thead>
        <tbody class="border-0 text-center">
          <tr v-for="s in t.combination" v-if="t.combination.length">
            <td>{{s.bet}}</td>
            <td><a class="text-danger" v-if="s.total<=129">{{s.total}}</a> <a class="text-success" v-else>{{s.total}}</a> </td>
          </tr>
          <tr v-if="!t.combination.length" >
            <td class=" text-center">There are no current bets for <b>Starting Fight {{t.startingfight}}</b>..</td>
          </tr>
        </tbody>
      </table>
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
          pick2:true,
          pick3:true,
          pick4:true,
          pick5:true,
          pick5:true,
          pick6:true,
          pick8:true,
          pick14:true,
          loading:false,
          computed:null,
          bets:[],
          control:[],
          winners:[],
          losers:[],
          pastwinners:[],
          pastwinners2:[],
          pastwinners3:[],
          results:[],
          resultstotal:[],
          events:[],
          eventx:[],
          eventx2:[],
          eventx3:[],
          eventx5:[],
          eventx6:[],
          eventx8:[],
          eventx14:[],
          losersleaders:[],
          pastlowesttoday:[]
        }
      },
      computed:{
        jackpotfinal: function(){
          if (this.control.addtojackpot) {

            return  parseFloat(this.control.addtojackpot)+parseFloat(this.control.jackpot);
          }else {
            return parseFloat(this.control.jackpot);
          }
       },
      },
      methods:{
        cpickall(){
          this.pick2=true;
          this.pick3=true;
          this.pick4=true;
          this.pick5=true;
          this.pick6=true;
          this.pick8=true;
          this.pick14=true;
        },
        cpick2(){
          this.pick2=true;
          this.pick3=null;
          this.pick4=null;
          this.pick5=null;
          this.pick6=null;
          this.pick8=null;
          this.pick14=null;
        },
        cpick3(){
          this.pick3=true;
          this.pick2=null;
          this.pick4=null;
          this.pick5=null;
          this.pick6=null;
          this.pick8=null;
          this.pick14=null;
        },
        cpick4(){
          this.pick4=true;
          this.pick2=null;
          this.pick3=null;
          this.pick5=null;
          this.pick6=null;
          this.pick8=null;
          this.pick14=null;
        },
        cpick5(){
          this.pick4=null;
          this.pick2=null;
          this.pick3=null;
          this.pick5=true;
          this.pick6=null;
          this.pick8=null;
          this.pick14=null;
        },
        cpick6(){
          this.pick4=null;
          this.pick2=null;
          this.pick3=null;
          this.pick5=null;
          this.pick6=true;
          this.pick8=null;
          this.pick14=null;
        },
        cpick8(){
          this.pick4=null;
          this.pick2=null;
          this.pick3=null;
          this.pick5=null;
          this.pick6=null;
          this.pick14=null;
          this.pick8=true;
        },
        cpick14(){
          this.pick4=null;
          this.pick2=null;
          this.pick3=null;
          this.pick5=null;
          this.pick6=null;
          this.pick8=null;
          this.pick14=true;
        },
        getallpossiblepayout(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutall').then(response=>{
            this.eventx = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          })
        },
        getallpossiblepayout2(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutallpick3').then(response=>{
            this.eventx2 = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          })
        },
        getallpossiblepayout5(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutallpick5').then(response=>{
            this.eventx5 = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          }).catch(()=>{
            this.loading = false;
          })
        },
        getallpossiblepayout6(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutallpick6').then(response=>{
            this.eventx6 = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          }).catch(()=>{
            this.loading = false;
          })
        },
        getallpossiblepayout8(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutallpick8').then(response=>{
            this.eventx8 = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          }).catch(()=>{
            this.loading = false;
          })
        },
        getallpossiblepayout14(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutallpick14').then(response=>{
            this.eventx14 = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          }).catch(()=>{
            this.loading = false;
          })
        },
        getallpossiblepayout4(){
          this.loading = true;
          axios.get('/pick20/possiblepayoutallpick4').then(response=>{
            this.eventx3 = response.data;
            this.loading = false;
          }).then(()=>{
            this.loading = false;
          })
        },
        pastlowestfortoday(){
          axios.get('/pick20/pastlowesttoday').then(response=>{
            this.pastlowesttoday = response.data;
          });
        },
        getpastwinners3(){
          axios.get('/pick20/pastwinners3').then(response=>{
            this.pastwinners3 = response.data;
          });
        },
        getpastwinners2(){
          axios.get('/pick20/pastwinners2').then(response=>{
            this.pastwinners2 = response.data;
          });
        },
        getlowest(){
          axios.get('/pick20/lowest').then(response=>{
            this.losers = response.data;
          });
        },
        getlowestleaderboard(){
          axios.get('/pick20/lowestleaders').then(response=>{
            this.losersleaders = response.data;
          });
        },
        getpastwinners(){
          axios.get('/pick20/pastwinners').then(response=>{
            this.pastwinners = response.data;
          });
        },
        computethis(){
          this.computed = this.events.startingfight - this.events.currentfight +1 ;
        },
        getevent(){
          axios.get('/pick20/getevents').then(response=>{
            this.events = response.data;
            this.computethis();
          })
        },
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.control = response.data;
            this.computethis();
          })
        },
        getresults(){
          this.loading=true,
          axios.get('/pick20/getresults').then(response=>{
            this.loading=false,
            this.results=response.data;
          })
        },
        getwinners(){
          this.loading=true,
          axios.get('/winnersfortoday').then(response =>{
            this.loading=false,
            this.winners=response.data;
          }).catch(()=>{
            this.loading=false,
            Swal.fire(
              'Please be inform',
              'Theres no active event',
              'warning'
            );
          })
        },
        getbets(){
          this.loading=true,
          axios.get('/pendingtopplayersx').then(response=>{
            this.loading=false,
            $('#prebets').modal('show')
            this.bets=response.data;
          }).catch(()=>{
            this.loading=false,
            Swal.fire(
              'Please be inform',
              'Theres no active event',
              'warning'
            );
          })
        },
        getresultstotal(){
          this.loading=true,
          axios.get('/pick20/getresultstotal').then(response=>{
            this.loading=false,
            this.resultstotal=response.data;
          })
        },
      },
        created() {
          this.getresultstotal();
            this.getresults();
            this.getevent();
            this.getcontrol();
            this.getallpossiblepayout();
            this.getallpossiblepayout2()
            this.getallpossiblepayout4()
            this.getallpossiblepayout5()
            this.getallpossiblepayout6()
            this.getallpossiblepayout8()
            this.getallpossiblepayout14()
            Echo.private('insert_bet')
            .listen('betevent',(event)=>{
              console.log(event.id);
              // if (event.startingfight===this.select&&this.fight.id===event.id) {
              //   this.odds = this.odds + event.bet;
              //   console.log(event)
              // }

              this.eventx.forEach((val)=>{
                // console.log(event.alldata.mm);
                if (val.id==event.id) {

                  val.mm = event.alldata[0].mm;
                  val.mw = event.alldata[0].mw;
                  val.md = event.alldata[0].md;
                  val.wm = event.alldata[0].wm;
                  val.ww = event.alldata[0].ww;
                  val.wd = event.alldata[0].wd;
                  val.dm = event.alldata[0].dm;
                  val.dw = event.alldata[0].dw;
                  val.dd = event.alldata[0].dd;
                  // this.possible = event.alldata;
                  // alexlang
                }
              });
              this.eventx2.forEach((val)=>{
                // console.log(event.alldata.mm);
                if (val.id==event.id) {
                  val.mmm = event.alldata[0].mmm;
                  val.mmw = event.alldata[0].mmw;
                  val.mmd = event.alldata[0].mmd;
                  val.mwd = event.alldata[0].mwd;
                  val.mww = event.alldata[0].mww;
                  val.mwm = event.alldata[0].mwm;
                  val.mdm = event.alldata[0].mdm;
                  val.mdw = event.alldata[0].mdw;
                  val.mdd = event.alldata[0].mdd;
                  val.wwm = event.alldata[0].wwm;
                  val.www = event.alldata[0].www;
                  val.wwd = event.alldata[0].wwd;
                  val.wdd = event.alldata[0].wdd;
                  val.wdw = event.alldata[0].wdw;
                  val.wdm = event.alldata[0].wdm;
                  val.wmm = event.alldata[0].wmm;
                  val.wmw = event.alldata[0].wmw;
                  val.wmd = event.alldata[0].wmd;
                  val.ddd = event.alldata[0].ddd;
                  val.ddm = event.alldata[0].ddm;
                  val.ddw = event.alldata[0].ddw;
                  val.dww = event.alldata[0].dww;
                  val.dwd = event.alldata[0].dwd;
                  val.dwm = event.alldata[0].dwm;
                  val.dmm = event.alldata[0].dmm;
                  val.dmw = event.alldata[0].dmw;
                  val.dmd = event.alldata[0].dmd;
                  // this.possible = event.alldata;
                  // alexlang
                }
              });
              this.eventx3.forEach((val)=>{
                // console.log(event.alldata.mm);
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
              this.eventx5.forEach((val)=>{
                if (val.id==event.id) {
                  this.eventx5 = event.alldata;
                }
              });
              this.eventx6.forEach((val)=>{
                if (val.id==event.id) {
                  this.eventx6 = event.alldata;
                }
              });
              this.eventx8.forEach((val)=>{
                if (val.id==event.id) {
                  this.eventx8 = event.alldata;
                }
              });
              this.eventx14.forEach((val)=>{
                if (val.id==event.id) {
                  this.eventx14 = event.alldata;
                }
              });
            });
            Echo.channel('leaders')
            .listen('leaderboards',(event)=>{
              this.getevent();
              this.getresults();
            })
        }
    }
</script>
