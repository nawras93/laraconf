<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms;

class Conference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'region',
        'venue_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'venue_id' => 'integer',
        'deleted_at' => 'timestamp',
        'region' => Region::class,
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Conference Name')
                ->placeholder('My Conference')
//                    ->default('My Conference')
//                    ->hint('The name of the conference')
//                    ->hintIcon('heroicon-o-information-circle')
//                    ->prefix('Conference Name')
//                    ->prefixIcon('heroicon-o-identification')
                ->required()
//                    ->markAsRequired(false)
//                    ->rules()
                ->maxLength(255),
            RichEditor::make('description')
                ->required()
                ->maxLength(255),
            DateTimePicker::make('start_date')
                ->native(false)
                ->required(),
            DateTimePicker::make('end_date')
                ->required(),
            Toggle::make('is_published')
                ->default(false),
            Select::make('status')
                ->required()
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'cancelled' => 'Cancelled',
                ]),
            Select::make('region')
                ->required()
                ->live()
                ->searchable()
                ->preload(true)
                ->enum(Region::class)
                ->options(Region::class),
            Select::make('venue_id')
                ->searchable()
                ->preload(true)
                ->createOptionForm(Venue::getForm())
                ->editOptionForm(Venue::getForm())
                ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                    return $query->where('region', $get('region'));
                }),
            Forms\Components\CheckboxList::make('speakers')
                ->relationship('speakers', 'name')
                ->required(),
        ];
    }
}
