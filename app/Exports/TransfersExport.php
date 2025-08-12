<?php

namespace App\Exports;

use App\Models\Transfer;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransfersExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function __construct(array $transfers)
    {
        $this->transfers = $transfers;
    }
    public function array(): array
    {
        return $this->transfers;
    }

    public function headings(): array
    {
        return [ "No",
                "Date",
                "Tarnsfer_No",
                "Warehouse From",
                "Warehouse To",
                "Code",
                "Brand",
                "Commodity",
                "Transfer Qty",
                "Transfer No",
                "Remarks",
                "Created By",
                "Updated By",
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
