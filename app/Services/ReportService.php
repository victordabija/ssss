<?php

namespace App\Services;

use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;

class ReportService
{
    public function __construct(
        private readonly TelegramNotifier $telegramNotifier,
    )
    {
    }

    public static function test()
    {
        app(self::class)->report();
    }

    public function report(): void
    {
        $this->telegramNotifier->send(
            $this->generateMessage(Student::recent()->get())
        );
    }

    private function generateMessage(Collection $students): string
    {
        $count = $students->count();

        $emoji = $count > 0 ? '😎' : '🥲';

        return view('report.message', [
            'count' => $count,
            'emoji' => $emoji,
            'students' => $students
        ])->render();
    }
}
