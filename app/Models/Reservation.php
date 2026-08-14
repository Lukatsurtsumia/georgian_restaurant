<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Reservation extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date',
        'time',
        'guests',
        'table_id',
        'notes',
        'discount',
        'status',
        'source',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    /** Table ids already held (pending/confirmed) for a given date + time slot. */
    public static function heldTableIds(string $date, string $time, ?int $excludingId = null): array
    {
        return static::query()
            ->whereDate('date', $date)
            ->where('time', $time)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->whereNotNull('table_id')
            ->when($excludingId, fn ($query) => $query->whereKeyNot($excludingId))
            ->pluck('table_id')
            ->all();
    }

    /**
     * Tables that can seat a party of $guests and aren't already taken for the
     * given date + time slot. Used to render the floor plan and validate a pick.
     */
    public static function availableTables(string $date, string $time, int $guests): Collection
    {
        $held = self::heldTableIds($date, $time);

        return RestaurantTable::query()
            ->where('seats', '>=', $guests)
            ->whereNotIn('id', $held ?: [0])
            ->orderBy('sort')
            ->get();
    }

    protected static function booted(): void
    {
        static::creating(function (self $reservation) {
            $reservation->manage_token ??= Str::random(48);
        });
    }

    /**
     * Guests already booked (pending or confirmed) for a given date + time slot.
     * Cancelled reservations free up their seats.
     */
    public static function bookedGuests(string $date, string $time, ?int $excludingId = null): int
    {
        return static::query()
            ->whereDate('date', $date)
            ->where('time', $time)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->when($excludingId, fn ($query) => $query->whereKeyNot($excludingId))
            ->sum('guests');
    }

    public static function remainingSeats(string $date, string $time, ?int $excludingId = null): int
    {
        return max(0, config('restaurant.reservation.seats_per_slot') - self::bookedGuests($date, $time, $excludingId));
    }

    /**
     * Create a reservation, re-checking capacity under a MySQL named lock so two
     * concurrent bookings for the same slot can't both slip past the optimistic
     * check already done in ReservationForm's validation (a classic check-then-act
     * race: two requests both read "2 seats free" for a party of 2 and both pass).
     * Returns null instead of throwing when the slot fills up under contention —
     * callers turn that into a normal validation error, not a 500.
     */
    public static function createWithCapacityCheck(array $attributes): ?self
    {
        $lockKey = sprintf('reservation_slot:%s:%s', $attributes['date'], $attributes['time']);

        $acquired = DB::selectOne('select get_lock(?, 10) as acquired', [$lockKey])->acquired;

        if (! $acquired) {
            return null;
        }

        try {
            if (self::remainingSeats($attributes['date'], $attributes['time']) < $attributes['guests']) {
                return null;
            }

            // If a specific table was requested, re-check under the same lock that
            // it hasn't just been taken — closes the table double-book race too.
            if (! empty($attributes['table_id'])) {
                $taken = in_array(
                    (int) $attributes['table_id'],
                    self::heldTableIds($attributes['date'], $attributes['time']),
                    true
                );

                if ($taken) {
                    return null;
                }
            }

            return self::create($attributes);
        } finally {
            DB::statement('select release_lock(?)', [$lockKey]);
        }
    }
}
