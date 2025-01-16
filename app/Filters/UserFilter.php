<?php

namespace App\Filters;

use Core\Api\Filters\Filter;

class UserFilter extends Filter
{
    protected function getSearchableFields(): array
    {
        return ['name', 'email'];
    }

    protected function getSortableFields(): array
    {
        return ['name', 'email', 'created_at'];
    }

    protected function filterRole(string $role): void
    {
        $this->query->where('role', '=', $role);
    }

    protected function filterActive(bool $active): void
    {
        $this->query->where('active', '=', $active);
    }

    protected function filterCreatedBetween(array $dates): void
    {
        $this->query->whereBetween('created_at', $dates);
    }
} 