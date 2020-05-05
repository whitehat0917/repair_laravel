@extends('back.layout')

@section('css')
    <style>
        textarea { resize: vertical; }
    </style>
    <link href="{{ asset('adminlte/plugins/colorbox/colorbox.css') }}" rel="stylesheet">
@endsection

@section('main')

    @yield('form-open')
        {{ csrf_field() }}

        <div class="row">

            <div class="col-md-8">
                @if (session('post-ok'))
                    @component('back.components.alert')
                        @slot('type')
                            success
                        @endslot
                        {!! session('post-ok') !!}
                    @endcomponent
                @endif
                @component('back.components.box')
                    @slot('type')
                        warning
                    @endslot
                    @slot('boxTitle')
                        @lang('User')
                    @endslot
                    @include('back.partials.input', [
                        'input' => [
                            'name' => 'user_id',
                            'values' => isset($post) ? $post->user_id : 0,
                            'input' => 'select',
                            'options' => $users,
                        ],
                    ])
                @endcomponent
                @include('back.partials.boxinput', [
                    'box' => [
                        'type' => 'box-primary',
                        'title' => __('Title'),
                    ],
                    'input' => [
                        'name' => 'title',
                        'value' => isset($post) ? $post->title : '',
                        'input' => 'text',
                        'required' => true,
                    ],
                ])
                @include('back.partials.boxinput', [
                    'box' => [
                        'type' => 'box-primary',
                        'title' => __('IMEI'),
                    ],
                    'input' => [
                        'name' => 'imei',
                        'value' => isset($post) ? $post->imei : '',
                        'input' => 'text',
                        'required' => true,
                    ],
                ])
                @include('back.partials.boxinput', [
                    'box' => [
                        'type' => 'box-primary',
                        'title' => __('Problem'),
                    ],
                    'input' => [
                        'name' => 'problem',
                        'value' => isset($post) ? $post->problem : '',
                        'input' => 'tag',
                        'required' => true,
                    ],
                ])
                <button type="submit" class="btn btn-primary">@lang('Submit')</button>
            </div>
        </div>
        <!-- /.row -->
    </form>

@endsection

@section('js')

    <script src="{{ asset('adminlte/plugins/colorbox/jquery.colorbox-min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/voca/voca.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
    <script>

        CKEDITOR.replace('body', {customConfig: '/adminlte/js/ckeditor.js'})

        $('.popup_selector').click( function (event) {
            event.preventDefault()
            var updateID = $(this).attr('data-inputid')
            var elfinderUrl = '/elfinder/popup/'
            var triggerUrl = elfinderUrl + updateID
            $.colorbox({
                href: triggerUrl,
                fastIframe: true,
                iframe: true,
                width: '70%',
                height: '70%'
            })
        })

        function processSelectedFile(filePath, requestingField) {
            $('#' + requestingField).val('\\' + filePath)
            $('#img').attr('src', '\\' + filePath)
        }

        $('#slug').keyup(function () {
            $(this).val(v.slugify($(this).val()))
        })

        $('#title').keyup(function () {
            $('#slug').val(v.slugify($(this).val()))
        })

    </script>

@endsection