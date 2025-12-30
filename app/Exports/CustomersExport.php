<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $customers)
    {
        $this->customers = $customers;
    }
    public function array(): array
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return ["No",
                "Customer ID",
                "Customer Name",
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
