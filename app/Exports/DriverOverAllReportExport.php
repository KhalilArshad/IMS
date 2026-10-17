<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class DriverOverAllReportExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithEvents
{
    protected $invoices;
    protected $driverName;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($invoices, $driverName, $dateFrom, $dateTo)
    {
        $this->invoices   = $invoices;
        $this->driverName = $driverName;
        $this->dateFrom   = $dateFrom;
        $this->dateTo     = $dateTo;
    }

    /**
     * Build the data array (rows) for the sheet.
     * ⚠️ IMPORTANT: Summary values must be in column F (index 5)
     */
    public function array(): array
    {
        $rows = [];

        foreach ($this->invoices as $customerId => $weeks) {
            $customerName = $weeks->first()->customer->name ?? 'N/A';

            // Blank spacer before each customer
            $rows[] = ['', '', '', '', '', '', ''];

            // Customer separator row
            $rows[] = ['CUSTOMER: ' . $customerName, '', '', '', '', '', ''];

            foreach ($weeks as $weekKey => $entry) {
                [$year, $week] = explode('-', $weekKey);
                $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $end   = Carbon::now()->setISODate($year, $week)->endOfWeek();

                $rows[] = [
                    $customerName,
                    $weekKey,
                    $start->format('d M Y'),
                    $end->format('d M Y'),
                    Carbon::parse($entry->date)->format('d M Y'),
                    (float) $entry->net_remaining,
                    $entry->description ?? '',
                ];
            }
        }

        // Blank spacer before summary
        $rows[] = ['', '', '', '', '', '', ''];

        // Summary header
        $rows[] = ['SUMMARY', '', '', '', '', '', ''];

        // ✅ Summary key-value rows — values MUST go in column F (index 5)
        $rows[] = [
            'Total Customers',
            '',
            '',
            '',
            '',
            count($this->invoices),
            '',
        ];

        $rows[] = [
            'Total Net Remaining',
            '',
            '',
            '',
            '',
            (float) collect($this->invoices)->flatMap(fn($w) => $w->pluck('net_remaining'))->sum(),
            '',
        ];

        $rows[] = [
            'Generated At',
            '',
            '',
            '',
            '',
            now()->format('d M Y H:i'),
            '',
        ];

        $rows[] = [
            'Date Range',
            '',
            '',
            '',
            '',
            ($this->dateFrom ?? '-') . '  →  ' . ($this->dateTo ?? '-'),
            '',
        ];

        $rows[] = [
            'Driver',
            '',
            '',
            '',
            '',
            $this->driverName,
            '',
        ];

        return $rows;
    }

    /**
     * Column headings.
     */
    public function headings(): array
    {
        return [
            'Customer',
            'Week',
            'Week Start',
            'Week End',
            'Last Entry Date',
            'Net Remaining',
            'Description',
        ];
    }

    /**
     * Column widths in Excel units.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 12,
            'C' => 16,
            'D' => 16,
            'E' => 18,
            'F' => 20,
            'G' => 45,
        ];
    }

    /**
     * Base styles applied to the sheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Header row style
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E79'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'FFFFFF'],
                ],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(26);

        // All cells base border + font
        $sheet->getStyle('A1:G' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'BFBFBF'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        return [];
    }

    /**
     * Events — apply conditional styles AFTER the sheet is built.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 1; $row <= $highestRow; $row++) {
                    $firstCell = $sheet->getCell('A' . $row)->getValue();

                    // Customer separator rows (merged, light blue)
                    if (is_string($firstCell) && str_starts_with($firstCell, 'CUSTOMER:')) {
                        $sheet->mergeCells("A{$row}:G{$row}");
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'font' => [
                                'bold'  => true,
                                'size'  => 12,
                                'color' => ['rgb' => '1F4E79'],
                            ],
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D9E1F2'],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical'   => Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $sheet->getRowDimension($row)->setRowHeight(22);
                        continue;
                    }

                    // Summary header row (red bar)
                    if ($firstCell === 'SUMMARY') {
                        $sheet->mergeCells("A{$row}:G{$row}");
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'font' => [
                                'bold'  => true,
                                'size'  => 13,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'C00000'],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical'   => Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $sheet->getRowDimension($row)->setRowHeight(24);
                        continue;
                    }

                    // Summary key-value rows
                    if (in_array($firstCell, ['Total Customers', 'Total Net Remaining', 'Generated At', 'Date Range', 'Driver'])) {
                        $sheet->mergeCells("A{$row}:E{$row}");
                        $sheet->mergeCells("F{$row}:G{$row}");

                        $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                                'vertical'   => Alignment::VERTICAL_CENTER,
                            ],
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FFF2CC'],
                            ],
                        ]);

                        $sheet->getStyle("F{$row}:G{$row}")->applyFromArray([
                            'font' => [
                                'bold'  => true,
                                'color' => ['rgb' => '1F4E79'],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical'   => Alignment::VERTICAL_CENTER,
                            ],
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FFF2CC'],
                            ],
                        ]);

                        // Number formats
                        if ($firstCell === 'Total Net Remaining') {
                            $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                        }
                        if ($firstCell === 'Total Customers') {
                            $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('0');
                        }

                        $sheet->getRowDimension($row)->setRowHeight(22);
                        continue;
                    }

                    // Data row — format Net Remaining + alternate row color
                    if (is_numeric($sheet->getCell('F' . $row)->getValue())) {
                        $sheet->getStyle('F' . $row)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.00');
                        $sheet->getStyle('F' . $row)
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                        if ($row % 2 === 0) {
                            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                                'fill' => [
                                    'fillType'   => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'F2F2F2'],
                                ],
                            ]);
                        }
                    }
                }

                // Hide unused columns H onwards
                foreach (range('H', 'Z') as $col) {
                    $sheet->getColumnDimension($col)->setVisible(false);
                }

                // Freeze top row (header stays visible while scrolling)
                $sheet->freezePane('A2');
            },
        ];
    }
}
