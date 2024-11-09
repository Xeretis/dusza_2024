<?php

namespace App\Filament\Organizer\Resources\TeamResource\Pages;

use App\Filament\Organizer\Resources\TeamResource;
use App\Models\CompetitorProfile;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $model = static::getModel()::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'programming_language_id' => $data['programming_language_id'],
            'school_id' => $data['school_id']
        ]);

        //TODO: Send out invites

        if ($data['competitor1']['name'] != null) {
            $p1 = CompetitorProfile::create(collect($data['competitor1'])->forget('invite')->merge(collect([
                'user_id' => User::where('email', $data['competitor1']['email'])->first()?->id
            ]))->toArray());

            $p1->teams()->attach($model);
        }

        if ($data['competitor2']['name'] != null) {
            $p1 = CompetitorProfile::create(collect($data['competitor2'])->forget('invite')->merge(collect([
                'user_id' => User::where('email', $data['competitor2']['email'])->first()?->id
            ]))->toArray());

            $p1->teams()->attach($model);
        }

        if ($data['competitor3']['name'] != null) {
            $p1 = CompetitorProfile::create(collect($data['competitor3'])->forget('invite')->merge(collect([
                'user_id' => User::where('email', $data['competitor3']['email'])->first()?->id
            ]))->toArray());

            $p1->teams()->attach($model);
        }

        if (count($data['teachers']) > 0) {
            foreach ($data['teachers'] as ['id' => $id]) {
                $model->competitorProfiles()->attach($id);
            }
        }
        
        return $model;
    }
}
