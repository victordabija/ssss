<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StudentsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?int $sort = -4;

    protected function getData(): array
    {
        $data = DB::table('students')
            ->selectRaw('studyYear, COUNT(*) as count')
            ->groupBy('studyYear')
            ->pluck('count', 'studyYear')
            ->toArray();

        return [
            'labels' => array_keys($data),
            'datasets' => [
                [
                    'label' => 'Users by Role',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#f87171', '#60a5fa', '#34d399', '#fbbf24', '#a78bfa'
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
