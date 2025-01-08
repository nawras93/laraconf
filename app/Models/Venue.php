<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Forms;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Venue extends Model Implements HasMedia
{
    use HasFactory, InteractsWithMedia;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'city',
        'state',
        'country',
        'zip',
        'region',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'deleted_at' => 'timestamp',
        'region' => Region::class,
    ];

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('city')
                ->required()
                ->maxLength(255),
            Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                ->collection('venue-images')
                ->label('Images')
                ->multiple()
                ->image()
                ->maxSize(1024 * 1024 * 20) // 20 MB
                ->columnSpan(2),
            TextInput::make('state')
                ->required()
                ->maxLength(255),
            TextInput::make('country')
                ->required()
                ->maxLength(255),
            TextInput::make('zip')
                ->required()
                ->maxLength(255),
            Select::make('region')
                ->required()
                ->enum(Region::class)
                ->options(Region::class),
        ];
    }
}
