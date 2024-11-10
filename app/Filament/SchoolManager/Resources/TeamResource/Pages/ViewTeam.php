<?php

namespace App\Filament\SchoolManager\Resources\TeamResource\Pages;

use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use App\Enums\TeamStatus;
use App\Filament\SchoolManager\Resources\TeamResource;
use App\Models\Team;
use App\Models\TeamEvent;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;
use Imagick;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Illuminate\Support\Str;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->viewPdfAction(),
            $this->approveAction(),
        ];
    }

    protected function viewPdfAction(): Actions\Action
    {
        return Actions\Action::make('viewPdf')
            ->label('Jelentkezési lap / Aláírás megtekintése')
            ->color('gray')
            ->hidden(fn(Team $record) => $record->status === TeamStatus::Inactive)
            ->form([
                PdfViewerField::make('accept_artifact_path')
                    ->label('Jelentkezési lap / Aláírás')
                    ->required()
                    ->fileUrl(fn(Team $record) => Storage::disk('public')->url($record->accept_artifact_path))
                    ->disabled(),
            ])
            ->modalSubmitAction(false);
    }

    protected function approveAction(): Actions\Action
    {
        return Actions\Action::make('approve')
            ->label(fn(Team $record) => $record->status !== TeamStatus::Inactive ? 'Jelentkezés elfogadva' : 'Jelenkezés elfogadása')
            ->modalDescription('Kérlek tölts fel egy digitális aláírást vagy egy jelentkezési lapot.')
            ->color('success')
            ->form([
                SignaturePad::make('signature')
                    ->label('Aláírás')
                    ->backgroundColor('#ffffff')
                    ->backgroundColorOnDark('#ffffff')
                    ->penColor('#000000')
                    ->penColorOnDark('#000000')
                    ->requiredIf('file', null),
                FileUpload::make('file')
                    ->disk('public')
                    ->directory('applications-pdf')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(5120)
                    ->label('Jelentkezési lap')
                    ->requiredIf('signature', null),
            ])
            ->disabled(fn($record) => $record->status !== TeamStatus::Inactive)
            ->action(fn(array $data, Team $record, $livewire) => $this->approveTeam($data, $record));
    }

    protected function approveTeam(array $data, Team $record): void
    {
        $file_path = $this->handleFileUpload($data);

        $record->accept_artifact_path = $file_path;
        $record->status = TeamStatus::SchoolApproved;
        $record->save();

        $this->createTeamEvent($record);

        Notification::make()
            ->title('Jelentkezés elfogadva')
            ->body('Jelentkezés sikeresen elfogadva.')
            ->success()
            ->send();
    }

    protected function handleFileUpload(array $data): ?string
    {
        if (isset($data['signature'])) {
            return $this->saveSignatureAsPdf($data['signature']);
        }

        return $data['file'];
    }

    protected function saveSignatureAsPdf(string $signature): string
    {
        $image = base64_decode(str_replace('data:image/png;base64,', '', $signature));
        $imagick = new Imagick();
        $imagick->readImageBlob($image);
        $imagick->setImageType(Imagick::IMGTYPE_GRAYSCALEMATTE);
        $imagick->setImageFormat('pdf');
        $path = 'applications-pdf/' . Str::random() . '.pdf';
        $filename = Storage::disk('public')->path($path);
        Storage::disk('public')->makeDirectory('applications-pdf');
        $imagick->writeImage($filename);

        return $path;
    }

    protected function createTeamEvent(Team $record): void
    {
        TeamEvent::create([
            'message' => 'A csapatot az iskola elfogadta.',
            'team_id' => $record->id,
            'scope' => TeamEventScope::School,
            'type' => TeamEventType::Approval,
            'status' => TeamEventStatus::Approved,
            'user_id' => auth()->user()->id,
            'closed_at' => now(),
        ]);
    }
}
