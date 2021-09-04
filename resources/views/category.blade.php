@extends('layouts.app')

@section('content')
    @include('frontend.__shared.hero')
    <div class="container">
        <h5 class="my-4 pink">Category: {{ $categories->name }}</h5>
        <div class="row row-cols-1 row-cols-md-4">
            @forelse($categories->courses as $course)
                <div class="col mb-4" style="display: flex;flex-flow: row wrap">
                    <div class="card border-0 shadow-sm rounded-0" style="background: #222222">
                        <img src="{{ $course->image != null ? asset('storage/uploads/courses/thumbnail') . '/' . $course->image : asset('images/frontend/no-image-found.png') }}"
                             class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('student.course.fetch', $course->slug) }}"
                                   class="text-white custom-hover">{{ $course->title }}</a>
                            </h5>
                            <p class="card-text" style="color: #aaa">{{ $course->subTitle }}</p>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <div>
                                        <img width="30" height="30" class="rounded-circle mr-2 ml-2"
                                             src="{{ $course->avatar != null ?
                                                asset('storage/uploads/users/thumbnail') . '/' . $course->avatar :
                                                asset('images/frontend/no-image-found.png') }}" alt="">
                                    </div>
                                    <div>
                                        <h6 class="small text-white">{{ $course->author->first_name . ' ' . $course->author->last_name }}</h6>
                                        <h6 class="small text-white">{{ date_format($course->author->created_at, 'j F, Y')}}</h6>
                                    </div>
                                </div><!-- first d-flex -->

                                <div class="d-flex">
                                    <div class="text-right mr-2">
                                        <h6 class="small text-white">1.4 hours</h6>
                                        <h6 class="small text-white">{{ $course->category->name }}</h6>
                                    </div>
                                </div><!-- first d-flex -->
                            </div>
                        </div>
                    </div>
                </div><!-- ////////////////////////////// end of col mb-3 ////////////////////////////// -->
            @empty
            <p>No course Available</p>
            @endforelse
        </div>
    </div>
@endsection
