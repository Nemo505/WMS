<?php

namespace App\Exports;

use App\Models\Commodity;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CommoditiesExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $commodities)
    {
        $this->commodities = $commodities;
    }
    public function array(): array
    {
        return $this->commodities;
    }

    public function headings(): array
    {
        return ["No",
                "Commodity ID",
                "Commodity Name",
                "Created By",
                "Updated By",
                "Created at",
                "Updated at",
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
