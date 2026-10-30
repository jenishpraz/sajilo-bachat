<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MembershipApplication extends Model
{
    public const STATUSES = ['pending' => 'Pending', 'under_review' => 'Under review', 'approved' => 'Approved', 'rejected' => 'Rejected'];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date', 'reviewed_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $app) {
            if (! $app->reference_no) {
                $app->reference_no = self::generateReference();
            }
        });
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'SB-'.now()->format('Y').'-'.strtoupper(Str::random(6));
        } while (static::where('reference_no', $ref)->exists());

        return $ref;
    }

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'membership_type_id');
    }
}
