<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Filament\Resources\SpeakerResource\RelationManagers;
use App\Models\Speaker;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

//    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Second Group';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Speaker::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('twitter_handle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('avatar')
                            ->circular(),
                        Group::make()
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('email'),
                                TextEntry::make('twitter_handle')
                                    ->label('Twitter')
                                    ->getStateUsing(fn ($record) => '@'.$record->twitter_handle)
//                                    ->formatStateUsing(fn (Speaker $speaker) => '@'.$speaker->twitter_handle)
//                                    ->prefix('@')
                                    ->url(fn (Speaker $speaker) => "https://twitter.com/{$speaker->twitter_handle}"),
                                TextEntry::make('has_spoken')
                                    ->getStateUsing(fn ($record) => $record->Talks->count() > 0 ? 'Previous Speaker' : 'Has not spoken')
                                    ->label('Has Spoken at a Conference')
                                    ->badge()
                                    ->color(fn ($record) => $record->Talks->count() > 0 ? 'success' : 'danger')
                            ])

                    ]),
                Section::make('Other Information')
                    ->schema([
                        TextEntry::make('bio')
                            ->html()
                            ->extraAttributes(['class' => 'prose dark:prose-invert']),
                        TextEntry::make('qualifications')
//                            ->listWithLineBreaks()
//                            ->bulleted()
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TalksRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            'view' => Pages\ViewSpeaker::route('/{record}'),
        ];
    }
}
