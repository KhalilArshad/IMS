<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrillaRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'trilla_id',
        'date',
        'total_trip_amount',
        'total_diesel',
        'total_maintenance',
        'total_d_trip',
        'other_expense'
    ];

    protected $appends = [
        'other_expense_items',
        'other_expense_total',
    ];

    public function trillaRecordChild()
    {
        return $this->hasMany(TrillaRecordChild::class,'trilla_record_id','id');
    }
    public function trilla()
    {
        return $this->belongsTo(Trilla::class,'trilla_id','id');
    }

    public function getOtherExpenseItemsAttribute(): array
    {
        $raw = $this->attributes['other_expense'] ?? null;

        if ($raw === null || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_map(function ($item) {
                return [
                    'amount' => (float) ($item['amount'] ?? 0),
                    'explanation' => (string) ($item['explanation'] ?? ''),
                ];
            }, $decoded));
        }

        if (is_numeric($raw)) {
            return [[
                'amount' => (float) $raw,
                'explanation' => '',
            ]];
        }

        return [];
    }

    public function getOtherExpenseTotalAttribute(): float
    {
        return collect($this->other_expense_items)->sum(function ($item) {
            return (float) ($item['amount'] ?? 0);
        });
    }
}
