<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FilterController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/tickets/filter', [FilterController::class, 'index'])->name('ticket.filter');

Route::post('/ticket/{ticketId}/due-date', [FilterController::class, 'store']);
Route::middleware('auth')->group(function () {
    Route::resource('/ticket', TicketController::class);
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

    $user = User::firstOrCreate(['email' => $user->email], [
        'name' => $user->name,
        'avatar' => $user->avatar,
        'password' => 'password',
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});
