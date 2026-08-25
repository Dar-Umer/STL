# Soil Lab Management System

## Goal
Build a soil sample management system with QR scanning, role dashboards, Excel export, government-portal-style public landing page, database-backed settings, zone‑based targets, five‑level location hierarchy (State → District → Zone → Panchayat → Village) with Excel import, and role-based access control (Admin full control, Lab Manager manages own lab only, Lab Technician accesses own lab data).

## Stack
- Laravel 12.61.0, PHP 8.2.12, MySQL 8.0
- Livewire 4.3.0, Alpine.js, Bootstrap 4
- html5-qrcode (camera scanning)

## Done
- `maatwebsite/excel` 3.1.69 installed; `SoilSamplesExport` with lab scoping
- Sample list with stats cards, search, status filter, date range, role-aware columns, export
- Audio beep on QR scan, sample testing form improvements
- Public home page, track page, front page editor, admin settings with reCAPTCHA
- All role dashboard redirects, public controllers, document controllers with eager-loaded state/district
- `users.zone_id` FK, `targets` table, `manage-targets` permission
- Default JK state + Rajouri district seeded, zones reparented under district
- `Target`, `User`, `Location`, `Farmer`, `Plot` models with new relationships (state_id, district_id, panchayat_id, village_id on farmers/plots)
- `ZoneTargetManager` — target CRUD with progress bars
- `LocationManagement` — stats cards, filter tabs, modal CRUD, indent-based tree view, import modal
- Modal visibility fix — replaced Alpine `x-show` with `@if` conditional rendering
- `LocationsImport` — flat-file Excel import for 5-level hierarchy
- `FarmerManagement` — cascading 5-level location selects; state/district required; lab-scoped for Lab Manager
- `SampleCollection` — state/district cascading in new farmer form; Collection Date hidden for non-Admin
- All sample views, PDFs updated with state/district columns
- `LocationSeeder` — 5-level hierarchy seeded
- `RolePermissionSeeder` — idempotent `firstOrCreate`/`syncPermissions`; Lab Manager gets `manage-users` + field perms; Lab Technician gets `view-reports`
- Routes: users, farmers, qr-codes moved to `Admin|Lab Manager` group; field routes opened to `Field Official|Lab Manager`
- User management scoped by lab_id for Lab Manager; Admin role excluded from dropdown
- Farmer management scoped by lab; lab_id auto-set for Lab Manager; 403 on cross-lab edit
- QR code permissions — `created_by` column; Admin filterable by lab; Lab Manager by lab_id; others by created_by
- QR prefix auto-set from zone code for Field Officials (readonly)
- QR print + print-selected with Lab Manager by lab_id
- Sample code race condition fixed — generation inside `DB::transaction` with `Lab::lockForUpdate()` at save time
- Timezone set to `Asia/Kolkata`
- Lab Manager sidebar rearranged into LABORATORY / FIELD OPERATIONS / ADMINISTRATION sections
- QR scanner in SampleCollection replaced with same inline toggle pattern used in Receive Samples and Test Samples (fps:20, qrbox:280×280, `{ facingMode: 'environment' }`, cached AudioContext, no modal)
- Target enforcement: sample collection blocked when zone target is met or no active target exists
- Field Official zone target banner + warning banner (no active target) on SampleCollection page
- Admin / Lab Manager Dashboard Zone Target Overview cards
- Target model helpers: `scopeActive`, `scopeForZone`, `collectedCount()`, `isExceeded()`, `remainingCount()`
- Date overlap validation on target creation — no overlapping date ranges for same zone
- `progressPercent()` uses `collectedCount()` (matches enforcement limit)
- `collected_at` refreshed on step→3 for accurate timestamp
- Home page: full-view hero, sticky nav with scroll shadow/gradient/underline animation, mobile slide‑in drawer with brand header + close button, fade‑in sections, animated counter facts, back‑to‑top button, Escape key closes drawer, updates auto-scroll, CHO section, service grid, leadership card, vision/mission/objectives
- Header: compact mobile (≤480px) with reduced padding/emblem/flag/font, portal-title hidden, flag hidden, left-aligned; desktop padding reduced (12px 0 10px)
- "Staff Login" → "Login" in nav
- Hero: min-height 60vh→40vh, padding 48px→32px, image 340×230→380×240→480×280, h2 1.8rem→2.2rem, p .88rem→.95rem, text-align justify
- Hero carousel — 3 images auto-scrolling with sliding animation, navigation dots, pause on hover/touch
- Track page: search button sizing, empty state "Back to Home" removed
- Request-sample page: header matches track page style, footer dark background, all form fields mandatory, phone validation (duplicate check on pending only)
- Contact page created at `/contact` — info cards, office hours, quick links
- Menu manager admin page: redesigned then reverted to original
- Updates auto-scroll: fixed `data-full` attribute escaping for mobile
- Front Page Manager: Section 11 — Meta Tags & Share Image (OG) for WhatsApp/Facebook
- Front Page Manager: Emblem & Flag image URL fields replaced with direct file uploads (PNG/JPG/SVG, preview + remove)
- Front Page Manager: Hero images 2 & 3 upload fields added (3-image carousel)
- Login page: emblem/flag switched from URL-based settings to uploaded file paths
- Security headers, rate limiting, custom 429 page

### Hardening & Code Quality Pass
- `Location::targets()` FK fixed — was `'location_id'` instead of `'zone_id'`
- `authorizeSampleAccess()` — catch-all `abort(403)` added after role/lab checks so unauthorized roles don't pass through silently
- `SoilSample` model — `reported_at` added to `$fillable` and `casts` (previously missing)
- `setting()` helper — boolean coercion fixed: `$val === '1'` returns `true`, `$val === '0'` returns `false` (previously `$val === '1' || $val === '0'` cast `"0"` to `true`)
- `AuthController::login()` — `is_active` check added; deactivated accounts return 423 instead of successful login
- `Settings::removeLogo()` — now deletes `logo_path` row from settings table (previously only cleared cache)
- Migrations — hardcoded `lab_id => 1` / `created_by => 1` replaced with `DB::table(...)->orderBy('id')->value('id')`
- Error message leaking — `$e->getMessage()` never returned to client; 2 locations fixed (`SampleCollectionController::collect()`, `LocationManagement::import()`)
- `FarmerController::search()` — N+1 `plots()->count()` replaced with `withCount('plots')`
- `LocationController::ancestors()` — N+1 while-loop replaced with batch `WHERE IN` query
- `Plot` model — `float` casts added for `latitude`, `longitude`, `area_kanals`, `age_of_plants`
- `report_token` removed from `$fillable` (not a mass-assignment concern)
- API response keys standardized: `targets`→`data`, `requests`→`data`, `notifications`→`data`, `request`→`data`, `farmer`→`data`
- `SampleResource` — added `lab_id`, `reported_at`; removed `report_token`
- `SampleCollectionController::collect()` response — added `lab_id`
- All 17 edited files pass `php -l` syntax check

## Roles & Permissions

5 roles via Spatie (`database/seeders/RolePermissionSeeder.php`):

| Role | Description |
|---|---|
| **Admin** | Full system access — all 24 permissions granted |
| **Lab Manager** | Own lab's operations — users, queue, testing, field ops, reports |
| **Lab Technician** | Lab testing workflow — queue, scanning, testing, reports |
| **Field Official** | Field ops — farmers, plots, sample collection, target-enforced |
| **Farmer** | Self-service — dashboard, own report downloads |

**Permission matrix** (24 permissions, 5 roles):

| Permission | Category | Admin | Lab Mgr | Lab Tech | Field | Farmer |
|---|---|---|---|---|---|---|
| `manage-users` | Admin | ✓ | ✓ | | | |
| `manage-roles` | Admin | ✓ | | | | |
| `manage-labs` | Admin | ✓ | | | | |
| `manage-locations` | Admin | ✓ | | | | |
| `manage-farmers` | Admin | ✓ | | | | |
| `manage-parameters` | Admin | ✓ | | | | |
| `manage-dropdowns` | Admin | ✓ | | | | |
| `manage-custom-fields` | Admin | ✓ | | | | |
| `manage-qr-codes` | Admin | ✓ | | | | |
| `manage-targets` | Targets | ✓ | ✓ | | | |
| `view-lab-dashboard` | Lab | ✓ | ✓ | ✓ | | |
| `manage-sample-queue` | Lab | ✓ | ✓ | ✓ | | |
| `scan-qr` | Lab | ✓ | ✓ | ✓ | | |
| `perform-testing` | Lab | ✓ | ✓ | ✓ | | |
| `view-reports` | Lab | ✓ | ✓ | ✓ | | |
| `register-farmers` | Field | ✓ | ✓ | | ✓ | |
| `manage-plots` | Field | ✓ | ✓ | | ✓ | |
| `collect-samples` | Field | ✓ | ✓ | | ✓ | |
| `track-samples` | Field | ✓ | ✓ | | ✓ | |
| `preprint-qr` | Field | ✓ | ✓ | | ✓ | |
| `view-dashboard` | Farmer | ✓ | | | | ✓ |
| `download-reports` | Farmer | ✓ | | | | ✓ |

**Access control rules** (beyond Spatie middleware checks):
- **Lab-scoped data** — Lab Manager/Technician see only records matching their `lab_id`. Field Officials scoped to lab + assigned zone. Filters applied at query level in Livewire components and API controllers.
- **QR code visibility** — Admin sees all (filterable by lab dropdown). Lab Manager sees only own lab's. Field Official sees only own created QR codes.
- **`is_active` gate** — Users with `is_active = false` blocked at login (423 response). Admin toggles via user management form.
- **Sample document access** — `authorizeSampleAccess()` in `SampleDocumentController` checks role + lab_id; catch-all `abort(403)` for any role that doesn't match prior checks.
- **Zone requirement** — Field Officials must have a `zone_id` set; sample requests and dashboard data filtered by their zone.
- **Target enforcement** — Sample collection blocked at API + Livewire level when no active target exists for the farmer's zone, or target count is exceeded.

### Admin Panel Implementation

**Route middleware groups** (`routes/web.php`):

| Group | Middleware | Key Routes |
|---|---|---|
| Admin-only | `role:Admin` | labs, locations, roles, parameters, dropdowns, settings, front-page, menus |
| Admin/Lab Manager | `role:Admin\|Lab Manager` | users, farmers, qr-codes, zone-targets, reports |
| Lab | `role:Lab Manager\|Lab Technician` | dashboard, queue, testing |
| Field | `role:Field Official\|Lab Manager` | collect-sample, qr-codes, acknowledgements |
| Farmer | `role:Farmer` | own samples, reports |

**`AdminBypassRole` middleware** (`app/Http/Middleware/AdminBypassRole.php`):
- Registered as the `role` alias in `bootstrap/app.php`, replacing Spatie's default `RoleMiddleware`
- Users with `Admin` role bypass all role gates — they can access any route regardless of the middleware's role requirement
- Non-Admin users fall through to standard `hasAnyRole($roles)` check

**Role Management** (`/admin/roles`, `RoleManagement` Livewire component):
- Create and edit roles (no delete in original — delete added with guard: prevents deleting Admin role, blocks deletion if users are assigned)
- All 24 permissions displayed as a 3-column checkbox grid
- Admin-only access via route middleware

**User Management** (`/admin/users`, `UserManagement`):
- Single-role dropdown; Lab Managers see only non-Admin roles
- `is_active` toggle for enabling/disabling accounts
- Zone dropdown visible only when role is "Field Official"
- Lab Manager auto-scoped to own lab (form field hidden, `lab_id` auto-set)
- Cross-lab edits blocked with `abort(403)`

**`manage-targets` permission enforcement:**
- Defined in seeder, assigned to Admin + Lab Manager
- Checked via `$this->authorize('manage-targets')` in `ZoneTargetManager::render()`
- Route middleware (`role:Admin|Lab Manager`) gates access at route level; permission check provides granular enforcement inside the component

**Lab-scoping in Livewire components:**

| Component | Scoping Logic |
|---|---|
| `UserManagement` | `where('lab_id', $currentUser->lab_id)` for Lab Manager |
| `FarmerManagement` | `where('lab_id', $currentUser->lab_id)` for Lab Manager |
| `QrCodeGenerator` | `applyRoleScope()` — Admin: all, Lab Mgr: by lab, others: own |
| `Reports` | `where('lab_id', $this->userLabId)` for non-Admin |
| `ZoneTargetManager` | Targets filtered by zones belonging to farmers in the lab |

**Admin sidebar menu order:** Dashboard, Users & Roles (treeview), Master Data (treeview), Laboratory (treeview), Field Operations (treeview), Targets & Reports (treeview: Zone Targets, Reports, Sample Requests), Settings (treeview: Settings, Front Page, Menus, Recommendation Rules)

**Role/Permission CRUD status:**
| Feature | Exists? | Details |
|---|---|---|
| Create Role | ✓ | Via RoleManagement at `/admin/roles` |
| Read/List Roles | ✓ | Table with all permissions comma-separated |
| Update Role | ✓ | Edit name + reassign permissions |
| Delete Role | ✓ | Guarded — blocks deletion of Admin role or roles with assigned users |
| Manage Permissions (CRUD) | ✗ | Permissions are seeded only; no admin UI to create/modify/delete individual permissions |

## Key Design Decisions
- `manage-targets` permission split from role middleware for future granularity
- `Target::achievedCount()` counts reported samples within date range via farmer zone join
- Location hierarchy imported via single-level flat rows — parents auto-created via `firstOrCreate` on name + type + parent_id
- Import file validation: only `xlsx`, `xls`, `csv` up to 5 MB
- State and District required in farmer/plot forms
- Location modals use Livewire `@if` (not Alpine `x-show`) to prevent flash on load
- QR permissions: non-Admin see only their own QR codes (Lab Manager by lab_id); Admin filterable by lab
- Sample code generated at save time inside `DB::transaction` + `lockForUpdate()` to prevent duplicates
- QR scanner uses inline toggle pattern (not modal) — same as Receive Samples and Test Samples pages
- Bootstrap 4 modal events unreliable after Livewire re-renders; polling element visibility more robust
- Target enforcement uses `collectedCount()` (any collected sample in range) not `achievedCount()` (reported only) so Field Officials cannot collect beyond limit even before testing
- Target check uses *any* active target for the farmer's zone — if target is exceeded, collection blocked with clear error message
- Lab Manager Zone Target Overview scoped by `lab_id` via farmers in the lab's zones
- Hero carousel: sliding `translateX` animation with 4s auto-play, amber dots, pause on hover/touch
- OG share image: stored as `front.og_image_path`, rendered as `og:image` + `twitter:image` meta tags
- Emblem/Flag: stored as uploaded file paths (`front.header_emblem_path` / `front.header_flag_path`) with fallback to Wikimedia default URLs

## Notable Files
- `app/Livewire/Field/SampleCollection.php` — wizard-based sample collection with QR scanner
- `app/Livewire/Admin/QrCodeGenerator.php` — QR generation with role scoping
- `app/Http/Controllers/QrPrintController.php` — QR print + print-selected
- `app/Livewire/Admin/LocationManagement.php` — location CRUD + import
- `app/Livewire/Admin/ZoneTargetManager.php` — zone target CRUD
- `app/Livewire/Admin/UserManagement.php` — lab-scoped user management for Lab Manager
- `app/Livewire/Admin/FarmerManagement.php` — lab-scoped farmer management
- `app/Livewire/Lab/SampleQueue.php` — receive samples with QR scanner
- `app/Livewire/Lab/SampleTesting.php` — test samples with QR scanner
- `app/Exports/SoilSamplesExport.php` — lab-scoped Excel export
- `app/Imports/LocationsImport.php` — 5-level location Excel import
- `app/Helpers/Settings.php` — `setting()` helper with cache read-through
- `database/seeders/RolePermissionSeeder.php` — idempotent permissions seeder
- `resources/views/public/home.blade.php` — full home page with hero carousel, nav, updates, counters, CHO, footer
- `resources/views/livewire/admin/front-page-manager.blade.php` — 11-section accordion editor with file uploads
- `resources/views/auth/login.blade.php` — standalone green gradient glass-morphism login page
- `resources/views/livewire/public/sample-request-form.blade.php` — public request form with cascading location dropdowns

## Pending
- Test Excel import of locations
- Verify cascading location selects
- Test Lab Manager user creation flow
- Verify Lab Technician can view reports
