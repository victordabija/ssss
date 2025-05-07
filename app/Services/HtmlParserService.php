<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Str;
use App\Data\StudentParsedData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\Client\ConnectionException;

class HtmlParserService
{

    protected string $origin;

    public function __construct()
    {
        $this->origin = config('services.parser.origin');
    }

    protected Crawler $crawler;

    public static function make()
    {
        return (app(self::class))->parse(Student::where('id', '1')->first()->content);
    }

    public function parse(string $content): StudentParsedData
    {
        $this->crawler = new Crawler($content);

        return StudentParsedData::from($this->getPersonalInfo());
    }

    protected function getPersonalInfo(): array
    {
        $data = [];
        $this->crawler->filter('#date-personale tr')->each(function (Crawler $node) use (&$data) {
            $key = $node->filter('th')->text();
            $val = $node->filter('td')->text();
            if ($key && $val) {
                $data[Str::snake($key)] = $val;
            }
        });

        return $data;
    }

    /**
     * @throws ConnectionException
     */
    public function fetchIdentifiers(string $url): array
    {
        try {
            $response = $this->getRequest()->get($url);

            $body = $response->getBody();

            $crawler = new Crawler($body);

            $identifiers = [];

            $crawler->filter('a')->each(function (Crawler $node) use (&$identifiers) {
                $text = trim($node->text());

                if (preg_match('/\d{13,}/', $text, $matches)) {
                    $identifiers[] = $matches[0]; // assuming the identifier is 13+ digits
                }
            });

            return $identifiers;
        } catch (ConnectionException $e) {
            Log::error("Error while fetching identifiers" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws ConnectionException
     */
    public function getStudentContent(string $idnp): string
    {
        return $this->getRequest()
            ->post(config('services.parser.student'), [
                'idnp' => $idnp,
            ])->getBody();
    }

    protected function getRequest(): PendingRequest
    {
        return Http::withHeaders([
            'Origin' => $this->origin,
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        ]);
    }
}
