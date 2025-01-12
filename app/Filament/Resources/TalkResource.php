<?php

namespace App\Filament\Resources;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
//    protected static ?string $navigationGroup = 'Second Group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(fn($action) => $action->button()->label('Filters'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->description(function (Talk $record) {
                        return Str::of($record->abstract)->limit(50);
                    }),
                ImageColumn::make('speaker.avatar')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(function (Talk $record) {
                        return 'https://ui-avatars.com/api/?name=' . urlencode($record->speaker->name) . '&color=7F9CF5&background=EBF4FF';
                    }),
                Tables\Columns\TextColumn::make('speaker.name')
                    ->label('Speaker')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('new_talk'),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => $state->getColor()),
                Tables\Columns\IconColumn::make('length')
                    ->icon(fn ($state) => $state->getIcon())
                    ->label('Length')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('new_talk')
                    ->label('New Talk'),
                Tables\Filters\SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->label('Speaker')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('has_avatar')
                    ->label('Show only speakers with avatars')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->whereHas('speaker', fn(Builder $query) => $query->whereNotNull('avatar'))),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
//                    ->action(fn (Talk $talk) => $talk->update(['status' => TalkStatus::APPROVED])),
                        ->action(fn (Talk $record) => $record->approve())
                        ->visible(fn(Talk $record) => $record->status === TalkStatus::SUBMITTED)
                        ->icon('heroicon-o-check-circle')
                        ->color('success')

                        ->after(function () {
                            Notification::make()->success()->title('This Talk was approved')
                                ->duration(1000)
                                ->body('The speaker has been notified.')
                                ->send();
                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->action(fn (Talk $talk) => $talk->update(['status' => TalkStatus::REJECTED]))
                        ->visible(fn (Talk $record) => $record->status === TalkStatus::SUBMITTED)
                        ->icon('heroicon-o-no-symbol')
                        ->requiresConfirmation()
                        ->color('danger')

                        ->after(function () {
                            Notification::make()->danger()->title('This Talk was rejected')
                                ->duration(1000)
                                ->body('The speaker has been notified.')
                                ->send();
                        }),
                ])


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve')
                        ->action(function (Collection $records) {
                            $records->each->approve();
                        }),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export')
                    ->tooltip('This will export all data which is visible in the table.')
                    ->action(function ($livewire) {
                        $livewire->getFilteredTableQuery()->count();
                    })
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
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
//            'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
