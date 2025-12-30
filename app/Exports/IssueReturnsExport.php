<?php

namespace App\Exports;

use App\Models\IssueReturn;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IssueReturnsExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
   
    public function __construct(array $issue_returns)
    {
        $this->issue_returns = $issue_returns;
    }
    public function array(): array
    {
        return $this->issue_returns;
    }

    public function headings(): array
    {
        return [
            "No",
            "Date" ,
            "MRR No" ,
            "Do Return" ,
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