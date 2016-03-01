@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ route('user.index') }}" class="btn btn-success">Return</a>
                </div>

                <div class="panel-body">
                    @if( isset($user) )
                        {!! Form::open(['route' => ['user.update', $user->id], 'method' => 'PUT']) !!}
                        {!! Form::hidden('id', $user->id) !!}
                    @else
                        {!! Form::open(['route' => 'user.store', 'method' => 'post']) !!}
                    @endif

                    <div class="form-group">
                        <label>Name</label>
                        {!!Form::text('name', isset($user) ? $user->name : old('name'), ['class' => 'form-control', 'placeholder' => '...'] ) !!}
                        {!! $errors->first('name', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        {!!Form::text('email', isset($user) ? $user->email : old('email'), ['class' => 'form-control', 'placeholder' => '...'] ) !!}
                        {!! $errors->first('email', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        {!!Form::text('phone', isset($user) ? $user->phone : old('phone'), ['class' => 'form-control', 'placeholder' => '...'] ) !!}
                        {!! $errors->first('phone', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Loan Amount</label>
                        {!!Form::text('loan_amount', isset($user) ? $user->loan_amount : old('loan_amount'), ['class' => 'form-control', 'placeholder' => '...'] ) !!}
                        {!! $errors->first('loan_amount', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Interest Rate</label>
                        {!!Form::text('interest_rate', isset($user) ? $user->interest_rate : old('interest_rate'), ['class' => 'form-control', 'placeholder' => '5.5'] ) !!}
                        {!! $errors->first('interest_rate', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Loan Term</label>
                        {!!Form::text('loan_term', isset($user) ? $user->loan_term : old('loan_term'), ['class' => 'form-control', 'placeholder' => '30'] ) !!}
                        {!! $errors->first('loan_term', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <a href="{{route('user.index')}}" class="btn btn-warning">Cancel</a>
                        @if(isset($user))
                            {!! Form::submit('Update', ['class' => 'btn btn-success', 'name' => 'update']) !!}
                        @else
                            {!! Form::submit('Create', ['class' => 'btn btn-primary', 'name' => 'create']) !!}
                        @endif
                    </div>
                    {!! Form::close() !!}

                </div>
        </div>
    </div>
</div>
@endsection
