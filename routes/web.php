<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    FavoriteController,
    NotificationsController,
    AdminCommunityRequestController,
    AdminUserController,
    FollowController,
    UserProfileController,
    CommunityCategoryController,
    CategoryController,
    QuoteController,
    UserQuoteController,
    ActivitiesController,
    GuestQuoteController,
    ConversationController,
    MessageController,
    AdminController,
    AdminMessageController,
    BlockController,
    ReportController,
    UserStatusController,
    UserController,
    CommunityController,
    CommunityFollowerController,
    CommunityMemberController,
    CommunityCommentController,
    CommunityPostController,
    CommunityRatingController,
    UserSearchController
};

// الصفحة الرئيسية
Route::get('/', fn() => view('welcome'))->name('welcome');

// إعادة توجيه /dashboard
Route::redirect('/dashboard', '/quotes')->middleware(['auth']);

// لوحة التحكم
Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

// ========================
// المجتمعات والتصنيفات
// ========================
Route::middleware('auth')->prefix('communities')->name('communities.')->group(function () {
    Route::get('/categories', [CommunityCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CommunityCategoryController::class, 'show'])->name('categories.show');
    Route::resource('/', CommunityController::class)->parameters(['' => 'community'])->except(['show']);
    Route::get('/{community}', [CommunityController::class, 'show'])->name('show');
    // باقي مسارات المجتمعات...
});

// ========================
// مسارات الحساب والمحادثات
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings', [UserProfileController::class, 'settings'])->name('user.settings');
    Route::post('/profile/cover', [ProfileController::class, 'updateCover'])->name('profile.update-cover');

    // المحادثات
    Route::prefix('inbox')->name('inbox.')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->name('index');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [MessageController::class, 'store'])->name('send');
        Route::post('/start/{user}', [ConversationController::class, 'startConversation'])->name('start');
    });

    // المفضلة
    Route::get('/my-favorites', function () {
        $favorites = auth()->user()->favorites()->latest()->get();
        return view('quotes.favorites', compact('favorites'));
    })->name('quotes.favorites');
    Route::post('/quotes/{quote}/favorite', [QuoteController::class, 'favorite'])->name('quotes.favorite');

    // ========================
    // مسارات الاقتباسات
    // ========================
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
    Route::get('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::get('/quotes/random', [QuoteController::class, 'random'])->name('quotes.random');
    Route::get('/quotes/search', [QuoteController::class, 'search'])->name('quotes.search');
    Route::get('/quotes/{quote}', [QuoteController::class, 'show'])->name('quotes.show');
    Route::get('/quotes/{quote}/edit', [QuoteController::class, 'edit'])->name('quotes.edit');
    Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');

    Route::get('/quotes/{quote}/full-content', [QuoteController::class, 'getFullContent'])->name('quotes.fullContent');

    Route::get('/quotes/{quote}/download', [QuoteController::class, 'downloadImage'])->name('quotes.download');

    // أكشنات AJAX
    Route::post('/quotes/{quote}/comment', [QuoteController::class, 'addComment'])->name('quotes.comment');
    Route::post('/quotes/{quote}/like', [QuoteController::class, 'toggleLike'])->name('quotes.like');
    Route::post('/quotes/{quote}/reaction', [QuoteController::class, 'toggleReaction'])->name('quotes.reaction');
    Route::post('/quotes/{quote}/report', [QuoteController::class, 'report'])->name('quotes.report');

    // حظر مستخدم
    Route::post('/users/{user}/block', [UserController::class, 'block'])->name('users.block');
});

// اقتراحات الزوار
Route::get('/guest-quotes/create', [GuestQuoteController::class, 'create'])->name('guest-quotes.create');
Route::post('/guest-quotes', [GuestQuoteController::class, 'store'])->name('guest-quotes.store');
Route::get('/feelings', [GuestQuoteController::class, 'index'])->name('guest-quotes.index');
Route::post('/guest-quotes/{guestQuote}/comment', [GuestQuoteController::class, 'addComment'])->name('guest-quotes.comment')->middleware('auth');

// ========================
// الإدارة
// ========================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/community-requests', [AdminCommunityRequestController::class, 'index'])->name('community-requests.index');
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [AdminMessageController::class, 'create'])->name('messages.create');

    // باقي مسارات الإدارة...
});
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');

Route::get('/guest-quotes/manage', [GuestQuoteController::class, 'manage'])->name('guest-quotes.manage');

Route::get('/user/activities', [ActivitiesController::class, 'index'])->name('user.activities');


Route::prefix('follows')->name('follows.')->group(function () {
    Route::get('/followers', [FollowController::class, 'followers'])->name('followers');
});


Route::prefix('follows')->name('follows.')->group(function () {
    Route::post('/toggle', [FollowController::class, 'toggle'])->name('toggle');
});

Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile');


Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');

// مصادقة Laravel Breeze
require __DIR__ . '/auth.php';
