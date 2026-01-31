<?php

namespace QuangPhuc\WebsiteReseller\Observers;

use Illuminate\Support\Facades\Storage;
use QuangPhuc\WebsiteReseller\Models\Theme;

class ThemeObserver
{
    public function deleted(Theme $theme): void
    {
        if ($theme->database_file) {
            Storage::delete($theme->database_file);
        }
    }
}
