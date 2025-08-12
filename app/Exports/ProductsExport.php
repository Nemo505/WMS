<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function __construct(array $products)
    {
        $this->products = $products;
    }
    public function array(): array
    {
        return $this->products;
    }

    public function headings(): array
    {
        return ["No",
                "Date",
                "Voucher No",
                "Warehouse",
                "Shelf",
                "Shelf No",
                "Supplier",
                "Code",
                "Brand",
                "Commodity",
                "Unit",
                "Receive Qty",
                "Transfer Qty",
                "MR Qty",
                "MRR Qty",
                "SupplierReturn Qty",
                "Balance Qty",
                "Type",
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
