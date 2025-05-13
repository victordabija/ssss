<?php

namespace App\Console\Commands;

use App\Jobs\ParseStudentJob;
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
        $url = ! blank($this->argument('url')) ? $this->argument('url') : config('services.parser.url');

        try {
            $identifiers = $parserService->fetchIdentifiers($url);
        } catch (ConnectionException $e) {
            $this->error($e);
            $this->fail();
        }

        $count = 0;
        foreach ($identifiers as $identifier) {
            if (Student::query()->where('idnp', $identifier)->exists()) {
                continue;
            }

            $count++;

            dispatch(new ParseStudentJob($identifier));
        }

        $this->info("Finished. Found $count new students.");
    }
}
