@extends('layouts.admin-app')

@section('content')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3>Dashboard</h3>
    </div>

    <!-- <div class="col-auto ms-auto text-end mt-n1">
            <a href="#" class="btn btn-light bg-white me-2">Invite a Friend</a>
            <a href="#" class="btn btn-primary">New Project</a>
        </div> -->
</div>
<div class="row">
    <div class="col-xl-12 col-xxl-12 d-flex">
        <div class="w-100">
            <div class="row">
                @if(in_array(auth()->user()->role->slug, ['superadmin', 'admin']))
                <!-- active customers -->
                <div class="col-sm-3">
                    <div class="card zoom-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Active Customers</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-success">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $customers->where('status', 1)->count() }}</h1>
                            <div class="mb-0">
                                <a class="w-100 btn btn-sm btn-primary" href="{{ route('admin.customers.index') }}">View <i class="fas fa-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- inactive customers -->
                <div class="col-sm-3">
                    <div class="card zoom-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">In-active Customers</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-danger">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $customers->where('status', 0)->count() }}</h1>
                            <div class="mb-0">
                                <a class="w-100 btn btn-sm btn-primary" href="{{ route('admin.customers.index') }}">View <i class="fas fa-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-sm-3">
                    <div class="card zoom-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Customer Active Docs</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="file"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $customerActiveDocs->count() }}</h1>
                            <div class="mb-0">
                                @php
                                $route = route('admin.customers.index');
                                if (auth()->user()->role->slug == 'customer') {
                                $route = url('admin/customers/'.auth()->user()->customer->id.'/docs');
                                }
                                @endphp
                                <a class="w-100 btn btn-sm btn-primary" href="{{ $route }}">View <i class="fas fa-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card zoom-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Customer Inactive Docs</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="file"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $customerInActiveDocs->count() }}</h1>
                            <div class="mb-0">
                                @php
                                $route = route('admin.customers.index');
                                if (auth()->user()->role->slug == 'customer') {
                                $route = url('admin/customers/'.auth()->user()->customer->id.'/docs');
                                }
                                @endphp
                                <a class="w-100 btn btn-sm btn-primary" href="{{ $route }}">View <i class="fas fa-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card zoom-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Customer Deleted Docs</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="file"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $customerDeleteDocs->count() }}</h1>
                            <div class="mb-0">
                                @php
                                $route = route('admin.customers.index');
                                if (auth()->user()->role->slug == 'customer') {
                                $route = url('admin/customers/'.auth()->user()->customer->id.'/docs');
                                }
                                @endphp
                                <a class="w-100 btn btn-sm btn-primary" href="{{ $route }}">View <i class="fas fa-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @if(in_array(auth()->user()->role->slug, ['superadmin']))
                <div class="col-sm-3">
                    <div class="card zoom-item">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Users</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $users->where('status', 1)->count() }}</h1>
                            <div class="mb-0">
                                <a class="w-100 btn btn-sm btn-primary" href="{{ route('admin.users.index') }}">View <i class="fas fa-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    @if(auth()->user()->role->slug !== 'customer')
        <div class="col-xl-6 col-xxl-6">
            <div class="card">
                <h5 style="padding-top:20px; text-align:center;">📈 Daily Customer Document Uploads</h5>
                <div class="card-header d-flex justify-content-between">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control me-2">
                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control me-2">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    </form>
                </div>
                <div class="card-body">
                    <canvas class="" id="documentsLineChart" height="120"></canvas>
                </div>
            </div>
        </div>
    @endif

    
    <div class="col-xl-6 col-xxl-6">
        <div class="card">
            <h5 style="padding-top:20px; text-align:center;">🍩 Upload Distribution</h5>
            <div class="card-body" style="height:400px;">
                <canvas class="" id="documentsDonutChart" width="400"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Prepare labels and data
        const dates = @json($documentsPerDay -> pluck('date'));
        const totals = @json($documentsPerDay -> pluck('total'));

        const donutData = totals.map(val => val === 0 ? 0.01 : val);
        
        @if(in_array(auth()->user()->role->slug, ['superadmin', 'admin']))
            // LINE CHART
            const ctxLine = document.getElementById('documentsLineChart').getContext('2d');

            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: '', // hide label for cleaner look
                        data: totals,
                        borderColor: '#3b8cff',   // blue line
                        backgroundColor: 'rgba(59, 140, 255, 0.1)', // light blue fill
                        borderWidth: 2,
                        tension: 0.4, // smooth curve
                        pointRadius: 0, // hide dots
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // no legend
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false // no vertical grid lines
                            },
                            ticks: {
                                maxTicksLimit: 6, // fewer labels for cleaner look
                                autoSkip: true
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: "rgba(0,0,0,0.05)", // very subtle horizontal grid
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 5 // adjust depending on values
                            }
                        }
                    }
                }
            });
        @endif

        // Generate dynamic colors for donut chart
        const colors = totals.map(() =>
            `rgba(${Math.floor(Math.random()*256)}, ${Math.floor(Math.random()*256)}, ${Math.floor(Math.random()*256)}, 0.6)`
        );

      
         const ctxDonut = document.getElementById('documentsDonutChart').getContext('2d');

        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Active Docs', 'Inactive Docs', 'Deleted Docs'],
                datasets: [{
                    label: 'Documents',
                    data: [{{ $activeCount }}, {{ $inactiveCount }}, {{ $deletedCount }}],
                    backgroundColor: ['#5b996aff', '#b49538ff', '#a15159ff'], // green, yellow, red
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endpush

<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradientLight = ctx.createLinearGradient(0, 0, 0, 225);
        gradientLight.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradientLight.addColorStop(1, "rgba(215, 227, 244, 0)");
        var gradientDark = ctx.createLinearGradient(0, 0, 0, 225);
        gradientDark.addColorStop(0, "rgba(51, 66, 84, 1)");
        gradientDark.addColorStop(1, "rgba(51, 66, 84, 0)");
        // Line chart
        new Chart(document.getElementById("chartjs-dashboard-line"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Sales ($)",
                    fill: true,
                    backgroundColor: window.theme.id === "light" ? gradientLight : gradientDark,
                    borderColor: window.theme.primary,
                    data: [
                        2115,
                        1562,
                        1584,
                        1892,
                        1587,
                        1923,
                        2566,
                        2448,
                        2805,
                        3438,
                        2917,
                        3327
                    ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: true,
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 1000
                        },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)",
                            fontColor: "#fff"
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pie chart
        new Chart(document.getElementById("chartjs-dashboard-pie"), {
            type: "pie",
            data: {
                labels: ["Chrome", "Firefox", "IE", "Other"],
                datasets: [{
                    data: [4306, 3801, 1689, 3251],
                    backgroundColor: [
                        window.theme.primary,
                        window.theme.warning,
                        window.theme.danger,
                        "#E8EAED"
                    ],
                    borderWidth: 5,
                    borderColor: window.theme.white
                }]
            },
            options: {
                responsive: !window.MSInputMethodContext,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                cutoutPercentage: 70
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Bar chart
        new Chart(document.getElementById("chartjs-dashboard-bar"), {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "This year",
                    backgroundColor: window.theme.primary,
                    borderColor: window.theme.primary,
                    hoverBackgroundColor: window.theme.primary,
                    hoverBorderColor: window.theme.primary,
                    data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
                    barPercentage: .75,
                    categoryPercentage: .5
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false
                        },
                        stacked: false,
                        ticks: {
                            stepSize: 20
                        }
                    }],
                    xAxes: [{
                        stacked: false,
                        gridLines: {
                            color: "transparent"
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var markers = [{
                coords: [31.230391, 121.473701],
                name: "Shanghai"
            },
            {
                coords: [28.704060, 77.102493],
                name: "Delhi"
            },
            {
                coords: [6.524379, 3.379206],
                name: "Lagos"
            },
            {
                coords: [35.689487, 139.691711],
                name: "Tokyo"
            },
            {
                coords: [23.129110, 113.264381],
                name: "Guangzhou"
            },
            {
                coords: [40.7127837, -74.0059413],
                name: "New York"
            },
            {
                coords: [34.052235, -118.243683],
                name: "Los Angeles"
            },
            {
                coords: [41.878113, -87.629799],
                name: "Chicago"
            },
            {
                coords: [51.507351, -0.127758],
                name: "London"
            },
            {
                coords: [40.416775, -3.703790],
                name: "Madrid "
            }
        ];
        var map = new jsVectorMap({
            map: "world",
            selector: "#world_map",
            zoomButtons: true,
            markers: markers,
            markerStyle: {
                initial: {
                    r: 9,
                    stroke: window.theme.white,
                    strokeWidth: 7,
                    stokeOpacity: .4,
                    fill: window.theme.primary
                },
                hover: {
                    fill: window.theme.primary,
                    stroke: window.theme.primary
                }
            },
            regionStyle: {
                initial: {
                    fill: window.theme["gray-200"]
                }
            },
            zoomOnScroll: false
        });
        window.addEventListener("resize", () => {
            map.updateSize();
        });
        setTimeout(function() {
            map.updateSize();
        }, 250);
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
        var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
        document.getElementById("datetimepicker-dashboard").flatpickr({
            inline: true,
            prevArrow: "<span class=\"fas fa-chevron-left\" title=\"Previous month\"></span>",
            nextArrow: "<span class=\"fas fa-chevron-right\" title=\"Next month\"></span>",
            defaultDate: defaultDate
        });
    });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function(event) { 
    setTimeout(function(){
      if(localStorage.getItem('popState') !== 'shown'){
        window.notyf.open({
          type: "success",
          message: "Get access to all 500+ components and 45+ pages with AdminKit PRO. <u><a class=\"text-white\" href=\"https://adminkit.io/pricing\" target=\"_blank\">More info</a></u> 🚀",
          duration: 10000,
          ripple: true,
          dismissible: false,
          position: {
            x: "left",
            y: "bottom"
          }
        });

        localStorage.setItem('popState','shown');
      }
    }, 15000);
  });
</script> -->