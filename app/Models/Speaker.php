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
        'avatar',
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

    const QUALIFICATIONS = [
        'Doctor of Philosophy',
        'Master of Science',
        'Bachelor of Science',
        'Diploma',
        'Certificate',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function Talks()
    {
        return $this->hasMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('avatar')
                ->avatar()
                ->imageEditor()
                ->maxSize(1024 * 1024 * 20) // 2MB
                ->columnSpanFull(),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('bio')
                ->required()
                ->columnSpanFull(),
            Forms\Components\CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->options(self::QUALIFICATIONS)
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
