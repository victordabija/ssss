<?php

namespace App\Jobs;

use App\Models\Student;
use App\Services\HtmlParserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\ConnectionException;

class ParseStudentJob implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Student $student,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(HtmlParserService $parserService): void
    {
        try {
            $body = $parserService->getStudentContent($this->student->idnp);

            $this->student->update([
                'content' => $body
            ]);
        } catch (ConnectionException $e) {
            Log::error("Error while parsing student $this->student->idnp. " . $e->getMessage());
            $this->fail($e);
        }
    }
}
