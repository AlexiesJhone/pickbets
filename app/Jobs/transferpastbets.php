<?php

namespace App\Jobs;

use App\Models\expertbet;
use App\Events\resultevent;
use App\Models\selection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use DB;
use Illuminate\Queue\SerializesModels;

class transferpastbets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $event_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event_id)
    {
      $this->event_id = $event_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $event_ids = $this->event_id;

       // Insert into Outbox_1 (field1, field 2......)
       // SELECT (field1, field2......)
       // FROM OUTBOX
       // WHERE DT_INSERT >= DATEADD(DAY, -30, GETDATE());
       // DB::insert('insert into past_expertbet (id, name) values (?, ?)', [1, 'Dayle']);
       // BEGIN TRANSACTION;
       //  INSERT INTO Table2 (<columns>)
       //  SELECT <columns>
       //  FROM Table1
       //  WHERE <condition>;
       //
       //  DELETE FROM Table1
       //  WHERE <condition>;
       //
       //  COMMIT;
       // return
         DB::transaction(function () use($event_ids){

         });
      foreach ($event_ids as $key) {
        // event(new resultevent('Last','endevent',$key,'eventupdate','id','awd','id','id'));
        DB::insert(DB::raw(
          '
          insert ignore into past_expertbet
          select  t1.id, t1.barcode, t1.bet, t1.amount, t1.event_id, t1.startingfight, t1.user_id, t1.wins, t1.lose, t1.winner, t1.result, t1.dividendo, t1.claimed, t1.turn,t1.reprint, t1.startingbalance,
          t1.created_at,t1.updated_at
          from expertbet as t1 where t1.event_id = '. $key
        ));
        DB::insert(DB::raw(
          '
          insert ignore into past_selection
          select t1.id, t1.expertbet_id, t1.event_id, t1.user_id, t1.selection, t1.fightnumber, t1.startingfight, t1.created_at, t1.updated_at
          from selection as t1 where t1.event_id = '. $key
        ));
       DB::table('expertbet')
            ->where('event_id',$key)
            ->delete();
       DB::table('selection')
            ->where('event_id',$key)
            ->delete();
        // expertbet::where('event_id',$key)
        // ->each(function ($oldRecord) {
        //   $newRecord = $oldRecord->replicate();
        //   $newRecord->setTable('past_expertbet');
        //   $newRecord->save();
        //
        //   $oldRecord->delete();
        // });
        //
        //
        // selection::where('event_id',$key)
        // ->each(function ($oldRecord) {
        //   $newRecord = $oldRecord->replicate();
        //   $newRecord->setTable('past_selection');
        //   $newRecord->save();
        //
        //   $oldRecord->delete();
        // });
      }

    }
}
