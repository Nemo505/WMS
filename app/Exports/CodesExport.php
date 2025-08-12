<?php

namespace App\Exports;

use App\Models\Code;
use App\Models\Brand;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CodesExport implements WithHeadings, ShouldAutoSize, WithStyles, FromCollection, WithDrawings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $codes;

    public function __construct($codes)
    {
        $this->codes = $codes;
    }

    public function collection()
    {
        return $this->codes;
    }

    public function headings(): array
    {
        return [
            "No",
            "ImagePath",
            "Name",
            "Brand Name",
            "Commodity Name",
            "Usage",
            "Created By",
            "Updated By",
            "Created at",
            "Updated at",
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;
        $column = 'B';
        foreach ($this->codes as $code) {
            $drawing = new Drawing();
            $drawing->setName($code['name']);
            $drawing->setDescription('Image');
            
            $imagePath = 'storage/img/code/'.$code['ImagePath'];
            if (!empty($code['ImagePath'])) {
                if (file_exists($imagePath)) {
                    $drawing->setPath($imagePath);
                } else {
                    $drawing->setPath('storage/img/code/no-img.jpg');
                }
            }else {
                    $drawing->setPath('storage/img/code/no-img.jpg');
                }
            $drawing->setHeight(30);
            $drawing->setWidth(30);

            $drawing->setCoordinates($column . $row);
            $drawings[] = $drawing;
            $row++;
        }
        return $drawings;
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach ($this->codes as $code) {
                    $event->sheet->getRowDimension($row)->setRowHeight(30);
                    $event->sheet->getColumnDimension($column)->setWidth(30 / 7); 
                }
            },
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

