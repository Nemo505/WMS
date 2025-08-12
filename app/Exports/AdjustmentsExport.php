<?php

namespace App\Exports;

use App\Models\Adjustment;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdjustmentsExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
   
    public function __construct(array $adjustments)
    {
        $this->adjustments = $adjustments;
    }
    public function array(): array
    {
        return $this->adjustments;
    }

    public function headings(): array
    {
        return [
            "No",
            "Date" ,
            'Warehouse',
            "Shelf No" ,
            "Supplier",

            "Code",
            "Brand",
            "Commodity" ,
            "Unit",
            "Qty" ,
            "Remarks",
            "Voucher No",

            "Create By",
            "Updated By",
            
            "Created At",
            "Updated At",
            ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
        // Style the first row as bold text.
        1    => ['font' => ['bold' => true]],
        ];
    }
}