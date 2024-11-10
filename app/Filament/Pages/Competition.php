<?php

namespace App\Filament\Pages;

use App\Settings\CompetitionSettings;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\HtmlString;

class Competition extends Page
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.teacher.pages.competition';

    protected static ?string $title = 'Aktuális versenykiírás';

    protected static ?int $navigationSort = -1;

    public function infolist(Infolist $infolist)
    {
        return $infolist->state(app(CompetitionSettings::class)->toArray())->schema([
            Grid::make(2)->schema([
                TextEntry::make('name')
                    ->label('Verseny')
                    ->badge(),
                TextEntry::make('registration_deadline')
                    ->label('Jelentkezési határidő')
                    ->dateTime()
                    ->weight(FontWeight::Bold),
            ]),
            TextEntry::make('description')
                ->label('')
                ->formatStateUsing(fn($state) => new HtmlString("<div class='prose dark:prose-invert max-w-full'>{$state}</div>"))
                ->html(),
        ]);
    }
}
