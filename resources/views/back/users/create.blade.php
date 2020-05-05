@extends('back.layout')

@section('css')

@endsection

@section('main')

    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            @if (session('user-updated'))
                @component('back.components.alert')
                    @slot('type')
                        success
                    @endslot
                    {!! session('user-updated') !!}
                @endcomponent
            @endif
            <!-- general form elements -->
            <div class="box box-primary">
                <!-- form start -->
                <form role="form" method="POST" action="{{ route('users.store') }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">@lang('Name')</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            {!! $errors->first('name', '<small class="help-block">:message</small>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                            <label for="email">@lang('Email')</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            {!! $errors->first('email', '<small class="help-block">:message</small>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
                            <label for="password">@lang('Password')</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            {!! $errors->first('password', '<small class="help-block">:message</small>') !!}
                        </div>
                        <div class="form-group">
                            <label for="role">@lang('Role')</label>
                            <select class="form-control" name="role" id="role">
                                <option value="admin">@lang('Administrator')</option>
                                <option value="user">@lang('User')</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
    <!-- /.row -->
@endsection

