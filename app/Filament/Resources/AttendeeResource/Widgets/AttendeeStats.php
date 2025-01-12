<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use App\Models\Attendee;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendeeStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();
        return [
            Stat::make('Attendees Count', $query->count())
                ->description('Total number of attendees')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->chart([2,3,4,5,6,5,4,3,2,1]),
            Stat::make('Total Revenue', $query->sum('ticket_cost') / 100),
        ];
    }
}
