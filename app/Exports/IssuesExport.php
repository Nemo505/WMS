<?php

namespace App\Exports;

use App\Models\Issue;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IssuesExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
   
    public function __construct(array $issues)
    {
        $this->issues = $issues;
    }
    public function array(): array
    {
        return $this->issues;
    }

    public function headings(): array
    {
        return [
            "No",
            "Date" ,
            "MR No" ,
            "Job No" ,
            'Warehouse',
            "Shelf No" ,
            "Customer",

            "Code",
            "Brand",
            "Commodity" ,
            "Unit",
            "MR Qty" ,
            "MRR Qty" ,
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