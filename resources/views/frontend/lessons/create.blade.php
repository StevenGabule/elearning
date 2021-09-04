@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>{{$section->title}}</h5>
                        <p>{{ __('Create Lesson on section') }}</p>
                    </div>
                    <div class="card-body">
                        <form method="POST"
                              action="{{ route('lesson.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ $course->id }}" name="course_id">
                            <input type="hidden" value="{{ $section->id }}" name="section_id">

                            <div class="form-group row">
                                <label for="title"
                                       class="col-md-4 col-form-label text-md-right">
                                    {{ __('Title') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="title"
                                           type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           name="title"
                                           value="{{ old('title') }}"
                                           required autocomplete="title">

                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <textarea id="description"
                                              rows="8"
                                              class="form-control @error('description') is-invalid @enderror"
                                              name="description"
                                              required>{{ old('description') }}</textarea>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="content_type"
                                       class="col-md-4 col-form-label text-md-right">
                                    {{ __('Content Type') }}</label>

                                <div class="col-md-6">
                                    <select class="custom-select mr-sm-2" name="content_type" id="content_type" required>
                                        <option value="" selected>Choose...</option>
                                        <option value="video" {{ old('content_type') == "video" ? 'selected' : '' }}>Video</option>
                                        <option value="youtube" {{ old('content_type') == "youtube" ? 'selected' : '' }}>Youtube</option>
                                        <option value="article" {{ old('content_type') == "article" ? 'selected' : '' }}>Article</option>
                                        <option value="quiz" {{ old('content_type') == "quiz" ? 'selected' : '' }}>Quiz</option>
                                    </select>

                                    @error('content_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="duration"
                                       class="col-md-4 col-form-label text-md-right">
                                    {{ __('Duration') }}</label>

                                <div class="col-md-6">
                                    <input id="duration"
                                           type="text"
                                           class="form-control @error('duration') is-invalid @enderror"
                                           name="duration"
                                           value="{{ old('duration') }}"
                                           required autocomplete="title">

                                    @error('duration')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ArticleBody"
                                       class="col-md-4 col-form-label text-md-right">
                                    {{ __('Article Body') }}</label>

                                <div class="col-md-6">
                                    <textarea id="ArticleBody" rows="6"
                                           class="form-control @error('article_body') is-invalid @enderror"
                                           name="article_body"
                                              required autocomplete="article_body">{{ old('article_body') }}</textarea>

                                    @error('article_body')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="preview"
                                       class="col-md-4 col-form-label text-md-right">
                                    </label>

                                <div class="col-md-6">
                                    <div class="form-group form-check">
                                        <input type="checkbox" name="preview" class="form-check-input" id="preview">
                                        <label class="form-check-label" for="preview">Preview</label>
                                    </div>

                                    @error('preview')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{ __('Create') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom_scripts')
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
