@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <a href="{{ route('user.create') }}" class="btn btn-success">Create</a>
                </div>

                <div class="panel-body">

                    @if( count($users) > 0 )
                        <table class="table table-bordered table-stripped">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        {!! Form::open(['route' => ['user.destroy', $user->id], 'method' => 'delete', 'class' => 'form-delete']) !!}
                                        <div class="btn-group">
                                            <a href="{{ route('user.edit', [$user->id]) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                        </div>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="clearfix"></div>
                        {!! $users->render() !!}
                    @else
                        <p>Don't have any users</p>
                    @endif
                </div>
        </div>
    </div>
</div>
@endsection
