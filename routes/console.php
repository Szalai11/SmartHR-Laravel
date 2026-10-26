<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Archivage automatique des candidatures de plus de 6 mois (tous les jours à minuit)
Schedule::command('candidatures:archive')->daily();