@extends('layouts.app')

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
    <link href="{{ asset('lib/mortgage-switching.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/mortgage-switching.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/highcharts.3.0.1.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // on the first click inside the calculator position the screen to the top of the calculator
            $("#calculator-container").click(function() {
                $('html,body').animate({scrollTop: $("#calculator-container").offset().top});
                $(this).unbind('click');
            });

            // GA Tracking - will ultimately be moved to GTM
            var calcName = 'Mortgage switching';
            $('#calculator-container select').live('change', function(event) {
                _gaq.push(['_trackEvent', 'Calculator - ' + calcName, 'Select - ' + $(this).parents('p').children('label').text(), $(this).children("option:selected").html(), 0]);
            });
            $('#calculator-container input').live('blur', function(event) {
                _gaq.push(['_trackEvent', 'Calculator - ' + calcName, 'Input - ' + $(this).parents('p').children('label').text(), $(this).val(), 0]);
            });
            //track expanding explore scenario
            $('#calculator-container .explore h4').live('click', function(event) {
                _gaq.push(['_trackEvent', 'Calculator - '+calcName, 'Explore scenario', $(this).text(), 0]);
            });

        });
    </script>
@endsection