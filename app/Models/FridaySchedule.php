<?php

namespace App\Models;

use Database\Factories\FridayScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FridaySchedule extends Model
{
    /** @use HasFactory<FridayScheduleFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->startOfWeek()->toDateString())
            ->orderBy('date', 'asc');
    }
}
