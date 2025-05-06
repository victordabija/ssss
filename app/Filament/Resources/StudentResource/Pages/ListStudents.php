<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Jobs\ProcessStudentContentJob;
use App\Models\Student;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Dispatch Jobs')
                ->label('Dispatch Jobs for All Records')
                ->action(function () {
                    foreach (Student::query()->cursor() as $student) {
                        ProcessStudentContentJob::dispatch($student);
                    }

                    Notification::make()
                        ->title('Jobs dispatched successfully.')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->color('primary')
                ->icon('heroicon-o-bolt'),
        ];
    }
}
