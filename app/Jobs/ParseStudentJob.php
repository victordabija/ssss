<?php

namespace App\Jobs;

use App\Models\Student;
use App\Services\HtmlParserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ParseStudentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $idnp,
        //        protected Student $student,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(HtmlParserService $parserService): void
    {
        $student = null;

        try {
            $body = $parserService->getStudentContent($this->idnp);

            if (Str::contains($body, 'login')) {
                Log::info("Couldn't parse student with IDNP {$this->idnp}.");

                return;
            }

            $student = Student::create([
                'idnp' => $this->idnp,
                'content' => $body,
            ]);
        } catch (ConnectionException $e) {
            Log::error("Error while parsing student with IDNP {$this->idnp}. ".$e->getMessage());
            $this->fail($e);
        }

        DB::afterCommit(function () use ($student) {
            dispatch(new ProcessStudentContentJob($student));
        });
    }
}
