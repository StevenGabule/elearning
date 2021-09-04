@extends('layouts.app')



@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 ">
                <div class="card mt-5">
                    <div class="card-header text-center bg-transparent border-bottom-0 h3 pt-4">{{ __('Create Course') }}</div>
                    <div class="card-body pb-5">
                        <form method="POST"
                              action="{{ route('course.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="category_id"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Select the category') }}</label>

                                <div class="col-md-6">
                                    <select class="custom-select mr-sm-2 @error('category_id') is-invalid @enderror"
                                            name="category_id"
                                            required
                                            id="category_id">
                                        <option value="" selected>Choose...</option>
                                        @foreach($categories as $category)
                                            <option
                                                value="{{ $category->id }}" {{old('category_id') == $category->id ? "selected": "" }} >{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="title"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

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
                                <label for="subTitle"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Sub Title') }}</label>

                                <div class="col-md-6">
                                    <input id="subTitle"
                                           type="text"
                                           class="form-control @error('subTitle') is-invalid @enderror"
                                           name="subTitle"
                                           value="{{ old('subTitle') }}"
                                           required autocomplete="subTitle">
                                    @error('subTitle')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description"
                                       class="col-md-4 col-form-label text-md-right">
                                    {{ __('Description') }}
                                </label>

                                <div class="col-md-6">

                                    <textarea id="description"
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
                                <label for="Language"
                                       class="col-md-4 col-form-label text-md-right">
                                    {{ __('Language') }}</label>

                                <div class="col-md-6">
                                    <select class="custom-select mr-sm-2" name="language" id="Language" required>
                                        <option value="" selected>Choose...</option>
                                        <option value="1" {{ old('language') == 1 ? 'selected' : '' }}>Filipino</option>
                                        <option value="2" {{ old('language') == 2 ? 'selected' : '' }}>English</option>
                                        <option value="3" {{ old('language') == 3 ? 'selected' : '' }}>Indian</option>
                                    </select>

                                    @error('language')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="Level"
                                       class="col-md-4 col-form-label text-md-right">
                                    {{ __('Level') }}</label>

                                <div class="col-md-6">
                                    <select class="custom-select mr-sm-2" name="level" id="Level" required>
                                        <option value="" selected>Choose...</option>
                                        <option value="all" {{ old('level') == "all" ? 'selected' : '' }}>All</option>
                                        <option value="beginner" {{ old('level') == "beginner" ? 'selected' : '' }}>
                                            Beginner
                                        </option>
                                        <option
                                            value="intermediate" {{ old('level') == "intermediate" ? 'selected' : '' }}>
                                            Intermediate
                                        </option>
                                        <option value="advanced" {{ old('level') == "advanced" ? 'selected' : '' }}>
                                            Advanced
                                        </option>
                                    </select>

                                    @error('level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="Price"
                                       class="col-md-4 col-form-label text-md-right">
                                    {{ __('Price') }}</label>

                                <div class="col-md-6">
                                    <input id="Price"
                                           type="text"
                                           class="form-control @error('price') is-invalid @enderror"
                                           name="price"
                                           value="{{ old('price') }}"
                                           required autocomplete="price">

                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <label for="image"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Preview Image') }}</label>

                                <div class="col-md-6">
                                    <input type="file" accept="image/*" class="form-control-file" id="image" name="image"
                                           onchange="previewImageUpload(event)">
                                    <img src="{{ asset('images/frontend/cover_photo.png') }}"
                                         class="my-3 img-fluid"
                                         alt="" id="previewImage">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn button-darker btn-block">
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
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script>
        window.onload = function(e) {
         CKEDITOR.replace('description');
     }
    </script>
@endpush
