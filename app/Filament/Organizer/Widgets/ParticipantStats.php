<?php

namespace App\Filament\Organizer\Widgets;

use App\Models\School;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use SaKanjo\EasyMetrics\Metrics\Trend;

class ParticipantStats extends BaseWidget
{
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $usersTrend = Trend::make(User::class)
            ->countByDays('created_at')->getData();

        $teamsTrend = Trend::make(Team::class)
            ->countByDays('created_at')->getData();

        $schoolsTrend = Trend::make(School::class)
            ->countByDays('created_at')->getData();

        return [
            BaseWidget\Stat::make('Felhasználók száma', User::count())
                ->chart($usersTrend)
                ->color('success'),
            BaseWidget\Stat::make('Cspatok száma', Team::count())
                ->chart($teamsTrend)
                ->color('success'),
            BaseWidget\Stat::make('Iskolák száma', School::count())
                ->chart($schoolsTrend)
                ->color('success'),
        ];
    }
}
