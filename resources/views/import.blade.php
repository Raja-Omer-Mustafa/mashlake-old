@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="dashboard-page">

       <h1 class="title-bar">{{__('Import Candidate')}}</h1>
    </div>
    <br>
    @include('admin.message')
   <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                    <form method="post" action="{{ route('import') }}" class="" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="custom-file mb-3">
                            <input type="file" name="file" class="custom-file-input" required>
                            <label class="custom-file-label" for="customFile">{{__("Choose file")}}</label>
                        </div>
                        <button  class="btn-info btn btn-icon dungdt-apply-form-btn" type="submit">{{__('Import')}}</button>
                    </form>
            </div>
        </div>

    
</div>
@endsection

@section('script.body')
<script src="{{url('libs/chart_js/Chart.min.js')}}"></script>
<script src="{{url('libs/daterange/moment.min.js')}}"></script>
<script src="{{url('libs/daterange/daterangepicker.min.js?_ver='.config('app.version'))}}"></script>
<link rel="stylesheet" href="{{url('libs/daterange/daterangepicker.css')}}" />
<script>
    var ctx = document.getElementById('earning_chart').getContext('2d');
        window.myMixedChart = new Chart(ctx, {
            type: 'bar',
            data: earning_chart_data,
            options: {
                responsive: true,
                tooltips: {
                    mode: 'index',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        stacked: true,
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '{{__("Timeline")}}'
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '{{__("Currency: :currency_main",['currency_main'=>setting_item('currency_main')])}}'
                        },
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += tooltipItem.yLabel + " ({{setting_item('currency_main')}})";
                            return label;
                        }
                    }
                }
            }
        });

        var start = moment().startOf('week');
        var end = moment();
        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            "alwaysShowCalendars": true,
            "opens": "left",
            "showDropdowns": true,
            ranges: {
                '{{__("Today")}}': [moment(), moment()],
                '{{__("Yesterday")}}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '{{__("Last 7 Days")}}': [moment().subtract(6, 'days'), moment()],
                '{{__("Last 30 Days")}}': [moment().subtract(29, 'days'), moment()],
                '{{__("This Month")}}': [moment().startOf('month'), moment().endOf('month')],
                '{{__("Last Month")}}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                '{{__("This Year")}}': [moment().startOf('year'), moment().endOf('year')],
                '{{__('This Week')}}': [moment().startOf('week'), end]
            }
        }, cb).on('apply.daterangepicker', function (ev, picker) {
            // Reload Earning JS
            $.ajax({
                url: '{{url('admin/module/dashboard/reloadChart')}}',
                data: {
                    chart: 'earning',
                    from: picker.startDate.format('YYYY-MM-DD'),
                    to: picker.endDate.format('YYYY-MM-DD'),
                },
                dataType: 'json',
                type: 'post',
                success: function (res) {
                    if (res.status) {
                        window.myMixedChart.data = res.data;
                        window.myMixedChart.update();
                    }
                }
            })
        });
        cb(start, end);
</script>
@endsection
