<?php

namespace App\Helpers\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // you want your 0 values to be actual 0 values in your Excel sheet instead of null (empty cells)

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/*
 *
 *    Handy Class to be extended, our used "as-is"
 * 
 *    Usage:
 *   
 *    $export = (new ArrayExport($data))->setStyles($styles)->setTitle($title)->setColumnFormats($columnFormats);
 *    // same as: 
 *    $export = new ArrayExport($data, $styles, $title, $columnFormats);
 *
 */
class ArrayExport implements FromArray, WithStyles, WithTitle, WithColumnFormatting, WithStrictNullComparison, ShouldAutoSize
{
    protected $data;
    protected $styles;
    protected $title;
    protected $columnFormats;

    public function __construct(array $data, array $styles = [], string $title = 'Worksheet', array $columnFormats = [])
    {
        $this->data   = $data;
        $this->styles = $styles;
        $this->title  = $title;
        $this->columnFormats = $columnFormats;
    }

    public function array(): array
    {
        return $this->data;
    }


    public function setStyles(array $styles = [])
    {
        
        $this->styles = $styles;

        return $this;

/*
        // https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#valid-array-keys-for-style-applyfromarray

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'italic' => true]],

            // Styling a specific cell by coordinate.
            'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'C'  => ['font' => ['size' => 16]],
        ];
*/
    }

    public function styles(Worksheet $sheet): array
    {
        return $this->styles;
    }


    public function setTitle(string $title)
    {
        
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }


    
    public function setColumnFormats(array $columnFormats = [])
    {
        
        $this->columnFormats = $columnFormats;

        return $this;

/*
        // https://docs.laravel-excel.com/3.1/exports/column-formatting.html

        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
*/
    }
    
    public function columnFormats(): array
    {
        return $this->columnFormats;
    }
}
