<?php

namespace App\Filament\Organizer\Widgets;

use App\Models\School;
use Filament\Widgets\ChartWidget;

class SchoolCityChart extends ChartWidget
{
    protected static ?string $heading = 'Iskolák eloszlása város szerint';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $schoolCounts = School::selectRaw("city, COUNT(*) as count")
            ->groupBy("city")
            ->get();

        $normalizedCounts = [];

        foreach ($schoolCounts as $schoolCount) {
            $normalizedCity = preg_replace('/[^a-zA-ZáéíóúüűöőÁÉÍÓÚÜŰÖ]/u', '', mb_strtolower($schoolCount->city));

            if (!empty($normalizedCity)) {
                if (isset($normalizedCounts[$normalizedCity])) {
                    $normalizedCounts[$normalizedCity]['count'] += $schoolCount->count;
                } else {
                    $normalizedCounts[$normalizedCity] = [
                        'count' => $schoolCount->count,
                        'display_name' => $schoolCount->city
                    ];
                }
            }
        }

        $data = [];
        $labels = [];

        foreach ($normalizedCounts as $city => $info) {
            $labels[] = $info['display_name'];
            $data[] = $info['count'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Iskolák száma',
                    'data' => $data,
                    'backgroundColor' => '#A9A2EB',
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
