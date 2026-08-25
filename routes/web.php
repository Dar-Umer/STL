<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\RoleManagement;
use App\Livewire\Admin\LabManagement;
use App\Livewire\Admin\LocationManagement;
use App\Livewire\Admin\FarmerManagement;
use App\Livewire\Admin\ParameterManagement;
use App\Livewire\Admin\DropdownManagement;
use App\Livewire\Admin\CustomFieldManager;
use App\Http\Controllers\QrPrintController;
use App\Http\Controllers\SampleDocumentController;
use App\Http\Controllers\PublicController;
use App\Livewire\Admin\QrCodeGenerator;
use App\Livewire\Admin\ZoneTargetManager;
use App\Livewire\Admin\Reports as AdminReports;
use App\Livewire\Admin\MenuManager as AdminMenuManager;
use App\Livewire\Admin\SampleRequestManager as AdminSampleRequestManager;
use App\Livewire\Public\SampleRequestForm;
use App\Livewire\Admin\RecommendationRules as AdminRecommendationRules;
use App\Livewire\Admin\AppSettings;
use App\Livewire\Admin\AuditLogManager;
use App\Livewire\Admin\InventoryManager;
use App\Livewire\Admin\ConsumptionRules;
use App\Livewire\Admin\NotificationSettings;
use App\Livewire\Admin\SendNotification;
use App\Livewire\Lab\LabDashboard;
use App\Livewire\Lab\SampleQueue;
use App\Livewire\Lab\SelfSampleCollection;
use App\Livewire\Lab\SampleTestingForm;
use App\Livewire\Field\SampleCollection;
use App\Livewire\Field\SampleDetail;
use App\Livewire\SampleList;
use Illuminate\Support\Facades\Route;

// Language switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hi', 'ur'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect(url()->previous() ?: route('home'));
})->name('lang.switch');

// Accessibility page
Route::get('/accessibility', [PublicController::class, 'accessibility'])->name('accessibility');

// Contact page
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

// Public home page
Route::get('/', [PublicController::class, 'home'])->name('home');

// Track report
Route::get('/track', [PublicController::class, 'track'])->name('public.track');
Route::post('/track', [PublicController::class, 'trackLookup'])->middleware('throttle:5,1')->name('public.track.lookup');

// Sample request
Route::get('/request-sample', SampleRequestForm::class)->middleware('throttle:10,10')->name('public.sample-request');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Profile (all roles)
    Route::get('/profile', \App\Livewire\Profile::class)->name('profile');

    // Unified Samples (all roles)
    Route::get('/samples', SampleList::class)->name('samples.index');

    // Admin-only Routes
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/roles', RoleManagement::class)->name('roles');
        Route::get('/labs', LabManagement::class)->name('labs');
        Route::get('/locations', LocationManagement::class)->name('locations');
        Route::get('/parameters', ParameterManagement::class)->name('parameters');
        Route::get('/dropdowns', DropdownManagement::class)->name('dropdowns');
        Route::get('/custom-fields', CustomFieldManager::class)->name('custom-fields');
        Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('settings');
        Route::get('/front-page', \App\Livewire\Admin\FrontPageManager::class)->name('front-page');
        Route::get('/menus', AdminMenuManager::class)->name('menus');
        Route::get('/audit-logs', AuditLogManager::class)->name('audit-logs');
        Route::get('/recommendation-rules', AdminRecommendationRules::class)->name('recommendation-rules');
        Route::get('/sample-requests', AdminSampleRequestManager::class)->name('sample-requests');
        Route::get('/locations/template', function () {
            $headers = ['State,District,Zone,Panchayat,Village'];
            $sample = 'Jammu & Kashmir,Rajouri,North Zone,Panchayat A,Village 1';
            $csv = implode("\n", [$headers[0], $sample]);
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="location-import-template.csv"',
            ]);
        })->name('locations.template');
    });

    // Admin & Lab Manager shared routes
    Route::middleware(['role:Admin|Lab Manager'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', UserManagement::class)->name('users');
        Route::get('/farmers', FarmerManagement::class)->name('farmers');
        Route::get('/qr-codes', QrCodeGenerator::class)->name('qr-codes');
        Route::get('/qr-codes/print/{prefix}', [QrPrintController::class, 'print'])->name('qr-codes.print');
        Route::get('/qr-codes/print-selected', [QrPrintController::class, 'printSelected'])->name('qr-codes.print-selected');
        Route::get('/zone-targets', ZoneTargetManager::class)->name('zone-targets');
        Route::get('/reports', AdminReports::class)->name('reports');
        Route::get('/app-settings', AppSettings::class)->name('app-settings');
        Route::get('/inventory', InventoryManager::class)->name('inventory')->middleware('permission:manage-inventory');
        Route::get('/inventory/{chemical}/consumption', ConsumptionRules::class)->name('inventory.consumption')->middleware('permission:manage-inventory');

        Route::get('/notification-settings', NotificationSettings::class)->name('notification-settings')->middleware('permission:manage-notifications');
        Route::get('/send-notification', SendNotification::class)->name('send-notification')->middleware('permission:manage-notifications');
    });

    // Lab Routes (Lab Manager & Lab Technician)
    Route::middleware(['role:Lab Manager|Lab Technician'])->prefix('lab')->name('lab.')->group(function () {
        Route::get('/dashboard', LabDashboard::class)->name('dashboard');
        Route::get('/queue', SampleQueue::class)->name('queue');
        Route::get('/self-sample', SelfSampleCollection::class)->name('self-sample');
        Route::get('/testing', \App\Livewire\Lab\SampleTesting::class)->name('testing-list');
        Route::get('/testing/{sampleId}', SampleTestingForm::class)->name('testing');
    });

    // Field Routes (Field Official & Lab Manager)
    Route::middleware(['role:Field Official|Lab Manager'])->prefix('field')->name('field.')->group(function () {
        Route::get('/collect-sample', SampleCollection::class)->name('collect-sample')->middleware('permission:collect-samples');
        Route::get('/samples/{sampleId}', SampleDetail::class)->name('samples.detail');
        Route::get('/samples/{sampleId}/acknowledgement', [SampleDocumentController::class, 'acknowledgement'])->name('samples.acknowledgement');
        Route::get('/samples/{sampleId}/acknowledgement/pdf', [SampleDocumentController::class, 'acknowledgementPdf'])->name('samples.acknowledgement-pdf');
        Route::get('/samples/{sampleId}/report', [SampleDocumentController::class, 'downloadReport'])->name('samples.report');
        Route::get('/qr-codes', QrCodeGenerator::class)->name('qr-codes');
        Route::get('/qr-codes/print/{prefix}', [QrPrintController::class, 'print'])->name('qr-codes.print');
        Route::get('/qr-codes/print-selected', [QrPrintController::class, 'printSelected'])->name('qr-codes.print-selected');
    });

    // Farmer Routes
    Route::middleware(['role:Farmer'])->prefix('farmer')->name('farmer.')->group(function () {
        Route::get('/samples/{sampleId}', SampleDetail::class)->name('samples.detail');
        Route::get('/samples/{sampleId}/acknowledgement', [SampleDocumentController::class, 'acknowledgement'])->name('samples.acknowledgement');
        Route::get('/samples/{sampleId}/acknowledgement/pdf', [SampleDocumentController::class, 'acknowledgementPdf'])->name('samples.acknowledgement-pdf');
        Route::get('/samples/{sampleId}/report', [SampleDocumentController::class, 'downloadReport'])->name('samples.report');
    });

});

// Public report download (for QR codes on printed reports)
Route::get('/report/{token}', [SampleDocumentController::class, 'report'])->name('public.report');
