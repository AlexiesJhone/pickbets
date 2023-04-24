<style media="screen">
.vs__search::placeholder,
 {
    color: gray;
    text-transform: lowercase;
    font-weight: normal;
  }
</style>
<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <session :userx='user'></session>
            <div class="col-md-12">
              <div id="overlay" v-if="loading">
                <tile style="color:white"></tile>
                <center ><p class="text-light h6 centerthis">LOADING PLEASE WAIT..</p></center>
              </div>
            <!-- main menu -->
            <!-- <div class="card" v-if="show">
              <div class="card-header bg-dark text-white">
                <b>Events</b>
              </div>
              <div class="card-body">
              <ul class="nav nav-tabs" id="active" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Active Events</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="pastevents" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Past Events</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Event name</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <td>{{events.event_name}}</td>
                      <td v-if="events.status===1">Active</td>
                      <td> <a @click.prevent='openevent(events)' class="btn btn-success btn-sm">OPEN</a></td>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                  Past Events dito
                </div>
              </div>
            </div>
            </div> -->
            <div class="card" v-if="!show">
            <div class="card-header card-header-tabs card-header-dark bg-dark">
              <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                  <ul class="nav nav-tabs" data-tabs="tabs">
                    <li class="nav-item">
                      <a class="nav-link active" href="#profile" data-toggle="tab">
                        <i class="material-icons">event</i> Active Event
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#messages" data-toggle="tab">
                        <i class="material-icons">date_range</i> Past Events
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                    <li class="nav-item" >
                      <a class="nav-link text-success" @click.prevent="showaddevent">
                        <i class="material-icons text-success">add_box</i> Create New Event
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                  </ul>

                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane active table-responsive" id="profile">
                  <table class="table table-stripped table-hover">
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Event ID</th>
                        <th class="font-weight-bold">Event name</th>
                        <th class="font-weight-bold">Pick</th>
                        <th class="font-weight-bold">Fights</th>
                        <th class="font-weight-bold">Arena</th>
                        <th class="font-weight-bold">Amount</th>
                        <th class="font-weight-bold">FightDate</th>
                        <th class="font-weight-bold">Status</th>
                        <th class="font-weight-bold">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-if="events">
                        <td>{{events.id}}</td>
                        <td>{{events.event_name}}</td>
                        <td>{{control.pick}}</td>
                        <td>{{events.fights}}</td>
                        <td>{{events.venue}}</td>
                        <td>{{control.amount}}</td>
                        <td>{{events.fightdate|datef}}</td>
                        <td class="text-success" v-if="events.status===1">Active</td>
                        <td><a @click.prevent='openevent(events)' class="btn btn-success btn-sm text-white">OPEN</a> <a @click.prevent='editevent(events)' class="btn btn-info btn-sm text-white">EDIT</a><a @click.prevent='endevent(events)' class="btn btn-danger btn-sm text-white">STATUS</a>
                        </td>
                      </tr>
                      <tr v-for="pending in pendingevents">
                        <td>{{pending.id}}</td>
                        <td>{{pending.event_name}}</td>
                        <td>{{control.pick}}</td>
                        <td>{{pending.fights}}</td>
                        <td>{{pending.venue}}</td>
                        <td>{{control.amount}}</td>
                        <td>{{pending.fightdate|datef}}</td>
                        <td> <b class="text-danger"v-if="pending.status===0">Pending</b></td>
                        <td><a @click.prevent='editevent(pending)' class="btn btn-info btn-sm text-white">EDIT</a><a @click.prevent='activeevent(pending)' class="btn btn-danger btn-sm text-white">STATUS</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane table-responsive" id="messages">
                  <table class="table table-stripped table-hover">
                    <thead>
                      <tr>
                        <th class="font-weight-bold">Event ID</th>
                        <th class="font-weight-bold">Event Name</th>
                        <th class="font-weight-bold">Pick</th>
                        <th class="font-weight-bold">Fight</th>
                        <th class="font-weight-bold">Venue</th>
                        <th class="font-weight-bold">Amount</th>
                        <th class="font-weight-bold">FightDate</th>
                        <th class="font-weight-bold">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="t in pastevents">
                        <td>{{t.id}}</td>
                        <td>{{t.event_name}}</td>
                        <td>{{t.pick}}</td>
                        <td>{{t.fights}}</td>
                        <td>{{t.venue}}</td>
                        <td>{{t.amount}}</td>
                        <td>{{t.fightdate|datef}}</td>
                        <td class="font-weight-bold text-success">Finished</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

                <!-- Opened Events -->
                <div class="row">
                  <div class="col-md-8">
                  <div class="card"  v-if="show">
                    <div class="card-header-dark bg-dark text-white">
                      <b>Event Name :</b> <b class="text-warning"><b>{{events.event_name}} <b class="text-white">[Pick : {{control.pick}} | {{events.fights}} Fights]</b></b></b><a class="float-right text-white" @click.prevet='back'><i class="fa fa-undo text-danger" aria-hidden="true"></i> Back to Events</a>
                    </div>
                    <div class="card-body" style="">
                      <center><button type="button" name="button" class="btn btn-success btn-round btn-sm" @click.prevent="showstartingfight"><i class="material-icons">add_circle</i> Add Starting Fight</button></center>
                      <div class="alert alert-success font-weight-bold bg-dark" style="padding:8px 8px"  role="alert">
                        STARTING FIGHT NUMBERS
                      </div>
                      <div class="card-body table-responsive-sm" style="height:auto; max-height:30vh;overflow:auto;">
                      <table class="table table-striped table-hover" v-if="startings.length">
                        <thead>
                          <tr>
                            <th><b>Starting Fight</b></th>
                            <th><b>Status</b></th>
                            <th><b>Control</b></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="t in startings">
                            <th class="">{{t.startingfight}}</th>
                            <td> <a class="text-danger font-weight-bold" v-if="t.control==='Closed'">Closed</a><a class="text-success font-weight-bold" v-if="t.control==='Open'">Open</a><a class="text-warning font-weight-bold" v-if="t.control==='Last Call'">Last Call</a>
                            <a class="text-success font-weight-bold" v-if="t.control==='Finished'">Finished</a> </td>
                            <td> <a class="btn btn-success btn-sm text-white font-weight-bold" @click.prevent='openstartingfight(t)' v-if="t.control==='Closed'"><i class="material-icons">lock_open</i> OPEN</a>
                            <!-- <td> <a class="btn btn-success btn-sm text-white font-weight-bold" @click.prevent='openstartingfight(t)' v-if="t.control==='Closed' && t.startingfight<events.currentfight"><i class="material-icons">lock_open</i> OPEN</a> -->
                              <!-- <a class="btn btn-danger btn-sm text-white font-weight-bold" v-if="t.control==='Closed'" @click.prevent='removestarting(t)'><i class="material-icons">delete</i> REMOVE</a> -->
                              <a class="btn btn-warning btn-sm font-weight-bold" v-if="t.control==='Open'" @click.prevent='lastcallstartingfight(t)'><i class="material-icons">warning</i> LAST CALL</a>
                              <a class="btn btn-danger btn-sm text-white font-weight-bold" v-if="t.control==='Open'|| t.control==='Last Call'" @click.prevent='closestarting(t)'><i class="material-icons">close</i> CLOSED</a>
                              <a class="btn btn-warning btn-sm font-weight-bold" v-if="t.control==='Last Call'" @click.prevent='removelastcallstarting(t)'><i class="material-icons">warning_amber</i> Remove Last Call</a>
                              <a class="btn btn-default text-white btn-sm font-weight-bold" @click.prevent='getodds(t)'><span class="material-icons">check_circle_outline</span>Check Odds</a>
                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <!-- <p class="h5">Starting fight number controller - <b v-if="events.control==='Last'"><a class="text-success text-decoration-none">Open</a> ( <a class="text-warning">{{events.control}} Call </a>)</b><b v-if="events.control==='Open'" class="text-success">Open</b>
                       <b class="text-danger" v-if="events.control==='Close'">Closed</b> </p>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon3">Starting fight number </span>
                        </div>
                        <input type="number" :disabled='isDisabled' v-model="form.startingfight" placeholder="Choose starting fight number .."  class="form-control">
                      </div>
                      <div class="row justify-content-center" style="margin-bottom:-30px">
                        <a @click.prevent="submiteventupdate" class="btn btn-success text-white col-md-12" v-if="events.control==='Close'">Change</a>
                        <a @click.prevent="submiteventreopen" class="btn btn-dark text-white col-md-12" v-if="events.control==='Close'&& events.startingfight>0">Reopen</a>
                        <div class="col-md-6" v-if="events.control==='Open'||events.control==='Last'" @click.prevent='closebetting(events)'>
                          <a  class="btn btn-danger col-md-12 text-white"><b>Close Betting</b></a>
                        </div><br><br>
                        <div class="col-md-6" v-if="events.control==='Open'||events.control==='Last'">
                          <a v-if="events.control==='Open'||events.control==='Last'" @click.prevent='lastcall(events)' class="btn btn-warning col-md-12 text-dark"><b>Last Call</b></a>
                        </div> -->
                      </div>
                      </div>
                      <div class="card-body">
                      <!-- <p class="h5">FIGHT RESULT CONTROLLER</p> -->
                      <div class="alert alert-success font-weight-bold bg-dark" style="padding:10px 10px"  role="alert">
                        FIGHT RESULT CONTROLLER
                      </div>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon3">Fight number </span>
                        </div>
                        <input type="number" v-model="results.fightnumber" placeholder="Choose starting fight number .."  class="form-control" disabled>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <button @click.prevent='meron' :disabled='maindisabled' class="btn btn-danger col-md-12 text-white"><b>MERON</b></button>
                        </div><br><br>
                        <div class="col-md-6">
                          <button @click.prevent='wala' :disabled='maindisabled' class="btn btn-info col-md-12 text-white"><b>WALA</b></button>
                        </div><br><br>
                        <div class="col-md-6">
                          <button @click.prevent='draw' :disabled='maindisabled' class="btn btn-success col-md-12 text-white"><b>DRAW</b></button>
                        </div><br><br>
                        <div class="col-md-6">
                          <button @click.prevent='cancel' :disabled='maindisabled' class="btn btn-default col-md-12 text-white"><b>CANCEL</b></button>
                        </div>
                      </div>
                        </div>
                        <div class="card-footer">
                          <div class="container">
                            <a class="btn bg-dark col-md-12 text-white" @click.prevent='showpotmoney'>Click to enable claiming wins</a>
                            <a class="btn bg-secondary col-md-12 text-white" @click.prevent='selectdeclarators'>Click to select declarator  <a v-if="results.declarator_id && t.id===results.declarator_id" v-for="t in declarators">({{t.name}})</a> <a v-if="!results.declarator_id">(No selected declarator)</a> </a>
                            <!-- <a @click.prevent='sampleevent' class="btn bg-danger col-md-12 text-white"><b>Clear all data</b></a> -->
                            <!-- <a @click.prevent='sampleevent2' class="btn bg-danger col-md-12 text-white"><b>Do it</b></a> -->
                          </div>
                        </div>
                    </div>
                  </div>

                  <div class="col-md-4" v-if="show">
                    <div class="card bg-dark" >
                      <div class="card-header-dark bg-dark text-white font-weight-bold">
                        Results of the current event
                      </div>
                      <div class="card-body" style="height:auto; max-height:65vh;overflow:auto;padding:0">
                        <table class="table noborder" style="">
                          <thead class="">
                            <tr>
                              <th class="font-weight-bold noborder ">Fight #</th>
                              <th class="font-weight-bold noborder">Result</th>
                              <th class="font-weight-bold noborder">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for='(r,index) in resultx' class="">
                              <td class="font-weight-bold noborder">{{r.fightnumber}}</td>
                              <td class="noborder">
                                <p  v-if="r.result==='Meron'" class="text-danger font-weight-bold text-uppercase">{{r.result}}</p>
                                <p  v-if="r.result==='Wala'" class="text-info font-weight-bold text-uppercase">{{r.result}}</p>
                                <p  v-if="r.result==='Draw'" class="text-success font-weight-bold text-uppercase">{{r.result}}</p>
                                <p  v-if="r.result==='Cancelled'" class="text-white font-weight-bold text-uppercase">{{r.result}}</p>
                              </td>
                              <td class="noborder"> <a @click.prevent='showregrade(r)' v-if="r.fightnumber==resultx.length" class="btn btn-danger btn-sm text-white">Regrade</a> <a class="btn btn-danger btn-sm disabled" v-else disabled>Regrade</a> </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="card-footer text-center">
                      <b class="text-white font-weight-bold text-center"><center>{{currentTime}}</center></b>
                      </div>
                    </div>
                  </div>
                  </div>
                    </div>
                  <!-- CLAIMING CONTROLLER MODAL -->
                  <div class="modal fade" id="claims" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content"  style="border:none !important;">
                        <div class="modal-header bg-dark text-warning">
                          <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Claiming Controller</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                          <table class="table">
                            <thead>
                              <tr >
                                <th class="align-middle">Starting fight number</th>
                                <th class="align-middle">Total payout</th>
                                <th class="align-middle font-weight-bold">Claim</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="w in winnings">
                                <td class="align-middle font-weight-bold"> {{w.startingfight}}</td>
                                <td class="align-middle"> {{Number(w.payout).toLocaleString('en-PH', {style:'currency', currency:'PHP'})}}</td>
                                <td class="align-middle">
                                  <a v-if='w.claim===1' @click.prevent='openclaiming(w)' class="btn btn-success text-white btn-sm">Open</a>
                                  <a v-if="w.claim===2" @click.prevent='close(w)' class="btn btn-danger text-white btn-sm">Close</a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default col-md-12" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- GRADE MODAL -->
                  <div class="modal fade" id="grading" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                      <div class="modal-content"  style="border:none !important;">
                        <div class="modal-header bg-dark text-white">
                          <h5 class="modal-title text-warning font-weight-bold" id="exampleModalCenterTitle">Send Request To</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-white">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body container" style=" border:none !important;">
                          <center>
                          <label for="user">Select admin to confirm</label>
                          <v-select v-model='results.declarator_id' class="col-sm-12" :options="declarators" placeholder="Choose declarator" :reduce="username => username.id" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/>
                            </center>
                        </div>
                        <div class="modal-footer" style="padding:0">
                          <div class="container-fluid">
                              <!-- <button ref='alexbutton' class="btn btn-success col-sm-12 text-white"  @click.prevent='newconfirmed' :disabled='isDisabledr' autofocus>Confirm</button> -->
                              <button type="button" class="btn btn-secondary col-md-12" data-dismiss="modal">Close</button>
                          </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <!-- REGRADE MODAL -->
                  <div class="modal fade" id="regrade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                      <div class="modal-content"  style="border:none !important;">
                        <div class="modal-header bg-dark text-white">
                          <h5 class="modal-title text-warning font-weight-bold" id="exampleModalCenterTitle">Regrade Confirmation</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" style=" border:none !important;">
                          <center>Please choose result for fightnumber: <br> <b>{{results.fightnumber}}</b><br> Current result : <b>{{results.result}}</b>
                            <label for="user">Select admin to confirm</label>
                            <v-select v-model='results.declarator_id' class="col-sm-12" :options="declarators" placeholder="Choose Admin" :reduce="username => username.id" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></center>
                        </div>
                        <div class="modal-footer" style="padding:0px;">
                          <div class="container-fluid">
                            <div class="row">
                              <div class="col"><button  class="btn btn-info text-white col-md-12" :disabled='isDisabledr'  @click.prevent='confirmgradew(results)'>Wala</button ></div>
                              <div class="w-100"></div>
                              <div class="col"><button  class="btn btn-danger text-white col-md-12" :disabled='isDisabledr' @click.prevent='confirmgradem(results)'>Meron</button ></div>
                                <div class="w-100"></div>
                                <div class="col">
                                  <button  class="btn btn-warning col-md-12" :disabled='isDisabledr' @click.prevent='confirmgradec(results)'>Cancelled</button >
                                </div>
                                <div class="w-100"></div>
                                <div class="col">
                                  <button  class="btn btn-default text-white col-md-12" :disabled='isDisabledr' @click.prevent='confirmgraded(results)'>Draw</button >
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- modal startingfight -->
                  <div class="modal fade" id="startingfight" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content"  style="border:none !important;">
                        <div class="modal-header bg-dark text-warning">
                          <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Starting Fight</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" >
                          <label for="">Starting Fight</label>
                          <input type="number" v-model="start.startingfight" class="form-control">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-success btn-sm" @click.prevent="addstartingfight" :disabled='startingdisable'>Add Starting</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Modal add event -->
                  <div class="modal fade" id="addevent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content"  style="border:none !important;">
                        <div class="modal-header bg-dark text-warning">
                          <h5 class="modal-title font-weight-bold" id="exampleModalLongTitle" v-if="modalevent==='add'">Create New Event</h5>
                          <h5 class="modal-title font-weight-bold" id="exampleModalLongTitle" v-if="modalevent==='edit'">Edit Event</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <label for="">Event Name</label>
                          <input type="text" class="form-control" name="" v-model="eventx.event_name">
                            <!-- <label for="" v-if="modalevent==='add'">Pick</label>
                            <select  v-if="modalevent==='add'" class="form-control" data-style="btn btn-link" v-model="eventx.pick">
                              <option value="15">15</option>
                              <option value="20" selected>20</option>
                              <option value="24">24</option>
                            </select> -->
                          <!-- <label for="">Amount</label>
                          <input type="number" class="form-control" name="" v-model="eventx.amount"> -->
                          <label for="">Startingfight</label>
                          <input type="number" class="form-control" name="" v-model="eventx.startingfight">
                          <label for="">Fights</label>
                          <input type="number" class="form-control" name="" v-model="eventx.fights">
                          <label for="">Arena</label>
                          <select class="form-control" data-style="btn btn-link" v-model="eventx.arena">
                            <option value="" selected disabled>Choose Arena</option>
                            <option v-for="t in arenas">{{t.name}} ({{t.code}})</option>
                          </select>
                          <!-- <label for="">Arena</label>
                          <input type="text" class="form-control" name="" v-model="eventx.arena"> -->
                          <!-- <label for="">Rake</label>
                          <input type="number" class="form-control" name="" v-model="eventx.rake">
                          <label for="">Jackpot</label>
                          <input type="number" class="form-control" name="" v-model="eventx.jackpot">
                          <label for="">Percentage of Jackpot</label>
                          <input type="number" class="form-control" name="" v-model="eventx.pjackpot"> -->
                          <label  v-if="modalevent==='add'">Fight Date</label>
                          <label v-if="modalevent==='edit'">Current Fight Date <p class="text-muted">{{eventx.fightdate|datef}}</p></label>
                          <input type="datetime-local" class="form-control" id="datetimepicker1" name="" v-model="eventx.fightdate">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary" @click.prevent='addevent' :disabled='disablethis'  v-if="modalevent==='add'">Add Event</button>
                          <button type="button" class="btn btn-primary" @click.prevent='updateevent'  v-if="modalevent==='edit'">Update Event</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- odds modal -->
                  <div class="modal fade" id="oddsmdal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content" style="border:none !important;">
                        <div class="modal-header bg-dark text-warning">
                          <h5 class="modal-title modal-title font-weight-bold" id="exampleModalLongTitle">Live Odds</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body table-responsive" style="padding:0">
                          <table class="table table-sm table-hover">
                            <thead>
                              <tr>
                                <th class="font-weigh-bold text-center"><b>Starting Fight</b></th>
                                <th class="font-weigh-bold text-center"><b>Rake</b></th>
                                <th class="font-weigh-bold text-center"><b>Odds</b></th>
                                <th class="font-weigh-bold text-center"><b>Bet Count</b></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="t in odds">
                                <td class="text-center">{{fight.start}}</td>
                                <td class="text-center">{{totalrake.toLocaleString()}}</td>
                                <td class="text-center">{{liveodds.toLocaleString()}}</td>
                                <td class="text-center">{{livecount.toLocaleString()}}</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding:0">
                          <button type="button" class="btn btn-default btn-sm col-md-11" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- confirmgrade version 2 -->
                  <div class="modal fade" id="confirmgrade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                      <div class="modal-content"  style="border:none !important;">
                        <div class="modal-header bg-dark text-white">
                          <h5 class="modal-title text-warning font-weight-bold" id="exampleModalCenterTitle">Please Confirm Grading</h5>
                          <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button> -->
                        </div>
                        <div class="modal-body" style=" border:none !important;">
                          <center>
                        <b>{{results.firstdeclarator}}</b> has entered a result for <b>Fightnumber : {{results.fightnumber}}</b>. <br>Please enter your result.  </center>
                            <!-- <label for="user">Select admin to confirm</label> -->
                            <!-- <v-select v-model='results.declarator_id' class="col-sm-12" :options="declarators" placeholder="Choose Admin" :reduce="username => username.id" id="user" label="username" :select-on-key-codes="[188, 13]" selectOnTab :clearable="false"/></center> -->
                        </div>
                        <div class="modal-footer" style="padding:0px;">
                          <div class="container-fluid">
                            <div class="row">
                                <div class="col"><button  class="btn btn-danger text-white col-md-12" :disabled='isDisabledr' @click.prevent='version2meron()'>Meron</button ></div>
                              <div class="w-100"></div>
                              <div class="col"><button  class="btn btn-info text-white col-md-12" :disabled='isDisabledr'  @click.prevent='version2wala()'>Wala</button ></div>
                                <div class="w-100"></div>
                                <div class="col">
                                  <button  class="btn btn-success text-white col-md-12" :disabled='isDisabledr' @click.prevent='version2draw()'>Draw</button >
                                </div>
                                <div class="w-100"></div>
                                <div class="col">
                                  <button  class="btn btn-default col-md-12" :disabled='isDisabledr' @click.prevent='version2cancelled()'>Cancelled</button >
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
          selected1:'Select Arena',
          mainbutton:false,
          starting:null,
          loading:false,
          modalevent:'',
          lastfight:null,
          events:[],
          startings:[],
          show:null,
          disabled:false,
          disabledx:true,
          disabled2:false,
          disabled4:false,
          currentTime: null,
          declarators: [],
          resultx:[],
          odds:[],
          liveodds:[],
          livecount:[],
          totalrake:[],
          result:[],
          winnings:[],
          arenas:[],
          pendingevents:[],
          pastevents:[],
          control:[],
          start:new Form({
            startingfight:null,
            event_id:null,
            id:null
          }),
          winning:new Form({
            id:'',
            amount:'',
            event_id:'',
            control:'',
            user_id:this.user.id,
            startingfight:null,
          }),
          results:new Form({
            id:'',
            result:'',
            newresult:'',
            event_id:'',
            fightnumber:null,
            fightnumberregrade:null,
            user_id:this.user.id,
            declarator_id:'',
            firstdeclarator:'',
          }),
          eventx:new Form({
            id:'',
            event_name:'',
            pick:'',
            fights:'',
            arena:'',
            rake:'',
            jackpot:'',
            startingfight:'',
            pjackpot:'',
            fightdate:'',
            amount:'',
            status:'',
          }),
          form:new Form({
            id:'',
            event_name:'',
            fights:'',
            startingfight:'',
            status:'',
            control:'',
          }),
          fight:new Form({
            id:'',
            start:null,
            amount:null,
            user_id:this.user.id,
          }),
        }
      },
      computed: {
        maindisabled: function(){
          return this.mainbutton;
        },
         isDisabled: function(){
           if (this.events.control==='Close') {
             return this.disabled=false;
           }else {
             return this.disabled=true;
           }
        },
         isDisabledr: function(){
             return this.disabled2;
        },
        resultbutton: function(){
            return this.disabled3=false;
       },
        startingdisable: function(){
          if (this.disabled4===false) {
            return this.disabled4=false;
          }else {
            return this.disabled4=true;
          }
       },
       disablethis: function(){
         if (this.disabledx===false) {
           return this.disabled=false;
         }else {
           return this.disabled=true;
         }

      },

      },
      methods:{
        getcontrol(){
          axios.get('/pick20/control').then(response=>{
            this.control=response.data;
          });
        },
        sampleevent2(){
          axios.get('/pick20/doit').then(()=>{
            this.loading=true;
            Toast.fire({
              icon: 'success',
              title: 'all past bets has been active.'
            });
            this.getresults();
            $('#confirmgrade').modal('hide');
          }).catch(()=>{
            Swal.fire(
              'Ooops!',
              'Something went wrong.',
              'error'
            );
          })
        },
        version2cancelled(){
          Swal.fire({
            title: 'Please Confirm',
            text: "You Selected Cancelled",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm Cancelled'
          }).then((result) => {
            if (result.isConfirmed) {
              if (this.results.result==='Cancelled') {
                this.results.post('/pick20/confirmed').then(()=>{
                  this.loading=true;
                  Toast.fire({
                    icon: 'success',
                    title: 'You Successfully Confirmed.'
                  });
                  this.getresults();
                  this.Getallstartingfights();
                  $('#confirmgrade').modal('hide');
                }).catch(()=>{
                  Swal.fire(
                    'Ooops!',
                    'Result has already been graded.',
                    'error'
                  );
                })
              }else {
                this.loading=true;
                this.results.post('/pick20/mismatchresults').then(()=>{
                  this.loading=false;
                  Swal.fire(
                    'Error',
                    'Mismatch Results for fightnumber'+this.results.fightnumber+'.',
                    'error'
                  )
                  $('#confirmgrade').modal('hide');
                })
              }

            }
          })

        },
        version2draw(){
          Swal.fire({
            title: 'Please Confirm',
            text: "You Selected Draw",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm Draw'
          }).then((result) => {
            if (result.isConfirmed) {
              if (this.results.result==='Draw') {
                this.loading=true;
                this.results.post('/pick20/confirmed').then(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'success',
                    title: 'You Successfully Confirmed.'
                  });
                  this.getresults();
                  this.Getallstartingfights();
                  $('#confirmgrade').modal('hide');
                }).catch(()=>{
                  this.loading=false;
                  Swal.fire(
                    'Ooops!',
                    'Result has already been graded.',
                    'error'
                  );
                })
              }else {
                this.loading=true;
                this.results.post('/pick20/mismatchresults').then(()=>{
                  this.loading=false;
                  Swal.fire(
                    'Error',
                  'Mismatch Results for fightnumber'+this.results.fightnumber+'.',
                    'error'
                  )
                  $('#confirmgrade').modal('hide');
                })
              }

            }
          })

        },
        version2meron(){
          Swal.fire({
            title: 'Please Confirm',
            text: "You Selected Meron",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm Meron'
          }).then((result) => {
            if (result.isConfirmed) {
              if (this.results.result==='Meron') {
                this.loading=true;
                this.results.post('/pick20/confirmed').then(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'success',
                    title: 'You Successfully Confirmed.'
                  });
                  this.getresults();
                  this.Getallstartingfights();
                  $('#confirmgrade').modal('hide');
                }).catch(()=>{
                  this.loading=false;
                  Swal.fire(
                    'Ooops!',
                    'Result has already been graded.',
                    'error'
                  );
                })
              }else {
                this.loading=true;
                this.results.post('/pick20/mismatchresults').then(()=>{
                  this.loading=false;
                  Swal.fire(
                    'Error',
                    'Mismatch Results for fightnumber'+this.results.fightnumber+'.',
                    'error'
                  )
                  $('#confirmgrade').modal('hide');
                })
              }

            }
          })

        },
        version2wala(){
          Swal.fire({
            title: 'Please Confirm',
            text: "You Selected Wala",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm Wala'
          }).then((result) => {
            if (result.isConfirmed) {
              if (this.results.result==='Wala') {
                this.loading=true;
                this.results.post('/pick20/confirmed').then(()=>{
                  this.loading=false;
                  Toast.fire({
                    icon: 'success',
                    title: 'You Successfully Confirmed.'
                  });
                  this.getresults();
                  this.Getallstartingfights();
                  $('#confirmgrade').modal('hide');
                }).catch(()=>{
                  this.loading=false;
                  Swal.fire(
                    'Ooops!',
                    'Result has already been graded.',
                    'error'
                  );
                })
              }else {
                this.loading=true;
                this.results.post('/pick20/mismatchresults').then(()=>{
                  this.loading=false;
                  Swal.fire(
                    'Error',
                    'Mismatch Results.',
                    'error'
                  )
                  $('#confirmgrade').modal('hide');
                })
              }

            }
          })

        },
        confirmgrading(event){
          this.results.result=event.result;
          this.results.fightnumber=event.fightnumber;
          this.results.event_id=this.events.id;
          this.results.firstdeclarator=event.name;
          this.results.id=event.id;
          this.disabled2=false;
          $('#confirmgrade').modal('show');
        },
        getodds(t){
          this.fight.start=t.startingfight;
          this.fight.post('/pick20/getliveoddsadmin').then(response=>{
          this.odds=response.data;
          this.liveodds=response.data[0].odds;
          this.livecount=response.data[0].totalbets;
          this.totalrake=response.data[0].totalrake;
          $('#oddsmdal').modal('show');
        }).catch(()=>{
          Toast.fire({
            icon: 'warning',
            title: 'there`s no available odds..'
          });
        });
        },
        removelastcallstarting(t){
          Swal.fire({
            title: 'Are you sure?',
            text: "That you want to remove LAST CALL on starting fight number "+t.startingfight+' ?' ,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.start.id=t.id;
              this.start.post('/pick20/removelastcallstarting').then(()=>{
                this.Getallstartingfights();
                Toast.fire({
                  icon: 'success',
                  title: 'Starting Fight number '+t.startingfight+', Successfully updated'
                });
              });
            }
          })

        },
        lastcallstartingfight(t){
          Swal.fire({
            title: 'Are you sure?',
            text: "That you want to LAST CALL on starting fight number "+t.startingfight+' ?' ,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.start.id=t.id;
              this.start.post('/pick20/lastcallstartingfight').then(()=>{
                this.Getallstartingfights();
                Toast.fire({
                  icon: 'success',
                  title: 'Starting Fight number '+t.startingfight+', Successfully updated'
                });
              });
            }
          })
        },
        closestarting(t){
          Swal.fire({
            title: 'Are you sure?',
            text: "That you want to CLOSE on starting fight number "+t.startingfight+' ?' ,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.start.id=t.id;
              this.start.post('/pick20/closestartingfight').then(()=>{
                this.Getallstartingfights();
                Toast.fire({
                  icon: 'success',
                  title: 'Starting Fight number '+t.startingfight+', Successfully Closed'
                });
              });
            }
          })
        },
        openstartingfight(t){
          Swal.fire({
            title: 'Are you sure?',
            text: "That you want to OPEN on starting fight number "+t.startingfight+' ?' ,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.start.id=t.id;
              this.start.post('/pick20/openstartingfight').then(response=>{
                if (response.data.error) {
                  Toast.fire({
                    icon: 'error',
                    title: response.data.error,
                  });
                }else {
                  this.Getallstartingfights();
                  Toast.fire({
                    icon: 'success',
                    title: 'Starting Fight number '+t.startingfight+', Successfully Opened'
                  });
                }
              });
            }
          })
        },
        removestarting(t){
          Swal.fire({
            title: 'Are you sure?',
            text: "That you want to OPEN on starting fight number "+t.startingfight+' ?' ,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.start.id=t.id;
              this.start.post('/pick20/removestarting').then(()=>{
                this.Getallstartingfights();
                Toast.fire({
                  icon: 'success',
                  title: 'Starting Fight number '+t.startingfight+', Successfully Removed'
                });
              });
            }
          })
        },
        // wala(){
        //   // bagong meron
        //   this.disabled2=false;
        //   this.results.result = "Wala";
        //   $('#grading').modal('show');
        // },
        // cancel(){
        //   // bagong meron
        //   this.disabled2=false;
        //   this.results.result = "Cancelled";
        //   $('#grading').modal('show');
        // },
        // draw(){
        //   // bagong meron
        //   this.disabled2=false;
        //   this.results.result = "Draw";
        //   $('#grading').modal('show');
        // },
        Getallstartingfights(){
          this.start.event_id = this.events.id;
          axios.get('/pick20/startingfights').then(response=>{
            this.startings = response.data;
          })
        },
        addstartingfight(){
          this.disabled4=true;
          this.start.event_id = this.events.id;
          this.start.post('/pick20/insertstartingfight').then(response=>{
            if (response.data.error) {
              this.disabled4=false;
              Toast.fire({
                icon: 'error',
                title: 'startingfight not successfully added'
              });
            }else {
              $('#startingfight').modal('hide');
            Toast.fire({
              icon: 'success',
              title: 'Starting Fight Successfully Added'
            });
            this.Getallstartingfights();
            }
          }).catch(()=>{
            this.disabled4=false;
            Toast.fire({
              icon: 'error',
              title: 'Starting Fight Not Successfully Added'
            });
          })
        },
        showstartingfight(){
          this.start.reset();
          this.disabled4=false;
          $('#startingfight').modal('show');
        },
        selectdeclarators(){
          // bagong meron
          $('#grading').modal('show');
        },
        newconfirmed(){
          // bagong meron
          this.disabled2 = true;
          $('#grading').modal('hide');
          this.loading=true;
          this.results.event_id=this.events.id;
          this.results.post('/pick20/insertresults').then(()=>{
          this.loading=false;
          this.getresults();
          Toast.fire({
            icon: 'success',
            title: 'Please wait for the others to confirm'
          });
          })
          .catch(()=>{
                this.loading=false;
                this.disabled2=false;
                $('#grading').modal('show');
                Swal.fire(
                  'Error!',
                  'Please make sure that the fight number field is required and admin to confirmed',
                  'error'
                )
              });
          //   }else {
          //     this.loading=false;
          //   }
          // })
        },
        getdeclarators(){
          axios.get('/pick20/getdeclarators').then(response=>{
            this.declarators = response.data;
          })
        },
        getpastevents(){
          axios.get('/pick20/getallpastbets').then(response=>{
            this.pastevents = response.data;
          })
        },
        maintainrefresh(){
          if (this.user.page>0) {
            this.getevents();
            this.show=this.user.page;
            // this.form.id=this.user.page;
            // this.form.control=this.events.control;
            // this.form.event_name=this.events.event_name;
            // this.form.event_name=this.events.fights;
            // this.form.status=this.events.status;
            this.getresults();
          }else {
            this.show=null;
          }
        },
        activeevent(events){
          Swal.fire({
            title: 'Select status',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: `Active`,
            cancelButtonText: `Go back`,
            // denyButtonText: `Don't save`,
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: 'Please Confirm',
                text: "Are you sure that you want to active this event?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
              }).then((result) => {
                if (result.isConfirmed) {
                  this.loading=true;
                  this.eventx.fill(events);
                  this.eventx.status = 1;
                  this.eventx.post('/pick20/updatestatus').then(()=>{
                    this.loading=false;
                    this.getevents();
                    this.getpastevent();
                    Swal.fire(
                      'Success!',
                      'Event has been moved to past events',
                      'success'
                    )
                  }).catch(()=>{
                    this.loading=false;
                    Swal.fire(
                      'Error',
                      'Event status has not been change <br>Please make sure that theres only 1 active event',
                      'error'
                    )
                  })
                }else {
                    this.activeevent();
                }
              })
            }
          })
        },
        endevent(events){
          Swal.fire({
            title: 'Select status',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Finished`,
            denyButtonText: `Pending`,
            cancelButtonText: `Go back`,
            denyButtonColor:'#f44336',
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: 'Please Confirm',
                text: "Do you really want to finish the event?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#f44336',
                cancelButtonText: `Go back`,
                confirmButtonText: 'Confirm'
              }).then((result) => {
                if (result.isConfirmed) {
                  this.loading=true;
                  this.eventx.fill(events);
                  this.eventx.status = 2;
                  this.eventx.post('/pick20/updatestatus').then(()=>{
                    this.loading=false;
                    this.getevents();
                    Swal.fire(
                      'Success!',
                      'Event has been moved to past events',
                      'success'
                    )
                  }).catch(()=>{
                    this.loading=false;
                    Swal.fire(
                      'Error',
                      'Event status has not been change <br>Please make sure that theres only 1 active event',
                      'error'
                    )
                  })
                }else {
                  this.endevent();
                }
              })
            } else if (result.isDenied) {
              Swal.fire({
                title: 'Please Confirm',
                text: "Do you really want to change the event status to pending?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#f44336',
                confirmButtonText: 'Confirm'
              }).then((result) => {
                if (result.isConfirmed) {
                  this.loading=true;
                  this.eventx.fill(events);
                  this.eventx.status = 0;
                  this.eventx.post('/pick20/updatestatus').then(()=>{
                    this.loading=false;
                    this.getpastevent();
                    this.getevents();
                    Swal.fire(
                      'Success!',
                      'Event has been updated',
                      'success'
                    )
                  }).catch(()=>{
                    this.loading=false;
                    Swal.fire(
                      'Error',
                      'Event status has not been change <br>Please make sure that theres only 1 active event',
                      'error'
                    )
                  })
                }else {
                  this.endevent();
                }
              })
            }
          })
        },
        updateevent(){
          this.loading=true;
          this.eventx.post('/pick20/editevent').then(()=>{
            Toast.fire({
              icon: 'success',
              title: 'Event successfully updated'
            });
            $('#addevent').modal('hide');
              this.getpastevent();
              this.getevents();
              this.loading=false;
          }).catch(()=>{
            this.loading=false;
            Swal.fire(
              'Error',
              'Please make sure that there is only 1 active event',
              'error'
            );
          })
        },
        editevent(data){
          this.modalevent='edit';
          this.eventx.reset();
          this.getallarena();
          $('#addevent').modal('show');
          this.eventx.fill(data);
          this.eventx.arena=data.venue;
        },
        getpastevent(){
          this.loading=true;
          axios.get('/pick20/getpastevent').then(response=>{
            this.pendingevents=response.data;
            this.loading=false;
          })
        },
        addevent(){
          this.loading=true;
          this.eventx.post('/pick20/addevent').then(response=>{

              this.disabledx=true;
              $('#addevent').modal('hide');
              this.loading=false;
              Toast.fire({
                icon: 'success',
                title: 'starting successfully added'
              });
              this.eventx.reset();
              this.getpastevent();
          }).catch(()=>{
            this.loading=false;
            Toast.fire({
              icon: 'error',
              title: 'Please double check all fields'
            });
          });
        },
        showaddevent(){
          this.getallarena();
          this.disabledx=false;
          this.eventx.reset();
          this.modalevent='add';
          $('#addevent').modal('show');
        },
        getallarena(){
          this.loading=true;
          axios.get('/pick20/getallarena').then(response=>{
            this.loading=false;
            this.arenas = response.data;
          }).catch(()=>{
            this.loading=false;
            Swal.fire(
              'Error',
              'No arena records',
              'error'
            )
          });
        },
        close(w){
          Swal.fire({
          title: 'Please Confirm',
          text: "That you want to close this claim?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirm'
        }).then((result) => {
          if (result.isConfirmed) {
            this.loading=true;
            this.winning.fill(w);
            this.winning.control="CLOSE";
            this.winning.post('/pick20/confirmclaimclose').then(()=>{
            this.loading=false;
            this.getwinnings();
            Toast.fire({
              icon: 'success',
              title: 'Please wait for the confirmation'
            });
            })
          }
        })

        },
        openclaiming(w){
          Swal.fire({
            title: 'Please Confirm',
            text: "That you want to open this claim?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.winning.fill(w);
              this.winning.control="OPEN";
              this.winning.post('/pick20/confirmclaimopen').then(()=>{
                this.loading=false;
                this.getwinnings();
                Toast.fire({
                  icon: 'success',
                  title: 'Please wait for the confirmation'
                });
              });
            }
          })
        },
        showpotmoney(){
          $('#claims').modal('show');
          this.getwinnings();
        },
        getwinnings(){
          this.loading=true;
          axios.get('/pick20/getwinnings').then(response=>{
            this.winnings=response.data;
            this.loading=false;
          });
        },
        updateCurrentTime() {
          this.currentTime =  moment().format('LTS');
        },
        confirmgradec(results){
          this.disabled2=true;
          $('#regrade').modal('hide');
          Swal.fire({
            title: 'Please Confirm',
            text: "Are you sure you want to regrade this fight?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.results.newresult="Cancelled";
              this.results.id=results.id;
              this.results.post('/pick20/confirmgrade').then(()=>{
                this.loading=false;
                $('#loading').modal('hide');
                Swal.fire(
                  'Success!',
                  'Please wait for the confirmation of the other admin',
                  'success'
                );
                $('#regrade').modal('hide');
              }).catch(()=>{
                this.disabled2=false;
                this.loading=false;
                $('#regrade').modal('show');
                Swal.fire(
                  'Error!',
                  'Please make sure that the fight number field is required and admin to confirmed',
                  'error'
                )
              });
            }else {
              this.disabled2=false;
              $('#regrade').modal('show');
            }
          }).catch(()=>{
            this.disabled2=false;
            this.loading=false;
            $('#regrade').modal('show');
            Swal.fire(
              'Error!',
              'Please make sure that the fight number field is required and admin to confirmed',
              'error'
            )
          })

        },
        confirmgraded(results){
          // $('#loading').modal('show');
          $('#regrade').modal('hide');
          this.disabled2=true;
          Swal.fire({
            title: 'Please Confirm',
            text: "Are you sure you want to regrade this fight?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.results.newresult="Draw";
              this.results.id=results.id;
              this.results.post('/pick20/confirmgrade').then(()=>{
                this.loading=false;
                Swal.fire(
                  'Success!',
                  'Please wait for the confirmation of the other admin',
                  'success'
                );
                $('#regrade').modal('hide');
              }).catch(()=>{
                this.disabled2=false;
                this.loading=false;
                $('#regrade').modal('show');
                Swal.fire(
                  'Error!',
                  'Please make sure that the fight number field is required and admin to confirmed',
                  'error'
                )
              });
            }else {
              this.disabled2=false;
              $('#regrade').modal('show');
            }
          }).catch(()=>{
            this.disabled2=false;
            this.loading=false;
            $('#regrade').modal('show');
            Swal.fire(
              'Error!',
              'Please make sure that the fight number field is required and admin to confirmed',
              'error'
            )
          })

        },
        confirmgradem(results){
          $('#regrade').modal('hide');
          this.disabled2=true;
          // $('#loading').modal('show');
          Swal.fire({
            title: 'Please Confirm',
            text: "Are you sure you want to regrade this fight?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.results.newresult="Meron";
              this.results.id=results.id;
              this.results.post('/pick20/confirmgrade').then(()=>{
                this.loading=false;
                $('#loading').modal('hide');
                Swal.fire(
                  'Success!',
                  'Please wait for the confirmation of the other admin',
                  'success'
                );
                $('#regrade').modal('hide');
              }).catch(()=>{
                this.disabled2=false;
                this.loading=false;
                $('#regrade').modal('show');
                Swal.fire(
                  'Error!',
                  'Please make sure that the fight number field is required and admin to confirmed',
                  'error'
                )
              });
            }else {
              this.disabled2=false;
                $('#regrade').modal('show');
            }
          }).catch(()=>{
            this.disabled2=false;
            this.loading=false;
            $('#regrade').modal('show');
            Swal.fire(
              'Error!',
              'Please make sure that the fight number field is required and admin to confirmed',
              'error'
            )
          })
        },
        confirmgradew(results){
          // $('#loading').modal('show');
          $('#regrade').modal('hide');
          this.disabled2=true;
          Swal.fire({
            title: 'Please Confirm',
            text: "Are you sure you want to regrade this fight?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.results.newresult = 'Wala'
              this.results.id=results.id;
              this.results.post('/pick20/confirmgrade').then(()=>{
                this.loading=false;
                $('#loading').modal('hide');
                Swal.fire(
                  'Success!',
                  'Please wait for the confirmation of the other admin',
                  'success'
                );
                $('#regrade').modal('hide');
              }).catch(()=>{
                this.disabled2=false;
                this.loading=false;
                $('#regrade').modal('show');
                Swal.fire(
                  'Error!',
                  'Please make sure that the fight number field is required and admin to confirmed',
                  'error'
                )
              });
            }else {
              this.disabled2=false;
              $('#regrade').modal('show');
            }
          }).catch(()=>{
            this.disabled2=false;
            this.loading=false;
            $('#regrade').modal('show');
            Swal.fire(
              'Error!',
              'Please make sure that the fight number field is required and admin to confirmed',
              'error'
            )
          })

        },
        rcancell(){
          this.loading=true;
          this.disabled2=true;
          this.results.result="Cancelled";
          $('#regrade').modal('hide');
          this.results.post('/pick20/regrade').then(()=>{
            this.loading=false;
            this.getresults();
          })
        },
        rdraw(){
          this.loading=true;
          this.disabled2=true;
          this.results.result="Draw";
          $('#regrade').modal('hide');
          this.results.post('/pick20/regrade').then(()=>{
            this.loading=false;
            this.getresults();
          })
        },
        rmeron(){
          this.loading=true;
          this.disabled2=true;
          this.results.result="Meron";
          $('#regrade').modal('hide');
          this.results.post('/pick20/regrade').then(()=>{
            this.loading=false;
            this.getresults();
          })
        },
        rwala(){
          this.loading=true;
          this.disabled2=true;
          this.results.result="Wala";
          $('#regrade').modal('hide');
          this.results.post('/pick20/regrade').then(()=>{
            this.loading=false;
            this.getresults();
          })
        },
        showregrade(r){
          this.disabled2=false;
          // this.results.fill(r);
          this.results.fightnumberregrade = r.fightnumber;
          this.results.event_id = r.event_id;
          this.results.id = r.id;
          this.results.user_id = this.user.id;
          this.results.result = r.result;
          $('#regrade').modal('show');
        },
        sampleevent(){
          Swal.fire({
            title: 'Are you sure?',
            text: "that you want to clear all data?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire(
                'Deleted!',
                'All data has been deleted.',
                'success'
              );
              axios.get('/pick20/samplelang');
            }
          })

        },
        getresults(){
          this.loading=true;
          axios.get('/pick20/getresults').then(response=>{
            this.loading=false;
            this.resultx=response.data;
            if (this.resultx.length) {
              this.results.fightnumber=this.resultx[0].fightnumber+1;
            }else {
                this.results.fightnumber=1;
            }
          });
        },
        draw(){
          Swal.fire({
            title: 'Are you sure?',
            text: "You clicked Draw",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.results.result="Draw";
              this.results.event_id=this.events.id;
              this.results.post('/pick20/insertresults').then(()=>{
              this.loading=false;
              this.getresults();
              Toast.fire({
                icon: 'success',
                title: 'Please wait for the others to confirm'
              });
              }).catch(()=>{
                this.mainbutton = false;
                this.loading=false;
                Swal.fire(
                  'Error!',
                  'Please make sure that you select a declarator',
                  'error'
                )
              });
            }else {
              $('#loading').modal('hide');
            }
          })
        },
        cancel(){
          Swal.fire({
            title: 'Are you sure?',
            text: "You clicked Cancel",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.results.result="Cancelled";
              this.results.event_id=this.events.id;
              this.results.post('/pick20/insertresults').then(()=>{
              this.loading=false;
              this.getresults();
              Toast.fire({
                icon: 'success',
                title: 'Please wait for the others to confirm'
              });
              }).catch(()=>{
                this.mainbutton = false;
                this.loading=false;
                Swal.fire(
                  'Error!',
                  'Please make sure that you select a declarator',
                  'error'
                )
              });
            }else {
            this.loading=false;
            }
          })
        },
        wala(){
          Swal.fire({
            title: 'Are you sure?',
            text: "You clicked Wala",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.results.result="Wala";
              this.results.event_id=this.events.id;
              this.results.post('/pick20/insertresults').then(()=>{
                this.loading=false;
              this.getresults();
              Toast.fire({
                icon: 'success',
                title: 'Please wait for the others to confirm'
              });
              }).catch(()=>{
                this.mainbutton = false;
                this.loading=false;
                Swal.fire(
                  'Error!',
                  'Please make sure that you select a declarator',
                  'error'
                )
              });
            }else {
              this.loading=false;
            }
          })
        },
        meron(){
          Swal.fire({
            title: 'Are you sure?',
            text: "You clicked Meron",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.results.result="Meron";
              this.results.event_id=this.events.id;
              this.results.post('/pick20/insertresults').then(()=>{
                // this.mainbutton = true;
                this.loading=false;
              this.getresults();
              Toast.fire({
                icon: 'success',
                title: 'Please wait for the others to confirm'
              });
              }).catch(()=>{
                // this.mainbutton = false;
                this.loading=false;
                Swal.fire(
                  'Error!',
                  'Please make sure that you select a declarator',
                  'error'
                )
              });
            }else {
              this.loading=false;
            }
          })
        },
        back(){
          this.loading=true;
          axios.get('/pick20/back').then(()=>{
            this.loading=false;
            this.show=null;
          })
        },
        lastcall(){
          this.form.control='Last';
          this.loading=true;
          this.form.post('/pick20/lastcall').then(()=>{
            this.loading=false;
            Toast.fire({
              icon: 'warning',
              title: 'Last Call'
            });
            this.getevents();
          });
        },
        closebetting(){
          Swal.fire({
            title: 'Please Confirm',
            text: "That you want to close the betting?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.form.control='Close';
              this.form.post('/pick20/closebetting').then(()=>{
                this.loading=false;
                Toast.fire({
                  icon: 'success',
                  title: 'Event Closed'
                });
                this.getevents();
              });
            }
          })
        },
        submiteventreopen(){
          Swal.fire({
            title: 'Are you sure?',
            text: "That you want to reopen the fight?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.form.control='Reopen';
              this.form.post('/pick20/updateevent').then(response=>{
                this.loading=false;
                this.events=response.data;
                Toast.fire({
                  icon: 'success',
                  title: 'Fight number moved !'
                });
                this.getevents();
              }).catch(()=>{
                this.loading=false;
                Swal.fire(
                  'Error!',
                  'Please make sure that the fight number is not below than the previous fight number, and not exceeded to the last fight.',
                  'error'
                )
              });
            }
          })

        },
        submiteventupdate(){
          Swal.fire({
            title: 'Are you sure?',
            text: "That you want to move the fight?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
          }).then((result) => {
            if (result.isConfirmed) {
              this.loading=true;
              this.form.control='Open';
              this.form.post('/pick20/updateevent').then(response=>{
                this.loading=false;
                this.events=response.data;
                Toast.fire({
                  icon: 'success',
                  title: 'Fight number moved !'
                });
                this.getevents();
              }).catch(()=>{
                this.loading=false;
                Swal.fire(
                  'Error!',
                  'Please make sure that the fight number is not below than the previous fight number, and not exceeded to the last fight.',
                  'error'
                )
              });
            }
          })
        },
        openevent(events){
          this.form.id=events.id;
          this.form.post('/pick20/pager').then(()=>{
            // Toast.fire({
            //   icon: 'success',
            //   title: 'id added to user !'
            // });
            this.form.fill(events);
            this.getevents();
            this.getresults();
            this.Getallstartingfights();
            this.show=1;
          })
        },
        getevents(){
          this.loading=true;
          axios.get('/pick20/getevents').then(response=>{
            this.events=response.data;}).then(()=>{
              this.form.fill(this.events)
              this.form.startingfight=this.events.startingfight;
              // this.results.fightnumber=this.events.startingfight;
              this.loading=false;
            });
        }
      },
        created() {
          this.getcontrol();
          this.getdeclarators();
          this.getwinnings();
          this.currentTime = moment().format('LTS');
          setInterval(() => this.updateCurrentTime(), 1 * 1000);
          this.getpastevents();
          this.getpastevent();
          this.getevents();
          this.maintainrefresh();
          this.getresults();
          this.Getallstartingfights();
          Echo.private('insert_bet')
          .listen('betevent',(event)=>{
            if (event.startingfight===this.fight.start) {
              this.liveodds = this.liveodds + event.bet;
              this.livecount = this.livecount + 1;
              console.log(event)
            }
          });
          Echo.private('grade')
          .listen('resultevent',(event)=>{
            if (event.type==='Confirm' && this.show > 0 && event.event_id === this.events.id && event.declarator_id === this.user.id) {
              // confirmgrade
                this.confirmgrading(event);
            // original grading
            //   Swal.fire({
            //   title: 'Please Confirm',
            //   html: "<b>Result<b><br>"+"Fight# : <b>"+event.fightnumber+"</b><br>Result : <b>"+event.result+"</b><br>Userss : <b>"+event.name+"</b>",
            //   icon: 'warning',
            //   showCancelButton: true,
            //   confirmButtonColor: '#3085d6',
            //   cancelButtonColor: '#d33',
            //   confirmButtonText: 'Confirm'
            // }).then((result) => {
            //   if (result.isConfirmed) {
            //     this.results.result=event.result;
            //     this.results.fightnumber=event.fightnumber;
            //     this.results.event_id=this.events.id;
            //     this.results.id=event.id;
                // this.results.post('/pick20/confirmed').then(()=>{
                //   this.loading=true;
                //   Toast.fire({
                //     icon: 'success',
                //     title: 'You Successfully Confirmed.'
                //   });
                //   this.getresults();
                // }).catch(()=>{
                //   Swal.fire(
                //     'Ooops!',
                //     'Result has already been graded.',
                //     'error'
                //   );
                // })
            //
            //   }
            // })
            // original grading
          }else if (event.type==='Confirmed' && this.show > 0) {
            this.loading=false;
            // Swal.fire(
            //   'Confirmed Graded!',
            //   'Result has been confirmed by '+event.name+'.',
            //   'success'
            // );
            this.mainbutton = false;
            Toast.fire({
              icon: 'success',
              title: 'All bets has been graded'
            });
            this.Getallstartingfights();
            this.getresults();
            this.getevents();
          }else if (event.type==='eventupdate' && this.show > 0) {
            $('#loading').modal('hide');
            this.form.staringfight=this.events.startingfight;
            this.getevents();
            this.Getallstartingfights();
            Toast.fire({
              icon: 'success',
              title: 'Starting fights updated.'
            });
            $('#loading').modal('hide');
          }else if (event.type==='Confirmgrade' && this.show > 0 && event.event_id === this.events.id && event.declarator_id === this.user.id) {
            Swal.fire({
              title: 'Please Confirm Regrade',
              html: "<b>Result<b><br>"+"Fight# : <b>"+event.fightnumber+"</b><br>Result : <b>"+event.newresult+"</b><br>User : <b>"+event.name+"</b>",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {
              if (result.isConfirmed) {
                this.loading = true;
                this.results.fill(event);
                this.results.event_id=this.events.id;
                this.results.user_id=event.declarator_id;
                this.results.result=event.result;
                this.results.newresult=event.newresult;
                this.results.post('/pick20/regrade').then(()=>{
                  Swal.fire(
                    'Success!',
                    'Result has been updated.',
                    'success'
                  );
                  this.getresults();
                })
              }
            })
          }else if (event.type==='Confirmgraded' && this.show > 0) {
            this.loading = false;
            Swal.fire(
              'Success!',
              'Result has been updated by '+event.name,
              'success'
            );
            this.getresults();
          }
          else if (event.type==='confirmclaimclose' && this.show > 0) {
            Swal.fire({
              title: 'Please Confirm',
              html: "User <b>"+event.name+'</b> wants to <b>'+event.result+'</b> claim, for fight number <b>'+event.fightnumber+'</b>',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {
              if (result.isConfirmed) {
                this.winning.id=event.id;
                this.winning.startingfight=event.fightnumber;
                this.winning.event_id=this.events.id;
                this.winning.user_id=event.declarator_id;
                this.winning.post('/pick20/closeclaim').then(()=>{
                  this.getwinnings();
                  Swal.fire(
                    'Success!',
                    'Claim successfully updated',
                    'success'
                  );
                })
              }
            })
          }
          else if (event.type==='confirmclaimedclose' && this.show > 0) {
            Swal.fire(
              'Success!',
              'Claim has been updated by '+event.name,
              'success'
            );
            this.getwinnings();
          }
          else if (event.type==='confirmclaimopen' && this.show > 0) {
            Swal.fire({
              title: 'Please Confirm',
              html: "User <b>"+event.name+'</b> wants to <b>'+event.result+'</b> claim, for fight number <b>'+event.fightnumber+'</b>',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {
              if (result.isConfirmed) {
                this.winning.id=event.id;
                this.winning.startingfight=event.fightnumber;
                this.winning.event_id=this.events.id;
                this.winning.user_id=event.declarator_id;
                this.winning.post('/pick20/openclaim').then(()=>{
                  this.getwinnings();
                  Swal.fire(
                    'Success!',
                    'Claim successfully updated',
                    'success'
                  );
                })
              }
            })
          }
          else if (event.type==='confirmclaimopened' && this.show > 0) {
            Swal.fire(
              'Success!',
              'Claim has been updated by '+event.name,
              'success'
            );
            this.getwinnings();
          }
          else if (event.type==='successreopen' && this.show > 0) {
            Swal.fire(
              'Success!',
              'Reopen for fight number '+event.fightnumber+' is confirmed by '+event.name,
              'success'
            );
            this.getevents();
          }
          else if (event.type==='reopenfight' && this.show > 0) {
            Swal.fire({
              title: 'Please Confirm',
              html: "User <b>"+event.name+'</b> wants to <b>REOPEN</b> starting fight number <b>'+event.fightnumber+'</b>',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {
              if (result.isConfirmed) {
                this.winning.id=event.id;
                this.winning.startingfight=event.fightnumber;
                this.winning.event_id=this.events.id;
                this.winning.user_id=event.declarator_id;
                this.winning.post('/pick20/confirmedreopen').then(()=>{
                  this.getevents();
                  Swal.fire(
                    'Success!',
                    'Starting fight number '+event.fightnumber+' has been successfully reopen',
                    'success'
                  );
                })
              }
            })
          }
          else if (event.type==='endevent') {
            this.show=null;
            this.getevents();
            this.getpastevent();
            Swal.fire(
              'Please be inform',
              'Event has been updated by '+event.name,
              'warning'
            );
          }
          else if (event.type==='confirmedloading' && this.show > 0) {
            this.loading=true;
          }
          else if (event.type==='Confirmed_result' && this.show > 0) {
            this.loading=false;
            this.mainbutton = true;
            // Swal.fire(
            //   'Confirmed!',
            //   'Result has been confirmed by '+event.name+'.',
            //   'success'
            // );
            Toast.fire({
              icon: 'success',
              title:  'Result has been confirmed by '+event.name+'.'
            });
            this.getresults();
            this.Getallstartingfights();
          }
          else if (event.type==='mismatchresults' && this.show > 0) {
            this.loading=false;
            Swal.fire(
              'Error',
              'Mismatch Results for fightnumber '+event.fightnumber+'.',
              'error'
            );
            // this.getresults();
            // this.Getallstartingfights();
          }
          else {
            $('#loading').modal('hide');
          }
            console.log(event);
            // Swal.fire(
            //   'Shhh!',
            //   'Try lang muna '+event.fightnumber,
            //   'success'
            // )

          })
        }
    }
</script>
