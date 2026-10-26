<?php

namespace App\Console\Commands;

use App\Models\Candidature;
use Illuminate\Console\Command;

class ArchiveCandidatures extends Command
{
    protected $signature = 'candidatures:archive';
    protected $description = 'Archive les candidatures de plus de 6 mois';

    public function handle()
    {
        $dateLimite = now()->subMonths(6);
        
        $count = Candidature::where('created_at', '<', $dateLimite)
            ->whereNull('archived_at')
            ->update(['archived_at' => now()]);

        $this->info("✅ $count candidatures archivées avec succès.");

        return Command::SUCCESS;
    }
}