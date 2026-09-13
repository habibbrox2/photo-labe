<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue' => Order::where('status', 'completed')->sum('total'),
            'orders' => Order::count(),
            'pending_quotes' => Quote::where('status', 'pending')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'services' => Service::count(),
            'portfolio' => PortfolioProject::count(),
            'products' => Product::count(),
        ];

        $recentQuotes = Quote::with('service')->latest()->take(5)->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $notifications = auth()->user()->notifications()->latest()->take(5)->get();
        $unreadNotifications = auth()->user()->unreadNotifications()->count();

        return view('admin.dashboard', compact(
            'stats', 'recentQuotes', 'recentOrders', 'notifications', 'unreadNotifications'
        ));
    }
}