<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $products;

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
        return [
            "No", "Date", "Voucher No", "Warehouse", "Shelf", "Shelf No",
            "Supplier", "Code", "Brand", "Commodity", "Unit",
            "Receive Qty", "Transfer Qty", "MR Qty", "MRR Qty",
            "SupplierReturn Qty", "Balance Qty", "Type", "Transfer No",
            "Remarks", "Created By", "Updated By",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // First row bold
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Date
            'C' => 20,  // Voucher No
            'D' => 20,  // Warehouse
            'E' => 15,  // Shelf
            'F' => 10,  // Shelf No
            'G' => 20,  // Supplier
            'H' => 15,  // Code
            'I' => 15,  // Brand
            'J' => 20,  // Commodity
            'K' => 10,  // Unit
            'L' => 15,  // Receive Qty
            'M' => 15,  // Transfer Qty
            'N' => 10,  // MR Qty
            'O' => 10,  // MRR Qty
            'P' => 20,  // SupplierReturn Qty
            'Q' => 15,  // Balance Qty
            'R' => 10,  // Type
            'S' => 15,  // Transfer No
            'T' => 30,  // Remarks (wider)
            'U' => 15,  // Created By
            'V' => 15,  // Updated By
        ];
    }
}

