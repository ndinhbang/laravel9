<?php

namespace App\GlobalSearch;

use App\Facades\Filament;

class DefaultGlobalSearchProvider implements Contracts\GlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults
    {
        $builder = GlobalSearchResults::make();

        foreach (Filament::getResources() as $resource) {
            if (! $resource::canGloballySearch()) {
                continue;
            }

            $resourceResults = $resource::getGlobalSearchResults($query);

            if (! $resourceResults->count()) {
                continue;
            }

            $builder->category($resource::getPluralLabel(), $resourceResults);
        }

        return $builder;
    }
}
