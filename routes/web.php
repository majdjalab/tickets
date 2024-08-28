<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FilterController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authentication Routes
require __DIR__.'/auth.php';

// Social Authentication Routes
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

    $user = User::firstOrCreate(['email' => $user->email], [
        'name' => $user->name,
        'avatar' => $user->avatar,
        'password' => bcrypt('password'), // It's good practice to hash the password
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/status', [ProfileController::class, 'updateStatus'])->name('profile.status.update');
    Route::patch('/profile/status', [ProfileController::class, 'updateStatus'])->name('profile.status.update');
    Route::get('/profile/status', [ProfileController::class, 'getStatus'])->name('profile.status.get');



    // Ticket Routes
    Route::resource('/ticket', TicketController::class);
    Route::post('/ticket/{ticketId}/due-date', [FilterController::class, 'store']);
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('ticket.create');

    // Category Routes
    Route::resource('/categories', CategoryController::class)->except(['create']);
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::delete('/categories/{categoryId}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Filter Routes
    Route::get('/tickets/filter', [FilterController::class, 'index'])->name('ticket.filter');
});
