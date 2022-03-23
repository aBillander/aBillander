<?php

namespace App\Helpers\Imports;

use App\Models\ActivityLogger;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

/*
 *
 *    Class to Import Categories
 * 
 *    Usage:
 *   
 *    Excel::import(new CategoriesImport( ActivityLogger $logger )), request()->file('your_file') | storage_path('users.xlsx'));
 *
 */
class CategoriesImport implements ToModel, WithHeadingRow, WithChunkReading
{
    protected $logger;

    public function __construct(ActivityLogger $logger)
    {
        $this->logger   = $logger;
    }

    /**
     * @param array $row
     *
     * @return Category|null
     */
    public function model(array $row)
    {
        return new Category([
           'name'     => $row[0],
           'email'    => $row[1], 
           'password' => Hash::make($row[2]),
        ]);
    }
    
    public function chunkSize(): int
    {
        return 100;
    }
}

