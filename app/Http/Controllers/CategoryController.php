<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $categories = Category::with('courses')->whereSlug($slug)->firstOrFail();
        return view('category', compact('categories'));
    }
}
