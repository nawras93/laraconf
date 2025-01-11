<?php

namespace App\Models;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use App\Filament\Resources\SpeakerResource\RelationManagers\TalksRelationManager;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'abstract',
        'length',
        'status',
        'new_talk',
        'speaker_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'speaker_id' => 'integer',
        'length' => TalkLength::class,
        'status' => TalkStatus::class,
        'deleted_at' => 'timestamp',
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function approve(): void
    {
        $this->status = TalkStatus::APPROVED;
        $this->save();

        // Email the speaker to notify them that their talk has been approved.
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('title')
                ->required()
                ->maxLength(255),
            Textarea::make('abstract')
                ->required()
                ->columnSpanFull(),
            Select::make('length')
                ->options(TalkLength::class)
                ->required(),
            Select::make('speaker_id')
                ->relationship('speaker', 'name')
                ->required()
                ->hiddenOn(TalksRelationManager::class),
        ];
    }

}
