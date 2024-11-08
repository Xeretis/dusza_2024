<?php

use Carbon\Carbon;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('competition.name', 'Alapértelmezett verseny');
        $this->migrator->add('competition.description', '');
        $this->migrator->add('competition.registration_deadline', Carbon::create(2030)->toString());
        $this->migrator->add('competition.registration_cancelled_at', null);
    }
};
