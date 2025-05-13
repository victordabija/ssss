<?php

namespace App\Console\Commands;

use App\Jobs\ParseStudentJob;
use App\Models\Student;
use Illuminate\Console\Command;

class ProcessNewStudentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-new-students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = Student::query()->whereNull('content')->count();

        $this->info("Found $count new students.");

        Student::query()->whereNull('content')->get()->each(function (Student $student) {
            dispatch(new ParseStudentJob($student));
            $this->info("Dispatched job for student $student->id.");
        });
    }
}
