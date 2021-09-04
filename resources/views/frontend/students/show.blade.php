@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card text-white mt-5" style="background: #222;">
                    <div class="card-header h3 bg-transparent border-bottom-0">All Courses</div>

                    <div class="card-body">
                        <div class="list-group">
                            @forelse($enrolled as $enroll)
                                <a href="{{ route('student.take_course', ['slug' => $enroll->course->slug]) }}"
                                   class="list-group-item list-group-item-action button-darker text-left px-4 py-3" aria-current="true">
                                    {{$enroll->course->title}}
                                </a>
                            @empty
                                <p>No learning videos!</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

