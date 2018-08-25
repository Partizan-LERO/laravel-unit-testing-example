@extends('layouts.main')
@section('content')
    <h2 class="mt-3 mb-3">Items List</h2>
    @foreach($items as $item)
        <div class="row">
            <div class="col-md-10">
                <h4>Name: <a href="{{route('show-item', $item->id)}}">{{$item->name}}</a></h4>
                <p>Key: {{$item->key}}</p>
            </div>

            <div class="col-md-2">
                <a class="btn btn-primary pull-left mr-1" href="{{ route('edit-item', $item->id) }}"><i class="fa fa-pencil-square-o edit" aria-hidden="true"></i></a>
                <form action="{{ route('delete-item', $item->id) }}" method="post">
                    <input type="hidden" name="_method" value="delete" />
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash delete" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>

    @endforeach
@endsection
