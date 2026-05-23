<?php

namespace App\Exports;

use App\Models\Athlete;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AthletesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    public function __construct(private array $filters = []) {}

    public function title(): string
    {
        return 'Athlètes';
    }

    public function query()
    {
        return Athlete::with(['event', 'coach'])
            ->when($this->filters['event_id'] ?? null,            fn ($q, $v) => $q->where('event_id', $v))
            ->when($this->filters['club'] ?? null,                fn ($q, $v) => $q->where('club', $v))
            ->when($this->filters['age_category'] ?? null,        fn ($q, $v) => $q->where('age_category', $v))
            ->when($this->filters['gender'] ?? null,              fn ($q, $v) => $q->where('gender', $v))
            ->when($this->filters['weight_category'] ?? null,     fn ($q, $v) => $q->where('weight_category', $v))
            ->when($this->filters['registration_status'] ?? null, fn ($q, $v) => $q->where('registration_status', $v))
            ->when($this->filters['payment_status'] ?? null,      fn ($q, $v) => $q->where('payment_status', $v))
            ->orderBy('age_category')
            ->orderBy('gender')
            ->orderBy('weight_category')
            ->orderBy('last_name')
            ->orderBy('first_name');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Prénom',
            'Nom',
            'Date Naissance',
            'Âge',
            'Genre',
            'Catégorie Âge',
            'Catégorie Poids',
            'Poids (kg)',
            'Club',
            'Nationalité',
            'N° Licence',
            'Événement',
            'Statut Inscription',
            'Statut Paiement',
            'Montant Payé',
            'N° Reçu',
            'Coach',
            'Date Inscription',
        ];
    }

    public function map($athlete): array
    {
        return [
            $athlete->id,
            $athlete->first_name,
            $athlete->last_name,
            $athlete->birth_date?->format('d/m/Y') ?? '',
            $athlete->age,
            Athlete::genderLabel($athlete->gender, $athlete->age_category ?? ''),
            $athlete->age_category ?? '',
            $athlete->weight_category ?? '',
            $athlete->weight,
            $athlete->club ?? '',
            $athlete->nationality ?? '',
            $athlete->license_number ?? '',
            $athlete->event?->name ?? '',
            $athlete->registration_status_label,
            $athlete->payment_status_label,
            $athlete->payment_amount ?? 0,
            $athlete->receipt_number ?? '',
            $athlete->coach?->name ?? '',
            $athlete->created_at->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 16,
            'C' => 18,
            'D' => 16,
            'E' => 8,
            'F' => 12,
            'G' => 16,
            'H' => 18,
            'I' => 12,
            'J' => 20,
            'K' => 14,
            'L' => 16,
            'M' => 28,
            'N' => 20,
            'O' => 18,
            'P' => 14,
            'Q' => 16,
            'R' => 22,
            'S' => 20,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row styling — dark background with gold text
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FF0F172A'],
                'size'  => 10,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFF59E0B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF78350F']],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(22);

        // Zebra striping — applied dynamically via callback
        return [
            '1' => ['font' => ['bold' => true]],
        ];
    }
}
