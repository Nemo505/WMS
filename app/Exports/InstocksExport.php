<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InstocksExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function __construct(array $instocks)
    {
        $this->instocks = $instocks;
    }
    public function array(): array
    {
        return $this->instocks;
    }

    public function headings(): array
    {
        return [
            "No",
            'Warehouse',
            'Shelf',
            'ShelfNumber',

            "Code",
            "Brand",
            "Commodity" ,
            
            "Total Opening Qty" ,
            "Total Received Qty" ,
            "Total Transfer In" ,
            "Total Transfer Out" ,
            
            "Total MR Qty" ,
            "Total MRR Qty" ,
            "Total Supplier Return" ,
            
            "Total Add Adjustment " ,
            "Total Sub Adjustment " ,
            "Total Balance " ,
           
            ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
        1    => ['font' => ['bold' => true]],
        ];
    }
}
