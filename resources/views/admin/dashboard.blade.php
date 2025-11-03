@extends('layouts.admin-header')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-4 rounded-2xl shadow">
            <p>Total Orders</p>
            <h3 class="text-xl font-bold">{{ $totalOrders }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow">
            <p>Active Orders</p>
            <h3 class="text-xl font-bold">{{ $activeOrders }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow">
            <p>Completed Orders</p>
            <h3 class="text-xl font-bold">{{ $completedOrders }}</h3>
        </div>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow mb-6">
        <h4 class="font-semibold mb-2">Sale Graph</h4>
        <canvas id="salesChart" height="150"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! $labels !!},
        datasets: [{
            label: 'Total Penjualan',
            data: {!! $dataPoints !!},
            borderColor: '#2563EB',
            tension: 0.4
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
</script>
@endsection
