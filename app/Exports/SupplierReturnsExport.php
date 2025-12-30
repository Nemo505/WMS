<?php

namespace App\Exports;

use App\Models\SupplierReturn;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplierReturnsExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
   
    public function __construct(array $suppliers)
    {
        $this->suppliers = $suppliers;
    }
    public function array(): array
    {
        return $this->suppliers;
    }

    public function headings(): array
    {
        return [
            "No",
            "Date" ,
            "Supplier No" ,
            'Warehouse',
            "Shelf No" ,
            "Supplier",

            "Code",
            "Brand",
            "Commodity" ,
            "Unit",
            "Supplier Return Qty" ,
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