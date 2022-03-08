@extends('layouts.master')

@section('title') {{ l('Chart - All Vouchers') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <!-- a href="{{ URL::to('countries/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a -->
    </div>
    <h2>
        {{ l('All Vouchers') }}
    </h2>        
</div>




    <br>
    <div class="container">
        <!-- Area Chart Example-->
        <div class="card mb-3">
            <!-- div class="card-header">
                <i class="fa fa-area-chart"></i> Blog Posting Trend </div -->
            <div class="card-body">
                <canvas id="myAreaChart" width="100%" height="30"></canvas>
            </div>
            <div class="card-footer small text-muted">{{ l('Updated at :when', ['when' => abi_date_short( \Carbon\Carbon::now() )]) }}</div>
        </div>
    </div>


















<div id="div_countries">
   <div class="table-responsive">

@if (1)

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection



@section('scripts')     @parent

<script src="{{ asset('vendor/Chart/js/Chart.min.js') }}"></script>

<!-- script src="{{ asset('vendor/Chart/js/utils.js') }}"></script -->
<script>

    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };

</script>

<script type="text/javascript">

$(document).ready(function() {
   //

var charts = {
        init: function () {
            // -- Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#292b2c';

            this.ajaxGetPostMonthlyData();

        },

        ajaxGetPostMonthlyData: function () {
            var urlPath =  "{{ route( 'chart.allvouchers.monthly.data' ) }}";
            var request = $.ajax( {
                method: 'GET',
                url: urlPath
        } );

            request.done( function ( response ) {
                console.log( response );
                charts.createCompletedJobsChart( response );
            });
        },

        /**
         * Created the Completed Jobs Chart
         */
        createCompletedJobsChart: function ( response ) {

            var ctx = $("#myAreaChart");
            var myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: response.months, // The response got from the ajax request containing all month names in the database
                    datasets: [{
                        label: "{{ l('All Vouchers') }}",
                        lineTension: 0.3,
                        backgroundColor: "rgba(2,117,216,0.2)",
                        borderColor: "rgba(2,117,216,1)",
                        pointRadius: 5,
                        pointBackgroundColor: "rgba(2,117,216,1)",
                        pointBorderColor: "rgba(255,255,255,0.8)",
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(2,117,216,1)",
                        pointHitRadius: 20,
                        pointBorderWidth: 2,
                        data: response.sales_data // The response got from the ajax request containing data for the completed jobs in the corresponding months
                    },
                    {
                        label: "{{ l('Pending Vouchers') }}",
                        lineTension: 0.3,
                        backgroundColor: window.chartColors.orange,
                        borderColor: window.chartColors.red,
                        pointRadius: 5,
                        pointBackgroundColor: "rgba(2,117,216,1)",
                        pointBorderColor: "rgba(255,255,255,0.8)",
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: window.chartColors.orange,
                        pointHitRadius: 20,
                        pointBorderWidth: 2,
                        data: response.pending_data // The response got from the ajax request containing data for the completed jobs in the corresponding months
                    }]
                },
                options: {
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: response.min,
                                max: response.max, // The response got from the ajax request containing max limit for y axis
                                maxTicksLimit: 5
                            },
                            gridLines: {
                                color: "rgba(0, 0, 0, .125)",
                            }
                        }],
                    },
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: false,
                        text: "{{ l('All Vouchers') }}"
                    }
                }
            });
        }
    };

charts.init();

});

</script>

@endsection



@section('styles')    @parent

  <!-- link rel="stylesheet" href="{{ asset('vendor/Chart/css/custom.css') }}"></script -->

<style>


</style>

@endsection
