<?php

namespace App\Imports;

use App\Models\ShelfNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShelfNumberImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ShelfNumber([
            //
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
