<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Special extends Model
{
    protected $fillable = [
        'title_en', 'title_fr', 'description_en', 'description_fr',
        'badge', 'starts_on', 'ends_on', 'is_active', 'sort',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date',
        'is_active' => 'boolean',
        'sort' => 'integer',
    ];

    /** Only specials that are switched on and within their date window today. */
    public function scopeActiveNow(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_on')->orWhereDate('starts_on', '<=', $today))
            ->where(fn ($q) => $q->whereNull('ends_on')->orWhereDate('ends_on', '>=', $today))
            ->orderBy('sort');
    }

    public function title(): string
    {
        return app()->getLocale() === 'fr' ? $this->title_fr : $this->title_en;
    }

    public function description(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->description_fr : $this->description_en;
    }
}
