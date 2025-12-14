<?php

namespace App\Enums\Reports;

enum ExportFormatEnum: string
{
    case PDF = 'pdf';
    case EXCEL = 'excel';
    case CSV = 'csv';

    public function label(): string
    {
        return match ($this) {
            self::PDF => 'PDF',
            self::EXCEL => 'Excel',
            self::CSV => 'CSV',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PDF => 'bi-file-pdf',
            self::EXCEL => 'bi-file-excel',
            self::CSV => 'bi-file-text',
        };
    }

    public function mimeType(): string
    {
        return match ($this) {
            self::PDF => 'application/pdf',
            self::EXCEL => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            self::CSV => 'text/csv',
        };
    }
}
