<?php

namespace App\GlobalSearch;

class GlobalSearchResult
{
    public function __construct(
        public string $title,
        public string $url,
        public array $details = [],
    ) {
    }
}
