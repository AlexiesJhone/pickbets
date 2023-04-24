@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-3">
		<modalcash :userx='{{ Auth::user() }}'></modalcash>
          <div class="card">
            <div class="card-header bg-dark text-warning font-weight-bold">
              Prizes for pick 20
            </div>
              <table class="table">
                <thead>
                  <tr>
                    <th colspan="2" class="text-center bg-dark text-warning">Highest Scores</th>
                  </tr>
                </thead>
                  <tbody>
                    <tr>
                      <th class="text-center">Perfect score of 20</th>
                      <td class="text-center">8 Million <br><i><b>Take Note	</b>if there`s a perfect score you will only get the jackpot prize otherwise if there are more than 1 winner the prize will be divided among the winners equally, Top 1 Highest Score is not included. </i></td>
                    </tr>
                    <tr>
                      <th class="text-center">Top 1</th>
                      <td class="text-center">40 percent of total net fees of pick 20.</td>
                    </tr>
                    <tr>
                      <th class="text-center">Top 2</th>
                      <td class="text-center">30 percent of total net fees of pick 20.</td>
                    </tr>
                    <tr>
                      <th class="text-center">Top 3</th>
                      <td class="text-center">20 percent of total net fees of pick 20.</td>
                    </tr>
                    <tr>
                      <th class="text-center">Top 4</th>
                      <td class="text-center">10 percent of total net fees of pick 20.</td>
                    </tr>
                    <tr>
                      <th>Take note</th>
                      <td>On top 1 - 4 if there are 1 more winner the prize will be divided among the winners equally.</td>
                    </tr>
                  </tbody>
                  <thead>
                    <tr>
                      <th colspan="2" class="text-center bg-dark text-warning">Lowest Scores</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th class="text-center">0</th>
                      <td class="text-center">10,000 each</td>
                    </tr>
                    <tr>
                      <th class="text-center">1</th>
                      <td class="text-center">3,000 each</td>
                    </tr>
                    <tr>
                      <th class="text-center">2</th>
                      <td class="text-center">2,000 each</td>
                    </tr>
                    <tr>
                      <th class="text-center">3</th>
                      <td class="text-center">500 each</td>
                    </tr>
                  </tbody>
              </table>
          </div>
          <div class="card">
            <div class="card-header text-center bg-dark text-warning font-weight-bold">
              Prize for Pick 2, Pick 3, Pick 4, Pick 5 & Pick 6
            </div>
              <table class="table">
                <thead>
                  <tr>
                    <th class="text-center">Winner</th>
                    <td  class="text-center">100 percent of total net fees of pick 2, pick 3,Pick 4, pick 5 & Pick 6 with specific starting fight.</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="text-center"><b>TAKE NOTE : </b>If there are 1 more winner the prize will be divided among the winners equally.</td>
                  </tr>
                </thead>
              </table>
          </div>
        </div>
    </div>
</div>
@endsection
