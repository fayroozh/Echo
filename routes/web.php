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
    CommunityChatController,
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
    Route::get('/{community}/dashboard', [CommunityController::class, 'dashboard'])->name('dashboard');

    // مسارات الأعضاء والمتابعين
    Route::post('/{community}/join', [CommunityMemberController::class, 'join'])->name('join');
    Route::post('/{community}/leave', [CommunityMemberController::class, 'leave'])->name('leave');
    Route::post('/{community}/follow', [CommunityFollowerController::class, 'follow'])->name('follow');
    Route::post('/{community}/unfollow', [CommunityFollowerController::class, 'unfollow'])->name('unfollow');

    // مسارات المنشورات
    Route::get('/{community}/posts/create', [CommunityPostController::class, 'create'])
        ->name('posts.create');
    Route::post('/{community}/posts', [CommunityPostController::class, 'store'])
        ->name('posts.store');
    Route::get('/{community}/posts/{post}', [CommunityPostController::class, 'show'])
        ->name('posts.show');
    Route::get('/{community}/posts/pending', [CommunityPostController::class, 'pending'])
        ->name('posts.pending');
    Route::get('/{community}/posts/{post}/edit', [CommunityPostController::class, 'edit'])
        ->name('posts.edit');
    Route::put('/{community}/posts/{post}', [CommunityPostController::class, 'update'])
        ->name('posts.update');
    Route::delete('/{community}/posts/{post}', [CommunityPostController::class, 'destroy'])
        ->name('posts.destroy');

    // تعليقات المنشورات
    Route::post('/{community}/posts/{post}/comments', [CommunityCommentController::class, 'store'])
        ->name('posts.comments.store');
    Route::delete('/{community}/posts/{post}/comments/{comment}', [CommunityCommentController::class, 'destroy'])
        ->name('posts.comments.destroy');

    // تفاعلات المنشورات
    Route::post('/{community}/posts/{post}/like', [CommunityPostController::class, 'toggleLike'])
        ->name('posts.like');

    // مسارات التقييم
    Route::post('/{community}/rate', [CommunityRatingController::class, 'store'])->name('rate');

    // مسارات المحادثات
    Route::get('/{community}/chat', [CommunityChatController::class, 'index'])->name('chat');
    Route::post('/{community}/chat', [CommunityChatController::class, 'store'])->name('chat.store');
    Route::post('/{community}/chat/mark-read', [CommunityChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::get('/{community}/chat/new-messages', [CommunityChatController::class, 'getNewMessages'])->name('chat.new-messages');

    // إدارة المجتمع
    Route::get('/{community}/dashboard', [CommunityController::class, 'dashboard'])
        ->name('dashboard');
    Route::get('/{community}/members', [CommunityMemberController::class, 'index'])
        ->name('members.index');
    Route::post('/{community}/members/approve/{member}', [CommunityMemberController::class, 'approveRequest'])
        ->name('members.approve');
    Route::post('/{community}/members/reject/{member}', [CommunityMemberController::class, 'rejectRequest'])
        ->name('members.reject');
    Route::delete('/{community}/members/{member}', [CommunityMemberController::class, 'removeMember'])
        ->name('members.remove');

    // متابعة وانضمام المجتمع
    Route::post('/{community}/follow', [CommunityController::class, 'toggleFollow'])
        ->name('follow');
    Route::post('/{community}/join', [CommunityMemberController::class, 'join'])
        ->name('join');
    Route::delete('/{community}/leave', [CommunityMemberController::class, 'leave'])->name('leave.delete');

    // تقييم المجتمع
    Route::post('/{community}/rate', [CommunityRatingController::class, 'store'])
        ->name('rate');

    // مراسلة مالك المجتمع
    Route::post('/{community}/message-owner', [CommunityController::class, 'messageOwner'])
        ->name('message-owner');

});

// ========================
// مسارات الحساب والمحادثات
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // تم نقل مسار عرض الملف الشخصي إلى الأسفل
    Route::get('/settings', [UserProfileController::class, 'settings'])->name('user.settings');
    Route::post('/profile/cover', [ProfileController::class, 'updateCover'])->name('profile.update-cover');

    // تم نقل مسارات المتابعة إلى الأسفل


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
    Route::put('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
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

    // رسائل للمشرف
    Route::get('/contact-admin', [AdminMessageController::class, 'contactForm'])->name('contact-admin');
    Route::post('/contact-admin', [AdminMessageController::class, 'sendMessage'])->name('contact-admin.send');
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
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');


});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/community-requests/{id}', [AdminCommunityRequestController::class, 'show'])
        ->name('community-requests.show');
    Route::get('/community-requests/{id}/approve', [AdminCommunityRequestController::class, 'approve'])
        ->name('community-requests.approve');
    Route::get('/community-requests/{id}/reject', [AdminCommunityRequestController::class, 'reject'])
        ->name('community-requests.reject');
    Route::put('/community-requests/{id}/approve', [AdminCommunityRequestController::class, 'approve'])
        ->name('admin.community-requests.approve');
    // تم إزالة هذا المسار لأنه مكرر

});

// ========================
// مسارات المتابعة
// ========================
Route::middleware(['auth'])->prefix('users')->name('follows.')->group(function () {
    Route::get('/{user}/following', [FollowController::class, 'following'])->name('following');
    Route::get('/{user}/followers', [FollowController::class, 'followers'])->name('followers');
    Route::post('/toggle', [FollowController::class, 'toggle'])->name('toggle');
});

// مسارات المتابعين للمستخدم الحالي (بدون معامل)
Route::middleware(['auth'])->group(function () {
    Route::get('/my-followers', [FollowController::class, 'myFollowers'])->name('follows.my-followers');
    Route::get('/my-following', [FollowController::class, 'myFollowing'])->name('follows.my-following');
});

// ========================
// مسارات البحث والإشعارات
// ========================
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
Route::get('/search/advanced', [UserController::class, 'advancedSearch'])->name('search.advanced');
Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
Route::get('/user/activities', [ActivitiesController::class, 'index'])->name('user.activities');
// مسار موحد لعرض الملف الشخصي
Route::get('/profile/{user}', [UserProfileController::class, 'show'])->name('profile.show');
Route::get('/user/{user}/profile', [UserProfileController::class, 'show'])->name('user.profile');

// مسار الملف الشخصي الحالي للمستخدم
Route::get('/my-profile', [UserProfileController::class, 'showCurrent'])->name('profile.current')->middleware('auth');

// ========================
// مسارات المحادثات
// ========================
Route::middleware(['auth'])->group(function () {
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::post('/conversations/start/{user}', [ConversationController::class, 'startConversation'])->name('conversations.start');
});

// ========================
// مسارات إدارة اقتراحات الزوار
// ========================
Route::get('/guest-quotes/manage', [GuestQuoteController::class, 'manage'])->name('guest-quotes.manage');

// مصادقة Laravel Breeze
require __DIR__ . '/auth.php';

// مسارات إدارة المجتمعات (للسوبر أدمن فقط)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/communities', [App\Http\Controllers\Admin\CommunityController::class, 'index'])->name('communities.index');
    Route::post('/communities/{community}/approve', [App\Http\Controllers\Admin\CommunityController::class, 'approve'])->name('communities.approve');
    Route::post('/communities/{community}/reject', [App\Http\Controllers\Admin\CommunityController::class, 'reject'])->name('communities.reject');
    Route::post('/communities/{community}/disable', [App\Http\Controllers\Admin\CommunityController::class, 'disable'])->name('communities.disable');
});
