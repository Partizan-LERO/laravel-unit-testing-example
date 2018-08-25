@extends('layouts.main')
@section('content')
    <h2 class="mt-3 mb-3">Create new item</h2>
    <form action="{{ route('store-item') }}" method="post">
        {{csrf_field()}}
        <div class="form-group row">
            <label for="name" class="col-sm-1">Name</label>
            <div class="col-sm-7">
                <textarea class="form-control @if( $errors->has('name')) is-invalid @endif"
                          name="name"
                          id=""
                          cols="30"
                          rows="10">
                </textarea>
            </div>

            <div class="col-sm-3">
                <small class="text-danger">
                    @if( $errors->has('name'))
                       {{ $errors->first('name') }}
                    @endif
                </small>
            </div>
        </div>
        <div class="form-group row">
            <label for="key" class="col-sm-1">Key</label>
            <div class="col-sm-7">
                <input type="text" required name="key" class="form-control @if( $errors->has('key')) is-invalid @endif">
            </div>
            <div class="col-sm-3">
                <small class="text-danger">
                    @if( $errors->has('key'))
                        {{ $errors->first('key') }}
                    @endif
                </small>
            </div>
        </div>
        <div class="form-group row">
            <div class="offset-sm-1 col-sm-3">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>

        </div>
    </form>
@endsection
