@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <ul class="breadcrumb" style="background: #222;font-weight: 500;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('homepage') }}" class="text-white" style="font-weight: 500;">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="" class="text-white" style="font-weight: 500;">{{ $course->category->name }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="" class="text-white" style="font-weight: 500;">{{ $course->title }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card rounded-0 text-white" style="background: #222">
                    <div class="card-header bg-transparent border-bottom-0">
                        <h2>{{ __($course->title) . ' | ' . number_format($course->price, 2) }}</h2>
                        <div class="d-flex justify-content-between">
                            <h5 class="w-75 text-white-50">{{ __($course->subTitle) }}</h5>
                            <form action="{{ route('buy_now') }}" method="post" id="paymentForm">
                                @csrf
                                <input type="hidden"
                                       id="value"
                                       name="value"
                                       value="{{ $course->price }}">

                                <input type="hidden"
                                       id="currency"
                                       name="currency"
                                       value="usd">

                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <button type="submit" class="btn button-darker-outline text-white rounded-0 px-5 py-3">Buy now</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 20px !important;">
                        <video controls width="70%" id="videoPlay">
                            <source src='{{ Storage::url($video->path) }}' type="video/mp4">
                        </video>
                        <h3 class="mb-3 mt-3">Course content</h3>
                        @forelse($course->sections as $section)
                            <div class="accordion" id="accordionExample" style="margin-bottom: 10px;">
                                <div class="card" style="background: #131628">
                                    <div class="card-header" id="headingOne{{$section->id}}">
                                        <h2 class="mb-0">
                                            <button class="btn button-darker text-left"
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
                                         class="collapse"
                                         aria-labelledby="headingOne{{$section->id}}"
                                         data-parent="#accordionExample">
                                        <div class="card-body">
                                            <table class="table table-sm small text-white">
                                                <tbody>
                                                @forelse($section->lessons as $lesson)
                                                    <tr>
                                                        <td class="pl-4">{!! $lesson->showContentType() !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-capitalize">{{ $lesson->title }}</span></td>
                                                        <td>{{ $lesson->preview ? 'Preview' : null }}</td>
                                                        <td>02:43</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">No lessons available</td>
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
                        <br>
                        <br>
                        <h5>Description:</h5>
                        <p class=" text-white-50">{!! $course->description !!}</p>
                        <br>
                        <br>
                        <h5>Instructor:</h5>
                        <div class="d-flex align-items-center">
                            <img width="50" class="rounded-circle" style="object-fit: cover;"
                                 src="{{ $course->author->avatar != null ?  asset('storage/uploads/users/thumbnail') . '/' . $course->author->avatar : asset('images/frontend/cover_photo.png') }}"
                                 alt="">
                            <h6 class="ml-3">{{ $course->author->first_name . ' ' . $course->author->last_name }}</h6>
                        </div>
                        <p class=" text-white-50">{{ $course->author->bio }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
