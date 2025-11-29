<?php

namespace App\Exports;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TodosExport implements FromCollection, WithHeadings, WithMapping, WithEvents {
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function collection() {
        return $this->data;
    }

    public function headings(): array {
        return [
            'Title',
            'Assignee',
            'Due Date',
            'Time Tracked',
            'Status',
            'Priority'
        ];
    }

    public function map($todo): array {
        return [
            $todo->title,
            $todo->assignee,
            $todo->due_date,
            $todo->time_tracked,
            $todo->status,
            $todo->priority
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $count = $this->data->count();
                $sum = $this->data->sum('time_tracked');
                $row  = $count + 2;

                $event->sheet->setCellValue("A{$row}", 'TOTAL');
                $event->sheet->setCellValue("D{$row}", $sum);
            }
        ];
    }
}
