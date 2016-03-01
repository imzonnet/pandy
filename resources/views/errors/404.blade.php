@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <p>Looks like the page you are trying to visit doesn’t exist. Please check the URL and try your luck again.</p>
                        <p><a href="<?php echo url('/'); ?>">Take Me Home</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
