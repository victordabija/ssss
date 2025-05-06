<?php

namespace App\Jobs;

use App\Models\Student;
use App\Services\HtmlParserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessStudentContentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Student $student,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(HtmlParserService $parserService): void
    {
        $data = $parserService->parse($this->student->content);

        $this->student->update($data->toArray());
    }
}
