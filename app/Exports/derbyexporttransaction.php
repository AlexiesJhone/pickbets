<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Transactions;
use App\Models\expertbet;
use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class derbyexporttransaction implements FromCollection, WithMapping,WithColumnFormatting,WithHeadings,ShouldAutoSize,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public $id1;
    public $id2;

    public function __construct($id1,$id2)
    {
       $this->id1 = $id1;
       $this->id2 = $id2;
    }
    public function collection()
    {
        $getevent = Event::where('event_name',$this->id2)->get();
        $array = array();
        foreach ($getevent as $key) {
          array_push($array,$key->id);
        }
        // $gettransactions = Transactions::with('user')->whereIn('event_id',$array)->where('cashier_id',$req['id'])->latest()->get();
        return Transactions::whereIn('event_id',$array)->where('cashier_id',$this->id1)->with('user')->get();
    }
    public function headings(): array
    {
        return [
            'Transaction ID',
            'Type',
            'Barcode',
            'Transacted To',
            'Starting Balance',
            'Amount',
            'Ending Balance',
            'Date And Time',
        ];
    }
    public function map($bets): array
   {
      return [
          $bets->id,
          $bets->type,
          $bets->barcode,
          $bets->user->username,
          $bets->startingbalancecashier,
          $bets->amount,
          $bets->endingbalancecashier,
          Date::dateTimeToExcel($bets->created_at)
      ];
   }
   public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],


        ];
    }
    public function columnFormats(): array
    {
        return [
            // 'A' => NumberFormat::FORMAT_TEXT,
            // 'B' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_DATE_XLSX22,
        ];
    }
}
