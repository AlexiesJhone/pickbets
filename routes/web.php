<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::any('/', [App\Http\Controllers\PendingController::class, 'leaders']);
// Route::post('/transferfunds', [App\Http\Controllers\PendingController::class, 'transferfunds']);
Route::any('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/leaders', function () {
    return view('topplayers');
})->name('leaders');
Route::get('/pastwinners', function () {
    return view('winners');
})->name('winners');
Route::get('/summary', function () {
	if(auth()->user()->role==9 && auth()->user()->lock)
	{
		return view('summary');
	}elseif(auth()->user()->role==4 && auth()->user()->lock){
		return view('summary2');
	}else{
			if(auth()->user()->role==4){
				return view('cashier');
			}else{
				return view('home');
			}

	}

})->name('summary');
Route::get('/test', function () {
    return view('test');
})->name('test');
Route::get('/rules', function () {
    return view('rules');
})->name('rules');
Route::get('/prizes', function () {
    return view('prizes');
})->name('prizes');
Route::get('/personal', function () {
    return view('personalreport');
});
Route::get('/password-reset', function () {
    return view('auth.passwords.email');
})->name('passwordreset');
Route::get('login', [App\Http\Controllers\HomeController::class, 'login']);
Route::get('registers/{id}', [App\Http\Controllers\Eventscontroller::class, 'registergroup']);
Route::POST('login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('login');
Route::POST('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::POST('logout2', [App\Http\Controllers\Auth\LoginController::class, 'logoutforce'])->name('logoutforce');

Auth::routes(['verify' => true]);
// users /cashiers

Route::any('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/pick20/possiblepayout', [App\Http\Controllers\BetController::class, 'possiblepayout']);
Route::get('/pick20/possiblepayoutall', [App\Http\Controllers\BetController::class, 'possiblepayoutall']);
Route::get('/pick20/possiblepayoutallpick3', [App\Http\Controllers\BetController::class, 'possiblepayoutallpick3']);
Route::get('/pick20/possiblepayoutallpick4', [App\Http\Controllers\BetController::class, 'possiblepayoutallpick4']);
Route::get('/pick20/possiblepayoutallpick5', [App\Http\Controllers\BetController::class, 'possiblepayoutallpick5']);
Route::get('/pick20/possiblepayoutallpick6', [App\Http\Controllers\BetController::class, 'possiblepayoutallpick6']);
Route::get('/pick20/possiblepayoutallpick8', [App\Http\Controllers\BetController::class, 'possiblepayoutallpick8']);
Route::get('/pick20/possiblepayoutallpick14', [App\Http\Controllers\BetController::class, 'possiblepayoutallpick14']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/pick20/control', [App\Http\Controllers\EventsController::class, 'control']);
Route::get('/personalreport', [App\Http\Controllers\EventsController::class, 'personalreport']);
Route::get('/pick20/pastwinners', [App\Http\Controllers\EventsController::class, 'pastwinners']);
Route::get('/pick20/pastwinners2', [App\Http\Controllers\EventsController::class, 'pastwinners2']);
Route::get('/pick20/pastwinners3', [App\Http\Controllers\EventsController::class, 'pastwinners3']);
Route::get('/pick20/pastwinners4', [App\Http\Controllers\EventsController::class, 'pastwinners4']);
Route::get('/pick20/pastlowesttoday', [App\Http\Controllers\EventsController::class, 'pastlowesttoday']);
Route::get('/pick20/lowestwinners', [App\Http\Controllers\EventsController::class, 'lowestwinners']);
Route::get('/pick20/pick2winners', [App\Http\Controllers\EventsController::class, 'pick2winners']);
Route::get('/pick20/pick3winners', [App\Http\Controllers\EventsController::class, 'pick3winners']);
Route::get('/pick20/pick4winners', [App\Http\Controllers\EventsController::class, 'pick4winners']);
Route::get('/pick20/pick5winners', [App\Http\Controllers\EventsController::class, 'pick5winners']);
Route::get('/pick20/pick6winners', [App\Http\Controllers\EventsController::class, 'pick6winners']);
Route::get('/pick20/pick8winners', [App\Http\Controllers\EventsController::class, 'pick8winners']);
Route::get('/pick20/pick14winners', [App\Http\Controllers\EventsController::class, 'pick14winners']);
Route::get('/pick20/lowest', [App\Http\Controllers\EventsController::class, 'lowestscore']);
Route::get('/pick20/lowestleaders', [App\Http\Controllers\EventsController::class, 'lowestleaders']);
Route::post('/deductfunds', [App\Http\Controllers\Transaction::class, 'testinglangnaman']);
Route::post('/checkpassword', [App\Http\Controllers\Transaction::class, 'checkpassword']);
Route::get('/withdrawal', [App\Http\Controllers\EventsController::class, 'withdrawal'])->name('withdrawal');
Route::get('/transactioncashier', [App\Http\Controllers\Transaction::class, 'transactioncashier'])->name('transactioncashier');
Route::get('/transferfunds', [App\Http\Controllers\EventsController::class, 'transferfunds'])->middleware(['verified'])->name('transferfunds');
// Route::get('/transferfunds', [App\Http\Controllers\EventsController::class, 'transferfunds'])->name('transferfunds');
Route::get('/deposit', [App\Http\Controllers\withdrawals::class, 'deposit'])->name('deposit');
Route::get('/pendingtopplayers', [App\Http\Controllers\PendingController::class, 'pendingtopplayers']);
Route::get('/pendingtopplayersx', [App\Http\Controllers\PendingController::class, 'pendingtopplayersx']);
Route::get('/winnersfortoday', [App\Http\Controllers\PendingController::class, 'winnersfortoday']);
Route::get('/Cashier', [App\Http\Controllers\HomeController::class, 'index'])->name('cashier');
Route::get('/withdrawcashier', [App\Http\Controllers\HomeController::class, 'cashier'])->name('withdrawcashier');
Route::get('/pick20/getevents', [App\Http\Controllers\EventsController::class, 'index']);
Route::get('/pick20/getevents1', [App\Http\Controllers\EventsController::class, 'index2']);
Route::get('/pick20/geteventx', [App\Http\Controllers\EventsController::class, 'index']);
Route::get('/pick20/getbets', [App\Http\Controllers\BetController::class, 'bets']);
Route::get('/getbet/{id}', [App\Http\Controllers\BetController::class, 'historybet']);
Route::post('/getbetxx', [App\Http\Controllers\BetController::class, 'getbetxx']);
Route::post('/pick20/selection', [App\Http\Controllers\EventsController::class, 'selected']);
Route::post('/pick20/selectionpick2', [App\Http\Controllers\BetController::class, 'selectionpick2']);
Route::post('/pick20/selectionpick3', [App\Http\Controllers\BetController::class, 'selectionpick3']);
Route::post('/pick20/selectionpick4', [App\Http\Controllers\BetController::class, 'selectionpick4']);
Route::post('/pick20/selectionpick5', [App\Http\Controllers\BetController::class, 'selectionpick5']);
Route::post('/pick20/selectionpick6', [App\Http\Controllers\BetController::class, 'selectionpick6']);
Route::post('/pick20/selectionpick8', [App\Http\Controllers\BetController::class, 'selectionpick8']);
Route::post('/pick20/selectionpick14', [App\Http\Controllers\BetController::class, 'selectionpick14']);
Route::post('/pick20/switchbetw', [App\Http\Controllers\BetController::class, 'switchw']);
Route::post('/pick20/switchbetm', [App\Http\Controllers\BetController::class, 'switchm']);
Route::post('/pick20/allmeron', [App\Http\Controllers\BetController::class, 'meronall']);
Route::post('/pick20/allwala', [App\Http\Controllers\BetController::class, 'walaall']);
Route::post('/pick20/switchbet', [App\Http\Controllers\BetController::class, 'switch']);
Route::post('/pick20/deleteprebets', [App\Http\Controllers\BetController::class, 'deleteprebet']);
Route::post('/pick20/randompick', [App\Http\Controllers\BetController::class, 'random']);
Route::post('/pick20/randompick2', [App\Http\Controllers\BetController::class, 'randompick2']);
Route::post('/pick20/randompick3', [App\Http\Controllers\BetController::class, 'randompick3']);
Route::post('/pick20/randompick4', [App\Http\Controllers\BetController::class, 'randompick4']);
Route::post('/pick20/randompick5', [App\Http\Controllers\BetController::class, 'randompick5']);
Route::post('/pick20/randompick6', [App\Http\Controllers\BetController::class, 'randompick6']);
Route::post('/pick20/randompick8', [App\Http\Controllers\BetController::class, 'randompick8']);
Route::post('/pick20/randompick14', [App\Http\Controllers\BetController::class, 'randompick14']);
Route::post('/pick20/testpost', [App\Http\Controllers\BetController::class, 'tests']);
Route::post('/pick20/insertbet', [App\Http\Controllers\BetController::class, 'insertbet']);
Route::get('/pick20/claims', [App\Http\Controllers\HomeController::class, 'claims'])->name('Withdrawals');
Route::get('/transactionhistory', [App\Http\Controllers\HomeController::class, 'transactionhistory'])->name('transactionhistory');
Route::post('/pick20/getbarcode', [App\Http\Controllers\withdrawals::class, 'getbarcodex']);
Route::post('/pick20/getbarcodewin', [App\Http\Controllers\withdrawals::class, 'barcodewin']);
Route::post('/pick20/getbarcodewinprebets', [App\Http\Controllers\withdrawals::class, 'prebets']);
Route::post('/pick20/withdraw', [App\Http\Controllers\withdrawals::class, 'withdrawal']);
Route::post('/pick20/withdrawkulang', [App\Http\Controllers\withdrawals::class, 'withdrawkulang']);
Route::get('/bethistory', [App\Http\Controllers\EventsController::class, 'bethistoryx'])->name('bethistory')->middleware('auth');
Route::get('/viewpending', [App\Http\Controllers\EventsController::class, 'viewpending'])->name('viewpending')->middleware('auth');
Route::get('/viewhistorybets', [App\Http\Controllers\BetController::class, 'viewhistorybets'])->name('viewhistorybets');
Route::get('/pick20/showdetailedbets/{id}', [App\Http\Controllers\BetController::class, 'showdetailedbets']);
Route::get('/pick20/getallevents', [App\Http\Controllers\EventsController::class, 'allevents']);
Route::get('/pick20/geteventsformonitoring', [App\Http\Controllers\Transaction::class, 'geteventsformonitoring']);
Route::post('/pick20/geteventsformonitoring', [App\Http\Controllers\Transaction::class, 'geteventsformonitoring']);
Route::get('/pick20/getalleventstrans', [App\Http\Controllers\EventsController::class, 'alleventstrans']);
Route::post('/pick20/historybets', [App\Http\Controllers\BetController::class, 'allbets']);
Route::get('/pick20/pendingbets', [App\Http\Controllers\BetController::class, 'pendingbets']);
Route::get('/pick20/pendingbetsonly', [App\Http\Controllers\BetController::class, 'pendingbetsonly']);
Route::get('/transactions', [App\Http\Controllers\EventsController::class, 'transactions'])->name('transactions');
Route::post('/pick20/getallwithdrawals', [App\Http\Controllers\EventsController::class, 'getalltransactions']);
Route::get('/pick20/getalltransactionhistory', [App\Http\Controllers\EventsController::class, 'getalltransactionhistory']);
Route::post('/pick20/reprint', [App\Http\Controllers\EventsController::class, 'getreceipt']);
Route::post('/pick20/changepassword', [App\Http\Controllers\EventsController::class, 'changepassword']);
Route::post('/pick20/updateaccount', [App\Http\Controllers\EventsController::class, 'updateaccount']);
Route::get('/pick20/getuser', [App\Http\Controllers\EventsController::class, 'user']);
Route::get('/pick20/getforpendings', [App\Http\Controllers\withdrawals::class, 'getforpendings']);
Route::post('/pick20/getpending', [App\Http\Controllers\withdrawals::class, 'getpendings']);
Route::post('/pick20/cancelpending', [App\Http\Controllers\withdrawals::class, 'cancelpending']);
Route::post('/pick20/withdrawaluser', [App\Http\Controllers\withdrawals::class, 'withdrawaluser']);
Route::get('/pick20/getplayers', [App\Http\Controllers\withdrawals::class, 'getplayers']);
Route::post('/pick20/getplayerpending', [App\Http\Controllers\withdrawals::class, 'getplayerpending']);
Route::post('/pick20/confirmwithdrawuser', [App\Http\Controllers\withdrawals::class, 'confirmwithdrawuser']);
Route::post('/pick20/rejectwithdrawal', [App\Http\Controllers\withdrawals::class, 'rejectwithdrawal']);
Route::get('/pick20/totalwithdraw', [App\Http\Controllers\withdrawals::class, 'totalwithdraw']);
Route::get('/pick20/gettotaldeposit', [App\Http\Controllers\withdrawals::class, 'gettotaldeposit']);
Route::get('/pick20/gettotalunclaimed', [App\Http\Controllers\withdrawals::class, 'gettotalunclaimed']);
Route::post('/pick20/depositconfirmed', [App\Http\Controllers\withdrawals::class, 'depositconfirmed']);
Route::get('/logs', [App\Http\Controllers\HomeController::class, 'logs'])->name('logs');
Route::get('/pick20/alllogs', [App\Http\Controllers\LogsController::class, 'logs']);
Route::get('/pick20/getcashiertrans', [App\Http\Controllers\Transaction::class, 'getcashiertrans']);
Route::post('/pick20/newgetcashiertrans', [App\Http\Controllers\Transaction::class, 'newgetcashiertrans']);
Route::get('/pick20/geteventoftransactions', [App\Http\Controllers\Transaction::class, 'geteventoftransactions']);
Route::post('/pick20/cashin', [App\Http\Controllers\Transaction::class, 'cashin']);
Route::get('/pick20/cashout', [App\Http\Controllers\Transaction::class, 'cashout']);
Route::get('/pick20/getmycash', [App\Http\Controllers\Transaction::class, 'getmycash']);
Route::post('/pick20/getusersfrommonitoring', [App\Http\Controllers\Transaction::class, 'getusersfrommonitoring']);
Route::post('/pick20/getliveodds', [App\Http\Controllers\PotmoneyController::class, 'getliveodds']);
Route::post('/pick20/getliveoddsadmin', [App\Http\Controllers\PotmoneyController::class, 'getliveoddsadmin']);
Route::post('/transferrollback', [App\Http\Controllers\Transaction::class, 'transferrollback']);
Route::post('/pick20/changeemail', [App\Http\Controllers\EventsController::class, 'changeemail']);
Route::post('/pick20/insertmultiplerandompicks', [App\Http\Controllers\BetController::class, 'insertmultiplerandompicks']);
Route::get('/pick20/getpendingtransactions', [App\Http\Controllers\withdrawals::class, 'getpendingtransactions']);
Route::post('/pick20/derbynames', [App\Http\Controllers\BetController::class, 'derbynames']);
Route::post('/pick20/checkpin', [App\Http\Controllers\withdrawals::class, 'checkpin']);
Route::post('/pick20/getwinningbets', [App\Http\Controllers\startingfightcontroller::class, 'getwinningbets']);
Route::get('/pick20/cashouttemporary', [App\Http\Controllers\Transaction::class, 'cashouttemporary']);
Route::post('/pick20/restoresession', [App\Http\Controllers\Transaction::class, 'restoresession']);
Route::get('/pick20/getmygroup', [App\Http\Controllers\EventsController::class, 'getmygroup']);
Route::post('/pick20/transmodaldownloadall', [App\Http\Controllers\Transaction::class, 'transmodaldownloadall']);
Route::post('/pick20/gettransactionscashierwithdraw', [App\Http\Controllers\withdrawals::class, 'gettransactionscashierwithdraw']);

// admin and delcarators
Route::post('/pick20/partner', [App\Http\Controllers\EventsController::class, 'partner']);
Route::post('/pick20/addgames', [App\Http\Controllers\EventsController::class, 'addgames']);
Route::post('/pick20/addgames2', [App\Http\Controllers\EventsController::class, 'addgames2']);
Route::post('/pick20/derbyprogram', [App\Http\Controllers\EventsController::class, 'derbyprogram']);
Route::get('/pick20/downloadusermonitoring/{id1}/{id2}', [App\Http\Controllers\Transaction::class, 'downloadmonitoring']);
Route::get('/pick20/downloadusertransaction/{id1}/{id2}', [App\Http\Controllers\Transaction::class, 'downloadusertransaction']);
Route::get('/Reports', [App\Http\Controllers\HomeController::class, 'Reports'])->name('Reports');
Route::get('/Admin', [App\Http\Controllers\HomeController::class, 'admin'])->name('Dashboard');
Route::get('/users', [App\Http\Controllers\HomeController::class, 'usermanagement'])->name('usermanagement');
Route::get('/arena', [App\Http\Controllers\HomeController::class, 'arena'])->name('arena');
Route::get('/groups', [App\Http\Controllers\HomeController::class, 'groups'])->name('groups');
Route::post('/pick20/insertstartingfight', [App\Http\Controllers\startingfightcontroller::class, 'insertstartingfight']);
Route::post('/pick20/openallfights', [App\Http\Controllers\startingfightcontroller::class, 'openallfights']);
Route::post('/pick20/removestarting', [App\Http\Controllers\startingfightcontroller::class, 'removestarting']);
Route::post('/pick20/openstartingfight', [App\Http\Controllers\startingfightcontroller::class, 'openstartingfight']);
Route::post('/pick20/openstartingfightconfirm', [App\Http\Controllers\startingfightcontroller::class, 'openstartingfightconfirm']);
Route::post('/pick20/closestartingfight', [App\Http\Controllers\startingfightcontroller::class, 'closestartingfight']);
Route::post('/pick20/lastcallstartingfight', [App\Http\Controllers\startingfightcontroller::class, 'lastcallstartingfight']);
Route::post('/pick20/removelastcallstarting', [App\Http\Controllers\startingfightcontroller::class, 'removelastcallstarting']);
Route::post('/pick20/startingfights', [App\Http\Controllers\startingfightcontroller::class, 'startingfights']);
Route::get('/pick20/startingfightshome', [App\Http\Controllers\startingfightcontroller::class, 'startingfightshome']);
Route::post('/pick20/updateevent', [App\Http\Controllers\EventsController::class, 'updateevent']);
Route::post('/pick20/closebetting', [App\Http\Controllers\EventsController::class, 'closefight']);
Route::post('/pick20/lastcall', [App\Http\Controllers\EventsController::class, 'lastcall']);
Route::post('/pick20/insertresults', [App\Http\Controllers\ResultsController::class, 'insertresults']);
Route::get('/pick20/getresults', [App\Http\Controllers\ResultsController::class, 'index']);
Route::post('/pick20/getresults2', [App\Http\Controllers\ResultsController::class, 'results']);
Route::get('/pick20/getresultstotal', [App\Http\Controllers\ResultsController::class, 'getresultstotal']);
Route::post('/pick20/confirmed', [App\Http\Controllers\ResultsController::class, 'confirmed']);
Route::get('/pick20/samplelang', [App\Http\Controllers\ResultsController::class, 'sample']);
Route::get('/pick20/searchusers', [App\Http\Controllers\transaction::class, 'searchusers']);
Route::post('/pick20/searchusertransaction', [App\Http\Controllers\transaction::class, 'searchusertransaction']);
Route::post('/pick20/regrade', [App\Http\Controllers\ResultsController::class, 'regrade']);
Route::post('/pick20/confirmgrade', [App\Http\Controllers\ResultsController::class, 'confirmgrade']);
Route::get('/pick20/getwinnings', [App\Http\Controllers\PotmoneyController::class, 'getpotmoney']);
Route::get('/pick20/getpastevent', [App\Http\Controllers\EventsController::class, 'getpast']);
Route::get('/pick20/allusers', [App\Http\Controllers\EventsController::class, 'alluserx']);
Route::post('/pick20/allusers', [App\Http\Controllers\EventsController::class, 'alluserx']);
Route::get('/pick20/allusers2', [App\Http\Controllers\EventsController::class, 'allusers2']);
Route::get('/pick20/allusersdeposit', [App\Http\Controllers\EventsController::class, 'allusersdeposit']);
Route::post('/pick20/openclaim', [App\Http\Controllers\PotmoneyController::class, 'open']);
Route::post('/pick20/openclaimpick2', [App\Http\Controllers\PotmoneyController::class, 'openclaimpick2']);
Route::post('/pick20/closeclaim', [App\Http\Controllers\PotmoneyController::class, 'close']);
Route::post('/pick20/closeclaimpick2', [App\Http\Controllers\PotmoneyController::class, 'closeclaimpick2']);
Route::post('/pick20/confirmclaimclose', [App\Http\Controllers\PotmoneyController::class, 'confirmclaim']);
Route::post('/pick20/confirmclaimopen', [App\Http\Controllers\PotmoneyController::class, 'confirmclaim2']);
Route::post('/pick20/confirmclaimopenpick2', [App\Http\Controllers\PotmoneyController::class, 'confirmclaim2']);
Route::post('/pick20/addevent', [App\Http\Controllers\EventsController::class, 'addeventx']);
Route::post('/pick20/editevent', [App\Http\Controllers\EventsController::class, 'editevent']);
Route::post('/pick20/adduser', [App\Http\Controllers\EventsController::class, 'adduser']);
Route::post('/pick20/edituser', [App\Http\Controllers\EventsController::class, 'edituser']);
Route::post('/pick20/updatestatus', [App\Http\Controllers\EventsController::class, 'updatestatus']);
Route::post('/pick20/pager', [App\Http\Controllers\EventsController::class, 'pager']);
Route::get('/pick20/back', [App\Http\Controllers\EventsController::class, 'back']);
Route::post('/pick20/confirmedreopen', [App\Http\Controllers\EventsController::class, 'reopen']);
Route::post('/pick20/addarena', [App\Http\Controllers\ArenaController::class, 'addarena']);
Route::get('/pick20/getallarena', [App\Http\Controllers\ArenaController::class, 'getallarena']);
Route::get('/pick20/getallarenax', [App\Http\Controllers\ArenaController::class, 'getallarenax']);
Route::post('/pick20/getallarena', [App\Http\Controllers\ArenaController::class, 'getallarena']);
Route::post('/pick20/updatearena', [App\Http\Controllers\ArenaController::class, 'updatearena']);
Route::post('/pick20/addgroup', [App\Http\Controllers\GroupController::class, 'addgroup']);
Route::get('/pick20/getallgroups', [App\Http\Controllers\GroupController::class, 'getallgroups']);
Route::get('/pick20/getallgroupname', [App\Http\Controllers\GroupController::class, 'getallgroupname']);
Route::post('/pick20/getallgroups', [App\Http\Controllers\GroupController::class, 'getallgroups']);
Route::post('/pick20/updategroup', [App\Http\Controllers\GroupController::class, 'updategroup']);
Route::post('/pick20/getgroupusers', [App\Http\Controllers\GroupController::class, 'getgroupusers']);
Route::post('/pick20/getgroupusersall', [App\Http\Controllers\GroupController::class, 'getgroupusersall']);
Route::get('/pick20/getallrake', [App\Http\Controllers\transaction::class, 'getallrake']);
Route::get('/pick20/getrakedata', [App\Http\Controllers\transaction::class, 'getrakedata']);
Route::get('/pick20/totalbets', [App\Http\Controllers\transaction::class, 'totalbets']);
Route::get('/pick20/totalbetsdata', [App\Http\Controllers\transaction::class, 'totalbetsdata']);
Route::get('/pick20/perhourbets', [App\Http\Controllers\transaction::class, 'perhourbets']);
Route::get('/pick20/perhourdata', [App\Http\Controllers\transaction::class, 'perhourdata']);
Route::get('/pick20/totalpendingbetsreport', [App\Http\Controllers\transaction::class, 'totalpendingbetsreport']);
Route::get('/pick20/totalcurrentrake', [App\Http\Controllers\transaction::class, 'totalcurrentrake']);
Route::get('/pick20/totalcurrentpendingbets', [App\Http\Controllers\transaction::class, 'totalcurrentpendingbets']);
Route::get('/pick20/getdailysales', [App\Http\Controllers\transaction::class, 'getdailysales']);
Route::get('/pick20/getdailysalesdata', [App\Http\Controllers\transaction::class, 'getdailysalesdata']);
Route::get('/pick20/thismonthsale', [App\Http\Controllers\transaction::class, 'thismonthsale']);
Route::get('/pick20/geteventsreports', [App\Http\Controllers\transaction::class, 'geteventsreports']);
Route::post('/pick20/searchgeteventsreports', [App\Http\Controllers\transaction::class, 'searchgeteventsreports']);
Route::get('/pick20/getalleventsreports', [App\Http\Controllers\transaction::class, 'getalleventsreports']);
Route::post('/pick20/getalleventsreports', [App\Http\Controllers\transaction::class, 'getalleventsreports']);
Route::post('/pick20/eventgetusersreport', [App\Http\Controllers\transaction::class, 'eventgetusersreport']);
Route::post('/pick20/eventgetusersreportexcel', [App\Http\Controllers\transaction::class, 'eventgetusersreportexcel']);
Route::post('/pick20/betdetailereport', [App\Http\Controllers\transaction::class, 'betdetailereport']);
Route::post('/pick20/arenareportsmodal', [App\Http\Controllers\transaction::class, 'arenareportsmodal']);
Route::post('/pick20/arenareportsmodal2', [App\Http\Controllers\transaction::class, 'arenareportsmodal2']);
Route::post('/pick20/arenareportsmodal3', [App\Http\Controllers\transaction::class, 'arenareportsmodal3']);
Route::post('/pick20/arenareportsmodalpick3', [App\Http\Controllers\transaction::class, 'arenareportsmodalpick3']);
Route::post('/pick20/arenareportsmodalpick4', [App\Http\Controllers\transaction::class, 'arenareportsmodalpick4']);
Route::post('/pick20/arenareportsmodalpick5', [App\Http\Controllers\transaction::class, 'arenareportsmodalpick5']);
Route::post('/pick20/arenareportsmodalpick6', [App\Http\Controllers\transaction::class, 'arenareportsmodalpick6']);
Route::post('/pick20/arenareportsmodalpick8', [App\Http\Controllers\transaction::class, 'arenareportsmodalpick8']);
Route::post('/pick20/arenareportsmodalpick14', [App\Http\Controllers\transaction::class, 'arenareportsmodalpick14']);
Route::post('/pick20/betsofarenareports', [App\Http\Controllers\transaction::class, 'betsofarenareports']);
Route::get('/pick20/geteventswithtransactions', [App\Http\Controllers\transaction::class, 'geteventswithtransactions']);
Route::post('/pick20/geteventswithtransactions', [App\Http\Controllers\transaction::class, 'geteventswithtransactions']);
Route::post('/pick20/transmodal', [App\Http\Controllers\transaction::class, 'transmodal']);
Route::post('/pick20/transtotal', [App\Http\Controllers\transaction::class, 'transtotal']);
Route::post('/pick20/totalarenareports', [App\Http\Controllers\transaction::class, 'totalarenareports']);
Route::post('/pick20/arenareportsmodaltotal', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotal']);
Route::post('/pick20/arenareportsmodaltotal2', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotal2']);
Route::post('/pick20/arenareportsmodaltotal3', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotal3']);
Route::post('/pick20/arenareportsmodaltotalpick3', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotalpick3']);
Route::post('/pick20/arenareportsmodaltotalpick4', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotalpick4']);
Route::post('/pick20/arenareportsmodaltotalpick5', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotalpick5']);
Route::post('/pick20/arenareportsmodaltotalpick6', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotalpick6']);
Route::post('/pick20/arenareportsmodaltotalpick8', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotalpick8']);
Route::post('/pick20/arenareportsmodaltotalpick14', [App\Http\Controllers\transaction::class, 'arenareportsmodaltotalpick14']);
Route::get('/pick20/spotcheck', [App\Http\Controllers\transaction::class, 'spotcheck']);
Route::get('/pick20/topplayerss', [App\Http\Controllers\transaction::class, 'topplayerss']);
Route::get('/pick20/toptellercashier', [App\Http\Controllers\transaction::class, 'toptellercashier']);
Route::get('/pick20/toploaders', [App\Http\Controllers\transaction::class, 'toploaders']);
Route::get('/pick20/topwithdrawals', [App\Http\Controllers\transaction::class, 'topwithdrawals']);
Route::get('/pick20/getallpastbets', [App\Http\Controllers\EventsController::class, 'getallpastbets']);
Route::post('/pick20/forcecashout', [App\Http\Controllers\transaction::class, 'forcecashout']);
Route::post('/pick20/gettransactions', [App\Http\Controllers\transaction::class, 'gettransactions']);
Route::post('/pick20/getuserdatailed', [App\Http\Controllers\transaction::class, 'getuserdatailed']);
Route::post('/pick20/geteventsdatailed', [App\Http\Controllers\transaction::class, 'geteventsdatailed']);
Route::post('/pick20/geteventsdatailedpage', [App\Http\Controllers\transaction::class, 'geteventsdatailedpage']);
Route::post('/pick20/checkeventdetails', [App\Http\Controllers\transaction::class, 'checkeventdetails']);
Route::post('/pick20/checkeventdetails2', [App\Http\Controllers\transaction::class, 'checkeventdetails2']);
Route::post('/pick20/search', [App\Http\Controllers\transaction::class, 'search']);
Route::post('/pick20/mismatchresults', [App\Http\Controllers\ResultsController::class, 'mismatchresults']);
Route::post('/pick20/searchuser', [App\Http\Controllers\LogsController::class, 'searchuser']);
Route::get('/pick20/getdeclarators', [App\Http\Controllers\EventsController::class, 'getdeclarators']);
Route::post('/pick20/doit', [App\Http\Controllers\EventsController::class, 'doit']);
Route::post('/pick20/announcement', [App\Http\Controllers\EventsController::class, 'announcement']);
Route::post('/pick20/removeaanouncement', [App\Http\Controllers\EventsController::class, 'removeaanouncement']);
Route::post('/pick20/getwinnerscount', [App\Http\Controllers\EventsController::class, 'getwinnerscount']);
Route::post('/pick20/addpin', [App\Http\Controllers\EventsController::class, 'addpin']);
Route::post('/pick20/downloadall', [App\Http\Controllers\transaction::class, 'downloadall']);
Route::get('/pick20/getmysummary', [App\Http\Controllers\transaction::class, 'checkeventdetailssummary']);
Route::get('/pick20/getmysummary2', [App\Http\Controllers\transaction::class, 'checkeventdetails2summary']);
Route::get('/pick20/totalsummary', [App\Http\Controllers\transaction::class, 'totalsummary']);
Route::get('/pick20/getbetonly', [App\Http\Controllers\transaction::class, 'getbetonly']);

Route::post('/pick20/eventgetgroupreport', [App\Http\Controllers\transaction::class, 'eventgetgroupreport']);
Route::post('/pick20/eventgetusersgroupreport', [App\Http\Controllers\transaction::class, 'eventgetusersgroupreport']);


// Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');
//
// Route::group(['middleware' => 'auth'], function () {
// 	Route::get('table-list', function () {
// 		return view('pages.table_list');
// 	})->name('table');
//
// 	Route::get('typography', function () {
// 		return view('pages.typography');
// 	})->name('typography');
//
// 	Route::get('icons', function () {
// 		return view('pages.icons');
// 	})->name('icons');
//
// 	Route::get('map', function () {
// 		return view('pages.map');
// 	})->name('map');
//
// 	Route::get('notifications', function () {
// 		return view('pages.notifications');
// 	})->name('notifications');
//
// 	Route::get('rtl-support', function () {
// 		return view('pages.language');
// 	})->name('language');
//
// 	Route::get('upgrade', function () {
// 		return view('pages.upgrade');
// 	})->name('upgrade');
// });

// Route::group(['middleware' => 'auth'], function () {
// 	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
// 	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
// 	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
// 	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
// });
