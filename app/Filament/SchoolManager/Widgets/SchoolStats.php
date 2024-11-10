<?php

namespace App\Filament\SchoolManager\Widgets;

use App\Enums\CompetitorProfileType;
use App\Models\CompetitorProfile;
use App\Models\School;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use SaKanjo\EasyMetrics\Metrics\Trend;

class SchoolStats extends StatsOverviewWidget
{
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $school_id = auth()->user()->school_id;

        $userQuery = User::whereSchoolId($school_id);

        $usersTrend = Trend::make($userQuery)
            ->countByDays('created_at')
            ->getData();

        $teamQuery = Team::whereSchoolId($school_id);

        $teamsTrend = Trend::make($teamQuery)
            ->countByDays('created_at')
            ->getData();

        $ids = $teamQuery->get()->pluck('id');

        $competitorProfileQuery = CompetitorProfile::query()
            ->whereIn('type', [
                CompetitorProfileType::Student,
                CompetitorProfileType::SubstituteStudent,
            ])
            ->whereHas('teams', function ($query) use ($ids) {
                $query->whereIn('teams.id', $ids);
            });

        $competitorProfileTrend = Trend::make($competitorProfileQuery)
            ->countByDays('created_at')
            ->getData();

        return [
            BaseWidget\Stat::make('Felhasználók száma', $userQuery->count())
                ->chart($usersTrend)
                ->color('success'),
            BaseWidget\Stat::make('Cspatok száma', $teamQuery->count())
                ->chart($teamsTrend)
                ->color('success'),
            BaseWidget\Stat::make(
                'Versenyzők száma',
                $competitorProfileQuery->count()
            )
                ->chart($competitorProfileTrend)
                ->color('success'),
        ];
    }
}
