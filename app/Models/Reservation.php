<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'notes',
        'status',
        'source',
    ];

    protected $casts = [
        'date' => 'date',
    ];

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

            return self::create($attributes);
        } finally {
            DB::statement('select release_lock(?)', [$lockKey]);
        }
    }
}
