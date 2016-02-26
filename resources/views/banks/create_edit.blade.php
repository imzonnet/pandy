@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ route('bank.index') }}" class="btn btn-success">Return</a>
                </div>

                <div class="panel-body">
                    @if( isset($bank) )
                        {!! Form::open(['route' => ['bank.update', $bank->id], 'method' => 'PUT']) !!}
                        {!! Form::hidden('id', $bank->id) !!}
                    @else
                        {!! Form::open(['route' => 'bank.store', 'method' => 'post']) !!}
                    @endif

                    <div class="form-group">
                        <label>Name</label>
                        {!!Form::text('name', isset($bank) ? $bank->name : old('name'), ['class' => 'form-control', 'placeholder' => 'Bank Name'] ) !!}
                        {!! $errors->first('name', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Interest Rate</label>
                        {!!Form::text('interest_rate', isset($bank) ? $bank->interest_rate : old('interest_rate'), ['class' => 'form-control', 'placeholder' => '5.5'] ) !!}
                        {!! $errors->first('interest_rate', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Comparison Rate</label>
                        {!!Form::text('comparison_rate', isset($bank) ? $bank->comparison_rate : old('comparison_rate'), ['class' => 'form-control', 'placeholder' => '4.5'] ) !!}
                        {!! $errors->first('comparison_rate', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Annual Fees</label>
                        {!!Form::text('annual_fees', isset($bank) ? $bank->annual_fees : old('annual_fees'), ['class' => 'form-control', 'placeholder' => '100000'] ) !!}
                        {!! $errors->first('annual_fees', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Monthly Fees</label>
                        {!!Form::text('monthly_fees', isset($bank) ? $bank->monthly_fees : old('monthly_fees'), ['class' => 'form-control', 'placeholder' => '2000'] ) !!}
                        {!! $errors->first('monthly_fees', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Special</label>
                        {!!Form::text('special', isset($bank) ? $bank->special : old('special'), ['class' => 'form-control', 'placeholder' => 'N/A'] ) !!}
                        {!! $errors->first('title', '<span class="help-block error">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <a href="{{route('bank.index')}}" class="btn btn-warning">Cancel</a>
                        @if(isset($bank))
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
