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
        $usersTrend = $this->getTrendData($userQuery);

        $teamQuery = Team::whereSchoolId($school_id);
        $teamsTrend = $this->getTrendData($teamQuery);

        $competitorProfileQuery = $this->getCompetitorProfileQuery($teamQuery);
        $competitorProfileTrend = $this->getTrendData($competitorProfileQuery);

        return [
            BaseWidget\Stat::make('Felhasználók száma', $userQuery->count())
                ->chart($usersTrend)
                ->color('success'),
            BaseWidget\Stat::make('Csapatok száma', $teamQuery->count())
                ->chart($teamsTrend)
                ->color('success'),
            BaseWidget\Stat::make('Versenyzők száma', $competitorProfileQuery->count())
                ->chart($competitorProfileTrend)
                ->color('success'),
        ];
    }

    protected function getTrendData($query)
    {
        return Trend::make($query)
            ->countByDays('created_at')
            ->getData();
    }

    protected function getCompetitorProfileQuery($teamQuery)
    {
        $ids = $teamQuery->get()->pluck('id');

        return CompetitorProfile::query()
            ->whereIn('type', [
                CompetitorProfileType::Student,
                CompetitorProfileType::SubstituteStudent,
            ])
            ->whereHas('teams', function ($query) use ($ids) {
                $query->whereIn('teams.id', $ids);
            });
    }
}
