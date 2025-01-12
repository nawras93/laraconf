<?php

namespace App\Models;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
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
                    Shout::make('price')
                        ->color('warning')
//                        ->content('Ticket Price is high!')
                        ->content(function (Get $get) {
                            $price = $get('ticket_price');
                            return 'This is ' . $price - 500 . ' more than the average ticket price.';
                        })
                        ->columnSpanFull()
                        ->visible(function (Get $get) {
                            return $get('ticket_price') > 500;
                        }),
                    TextInput::make('name'),
                    TextInput::make('email')->email(),
                    TextInput::make('ticket_price')->numeric()->lazy(),
            ])
        ];
    }


}
