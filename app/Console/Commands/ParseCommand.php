<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Jobs\ParseStudentJob;
use Illuminate\Console\Command;

class ParseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts the parsing process.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = 1;

        /** @var Student $student */
        foreach (Student::query()->whereNull('content')->cursor() as $student) {
            dispatch(new ParseStudentJob($student));

            $this->info("$count | $student->idnp | Dispatching job...");

            $count++;
        }
    }
}
