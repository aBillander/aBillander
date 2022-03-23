<?php

namespace App\Helpers\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

/*
 *
 *    Handy Class to be extended, our used "as-is"
 * 
 *    Usage:
 *   
 *    Excel::import(new ArrayImport, request()->file('your_file') | storage_path('users.xlsx'));
 *
 */
class ArrayImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return Category|null
     */
    public function model(array $row)
    {
        return true;
    }
    
    public function chunkSize(): int
    {
        return 100;
    }
}

