<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CourseCreateRequest;
use App\Jobs\uploadImage;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('frontend.course.index');
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $id = (int)auth()->id();
            $courses = Course::with('category:id,name')
                ->where('user_id', $id)
                ->get();
            return DataTables::of($courses)->addColumn('action', static function ($data) {
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">
                                <a class='dropdown-item small'
                                  href='/course/$data->id/sections'>
                                  <i class="bi-intersect pr-2"></i> Sections
                                </a>
                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> View
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Delete
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }
    }


    public function create()
    {
        $categories = Category::all();
        return view('frontend.course.create', compact('categories'));
    }

    public function store(CourseCreateRequest $request)
    {
        $image = null;
        if ($originalImage = $request->file('image')) {
            $originalImage->getPathName();
            $image = time() . '_' . preg_replace('/\s+/', '_', strtolower($originalImage->getClientOriginalName()));
            $originalImage->storeAs('uploads/original/', $image, 'tmp');
        }

        $data = $request->except('_token');
        $data['user_id'] = auth()->id();
        $data['uuid'] = (string)Str::uuid();
        $data['slug'] = Str::slug($request->title);
        $data['image'] =  $image != null ? $image : null;
        $course = Course::create($data);
        $this->dispatch(new uploadImage($course));
        return redirect()->route('course.index')->with('success', 'You successfully added a new course, now add some lessons.');
    }

    public function show($slug)
    {
        $course = Course::with(['author', 'category'])->whereSlug($slug)->first();
        dd($course);
    }

    public function edit(Course $course)
    {
        //
    }

    public function update(Request $request, Course $course)
    {
        //
    }


    public function destroy(Course $course)
    {
        //
    }
}
