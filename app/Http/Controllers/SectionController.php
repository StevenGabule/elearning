<?php

namespace App\Http\Controllers;

use App\Http\Requests\Section\newSectionRequest;
use App\Models\{Course, Section};
use Illuminate\Support\Str;
use Illuminate\Http\{Request, Response};
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    public function store(newSectionRequest $request)
    {
        $res = Section::create($request->except('_token') + ['uuid' => Str::uuid(), 'sortOrder' => 0]);
        return response()->json(['result' => $res], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Section $section
     * @return Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Section $section
     * @return Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Section $section
     * @return Response
     */
    public function update(Request $request, Section $section)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Section $section
     * @return Response
     */
    public function destroy(Section $section)
    {
        //
    }

    public function showAllSection(Course $course)
    {
        if (request()->wantsJson()) {
            $id = $course->id;
            $sections = Section::with('lessons')->where('course_id', $id)->get();
            return DataTables::of($sections)->addColumn('action', static function ($data) use ($id) {
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">

                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>

                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Delete
                                </a>

                                 <a class='dropdown-item small'
                                  href='/course/{$id}/section/$data->id'>
                                  <i class="bi-intersect pr-2"></i> Add Lesson
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }
        return view('frontend.sections.index', [
            'course' => $course
        ]);
    }
}
