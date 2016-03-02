@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ route('bank.create') }}" class="btn btn-success">Add New</a>
                </div>

                <div class="panel-body">

                    @if( count($banks) > 0 )
                        <table class="table table-bordered table-stripped">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Interest Rate</th>
                                <th>Comparison Rate</th>
                                <th>Annual Fees</th>
                                <th>Monthly Fees</th>
                                <th>Special</th>
                                <th>Actions</th>
                            </tr>
                            @foreach($banks as $bank)
                                <tr>
                                    <td>{{ $bank->id }}</td>
                                    <td>{{ $bank->name }}</td>
                                    <td>{{ $bank->interest_rate }}</td>
                                    <td>{{ $bank->comparison_rate }}</td>
                                    <td>{{ $bank->annual_fees }}</td>
                                    <td>{{ $bank->monthly_fees }}</td>
                                    <td>{{ $bank->special }}</td>
                                    <td>
                                        {!! Form::open(['route' => ['bank.destroy', $bank->id], 'method' => 'delete']) !!}
                                        <div class="btn-group">
                                            <a href="{{ route('bank.edit', [$bank->id]) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                            <button type="submit" class="btn btn-danger btn-sm form-delete"><i class="fa fa-trash"></i></button>
                                        </div>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="clearfix"></div>
                        {!! $banks->render() !!}
                    @else
                        <p>Don't have any banks</p>
                    @endif
                </div>
        </div>
    </div>
</div>
@endsection
