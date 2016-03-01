@extends('layouts.app')
@section('title')
Pandy Mortgage Switching Calculator
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="calculator-container">&nbsp;</div>
            </div>
        </div>
    </div>
@endsection


@section('styles')
    <link href="{{ asset('libs/mortgage-switching.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('libs/highcharts.3.0.1.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/mortgage-switching.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // on the first click inside the calculator position the screen to the top of the calculator
            $("#calculator-container").click(function() {
                $('html,body').animate({scrollTop: $("#calculator-container").offset().top});
                $(this).unbind('click');
            });
        });
    </script>
@endsection