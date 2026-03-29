<?php

namespace App\Filament\Widgets;

use App\Models\RentPayment;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RentStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total collected (all time)
        $totalCollected = RentPayment::sum('amount');

        // Current month collected
        $monthlyCollected = RentPayment::whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');

        // Current year collected
        $yearlyCollected = RentPayment::whereYear('payment_date', now()->year)
            ->sum('amount');

        // Total expected monthly rent (sum of all customers' monthly_rent)
        $totalExpectedMonthly = Customer::sum('monthly_rent');

        // Total customers
        $totalCustomers = Customer::count();

        return [
            Stat::make('کل مشتریان', number_format($totalCustomers))
                ->description('تعداد کل مشتریان فعال')
                ->color('secondary')
                ->icon('heroicon-o-users')
                ->extraAttributes([
                    'class' => 'shadow-md rounded-lg',
                ]),

            Stat::make('کل دریافتی اجاره', number_format($totalCollected) . ' AFN')
                ->description('از ابتدا تا کنون')
                ->color('success')
                ->icon('heroicon-o-currency-dollar')
                ->chart([7, 3, 4, 5, 6, 8, 10]) // optional: mini sparkline
                ->extraAttributes([
                    'class' => 'shadow-md rounded-lg',
                ]),

            Stat::make('دریافتی ماه جاری', number_format($monthlyCollected) . ' AFN')
                ->description(now()->format('F Y'))
                ->color('primary')
                ->icon('heroicon-o-calendar')
                ->extraAttributes([
                    'class' => 'shadow-md rounded-lg',
                ]),

            Stat::make('دریافتی سال جاری', number_format($yearlyCollected) . ' AFN')
                ->description(now()->year)
                ->color('info')
                ->icon('heroicon-o-chart-bar')
                ->extraAttributes([
                    'class' => 'shadow-md rounded-lg',
                ]),

            Stat::make('کل اجاره ماهیانه مورد انتظار', number_format($totalExpectedMonthly) . ' AFN')
                ->description('جمع اجاره تمام مشتریان')
                ->color('warning')
                ->icon('heroicon-o-home')
                ->extraAttributes([
                    'class' => 'shadow-md rounded-lg',
                ]),
        ];
    }

    // Optional: Make the widget full width
    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    // Optional: Add custom CSS to increase card height or style
    protected function getExtraAttributes(): array
    {
        return [
            'style' => 'margin-bottom: 1rem;',
        ];
    }
}