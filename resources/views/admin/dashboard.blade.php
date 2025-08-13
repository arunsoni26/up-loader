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
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Sales</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="truck"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">2.382</h1>
                                <div class="mb-0">
                                    <span class="badge badge-primary-light">-3.65%</span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Visitors</h5>
                                    </div>
                                    
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="users"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">14.212</h1>
                                <div class="mb-0">
                                    <span class="badge badge-success-light">5.25%</span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Earnings</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">$21.300</h1>
                                <div class="mb-0">
                                    <span class="badge badge-success-light">6.65%</span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Orders</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="shopping-cart"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">64</h1>
                                <div class="mb-0">
                                    <span class="badge badge-danger-light">-2.25%</span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
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
</script>
@endpush