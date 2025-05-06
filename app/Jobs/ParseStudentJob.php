<?php

namespace App\Jobs;

use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ParseStudentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $idnp,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::asForm()->post('https://api.ceiti.md/date/login', [
                'idnp' => $this->idnp,
            ]);

            Student::idnp($this->idnp)->first()->update([
                'content' => $response->getBody()
            ]);
        } catch (ConnectionException $e) {
            Log::error("Error while parsing student $this->idnp. " . $e->getMessage());
        }
    }
}
