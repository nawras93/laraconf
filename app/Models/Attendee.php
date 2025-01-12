<?php

namespace App\Models;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
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
        'ticket_cost',
        'is_paid',
        'conference_id',
    ];

    protected function casts(): array
    {
        return [
            'is_paid' => 'boolean',
        ];
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            Group::make()
                ->columns(2)
                ->schema([
                TextInput::make('name'),
                TextInput::make('email')->email(),
            ])
        ];
    }


}
