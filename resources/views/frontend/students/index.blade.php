@extends('layouts.app')

@push('custom_css')
    <style>
        td {
            vertical-align: initial !important;
        }
    </style>
@endpush

@php
$nextLesson=0;
@endphp

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12 mt-3 mb-3 ">
                <div class="card" style="background: #222">
                    <div class="card-header text-white">{{ __($course->title) }}</div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card" style="background: #222">
                    <div class="card-body text-white p-4">
                        <video controls width="100%" id="videoPlay">
                            <source src='{{ Storage::url($video->path) }}' type="video/mp4">
                        </video>
                        <h4 class="mt-3 mb-3">About this course</h4>
                        <p class="text-white-50">{{ $course->subTitle }}</p>
                        <table>
                            <tbody>
                            <tr>
                                <td class="font-weight-bold" style="width: 20% !important;">By the numbers</td>
                                <td style="width: 56% !important;">
                                    <p>
                                        <span style="font-weight: 500">Skill level:</span> <span
                                            class="text-capitalize">{{ $course->level }}</span> <br/>
                                        <span style="font-weight: 500">Students:</span> {{ random_int(50,100) }} <br/>
                                        <span style="font-weight: 500">Language:</span> {{ $course->language }} <br/>
                                    </p>
                                </td>
                                <td>
                                    @php $totalLecture=0 @endphp
                                    @foreach($course->sections as $section)
                                        @php $totalLecture += $section->lessons->count() @endphp
                                    @endforeach
                                    <span style="font-weight: 500">Lecture:</span> {{ $totalLecture }} <br/>
                                    <span style="font-weight: 500">Video:</span> {{ random_int(1,50) }} Total Hours<br/>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Description</td>
                                <td class=" text-white-50">
                                    {!! $course->description !!}
                                </td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold"></td>
                                <td>
                                    <div class="pt-5 pb-3 h5">Instructor</div>
                                    <div class="d-flex mb-3">
                                        <img width="50" class="rounded-circle" style="object-fit: cover;"
                                             src="{{ $course->author->avatar != null ?  asset('storage/uploads/users/thumbnail') . '/' . $course->author->avatar : asset('images/frontend/icon-image-not-found.png') }}"
                                             alt="">
                                        <div>
                                            <h6 class="ml-3">{{ $course->author->first_name . ' ' . $course->author->last_name }}</h6>
                                            <h6 class="ml-3 text-white-50">{{$course->author->tagline}}</h6>
                                        </div>
                                    </div>
                                    <p class=" text-white-50">{{ $course->author->bio }}</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white" style="background: #222;">
                    <div class="card-header h4 bg-transparent border-bottom-0">{{ __('Course content') }}</div>
                    <div class="card-body">
                        @forelse($course->sections as $section)
                            <div class="accordion text-left" id="accordionExample" data-section="section-{{$section->id}}">
                                <div class="card button-darker rounded-0">
                                    <div class="card-header" id="headingOne{{$section->id}}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-block text-left text-white "
                                                    type="button"
                                                    data-toggle="collapse"
                                                    data-target="#collapseOne{{$section->id}}"
                                                    aria-expanded="true"
                                                    aria-controls="collapseOne{{$section->id}}">
                                                {{$section->title}}
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne{{$section->id}}"
                                         class="collapse @foreach($section->lessons as $lesson) @if($lesson->id == $video->lesson_id) show @endif @endforeach"
                                         aria-labelledby="headingOne{{$section->id}}"
                                         data-parent="#accordionExample">
                                        <div class="card-body">
                                            <table class="table table-hover table-sm small">
                                                <tbody>
                                                @forelse($section->lessons as $lesson)
                                                    <tr id="lesson-{{ $lesson->id }}"
                                                        class="@if($lesson->id == $video->lesson_id) bg-light @endif text-left">
                                                        <td class="small">
                                                            <a href="{{ route('student.take_course', ['lessonId' => $lesson->id, 'slug' => $course->slug ]) }}"
                                                               class="btn btn-link">{!! $lesson->showContentType() !!}
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $lesson->title }}
                                                            </a>
                                                        </td>
                                                        <td>{{$lesson->duration}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-white">No lessons available</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- ///////////// end of card ///////////// -->
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom_scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function () {

            var course_id = '{{$course->id}}';
            var user_id = '{{auth()->id()}}';
            // determined the first lesson  or the selected lesson by the learner
            var lesson_id = '{{ $lessonId != 0 ? $lessonId : $video->lesson_id  }}';

            var currentLesson = $(".bg-light").next('tr');
            // var nextLesson =19;
            var nextLesson = currentLesson.length > 0 ? currentLesson.attr('id').split('-')[1] : null;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#videoPlay").on("ended", closeThat);

            function closeThat() {
                var player = $("#videoPlay");
                if (player.duration == player.currentTime) {
                    // save the completion lesson
                    // and redirect to the next lesson
                    swal({
                        title: "Saving your progress...",
                        text: "Please wait for 5 seconds to save your progress...",
                        icon: "success",
                        buttons: false,
                        closeModal: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        timer: 5000,
                    }).then(() => {
                        $.ajax({
                            url: "{{ route('student.course.lesson.save') }}",
                            method: 'POST',
                            data: {
                                user_id: user_id,
                                lesson_id: lesson_id,
                                course_id: course_id,
                            },
                            success: ({ok, completed}) => {
                                if (completed) {
                                    {{--window.location.href = "/course/{{ $course->slug }}/learn/lecture/start=0/" + nextLesson--}}
                                    alert('You completed the course!')
                                } else {
                                    if (ok) {
                                        window.location.href = "/course/{{ $course->slug }}/learn/lecture/start=0/" + nextLesson
                                    } else {
                                        alert('Something went wrong! Kindly refresh the page or contact the admin for support!')
                                    }
                                }

                            },
                            error: ({status, responseText}) => {
                                if (status === 500) {
                                    const messages = $.parseJSON(responseText);
                                    swal("Oops..something went wrong!", messages.message, "error");
                                }
                            }
                        });
                    });
                }
            }
        })
    </script>
@endpush
