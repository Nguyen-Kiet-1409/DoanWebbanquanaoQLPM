<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.header', function ($view) {
            $notificationCount = 0; 
            
            if (Auth::check() && Auth::user()->role == 2) { 
                $userId = Auth::id();
                // --- SỬA LẠI LOGIC ĐẾM (Dùng Cache) ---
                $cacheKey = 'user_notifications_' . $userId;
                $notifications = Cache::get($cacheKey, []); // Lấy mảng ID từ cache
                $notificationCount = count($notifications);
                // -------------------------
            }
            
            $view->with('notificationCount', $notificationCount); 
        });
    }
}
