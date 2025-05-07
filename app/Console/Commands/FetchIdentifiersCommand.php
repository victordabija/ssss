<?php

namespace App\Console\Commands;

use App\Jobs\ProcessStudentContentJob;
use App\Models\Student;
use App\Services\HtmlParserService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;

class FetchIdentifiersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-identifiers {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(HtmlParserService $parserService): void
    {
        $url = !blank($this->argument('url')) ? $this->argument('url') : config('services.parser.url');

        try {
            $identifiers = $parserService->fetchIdentifiers($url);
        } catch (ConnectionException $e) {
            $this->fail($e);
        }

        $count = 0;
        foreach ($identifiers as $identifier) {
            if (Student::query()->where('idnp', $identifier)->exists()) {
                continue;
            }

            $count++;

            $student = Student::create(['idnp' => $identifier]);

            dispatch(new ProcessStudentContentJob($student));

            $this->info("$count | $identifier | Dispatching job...");;
        }

        $this->info("Finished. Found $count new students.");
    }
}
