<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateStudentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-students {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (! File::exists($filePath)) {
            $this->error("File not found: $filePath");

            return 1;
        }

        $lines = File::lines($filePath);
        $count = 0;

        foreach ($lines as $line) {
            gc_collect_cycles();

            $identifier = trim($line);

            if ($identifier === '') {
                continue;
            }

            Student::query()->firstOrCreate(['idnp' => $identifier]);
            $count++;
        }

        $this->info("Imported $count students.");

        return 0;
    }
}
