<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArticleExport implements FromCollection, WithTitle, ShouldAutoSize, WithStyles
{
    public $articles;

    public function __construct($articles)
    {
        $this->articles = $articles;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Article::whereIn('id', $this->articles)->get();
    }

    public function title(): string
    {
        return 'Articulos'; // El nombre de la hoja
    }

    /// use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        // Opcional: aplicar un color de fondo a los encabezados
        $sheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCC');

        // Aplicar bordes a todas las celdas de la tabla
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:O' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }
}
