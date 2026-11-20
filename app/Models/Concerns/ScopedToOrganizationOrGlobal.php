<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * For lookup tables (education levels, availabilities, ...) where a row either
 * belongs to one organization or is shared by all (organization_id = null).
 */
trait ScopedToOrganizationOrGlobal
{
    public function scopeVisibleTo(Builder $query, ?int $organizationId): Builder
    {
        return $query->where(function (Builder $q) use ($organizationId) {
            $q->whereNull('organization_id');
            if ($organizationId) {
                $q->orWhere('organization_id', $organizationId);
            }
        });
    }
}
