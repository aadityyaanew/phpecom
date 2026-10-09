<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommerceStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $revenue = Order::where('payment_status', 'paid')->sum('total');
        $ordersCount = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $lowStockCount = Product::where('stock', '<=', 5)->count();
        $customersCount = User::where('role', 'customer')->count();

        return [
            Stat::make('Gross Revenue', '$' . number_format($revenue, 2))
                ->description('All settled transactions')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([210, 420, 680, 890, 750, 920, 1240])
                ->color('amber'),

            Stat::make('Total Orders', (string) $ordersCount)
                ->description("{$pendingOrders} orders awaiting fulfillment")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart([3, 5, 8, 12, 10, 15, 18])
                ->color('success'),

            Stat::make('Active Customers', (string) $customersCount)
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Inventory Alerts', (string) $lowStockCount)
                ->description('SKUs at or below low stock threshold')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'warning' : 'gray'),
        ];
    }
}
