@extends('layouts.admin')

@section('content')
<div class="main-content-wrap">
    <!-- Summary Section -->
    <div class="wg-box mb-30">
        <div class="wg-box-header">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="mb-0" style="color: var(--admin-text); font-weight: 600;">System Overview</h4>
                <div class="text-muted small">Last updated: {{ now()->format('M d, Y H:i') }}</div>
            </div>
        </div>
        <div class="wg-box-body">
            <div class="stats-grid">
                <div class="stat-item products">
                    <div class="stat-icon">
                                <i class="icon-shopping-bag"></i>
                            </div>
                    <div class="stat-number">{{ \App\Models\Product::count() }}</div>
                    <div class="stat-label">Products</div>
                </div>
                <div class="stat-item categories">
                    <div class="stat-icon">
                        <i class="icon-layers"></i>
                            </div>
                    <div class="stat-number">{{ \App\Models\Category::count() }}</div>
                    <div class="stat-label">Categories</div>
                        </div>
                <div class="stat-item brands">
                    <div class="stat-icon">
                        <i class="icon-award"></i>
                    </div>
                    <div class="stat-number">{{ \App\Models\Brand::count() }}</div>
                    <div class="stat-label">Brands</div>
                </div>
                <div class="stat-item users">
                    <div class="stat-icon">
                        <i class="icon-users"></i>
                            </div>
                    <div class="stat-number">{{ \App\Models\User::count() }}</div>
                    <div class="stat-label">Users</div>
                            </div>
                        </div>
                    </div>
                </div>

    <!-- Sales Performance Graph Section -->
    <div class="wg-box mb-30">
        <div class="wg-box-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0" style="color: var(--admin-text); font-weight: 600;">Sales Performance</h5>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="icon-more"><i class="icon-more-horizontal"></i></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a href="javascript:void(0);">This Week</a></li>
                        <li><a href="javascript:void(0);">Last Week</a></li>
                        <li><a href="javascript:void(0);">This Month</a></li>
                    </ul>
                            </div>
                        </div>
                    </div>
        <div class="wg-box-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="d-flex align-items-center p-3" style="background: var(--admin-bg-secondary); border-radius: 8px;">
                        <div class="me-3" style="width: 50px; height: 50px; background: var(--admin-success); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="icon-dollar-sign"></i>
                            </div>
                            <div>
                            <div class="text-muted mb-1">Total Sales</div>
                            <h4 class="mb-0" style="color: var(--admin-text);">${{ number_format(\App\Models\Order::where('status', 'delivered')->sum('total'), 2) }}</h4>
                            </div>
                        </div>
                    </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center p-3" style="background: var(--admin-bg-secondary); border-radius: 8px;">
                        <div class="me-3" style="width: 50px; height: 50px; background: var(--admin-info); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="icon-shopping-bag"></i>
                            </div>
                            <div>
                            <div class="text-muted mb-1">Total Orders</div>
                            <h4 class="mb-0" style="color: var(--admin-text);">{{ \App\Models\Order::count() }}</h4>
                            </div>
                        </div>
                    </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center p-3" style="background: var(--admin-bg-secondary); border-radius: 8px;">
                        <div class="me-3" style="width: 50px; height: 50px; background: var(--admin-warning); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="icon-clock"></i>
                            </div>
                            <div>
                            <div class="text-muted mb-1">Pending</div>
                            <h4 class="mb-0" style="color: var(--admin-text);">{{ \App\Models\Order::where('status', 'ordered')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center p-3" style="background: var(--admin-bg-secondary); border-radius: 8px;">
                        <div class="me-3" style="width: 50px; height: 50px; background: var(--admin-accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="icon-trending-up"></i>
                            </div>
                            <div>
                            <div class="text-muted mb-1">Growth</div>
                            <h4 class="mb-0" style="color: var(--admin-text);">+12.5%</h4>
                            </div>
                        </div>
                    </div>
                </div>
            <div id="sales-chart" style="height: 400px; width: 100%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;" class="chart-loading">
                <div style="text-align: center; color: #64748b;">
                    <div style="width: 60px; height: 60px; border: 4px solid #e2e8f0; border-top: 4px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                    <p style="margin: 0; font-weight: 500;">Loading Sales Performance Chart...</p>
                    <button id="test-chart-btn" class="btn btn-primary btn-sm mt-3" onclick="testChart()" style="display: none;">Test Chart</button>
                            </div>
                        </div>
                    </div>
                </div>

    <!-- Recent Orders Section -->
        <div class="wg-box">
        <div class="wg-box-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0" style="color: var(--admin-text); font-weight: 600;">Recent Orders</h5>
                <a href="{{ route('admin.orders') }}" class="btn btn-primary btn-sm">View All</a>
                </div>
            </div>
        <div class="wg-box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                            <th style="width: 80px">Order No</th>
                                <th>Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Tax</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center">Total Items</th>
                                <th class="text-center">Delivered On</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @php($recentOrders = \App\Models\Order::latest()->limit(10)->get())
                            @forelse($recentOrders as $order)
                            <tr>
                                <td class="text-center">{{ '1' . str_pad($order->id,4,'0',STR_PAD_LEFT) }}</td>
                                <td class="text-center">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $order->shippingAddress->phone ?? 'N/A' }}</td>
                                <td class="text-center">${{ number_format($order->subtotal,2) }}</td>
                                <td class="text-center">${{ number_format($order->tax,2) }}</td>
                                <td class="text-center">${{ number_format($order->total,2) }}</td>
                            <td class="text-center">
                                @if($order->status == 'ordered')
                                    <span class="badge" style="background: var(--admin-warning); color: white; padding: 0.5rem 0.75rem; border-radius: 6px;">Pending</span>
                                @elseif($order->status == 'delivered')
                                    <span class="badge" style="background: #000; color: #fff; font-weight: bold; padding: 0.5rem 0.75rem; border-radius: 6px;">Delivered</span>
                                @elseif($order->status == 'canceled')
                                    <span class="badge" style="background: var(--admin-danger); color: white; padding: 0.5rem 0.75rem; border-radius: 6px;">Canceled</span>
                                @else
                                    <span class="badge" style="background: var(--admin-info); color: white; padding: 0.5rem 0.75rem; border-radius: 6px;">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="text-center">{{ $order->items->count() }}</td>
                            <td class="text-center">{{ $order->delivered_date ? \Carbon\Carbon::parse($order->delivered_date)->format('M d, Y') : 'N/A' }}</td>
                                <td class="text-center">
                                <a href="{{ route('admin.order.details', ['id' => $order->id]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="icon-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="icon-shopping-bag" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p class="mt-2 mb-0">No recent orders</p>
                                </div>
                            </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

<script>
// Optimized chart initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM loaded, initializing chart...");
    
    // Try to initialize chart immediately
    if (typeof ApexCharts !== 'undefined') {
        initializeChart();
    } else {
        // Wait for ApexCharts to load
        waitForApexCharts();
    }
});

function waitForApexCharts() {
    let attempts = 0;
    const maxAttempts = 10;
    
    const checkInterval = setInterval(function() {
        attempts++;
        console.log(`Checking for ApexCharts... Attempt ${attempts}`);
        
        if (typeof ApexCharts !== 'undefined') {
            clearInterval(checkInterval);
            console.log("ApexCharts found, initializing chart...");
            initializeChart();
        } else if (attempts >= maxAttempts) {
            clearInterval(checkInterval);
            console.error("ApexCharts not found after maximum attempts");
            showChartError("ApexCharts library failed to load. Please refresh the page.");
        }
    }, 500);
}

function initializeChart() {
    console.log("Initializing chart...");
    
    var chartContainer = document.getElementById('sales-chart');
    if (!chartContainer) {
        console.error("Chart container not found!");
        return;
    }
    
    // Clear the loading message
    chartContainer.innerHTML = '';
    
    var options = {
        series: [{
            name: 'Sales Revenue',
            data: [0, 0, 0, 0, 0, 273.22, 208.12, 150.50, 89.75, 120.30, 95.60, 180.25]
        }, {
            name: 'Orders Count',
            data: [0, 0, 0, 0, 0, 8, 6, 4, 3, 5, 4, 7]
        }],
        chart: {
            type: 'line',
            height: 400,
            toolbar: {
                show: false,
            },
            fontFamily: 'Inter, sans-serif',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        plotOptions: {
            line: {
                curve: 'smooth',
                lineWidth: 3,
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'left',
            fontSize: '12px',
            colors: ['#3b82f6', '#10b981'],
            markers: {
                width: 12,
                height: 12,
                radius: 6
            }
        },
        colors: ['#3b82f6', '#10b981'],
        stroke: {
            curve: 'smooth',
            width: [3, 3],
            dashArray: [0, 0]
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.1,
                gradientToColors: ['#3b82f6', '#10b981'],
                inverseColors: false,
                opacityFrom: 0.8,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        markers: {
            size: [6, 6],
            colors: ['#3b82f6', '#10b981'],
            strokeColors: '#ffffff',
            strokeWidth: 2,
            hover: {
                size: 8
            }
        },
        xaxis: {
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '12px',
                },
            },
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            axisBorder: {
                show: true,
                color: '#e2e8f0',
                height: 1,
                width: '100%',
                offsetX: 0,
                offsetY: 0
            },
            axisTicks: {
                show: true,
                borderType: 'solid',
                color: '#e2e8f0',
                height: 6,
                offsetX: 0,
                offsetY: 0
            }
        },
        yaxis: [{
            title: {
                text: 'Revenue ($)',
                style: {
                    color: '#64748b',
                    fontSize: '12px',
                    fontWeight: 600
                }
            },
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '12px',
                },
                formatter: function (val) {
                    return "$" + val.toFixed(2);
                }
            },
            axisBorder: {
                show: true,
                color: '#e2e8f0',
                width: 1,
                offsetX: 0,
                offsetY: 0
            }
        }, {
            opposite: true,
            title: {
                text: 'Orders Count',
                style: {
                    color: '#64748b',
                    fontSize: '12px',
                    fontWeight: 600
                }
            },
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '12px',
                }
            },
            axisBorder: {
                show: true,
                color: '#e2e8f0',
                width: 1,
                offsetX: 0,
                offsetY: 0
            }
        }],
        tooltip: {
            shared: true,
            intersect: false,
            y: [{
                formatter: function (val) {
                    return "$" + val.toFixed(2);
                }
            }, {
                formatter: function (val) {
                    return val + " orders";
                }
            }],
            style: {
                fontSize: '12px'
            }
        },
        grid: {
            borderColor: '#e2e8f0',
            strokeDashArray: 5,
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        theme: {
            mode: 'light'
        }
    };

    try {
        console.log("Creating ApexCharts instance...");
        var chart = new ApexCharts(chartContainer, options);
        chart.render();
        console.log("Chart rendered successfully!");
        
        // Update container to show success
        chartContainer.classList.remove('chart-loading');
        chartContainer.classList.add('chart-success');
        chartContainer.style.border = '2px solid #10b981';
        chartContainer.style.background = '#f0fdf4';
        
        // Remove test button if it exists
        const testBtn = document.getElementById('test-chart-btn');
        if (testBtn) {
            testBtn.style.display = 'none';
        }
    } catch (error) {
        console.error("Error rendering chart:", error);
        chartContainer.classList.remove('chart-loading');
        chartContainer.classList.add('chart-error');
        showChartError("Chart Error: " + error.message);
    }
}

function showChartError(message) {
    var chartContainer = document.getElementById('sales-chart');
    if (chartContainer) {
        chartContainer.classList.remove('chart-loading');
        chartContainer.classList.add('chart-error');
        chartContainer.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #ef4444; font-size: 16px; text-align: center;"><i class="icon-alert" style="margin-right: 10px; font-size: 2rem;"></i><div><strong>Chart Error!</strong><br>' + message + '<br><button onclick="location.reload()" class="btn btn-primary btn-sm mt-2">Refresh Page</button></div></div>';
    }
}

// Test function for manual chart testing
window.testChart = function() {
    console.log("Manual chart test triggered");
    if (typeof ApexCharts !== 'undefined') {
        initializeChart();
    } else {
        alert("ApexCharts is not loaded! Please refresh the page.");
    }
};

// Window load event as backup
window.addEventListener('load', function() {
    if (typeof ApexCharts !== 'undefined' && !document.querySelector('#sales-chart svg')) {
        console.log("Window loaded, initializing chart...");
        setTimeout(initializeChart, 100);
    }
});
</script>
@endsection