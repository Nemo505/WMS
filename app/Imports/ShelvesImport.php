<?php

namespace App\Imports;

use App\Models\Shelf;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShelvesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Shelf([
            //
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
