<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CoopController;
use App\Http\Controllers\CommentController;




Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Auth routes (Breeze)
require __DIR__.'/auth.php';



// Routes accessible by any authenticated user
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Main dashboard redirects based on role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 1) return redirect()->route('jammiya');      // Coop/Jm3iya
        return redirect()->route('coops.index');                          // Client
    })->name('dashboard');
});

Route::get('/viewPageInfo', [ProfileController::class, 'viewPageInfoController'])
    ->middleware(['auth', 'verified'])
    ->name('viewPageInfo');


// Coop / Jm3iya routes (role = 1)
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/jammiya', [ProductController::class, 'show'])->name('jammiya');
    Route::post('/addProduct', [ProductController::class, 'store'])->name('addProduct');
    Route::delete('/deleteProduct/{id}', [ProductController::class, 'destroy'])->name('deleteProduct');
    Route::get('editeProduct/{id}', [ProductController::class, 'edite'])->name('editeProduct');
    Route::put('updateProduct/{id}', [ProductController::class, 'update'])->name('updateProduct');
});

Route::put('/updateInfo', [ProfileController::class, 'updateInfo'])
    ->middleware(['auth', 'verified'])
    ->name('updateInfo');

Route::post('/insertInfoJaam', [ProfileController::class, 'insertInfoJaam'])
    ->middleware(['auth', 'verified'])
    ->name('insertInfoJaam');

// Client routes (role = 0)
Route::middleware(['auth', 'verified', 'client'])->group(function () {
    Route::get('/coops', [CoopController::class, 'index'])->name('coops.index');
    Route::get('/coops/{coop}', [CoopController::class, 'show'])->name('coops.show');
    Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::post('/products/{product}/comments', [CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('auth');
