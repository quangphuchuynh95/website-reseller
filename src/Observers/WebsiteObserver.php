<?php

namespace QuangPhuc\WebsiteReseller\Observers;

use QuangPhuc\WebsiteReseller\Models\Website;
use QuangPhuc\WebsiteReseller\Services\WebsiteService;

class WebsiteObserver
{
    public function __construct(
        protected WebsiteService $websiteService
    ) {
    }

    public function created(Website $website): void
    {
        $this->websiteService->setupWebsite($website);
    }
}
