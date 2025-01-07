<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms;

class Speaker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'bio',
        'qualifications',
        'twitter_handle',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'deleted_at' => 'timestamp',
        'qualifications' => 'array',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    protected static function getForm()
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('bio')
                ->required()
                ->columnSpanFull(),
            Forms\Components\CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->options([
                    'PhD',
                    'Masters',
                    'Bachelors',
                    'Diploma',
                    'Certificate',
                ])
                ->columns(3)
                ->searchable()
                ->bulkToggleable()
                ->descriptions([
                    'Doctor of Philosophy',
                    'Master of Science',
                    'Bachelor of Science',
                    'Diploma',
                    'Certificate',
                ])
                ->required(),
            TextInput::make('twitter_handle')
                ->required()
                ->maxLength(255),
        ];
    }
}
