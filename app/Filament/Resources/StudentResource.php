<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Jobs\ProcessStudentContentJob;
use App\Models\Student;
use App\Services\HtmlParserService;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->columnSpan(2),
                TextInput::make('studyYear'),
                TextInput::make('speciality'),
                TextInput::make('group'),
                TextInput::make('idnp')
                    ->required(),
                Textarea::make('content')
                    ->columnSpanFull()
                    ->rows(20)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('idnp')
                    ->badge(),
                TextColumn::make('studyYear'),
                TextColumn::make('group'),
                TextColumn::make('speciality'),
            ])
            ->filters([
                Filter::make('name')
                    ->form([
                        TextInput::make('name')
                    ])
                    ->query(fn($query, array $data) => $query->when($data['name'] ?? '', fn($q, $name) => $q->where('name', 'like', '%' . $name . '%'))),
                SelectFilter::make('studyYear')
                    ->options([1 => 1, 2 => 2, 3 => 3, 4 => 4]),
                SelectFilter::make('speciality')
                    ->searchable()
                    ->options(
                        Student::query()
                            ->select('speciality')
                            ->whereNotNull('speciality')
                            ->distinct()
                            ->pluck('speciality', 'speciality') // associative array: ['value' => 'label']
                            ->toArray()
                    ),
                SelectFilter::make('group')
                    ->label('Group')
                    ->searchable()
                    ->options(
                        Student::query()
                            ->select('group')
                            ->whereNotNull('speciality')
                            ->distinct()
                            ->pluck('group', 'group') // associative array: ['value' => 'label']
                            ->toArray()
                    )
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
