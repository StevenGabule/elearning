<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\View\View;

class ComposerCategory {
    function compose(View $view) {
        $categories = Category::where('live', true)->orderByDesc('created_at')->get();
        $view->with(compact('categories'));
    }
}
