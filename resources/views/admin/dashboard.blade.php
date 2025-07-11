@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
        <div class="text-gray-500">Total Orders</div>
        <div class="text-2xl font-bold">2,500</div>
        <div class="text-green-500 text-sm">+20% this month</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
        <div class="text-gray-500">Total Users</div>
        <div class="text-2xl font-bold">3,145</div>
        <div class="text-red-500 text-sm">-15% this month</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
        <div class="text-gray-500">Total Providers</div>
        <div class="text-2xl font-bold">350</div>
        <div class="text-green-500 text-sm">+10% this month</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-start">
        <div class="text-gray-500">Revenue</div>
        <div class="text-2xl font-bold">₹1,20,000</div>
        <div class="text-green-500 text-sm">+8% this month</div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Orders Analytics</h3>
        </div>
        <canvas id="ordersChart" height="120"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
        <ul class="divide-y divide-gray-200">
            <li class="py-2 text-gray-700">New order placed by <span class="font-semibold">John Doe</span> <span class="text-xs text-gray-400">10 min ago</span></li>
            <li class="py-2 text-gray-700">Provider <span class="font-semibold">HomeChef</span> approved <span class="text-xs text-gray-400">30 min ago</span></li>
            <li class="py-2 text-gray-700">User <span class="font-semibold">Jane Smith</span> registered <span class="text-xs text-gray-400">1 hr ago</span></li>
            <li class="py-2 text-gray-700">Order #1234 marked as delivered <span class="text-xs text-gray-400">2 hrs ago</span></li>
        </ul>
    </div>
</div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Orders',
                    data: [120, 190, 300, 250, 220, 310, 400],
                    backgroundColor: 'rgba(37, 99, 235, 0.7)',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 50 }
                    }
                }
            }
        });
    });
</script>
@endsection 