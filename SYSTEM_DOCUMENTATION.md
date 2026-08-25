# Soil Lab — System Documentation

Complete blueprint of the Soil Testing Management System. Use this to understand or rebuild the entire platform.

---

## 1. System Overview

A government soil-testing management platform (Soil Health Card program, J&K Horticulture Department) with four surfaces:

| Surface | Tech | Purpose |
|---------|------|---------|
| **Web panels** | Laravel 12 + Livewire 4.3 + AdminLTE 3.2 + Bootstrap 4.6 | Admin / Lab / Field / Farmer back-office |
| **REST API** | Laravel Sanctum token auth | Mobile app backend |
| **Flutter app** | `com.lab.field`, Provider pattern | Field officials: sample collection in the field |
| **Public website** | Blade + Livewire | Farmer-facing: info pages, sample request form, report tracking |

### Tech Stack

- **Backend:** PHP 8.2, Laravel 12, Livewire 4.3, MySQL
- **Auth:** Sanctum tokens (API), session auth (web)
- **Roles/Permissions:** Spatie laravel-permission
- **PDF:** barryvdh/laravel-dompdf
- **QR:** simplesoftwareio/simple-qrcode (SVG generation for reports)
- **Push:** FCM HTTP v1 with OAuth2 JWT signing (no SDK)
- **Mobile:** Flutter, Provider state management

### Architecture Rules (project conventions)

- No EAV; no service/repository layers except small `app/Services`
- No `env()` outside config; settings stored in DB via `setting()`/`set_setting()` helpers
- No `dd()`/`dump()`; no `shell_exec()`
- API responses: camelCase keys, ISO-8601-ish timestamps (`Y-m-d H:i:s`), numbers as numbers, null not "N/A"
- Global response wrapper `.data` for collections and single items

---

## 2. Roles & Permissions

Five roles (Spatie). **Admin bypasses all role/permission checks** via `AdminBypassRole` middleware.

| Role | Scope | Capabilities |
|------|-------|--------------|
| **Admin** | All data | Everything: users, roles, labs, locations, parameters, dropdowns, custom fields, QR batches, targets, settings, front-page CMS, menus, recommendation rules, inventory, notifications, audit logs |
| **Lab Manager** | Own lab (+ zone field ops if assigned) | Users & farmers management, QR codes, zone targets, reports, app settings, inventory (perm `manage-inventory`), notifications (perm `manage-notifications`); lab ops like technician; field collection |
| **Lab Technician** | Own lab | Lab dashboard, receive samples, self-sample collection, sample testing |
| **Field Official** | Own zone + lab | Collect samples (perm `collect-samples`), register farmers (perm `register-farmers`), manage plots (perm `manage-plots`), own samples list, notifications/requests |
| **Farmer** | Own records only | Own samples list + detail, acknowledgement/report PDFs |

### Data scoping rules

- Lab staff queries filter by `lab_id = user.lab_id`
- Field officials see sample requests/targets by `zone_id = user.zone_id`
- Farmers see only samples where the linked farmer record matches their user
- Samples list (`SampleList`) is shared across roles with role-based filtering

---

## 3. Database Schema (~25 tables)

All tables use `$table->id()` PK and `timestamps()` unless noted. FK convention: `foreignId(...)->constrained()`; optional FKs `->nullable()->constrained()->nullOnDelete()`.

### Core entities

**users** — name, email (unique), password, phone nullable, avatar nullable, is_active bool default true, lab_id → labs nullable, zone_id → locations nullable. Hidden: password, remember_token. Casts: password hashed, is_active boolean.

**labs** — name, code, address, phone, email, contact_person, is_active default true.

**locations** — name, type (`state|district|zone|panchayat|village`), parent_id self-FK nullable, code nullable. Self-referential hierarchy state→district→zone→panchayat→village.

**farmers** — name, parentage (required), phone unique, aadhar_no (12 digits), lab_id, state_id/district_id/zone_id/panchayat_id/village_id → locations, created_by → users.

**plots** — farmer_id → farmers, same five location FKs (inherited from farmer server-side), plot_name, khasra_no, latitude/longitude decimal nullable, soil_type_id, soil_color_id, area_kanals decimal, crop_id, variety nullable, age_of_plants decimal.

**crops / soil_types / soil_colors** — name, is_active.

### Soil testing chain

**soil_samples** — farmer_id, plot_id, sample_code (e.g. `SL-N-0001`), source (`field|self`), qr_code_path, collected_by → users, collected_at datetime, status (`collected|received|testing|reported` enum-backed string), received_at, reported_at, analyzed_by → users, analyzed_at, recommendations text nullable, lab_id, report_token (Str::random(32), auto-generated on create).

**soil_test_parameters** — name, unit, min_range/max_range numeric nullable, method, rating_low_max, rating_medium_max, rating_high_min (numeric thresholds), rating_labels JSON `{low,medium,high}`, rating_colors JSON `{low,medium,high}` hex, order_column int, is_active. Pivot `lab_test_parameter` (lab_id ↔ parameter id) controls which lab tests which parameter.

Rating logic (`calculateRating`):
```
value >= rating_high_min                          → high
rating_low_max < value <= rating_medium_max       → medium
value <= rating_low_max                           → low
else null
```

**soil_sample_results** — soil_sample_id, parameter_id, value decimal, rating (`low|medium|high`).

**sample_status_histories** — soil_sample_id, from_status nullable, to_status, changed_by → users nullable, notes nullable. One row per status transition.

### Collection support

**qr_codes** — code unique, prefix, sequence int, is_used default false, used_by/used_at/soil_sample_id nullable, lab_id, created_by. Lifecycle: admin generates batch per lab → validated by field official → consumed atomically on collection.

**sample_requests** — request_id unique (`REQ-{6-digit}`), farmer_name, farmer_phone, farmer_address, zone_id → locations, status (`pending|assigned|collected`), field_official_id → users nullable, soil_sample_id nullable, submitter_ip, submitter_ua (security fields).

**targets** — zone_id → locations, target_count int, start_date/end_date date, created_by → users. Scopes: `active` (today within window), `forZone($zoneId)`.

### Messaging

**notifications** — zone_id nullable (zone-wide), user_id nullable, type string, title, body, data JSON nullable, is_read default false. Visibility rule: personal (`user_id`) OR zone-wide (`zone_id` set, `user_id` null matching user's zone).

**device_tokens** — user_id → users, token, platform. Upserted per (user, token) on FCM registration.

### Inventory

**lab_chemicals** — lab_id cascade, name, sku unique, unit, current_stock decimal(12,2) default 0, minimum_stock nullable, is_active.

**chemical_stock_movements** — lab_chemical_id, type (`in|out`), quantity, balance_before, balance_after, reference_type/reference_id morphTo nullable, notes, created_by.

**chemical_parameter_consumption** — explicit table name; lab_chemical_id, soil_test_parameter_id, consumption_per_test decimal. Rule: each test of a parameter consumes X units of a chemical.

### Configuration & audit

**recommendation_rules** — name, description, conditions JSON, recommendation text, priority int default 0, is_active. Ordered by priority desc when evaluating.

**custom_fields** — name, type, options JSON, required bool, order_column, is_active. **custom_field_values** — custom_field_id, valueable morphTo (farmer/plot/sample), value.

**settings** — key unique, value (all app config lives here; see §8).

**audit_logs** — user_id nullable, auditable_type/auditable_id polymorphic, event (`created|updated|deleted|login|logout|settings_changed`), old_values/new_values JSON nullable, url, ip_address(45), user_agent text, notes. Indexed on morph pair, event, created_at. Populated via `Auditable` trait on 16 models + `AuditLogger` service.

**personal_access_tokens** — Sanctum standard.

---

## 4. End-to-End Workflows

### 4.1 Public sample request flow

Route `/request-sample` (Livewire `Public\SampleRequestForm`, throttled 10 req/10min at route level).

Form fields: name, phone, full location cascade (state→district→zone→panchayat→village, all required), landmark/address.

**Anti-spam gauntlet on submit (in order):**
1. Honeypot hidden field non-empty → silently fake-succeed (`REQ-000000`)
2. Session form token must exist and not be expired (30 min TTL, single-use)
3. Submit speed check: < 3s since mount → reject ("take your time")
4. Rate limit: **3 submissions/hour/IP** (`sample-request|IP`)
5. Sanitize all inputs (`strip_tags(trim())`)
6. Block URLs in name/address (`https?://|www.` regex)
7. Validate (phone regex `^[6-9]\d{9}$`)
8. Phone cooldown: any pending request with same phone → reject
9. Duplicate check: same name+phone+zone pending → reject
10. Zone cap: ≥ 50 pending/assigned requests in zone → reject

On success: create SampleRequest (`REQ-` + zero-padded next id), store submitter IP + UA, hit rate limiter (1h window). If trigger enabled, notify all users in zone via `sendToZone` with templated message.

### 4.2 Request acceptance (field official, app)

1. App polls `GET /api/notifications/pending-requests` (own zone, pending/assigned)
2. Official accepts → request becomes `assigned`, field_official_id set
3. `POST /requests/{id}/prepare-farmer`: returns existing farmer matched by phone, else creates one (name/phone/zone; district/state resolved by walking zone's parent chain; lab_id from official)

### 4.3 Field sample collection flow (the core flow)

Wizard steps in Flutter (`CollectSampleScreen`) and web (`Field\SampleCollection`):

1. **Select/request source**: existing farmer search OR prepare-from-request OR new registration (name → parentage → phone → Aadhaar; phone 10 digits starting 6-9; Aadhaar 12 digits mandatory)
2. **Plot**: pick existing or create (name, khasra no, GPS capture, soil type/color, crop, area kanals, plant age). Location inherited from farmer.
3. **QR code**: scan/manual entry → `POST /qr-codes/validate` (must exist, unused, belong to official's lab)
4. **Submit** → `POST /samples/collect`:

Server pipeline (in order):
- Validate payload; optional request link must be pending/assigned
- QR must exist + unused + lab match
- Farmer must exist + have zone
- **Target gate:** active target for zone must exist AND not exceeded → else 422 with message
- **DB transaction** with `lockForUpdate` on lab row:
  - Generate code `{LAB_CODE}-{ZONE_CODE}-{NNNN}` — NNNN = count(existing samples in lab+zone)+1, zero-padded 4
  - Create SoilSample (status collected, source field)
  - Insert SampleStatusHistory (null → collected)
  - Mark QR used (used_by, used_at, soil_sample_id)
- Outside transaction: linked request → status `collected`, attach soil_sample_id

5. Working-hours enforcement (client side): app blocks submission outside configured hours/days; dashboard shows amber banner + locked action cards.

### 4.4 Lab processing flow

1. **Receive** (`Lab\SampleQueue.markReceived`): status → `received`, received_at=now. History row appended.
2. **Test** (`Lab\SampleTestingForm`): parameters active for the sample's lab (via pivot). Per parameter enter value → live auto-rating via thresholds + range warning if outside min/max.
3. **Generate recommendations** button: saves results, runs RecommendationEngine, fills textarea (editable).
4. **Save/Publish**: updateOrCreate results → sample status `reported`, analyzed_by/at set, recommendations saved → `consumeChemicals()`:
   - For each result whose parameter has consumption rules: decrement chemical stock, log ChemicalStockMovement (`out`, balances, reference=sample, notes `{sample_code} — {parameter}`)
   - Wrapped in DB::transaction
5. Notify collector (`send` to collected_by) if trigger enabled; browser opens report PDF.
6. **Report access**: `GET /samples/{id}/report/pdf` requires status `reported`; acknowledgement PDF available anytime. Role checks: Admin=all, Farmer=own only, Lab Manager/Field Official=same lab. Public URL `GET /report/{token}` (token printed as QR inside PDF).

### 4.5 Self-collection (lab walk-in)

`Lab\SelfSampleCollection`: lab staff registers walk-in farmers/samples at the desk. Farmer looked up globally by phone; source = `self`; same target-gate + transactional creation pipeline.

### 4.6 Targets workflow

Admin/Lab Manager create zone targets (count + date window) in `ZoneTargetManager`. Metrics computed live:
- `collectedCount` = field-source samples in zone within window (any status)
- `achievedCount` = subset that reached at least `received` within window
- `progressPercent = min(100, round(achieved/target*100))`; `isExceeded = collected >= target`

The target gate in collection uses `isExceeded()` to stop over-collecting.

### 4.7 QR batch lifecycle

Admin `QrCodeGenerator`: choose prefix + quantity + lab → rows created with sequential codes. Print sheets via `QrPrintController` (print all/selected). Codes are lab-scoped; validation and consumption enforce lab ownership end-to-end.

---

## 5. Recommendation Engine Spec

`App\Services\RecommendationEngine::generate(sample)`:

1. Load rules: `RecommendationRule::active()->orderByDesc('priority')`
2. For each rule, evaluate its `conditions` array — **ALL conditions must match** (AND)
3. **Empty conditions array = universal match** (always applies)
4. Join matched recommendation texts with `\n\n`

Condition JSON schema (each object):

```json
{ "type": "parameter_value", "parameter_id": 3, "operator": "lt", "value": 50 }
```

| type | Fields | Semantics |
|------|--------|-----------|
| `parameter_value` | parameter_id, operator, value, value_max (for between) | Numeric compare against test result: `lt, lte, eq (±0.001), gte, gt, between` |
| `parameter_rating` | parameter_id, rating | Result rating equals (case-insensitive) low/medium/high |
| `plant_age` | operator, value | Compare vs plot.age_of_plants (int cast) |
| `crop_is` | crop_id | Plot's crop_id equality |

Missing result / missing plot data → condition fails (rule doesn't match). Unknown type → false.

Seeded universal rules (20): e.g. N low → urea dose; P low → DAP; K low → MOP; pH acidic → lime; pH alkaline → gypsum; organic carbon low → FYM/compost etc.

---

## 6. Web Panels (Livewire components)

Namespace `App\Livewire\{Admin|Lab|Field|Public}`, views `resources/views/livewire/...`, layout `layouts.app` (AdminLTE sidebar) or `layouts.public`.

### Admin panel (`/admin/*`, role-gated)

| Component | Route | Purpose |
|-----------|-------|---------|
| Dashboard | admin.dashboard | Stats cards, charts (samples by status/zone/time) |
| UserManagement | admin.users | CRUD users, assign roles/lab/zone, activate/deactivate |
| RoleManagement | admin.roles | Spatie roles & permissions matrix |
| LabManagement | admin.labs | Labs CRUD, assign test-parameter pivot |
| LocationManagement | admin.locations | Location tree CRUD + CSV import template download |
| FarmerManagement | admin.farmers | Farmers list/edit (admin sees all; lab manager scoped) |
| ParameterManagement | admin.parameters | Test parameters incl. rating thresholds/labels/colors, lab assignment |
| DropdownManagement | admin.dropdowns | Soil types / colors / crops |
| CustomFieldManager | admin.custom-fields | Dynamic fields for farmer/plot/sample |
| QrCodeGenerator | admin.qr-codes | Batch generation + print selection |
| ZoneTargetManager | admin.zone-targets | Targets CRUD + progress |
| Reports | admin.reports | Analytics: donut/bar charts, filters |
| MenuManager | admin.menus | Nav menu + footer links (JSON in settings) |
| SampleRequestManager | admin.sample-requests | All requests table w/ status filter |
| RecommendationRules | admin.recommendation-rules | Rules CRUD w/ conditions builder |
| AppSettings | admin.app-settings | Version gating, working hours, announcements, guide |
| Settings | admin.settings | Site name/logo, reCAPTCHA, maintenance |
| FrontPageManager | admin.front-page | Full public homepage CMS (hero, notices, CHO card, vision/mission, contacts, meta, footer…) |
| InventoryManager | admin.inventory | Chemicals CRUD, stock in/out |
| ConsumptionRules | admin.inventory/{chemical}/consumption | Per-parameter consumption matrix |
| NotificationSettings | admin.notification-settings | FCM service account upload (encrypted), triggers editor |
| SendNotification | admin.send-notification | Compose push (role/zone/user targeting) |
| AuditLogManager | admin.audit-logs | Audit trail viewer: search, event/type/date filters, expandable diffs, CSV export, clear-all |

### Lab panel (`/lab/*`)
LabDashboard (stats + overdue count using `overdue_days` setting), SampleQueue (receive), SelfSampleCollection (walk-in), SampleTesting (list w/ overdue flags), SampleTestingForm (per-sample testing UI described above).

### Field panel (`/field/*`)
SampleCollection (web wizard mirroring app flow), SampleDetail, QR code browse/print. PDF endpoints under both field and farmer route groups.

### Farmer surface
SampleList filtered to own samples; SampleDetail; document downloads.

### Shared
Profile (Livewire), SampleList (unified, role-aware).

---

## 7. Notification System

Two layers: **DB notification rows** (in-app inbox) + **FCM push** (mobile).

### NotificationService

- `send(userId, type, title, body, data)` — creates row + pushes to that user's devices
- `sendToZone(zoneId, ...)` — every active user with that zone (rows carry zone_id)
- `sendToRole(roleName, ...)` — every active user having the Spatie role

### Triggers (settings key `notification_triggers`, JSON)

```json
{
  "sample_request":   { "enabled": true, "message": "{farmer_name} ({farmer_phone}) requested sample collection in your zone." },
  "sample_reported":  { "enabled": true, "message": "Sample {sample_code} has been tested and the report is ready." },
  "new_announcement": { "enabled": true, "message": "New announcement: {announcement}" }
}
```

Placeholders resolved via `resolve_notification_message()` helper. Fired from: public request form (to zone officials), sample publish (to collector), announcement save (to Field Officials).

### FirebaseService (FCM HTTP v1, no SDK)

- Service account JSON stored encrypted (`encrypted:` prefix + Crypt) in settings `notification_credentials`
- Auth: build RS256 JWT (iss=client_email, scope firebase.messaging, aud oauth2.googleapis.com/token, exp 1h) signed with openssl → exchange for access token → POST `fcm.googleapis.com/v1/projects/{project_id}/messages:send` per device token
- Data payload always includes `click_action=FLUTTER_NOTIFICATION_CLICK` + type + notification_id
- Housekeeping: delete DeviceToken rows returning `UNREGISTERED`/`INVALID_ARGUMENT`

Device registration: app calls `POST /api/notifications/register-device` after login (upsert per user+token).

---

## 8. Settings Catalog (DB `settings` table)

Helpers: `setting($key, $default)` (cached read), `set_setting($key, $value)`.

| Category | Keys |
|----------|------|
| App versioning/update | `app_version`, `app_update_url`, `force_update` |
| Maintenance | `maintenance_mode` |
| Security | `recaptcha_enabled`, `recaptcha_site_key`, `recaptcha_secret_key` |
| Lab ops | `overdue_days` (default 2) |
| Working hours | `app.working_hours_enabled`, `app.work_start_time`, `app.work_end_time`, `app.working_days` (JSON `[1..7]`, PHP N format Mon=1) |
| Announcements/guide | `front.app_announcements` JSON, `front.collection_guide` |
| Front page content | `front.hero_title/subtitle/photo_path/btn_text/btn_url`, `front.header_ministry/dept_en/dept_ur/dept_hi/emblem_path/flag_path`, `front.notice_label`, `front.notices` JSON, `front.helpline`, `front.working_hours`, `front.vision_text/mission_text/objectives_text`, `front.updates` JSON, `front.cta_*`, `front.meta_title/description/keywords`, `front.og_image_path`, `front.footer_about/copyright/disclaimer`, `front.footer_credit_text`, `front.nav_menu` JSON, `front.footer_links` JSON |
| CHO contact card | `front.cho_title/name/description/phone/email/address/post/photo_path`, `front.lab_phone` |
| Notifications | `notification_credentials` (encrypted service account JSON), `notification_triggers` JSON |

---

## 9. API Summary

Full endpoint reference with payloads: see **API.md** (same repo root). Quick map:

```
PUBLIC     POST /auth/login            GET /front-page
AUTH       POST /auth/logout           GET /user
           PUT  /user/profile          PUT  /user/password        POST /user/avatar
REF        GET  /soil-types /crops /soil-colors
LOCATIONS  GET  /locations/states      GET /locations/{id}/children    GET /locations/{id}/ancestors
FARMERS*   GET  /farmers/search        GET /farmers/{id}[+/plots]      POST /farmers
PLOTS*     POST /plots
REQUESTS   GET  /notifications/pending-requests   GET/POST /requests/{id}[/accept|/prepare-farmer]
NOTIFS     GET  /notifications[|/unread]         POST /notifications/register-device|mark-read
SAMPLES    GET  /samples               GET /samples/{id}
DOCS       GET  /samples/{id}/acknowledgement/pdf                 GET /samples/{id}/report/pdf
COLLECT*   GET  /qr-codes/available    POST /qr-codes/validate          POST /samples/collect
TARGETS    GET  /targets
```
`*` = permission-gated (`register-farmers`, `manage-plots`, `collect-samples`). Web-only extras: `GET /report/{token}` public report PDF.

---

## 10. Security Model

### Implemented

- Login rate limiting: web 5/60s per email+IP; API 5/60s per IP (`api-login|IP`)
- Session regeneration on login; CSRF everywhere (Laravel + Livewire built-in)
- Sanctum bearer tokens; revoked on logout; inactive accounts rejected at login
- Password bcrypt via `hashed` cast
- File uploads: MIME + size validated (avatar 2MB jpeg/png/jpg/webp; front-page images validated)
- Public form anti-spam stack (§4.1)
- SecurityHeaders middleware: X-Frame-Options, X-Content-Type-Options, Referrer-Policy, HSTS (prod only)
- CheckMaintenanceMode middleware (503 for non-admins; login/api/front-page exempt)
- Audit logging: Auditable trait (16 models, before/after JSON diffs, skips updated_at-only changes), AuditLogger (login/logout/settings changes); admin viewer with export
- SQL injection safe: parameterized Eloquent throughout

### Known gaps (fix before production hardening)

1. Aadhaar returned unmasked by farmer endpoints; farmer search/show/plots lack zone/lab scoping (IDOR risk)
2. Request detail/accept/prepare-farmer lack zone validation
3. `/samples/collect` + `/qr-codes/validate` trust client-sent `lab_id`
4. Notification mark-read by ids not user-scoped
5. Sanctum tokens never expire; password change doesn't revoke tokens
6. CORS `allowed_origins = *`
7. Weak password policy (min 6, no complexity)
8. Lab Manager can escalate to Admin via Livewire property tampering (UserManagement lacks server-side role guard)
9. Stored-XSS surfaces: `{!! setting('front.footer_copyright') !!}` / `footer_credit_text` on public home; login page renders error HTML
10. Missing CSP header; debug files in root (curl_test.php, make_placeholder.php, app-debug.apk); APP_DEBUG=true in dev .env

---

## 11. Flutter App Map (reference)

Package `com.lab.field`. Provider-based state (`auth_provider`, `front_page_provider`, `farmer_provider`, …), `config/api_config.dart` base URL switchable prod/local.

Screens: Splash/login → Dashboard (quick-actions grid: Collect Sample, My Samples, Requests, Targets, Register Farmer; working-hours banner + lock states) → CollectSample wizard (farmer select/search/new dialog → plot → QR scan → summary) → RegisterFarmer standalone → Profile/EditProfile (avatar, phone, password) → Requests list (accept/prepare-farmer) → Notifications (register FCM device, mark-read) → My Samples (status timeline, PDFs) .

Client-side gates: version/force-update + maintenance mode + working hours parsed from `GET /front-page`.
