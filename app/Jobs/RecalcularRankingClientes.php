<?php

namespace App\Jobs;

use App\Services\ParametroClienteService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RecalcularRankingClientes implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private $parametroClienteService)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->parametroClienteService->asignarRank();
    }
}
