@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
		<modalcash :userx='{{ Auth::user() }}'></modalcash>
          <div class="card">
            <div class="card-header bg-dark text-warning font-weight-bold">
              Rules for prizing Pick 20
            </div>
            <div class="card-body">
              •	There are top 4 score winners if there are more than 1 winner the prize will be divided among the winners equally.<br><br>
              •	Prizes for the highest scores are:<br><br>
              <b>1.</b>	Jackpot Prize 8 million fixed [(20/20)score/point] (40 percent not included).<br>
              <b>2.</b>	Highest Score is 40 percent of net fees deducted by the bottom 4 score winners[top 1 highest score].<br>
              <b>3.</b>	2nd to highest score is 30 percent of net fees deducted by the bottom 4 score winners [top 2 highest score].<br>
              <b>4.</b>	3rd to the highest is 20 percent of net fees deducted by the bottom 4 score winners [top 3 highest score].<br>
              <b>5.</b>	4th to the highest is 10 percent of net fees deducted by the bottom 4 score winners [top 4 highest score].<br><br>
              •	There are top 4 Bottom score winners. Take note the winners of the bottom 4 score winners are 0, 1, 2, 3 Score/Point only.<br><br>
              •	Prizes for the bottom 4 are:<br><br>
              <b>1.</b>	0 Score/Points = 10,000 each.<br>
              <b>2.</b>	1 Score/Points = 3,000 each.<br>
              <b>3.</b>	2 Score/Points = 2,000 each.<br>
              <b>4.</b>	3 Score/Points = 500 each.<br><br>
              •	You can bet as much as you like while its open.<br>
              •	Prizes for bottom 4 will be deducted from total net fees.<br>
              •	If the player wins the jackpot, the player will only get the fixed jackpot.<br>
               and there will still be prizes for top 2, top 3, top 4 and bottom 4.<br>
              •	If theres any cancelled fight it will replaced by another fight (max 3 additional fights) if there are 4 and above cancelled fights the prizes will be the same but there will be no jackpot.<br>
              •	10 rakes for office and 10 percent contingency funds.<br>
              •	you can only pick 2 draws on any other bet. Reason is because we have prizes for lowest scores.<br>
              •	20 percent total rake, and remaining 80 percent are for prizes.<br>
              •	If in case the payout are low, there will be a minimum payout for top 1 - 4.
              <br>
              • minimum payout for top 1 is 500 each.<br>
              • minimum payout for top 2 is 400 each.<br>
              • minimum payout for top 3 is 300 each.<br>
              • minimum payout for top 4 is 200 each.<br>
              • If our Highest Score is only 4, 3, 2 and 1 point, they automatically belong to the Highest Score and not to the Bottom 4. Highest score will be prioritize in the ruling of pick 20.<br>
            </div>
            <div class="card-header bg-dark text-warning font-weight-bold">
              Rules of how to play pick 20
            </div>
            <div class="card-body">
              <ol>
                <li>In addition to these terms and conditions, certain rules of play shall apply to you during your engagement in this website.</li>
                <li>you are hereby agree to bound  by the aforementioned Rules of play as if they were specifically incorporated into these terms and conditions.</li>
                <li>Pick 20 bets are subject to a "plasada of 20 percent"</li>
                <li>Betting can only be done through game/betting console. done through any other means besides the game/betting console is VOID.</li>
                <li>We reserve the right to exclude players from participating in the betting without prior notice and/or providing reasons for the exclusion.</li>
                <li>If theres any cancelled fight it will replaced by another fight (max 3 additional fights).</li>
                <li>The DECLARED WINNERS will be announced from the leaderboards.</li>
                <li>In the event of Re declare wrong declaration of wins/winner will be corrected by this process.</li>
                <li>The system computes the winning AUTOMATICALLY after all the fight number has been graded and the RESULTS has been DECLARED.</li>
                <li>Total Bets will also display to Betting Console.</li>
                <li>Current Points that the player sees have an equivalent of 1pt to 1peso.</li>
                <li>After placing Bets(Pick 20), and after the betting is closed, the Potmoney will be displayed base on the odds/payout.</li>
                <li>The betting period is on or before your startingfight begin.</li>
                <li>How to compute Payout for Pick 20. <br>
                  e.g. Value is all point
                  <ul>
                    <li>Total Bets = 100,000</li>
                    <li>Plasada = 20% rake deducted from the total bets <br>
                    (e.g. computation on how to get 20% rake/plasada)<br>
                     <table class="table table-sm table-borderless">
                       <thead>
                         <tr>
                           <th></th>
                           <th>Bets</th>
                           <th>Rake</th>
                         </tr>
                         <tr>
                           <th>Total Amount bets</th>
                           <td>100,000 * 0.20</td>
                           <td>20,000</td>
                         </tr>
                       </thead>
                     </table> </li>
                     <li>Overall total minus 20% rake or Plasada = Sum of total bet less 20% rake.<br></li>
                     <li><b>Total Odds</b> = Overall total minus rake<br>(e.g. (100,000.00 – 20,000.00 = 80,000))</li>
                     <li><b>Payout for lowest score 0 </b>= Overall total minus rake minus 10,000 if more than one winner for the 0 score the 10,000 will be multiplied by the number of winners for 0 score <br> (e.g. (100,000.00 – 20,000.00 = 80,000 - 10,000)) 10,000 each</li>
                     <li><b>Payout for lowest score 1 </b>= Overall total minus rake minus 3,000 if more than one winner for the 1 score the 3,000 will be multiplied by the number of winners for 1 score <br> (e.g. (100,000.00 – 20,000.00 = 80,000 - 3,000)) 3,000 each</li>
                     <li><b>Payout for lowest score 2 </b>= Overall total minus rake minus 2,000 if more than one winner for the 2 score the 2,000 will be multiplied by the number of winners for 2 score <br> (e.g. (100,000.00 – 20,000.00 = 80,000 - 2,000)) 2,000 each</li>
                     <li><b>Payout for lowest score 3 </b>= Overall total minus rake minus 500 if more than one winner for the 3 score the 500 will be multiplied by the number of winners for 3 score <br> (e.g. (100,000.00 – 20,000.00 = 80,000 - 500)) 500 each</li>
                     <li><b>Payout for top 1</b> = Overall total minus rake multiply by 40 percent and if more than 1 winner it will be divided equally <br> (e.g. (100,000.00 – 20,000.00 = 80,000 * 0.40 = 32,000/divided by number of top 1 players))</li>
                     <li><b>Payout for top 2</b> = Overall total minus rake multiply by 30 percent and if more than 1 winner it will be divided equally <br> (e.g. (100,000.00 – 20,000.00 = 80,000 * 0.30 = 24,000/divided by number of top 2 players))</li>
                     <li><b>Payout for top 3</b> = Overall total minus rake multiply by 20 percent and if more than 1 winner it will be divided equally <br> (e.g. (100,000.00 – 20,000.00 = 80,000 * 0.20 = 16,000/divided by number of top 3 players))</li>
                     <li><b>Payout for top 4</b> = Overall total minus rake multiply by 10 percent and if more than 1 winner it will be divided equally <br> (e.g. (100,000.00 – 20,000.00 = 80,000 * 0.10 = 10,000/divided by number of top 4 players))</li>
                  </ul>
                 </li>
                 <li>Expected / Potential Winnings will not include the decimal numbers</li>
                 <li>No cancellation of bets after bet is posted. (Note there is always a confirmation every time player posts their bets.)</li>
                 <li>Displaying of total odds will be on the player side and teller side.</li>
                 <li>Minimum points for player to be able to bet is 100, otherwise the player cannot click play.</li>
                 <li>Minimum and Maximum bet for pick 20 (plus 3 additional fight is for cancel fights) <br>
                   <ul>
                     <li>The Minimum bet limit per player 100 points per place of bet.</li>
                     <li>The Maximum bet limit per player has no limit.</li>
                   </ul>
                   <li>Details that player see on Our Game Dash Board/Player Console. <br>
                     <ul>
                       <li>On the upper part of the home screen you will see your cash and and the jackpot (this is example only).<br> <center><img src="/img/rule1.png" alt=""></center> </li>
                       <li>On the middle part of the home screen you will see all available starting fight that you can play (this is example only).<br> <center><img src="/img/rule2.png" alt=""></center> </li>
                       <li>On the bottom part of the home screen you will see all your current pending bets (this is example only).<br> <center><img src="/img/rule3.png" alt=""></center> </li>
                       <li>After you click "Play" on the specific starting fight you will see at the top part the "go back to starting fights" if you click this it will return to the start page.<br> <center><img src="/img/rule4.png" alt=""></center> </li>
                       <li>After you click "Play" on the specific starting fight you will see at the lower of top area; the Event name of the starting fight of that you clicked in the beginning and also you can see your cash and jackpot (jackpot is example only) and the total Payout.<br> <center><img src="/img/rule5.png" alt=""></center> </li>
                       <li>After you click "Play" on the specific starting fight you will see at the mid part area; here you will see the starting fight number, bet amount and you can click the random picks and "switch to multiple bet".<br> <center><img src="/img/rule6.png" alt=""></center> </li>
                       <li>After you click "Play" on the specific starting fight you will see at the middle part area; here you can manually pick if you didnt click the random pick checkbox(this only applies to single pick).<br> <center><img src="/img/rule7.png" alt=""></center> </li>
                       <li>After you click "Play" on the specific starting fight you will see at the bottom part area; here you can click place bet whatever you changes one the above picture, or multiple selection button.<br> <center><img src="/img/rule8.png" alt=""></center> </li>
                       <li>After you click "Play" on the specific starting fight you will see at the bottom part area; here you can click place multiple bet means if you click this another form will appear and there you can choose how many random bets you want.<br> <center><img src="/img/rule9.png" alt=""></center> </li>
                       <li>After you click "Play" on the specific starting fight you will see at the bottom part area; after you click <b>place multiple random picks here you can choose how many random pick you want and click confirm.</b>.<br> <center><img src="/img/rule10.png" alt=""></center> </li>
                       <li>if you click the menu on the top part you can see the all menu option.<br> <center><img src="/img/rule11.png" alt=""></center><ul>
                         <li><b>leaderboards / Results</b> to see who wins today, and you can see the results.</li>
                         <li><b>Pending Bets</b> here you can see all your pending bets that has not been graded yet.</li>
                         <li><b>History Bets</b> here you can see all your history bets that already been graded.</li>
                         <li><b>Transactions</b> here you can see all your transactions like for example withdrawals/deposit.</li>
                         <li><b>Withdrawals</b> here you can withdraw.</li>
                       </ul> </li>
                     </ul>
                    </li>
              </ol>
            </div>
            <div class="card-header bg-dark text-warning font-weight-bold">
              Rules for Pick 2
            </div>
            <div class="card-body">
              <ol>
                <li>in Order to win pick 2 you need atleast 2 wins, if there are no cancelled fights within the starting fight of pick 2.</li>
                <li>If there`s 1 cancelled you need atleast 1 win in order to win.</li>
                <li>If there`s 2 cancelled it will return all bet amount to the players.</li>
                <li>You can manually change the amount of bet if you are playing pick 2. <br>the amounts are [100,200,300,400,500]. </li>
              </ol>
            </div>
            <!-- <div class="card-header bg-dark text-warning font-weight-bold">
              Rules for Prizing Pick 2
            </div>
            <div class="card-body">
              <ol>
                <li>Total Bets = 100,000</li>
                <li>Plasada = 5% rake deducted from the total bets <br>
                (e.g. computation on how to get 5% rake/plasada)<br>
                 <table class="table table-sm table-borderless">
                   <thead>
                     <tr>
                       <th></th>
                       <th>Bets</th>
                       <th>Rake</th>
                     </tr>
                     <tr>
                       <th>Total Amount bets</th>
                       <td>100,000 * 0.05</td>
                       <td>5,000</td>
                     </tr>
                   </thead>
                 </table> </li>
                 <li>Example Payout
                   <ul>
                     <li>Total Net Fees : 95,000</li>
                     <li>Total Amount of winning bets : 300</li>
                     <li>Winning Bets amount are 100 and 200</li>
                     <li>95,000/300 = 316.66 * 100 = 31,666.66</li>
					 <li>remove decimal of 31,666.66 to 31,666</li>
                     <li>payout for 100 is 31,666 * 1 = 31,666.</li>
                     <li>payout for 200 is 31,666 * 2 = 63,332.</li>
                   </ul>
                 </li>
                 <li>Expected / Potential Winnings will not include the decimal numbers</li>
                 <li>No cancellation of bets after bet is posted. (Note there is always a confirmation every time player posts their bets.) <br> Except when the payout is 129 and below, it will automatically Cancelled after graded</li>
                 <li>Displaying of total odds will be on the player side and teller side.</li>
                 <li>There will be no jackpot in pick 2.</li>
              </ol>
            </div> -->
            <div class="card-header bg-dark text-warning font-weight-bold">
              Rules for Pick 3
            </div>
            <div class="card-body">
              <ol>
                <li>in Order to win pick 3 you need atleast 3 wins, if there are no cancelled fights within the starting fight of pick 3.</li>
                <li>If there`s 1 cancelled you need atleast 2 win in order to win.</li>
                <li>If there`s 2 cancelled you need atleast 1 win in order to win.</li>
                <li>If there`s 3 cancelled it will return all bet amount to the players.</li>
                <li>You can manually change the amount of bet if you are playing pick 3. <br>the amounts are [100,200,300,400,500]. </li>
              </ol>
            </div>
            <div class="card-header bg-dark text-warning font-weight-bold">
              Rules for Pick 4
            </div>
            <div class="card-body">
              <ol>
                <li>in Order to win pick 4 you need atleast 4 wins, if there are no cancelled fights within the starting fight of pick 4.</li>
                <li>If there`s 1 cancelled you need atleast 3 win in order to win.</li>
                <li>If there`s 2 cancelled you need atleast 2 win in order to win.</li>
                <li>If there`s 3 cancelled you need atleast 1 win in order to win.</li>
                <li>If there`s 4 cancelled it will return all bet amount to the players.</li>
                <li>You can manually change the amount of bet if you are playing pick 4. <br>the amounts are [100,200,300,400,500]. </li>
              </ol>
            </div>
            <div class="card-header bg-dark text-warning font-weight-bold">
              Rules for Pick 5
            </div>
            <div class="card-body">
              <ol>
                <li>in Order to win pick 5 you need atleast 5 wins, if there are no cancelled fights within the starting fight of pick 5.</li>
                <li>If there`s 1 cancelled you need atleast 4 win in order to win.</li>
                <li>If there`s 2 cancelled you need atleast 3 win in order to win.</li>
                <li>If there`s 3 cancelled you need atleast 2 win in order to win.</li>
                <li>If there`s 4 cancelled you need atleast 2 win in order to win.</li>
                <li>If there`s 5 cancelled it will return all bet amount to the players.</li>
                <li>You can manually change the amount of bet if you are playing pick 5. <br>the amounts are [100,200,300,400,500]. </li>
              </ol>
            </div>
            <div class="card-header bg-dark text-warning font-weight-bold">
              Rules for Pick 6
            </div>
            <div class="card-body">
              <ol>
                <li>in Order to win pick 6 you need atleast 6 wins, if there are no cancelled fights within the starting fight of pick 6.</li>
                <li>If there`s 1 cancelled you need atleast 5 win in order to win.</li>
                <li>If there`s 2 cancelled you need atleast 4 win in order to win.</li>
                <li>If there`s 3 cancelled you need atleast 3 win in order to win.</li>
                <li>If there`s 4 cancelled you need atleast 2 win in order to win.</li>
                <li>If there`s 5 cancelled you need atleast 1 win in order to win.</li>
                <li>If there`s 6 cancelled it will return all bet amount to the players.</li>
                <li>You can manually change the amount of bet if you are playing pick 6. <br>the amounts are [100,200,300,400,500]. </li>
              </ol>
            </div>
            <div class="card-header bg-dark text-warning font-weight-bold">
              Rules for Prizing Pick 2, Pick 3, Pick 4, Pick 5 and Pick 6
            </div>
            <div class="card-body">
              <ol>
                <li>Total Bets = 100,000</li>
                <li>Plasada = 5% rake deducted from the total bets <br>
                (e.g. computation on how to get 5% rake/plasada)<br>
                 <table class="table table-sm table-borderless">
                   <thead>
                     <tr>
                       <th></th>
                       <th>Bets</th>
                       <th>Rake</th>
                     </tr>
                     <tr>
                       <th>Total Amount bets</th>
                       <td>100,000 * 0.05</td>
                       <td>5,000</td>
                     </tr>
                   </thead>
                 </table> </li>
                 <li>Example Payout
                   <ul>
                     <li>Total Net Fees : 95,000</li>
                     <li>Total Amount of winning bets : 300</li>
                     <li>Winning Bets amount are 100 and 200</li>
                     <li>95,000/300 = 316.66 * 100 = 31,666.66</li>
					 <li>remove decimal of 31,666.66 to 31,666</li>
                     <li>payout for 100 is 31,666 * 1 = 31,666.</li>
                     <li>payout for 200 is 31,666 * 2 = 63,332.</li>
                   </ul>
                 </li>
                 <li>Expected / Potential Winnings will not include the decimal numbers</li>
                 <li>No cancellation of bets after bet is posted. (Note there is always a confirmation every time player posts their bets.) <br> Except when the payout is 129 and below, it will automatically Cancelled after graded</li>
                 <li>Displaying of total odds will be on the player side and teller side.</li>
                 <li>There will be no jackpot in pick 2, pick 3, Pick 4, pick 5 and Pick 6.</li>
              </ol>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
