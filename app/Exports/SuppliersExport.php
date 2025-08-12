<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuppliersExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
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
        return ["No",
                "Supplier ID",
                "Supplier Name",
                "Created By",
                "Updated By",
                "Created at",
                "Updated at",
            ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
        1    => ['font' => ['bold' => true]],
        ];
    }
}
