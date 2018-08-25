@extends('layouts.main')
@section('content')
    <h2>{{$item->name}}</h2>
    <div class="row">
        <div class="col-md-8">
            <p>Key: {{$item->key}}</p>
        </div>

        <div class="col-md-4">
            <div class="small text-success">Created at: {{ $item->created_at }}</div>
            <div class="small text-danger">Updated at: {{$item->updated_at }}</div>
        </div>

    </div>
@endsection
