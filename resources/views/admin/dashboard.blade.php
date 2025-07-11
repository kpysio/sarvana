@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-start">
        <div class="text-gray-500 dark:text-gray-400">Total Orders</div>
        <div class="text-2xl font-bold">2,500</div>
        <div class="text-green-500 text-sm">+20% this month</div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-start">
        <div class="text-gray-500 dark:text-gray-400">Total Users</div>
        <div class="text-2xl font-bold">3,145</div>
        <div class="text-red-500 text-sm">-15% this month</div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-start">
        <div class="text-gray-500 dark:text-gray-400">Total Providers</div>
        <div class="text-2xl font-bold">350</div>
        <div class="text-green-500 text-sm">+10% this month</div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-start">
        <div class="text-gray-500 dark:text-gray-400">Revenue</div>
        <div class="text-2xl font-bold">₹1,20,000</div>
        <div class="text-green-500 text-sm">+8% this month</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Orders Analytics Bar Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Orders Analytics</h3>
        </div>
        <canvas id="ordersChart" height="120"></canvas>
    </div>
    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Recent Activity</h3>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            <li class="py-2 text-gray-700 dark:text-gray-200">New order placed by <span class="font-semibold">John Doe</span> <span class="text-xs text-gray-400">10 min ago</span></li>
            <li class="py-2 text-gray-700 dark:text-gray-200">Provider <span class="font-semibold">HomeChef</span> approved <span class="text-xs text-gray-400">30 min ago</span></li>
            <li class="py-2 text-gray-700 dark:text-gray-200">User <span class="font-semibold">Jane Smith</span> registered <span class="text-xs text-gray-400">1 hr ago</span></li>
            <li class="py-2 text-gray-700 dark:text-gray-200">Order #1234 marked as delivered <span class="text-xs text-gray-400">2 hrs ago</span></li>
        </ul>
    </div>
</div>

<!-- More Chart Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Pie Chart: Order Status Distribution -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Order Status Distribution</h3>
        <canvas id="orderStatusChart" height="180"></canvas>
    </div>
    <!-- Line Chart: Revenue Trend -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Revenue Trend</h3>
        <canvas id="revenueChart" height="180"></canvas>
    </div>
    <!-- Doughnut Chart: User Types -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">User Types</h3>
        <canvas id="userTypeChart" height="180"></canvas>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Orders Bar Chart
    new Chart(document.getElementById('ordersChart').getContext('2d'), {
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
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 50 } } }
        }
    });

    // Pie Chart: Order Status
    new Chart(document.getElementById('orderStatusChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Pending', 'Completed', 'Cancelled'],
            datasets: [{
                data: [120, 300, 80],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)', // blue-500
                    'rgba(16, 185, 129, 0.7)', // green-500
                    'rgba(239, 68, 68, 0.7)'   // red-500
                ]
            }]
        },
        options: { responsive: true }
    });

    // Line Chart: Revenue Trend
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Revenue',
                data: [20000, 25000, 22000, 27000, 30000, 32000, 40000],
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: false } }
        }
    });

    // Doughnut Chart: User Types
    new Chart(document.getElementById('userTypeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Customers', 'Providers', 'Admins'],
            datasets: [{
                data: [2500, 350, 10],
                backgroundColor: [
                    'rgba(37, 99, 235, 0.7)', // blue-600
                    'rgba(245, 158, 11, 0.7)', // yellow-500
                    'rgba(16, 185, 129, 0.7)'  // green-500
                ]
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endsection 