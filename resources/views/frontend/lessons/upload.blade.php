@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <lesson-uploads :lesson="{{ $lesson->id }}" inline-template v-cloak>

                <div class="col-md-8">

                    <div class="card p-3 justify-content-center align-items-center" v-if="!selected">

                        <svg onclick="document.getElementById('video-files').click()"
                             xmlns="http://www.w3.org/2000/svg"  width="100" height="100"
                             viewBox="0 0 24 24">
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"/>
                                <path d="M16 4c.552 0 1 .448 1 1v4.2l5.213-3.65c.226-.158.538-.103.697.124.058.084.09.184.09.286v12.08c0 .276-.224.5-.5.5-.103 0-.203-.032-.287-.09L17 14.8V19c0 .552-.448 1-1 1H2c-.552 0-1-.448-1-1V5c0-.552.448-1 1-1h14zm-1 2H3v12h12V6zM9 8l4 4h-3v4H8v-4H5l4-4zm12 .841l-4 2.8v.718l4 2.8V8.84z"/>
                            </g>
                        </svg>

                        <input type="file" multiple ref="videos" id="video-files" class="d-none" @change="upload">
                        <p class="text-center">Upload videos</p>

                    </div>

                    <div class="card p-3" v-else>
                        <div class="my-4" v-for="video in videos">
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="{ width: `${video.percentage || progress[video.name]}%` }"  aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    @{{ video.percentage ? video.percentage === 100 ? 'Video processing completed' : 'Processing' : 'Uploading' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div v-if="!video.thumbnail" class="d-flex justify-content-center align-items-center"
                                         style="height: 180px;color: #fff;font-size: 18px;background-color: #808080">
                                        Loading thumbnail...
                                    </div>
                                    <img v-else :src="video.thumbnail" style="width: 100%" alt="Hello World" title="image">
                                </div>
                                <div class="col-md-4">
                                    <a v-if="video.percentage && video.percentage === 100" target="_blank" :href="`/videos/${video.id}`">@{{ video.title }}</a>
                                    <h4 v-else class="text-center">
                                        @{{ video.title || video.name }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </lesson-uploads>
        </div>
    </div>
@endsection

@push('custom_scripts')
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush
