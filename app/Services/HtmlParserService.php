<?php

namespace App\Services;

use App\Data\StudentParsedData;
use App\Models\Student;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class HtmlParserService
{

    protected Crawler $crawler;

    public static function make()
    {
        return (new self())->parse(Student::where('id', '1')->first()->content);;
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
}
