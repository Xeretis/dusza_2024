<?php

namespace App\Filament\Organizer\Widgets;

use App\Models\School;
use Filament\Widgets\ChartWidget;

class SchoolStateChart extends ChartWidget
{
    protected static ?string $heading = 'Iskolák eloszlása vármegyék szerint';

    protected function getData(): array
    {
        $schoolCounts = School::selectRaw("state, COUNT(*) as count")
            ->groupBy("state")
            ->get();

        $normalizedCounts = [];

        foreach ($schoolCounts as $schoolCount) {
            $normalizedState = preg_replace('/[^a-zA-ZáéíóúüűöőÁÉÍÓÚÜŰÖ]/u', '', mb_strtolower($schoolCount->state));

            if (!empty($normalizedState)) {
                if (isset($normalizedCounts[$normalizedState])) {
                    $normalizedCounts[$normalizedState]['count'] += $schoolCount->count;
                } else {
                    $normalizedCounts[$normalizedState] = [
                        'count' => $schoolCount->count,
                        'display_name' => $schoolCount->state
                    ];
                }
            }
        }

        $data = [];
        $labels = [];

        foreach ($normalizedCounts as $state => $info) {
            $labels[] = $info['display_name'];
            $data[] = $info['count'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Iskolák száma',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
