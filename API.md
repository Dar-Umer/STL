# Soil Lab — API Documentation

Full reference for the Soil Testing Management System API. Use this to build custom clients (web apps, mobile apps, integrations).

---

## 1. Overview

| Item | Value |
|------|-------|
| **Production base URL** | `https://stl.theumer.store/api` |
| **Local dev base URL** | `http://192.168.31.150:8000/api` |
| **Format** | JSON |
| **Auth** | Bearer token (Laravel Sanctum) |
| **Total endpoints** | 34 |

### Conventions

- **camelCase** keys everywhere (e.g. `sample_code`, `farmer_id`)
- **ISO 8601** timestamps (`2026-07-01 10:30:00`)
- **Numbers as numbers**, never strings (`area_kanals: 5.5`)
- **null** for missing values, never `"N/A"`
- Collections wrapped in `.data` array; single resources in `.data` object
- Paginated responses use Laravel's standard paginator shape

### Error format

Validation errors return `422` with:

```json
{
  "message": "The phone field is required.",
  "errors": {
    "phone": ["The phone field is required."]
  }
}
```

Other errors: `401` (unauthenticated), `403` (forbidden), `404` (not found), `422` (validation/business rule), `500` (server error).

### Authentication

```http
POST /api/auth/login
Authorization: none
Content-Type: application/json

{ "email": "user@example.com", "password": "secret" }
```

All subsequent requests require:

```http
Authorization: Bearer <token>
```

- Login is rate-limited: **5 attempts / 60 seconds per IP**.
- Tokens do **not** expire (current config). Revoked on logout.
- The `CheckMaintenanceMode` middleware returns `503` for non-Admin users when maintenance is on. `GET /front-page` and `POST /auth/login` are exempt so the app can check maintenance before login.

### Roles & Permissions

Spatie roles/permissions. Routes enforce via `permission:` middleware. An Admin bypasses all checks.

| Permission | Routes |
|-----------|--------|
| `register-farmers` | `GET/POST /farmers*` |
| `manage-plots` | `POST /plots` |
| `collect-samples` | `POST /samples/collect`, `GET/POST /qr-codes/*` |
| *(none required)* | notifications, samples view, targets, locations, reference, user profile |

---

## 2. Endpoints Reference

### 2.1 Authentication & User Profile

#### `POST /auth/login` — Public
Logs in and returns a token + user object.

```json
{
  "token": "1|abc123def...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@soil.com",
    "phone": "9876543210",
    "avatar": "avatars/xyz.jpg",
    "is_active": true,
    "lab_id": 1,
    "zone_id": null,
    "roles": ["Admin"],
    "lab": { "id": 1, "name": "Main Lab", "code": "SL" },
    "zone": { "id": 5, "name": "North Zone" }
  }
}
```

Rejected cases (all `422`): invalid credentials, too many attempts, deactivated account.

#### `POST /auth/logout` — Auth
Revokes the current token. Response: `{ "message": "Logged out." }`

#### `GET /user` — Auth
Returns the user object (same shape as login).

#### `PUT /user/profile` — Auth
Updates profile. Only `phone` is editable.

```json
{ "phone": "9876543210" }
```

Response: `{ "data": { ...user object } }`

#### `PUT /user/password` — Auth
```json
{
  "current_password": "old123",
  "password": "new123",
  "password_confirmation": "new123"
}
```
Response: `{ "message": "Password updated." }`
*Note: existing tokens are NOT revoked on password change (known gap).*

#### `POST /user/avatar` — Auth · multipart/form-data
Field `avatar` — image only, `jpeg/png/jpg/webp`, max **2MB**. Uploaded to `storage/app/public/avatars/`. Response: `{ "data": { ...user object } }`.

---

### 2.2 Front Page — Public

#### `GET /front-page`
Used before login to gate the app (maintenance, force-update, working hours, contacts).

```json
{
  "data": {
    "maintenance_mode": false,
    "required_version": "1.0.0",
    "force_update": false,
    "store_url": "https://play.google.com/store/...",
    "app_announcements": ["New scheme launched"],
    "collection_guide": "<p>HTML guide text</p>",
    "contacts": {
      "cho_phone": "+91-XXXX-XXXXXX",
      "helpline": "1800-180-1551",
      "lab_phone": "+91-XXXX-XXXXXX",
      "cho_email": "cho.pulwama@jk.gov.in"
    },
    "working_hours": {
      "enabled": true,
      "start": "09:00",
      "end": "17:30",
      "days": [1, 2, 3, 4, 5]
    }
  }
}
```

`working_hours.days` uses PHP `date('N')` — **1 = Monday ... 7 = Sunday**. Clients should block collection outside `start–end` on non-enabled days.

---

### 2.3 Reference Data — Auth

| Endpoint | Response |
|----------|----------|
| `GET /soil-types` | `{ "data": [ { "id": 1, "name": "Sandy" } ] }` |
| `GET /crops` | `{ "data": [ { "id": 1, "name": "Rice" } ] }` |
| `GET /soil-colors` | `{ "data": [ { "id": 1, "name": "Red" } ] }` |

Only `is_active = true` records are returned, ordered by name.

---

### 2.4 Locations — Auth

Hierarchy: **state → district → zone → panchayat → village**

#### `GET /locations/states`
```json
{ "data": [ { "id": 1, "name": "Jammu & Kashmir", "type": "state", "code": "JK" } ] }
```

#### `GET /locations/{parentId}/children`
Returns direct children. Response includes the child `type`.

```json
{
  "type": "district",
  "data": [ { "id": 5, "name": "Rajouri", "type": "district", "code": "RJ", "parent_id": 1 } ]
}
```

Village has no children → `422 { "message": "No children available for this location type." }`

#### `GET /locations/{id}/ancestors`
Full chain, **most specific first** (used to rebuild a farmer's state/district when only a village/zone id is known).

```json
{
  "data": [
    { "id": 9, "name": "Village 1", "type": "village" },
    { "id": 7, "name": "Panchayat A", "type": "panchayat" },
    { "id": 6, "name": "North Zone", "type": "zone" },
    { "id": 5, "name": "Rajouri", "type": "district" },
    { "id": 1, "name": "Jammu & Kashmir", "type": "state" }
  ]
}
```

---

### 2.5 Farmers — `permission:register-farmers`

#### `GET /farmers/search?q=term`
Search by **name / phone / Aadhaar**. Requires `q` ≥ 2 chars, returns max 20.

> ⚠️ **Sensitive:** response includes `aadhar_no` in full. Treat as PII.

```json
{
  "data": [{
    "id": 1,
    "name": "Ramesh Kumar",
    "phone": "9876543210",
    "parentage": "Suresh",
    "aadhar_no": "123456789012",
    "state": { "id": 1, "name": "Jammu & Kashmir" },
    "district": { "id": 5, "name": "Rajouri" },
    "zone": { "id": 6, "name": "North Zone" },
    "panchayat": { "id": 7, "name": "Panchayat A" },
    "village": { "id": 9, "name": "Village 1" },
    "plot_count": 2,
    "created_at": "2026-07-01 10:00:00"
  }]
}
```

#### `GET /farmers/{id}`
Same shape, single object in `data`.

#### `GET /farmers/{id}/plots`
```json
{
  "data": [{
    "id": 1,
    "farmer_id": 1,
    "plot_name": "Plot A",
    "khasra_no": "123",
    "area_kanals": 5.5,
    "soil_type": "Sandy",
    "crop": "Rice",
    "age_of_plants": 3.0,
    "latitude": 33.1,
    "longitude": 74.2
  }]
}
```

#### `POST /farmers`
```json
{
  "name": "Ramesh Kumar",
  "phone": "9876543210",
  "parentage": "Suresh",
  "aadhar_no": "123456789012",
  "state_id": 1,
  "district_id": 5,
  "zone_id": 6,
  "panchayat_id": 7,
  "village_id": 9,
  "lab_id": 1
}
```

**Validation rules:**

| Field | Rule |
|-------|------|
| `name` | required, string, ≤255 |
| `phone` | required, **exactly 10 digits**, must start with `6/7/8/9`, **unique** per farmer |
| `parentage` | required, string, ≤255 |
| `aadhar_no` | required, **exactly 12 digits** |
| `state_id` / `district_id` | required, must exist in `locations` |
| `zone_id` / `panchayat_id` / `village_id` | nullable, must exist in `locations` |
| `lab_id` | required, must exist in `labs` |

Response `201` with full farmer object + `"message": "Farmer registered successfully."`.

---

### 2.6 Plots — `permission:manage-plots`

#### `POST /plots`
```json
{
  "farmer_id": 1,
  "plot_name": "Plot A",
  "khasra_no": "123",
  "area_kanals": 5.5,
  "latitude": 33.1,
  "longitude": 74.2,
  "soil_type_id": 1,
  "crop_id": 2,
  "age_of_plants": 3.0
}
```

- `area_kanals` / `age_of_plants`: required, numeric, ≥ 0
- **Location is inherited automatically** from the farmer's `state/district/zone/panchayat/village` — client does not send them.

Response `201`: `{ "message": "Plot created successfully.", "data": { ...plot object } }`

---

### 2.7 Sample Requests — Auth

Sample requests come from the **public web form** (`/request-sample`) and are claimed/collected by field officials.

Status flow: `pending → assigned → collected`

#### `GET /notifications/pending-requests`
Requests in the **user's own zone** with status `pending` or `assigned`.

```json
{
  "zone_id": 6,
  "data": [{
    "id": 1,
    "request_id": "REQ-000001",
    "farmer_name": "Ramesh Kumar",
    "farmer_phone": "9876543210",
    "farmer_address": "Village 1, Panchayat A",
    "zone": "North Zone",
    "status": "pending",
    "created_at": "2026-07-01 10:00:00"
  }]
}
```

Returns `403` if the user has no zone assigned.

#### `GET /requests/{id}`
Single request detail (same shape).

#### `POST /requests/{id}/accept`
Claims the request for the current user. Only `pending`/`assigned` allowed.

```json
{
  "message": "Request accepted successfully.",
  "data": { "id": 1, "request_id": "REQ-000001", "status": "assigned" }
}
```

#### `POST /requests/{id}/prepare-farmer`
Used by the collection wizard to pre-fill a farmer from a sample request.

- If a farmer with the request's **phone** already exists → returns it.
- Otherwise **creates** a new farmer (name + phone + zone; `parentage`/`aadhar_no` are null). Resolves `district_id`/`state_id` by walking the zone's parent chain.
- Requires the user to have a `lab_id`.

Response `200` (existing) or `201` (created), full farmer object.

---

### 2.8 Notifications — Auth

Notifications are scoped to the user: personal (`user_id` set) **or** zone-wide (`zone_id` set, `user_id` null) for the user's zone.

#### `GET /notifications?page=1`
Paginated (20/page), Laravel paginator shape. Item:

```json
{
  "id": 1,
  "type": "announcement",
  "title": "New Announcement",
  "body": "...",
  "data": {},
  "is_read": false,
  "created_at": "2026-07-01 10:00:00"
}
```

#### `GET /notifications/unread`
All unread notifications for the user (personal + zone).

#### `POST /notifications/register-device` — Auth
Registers the device for **FCM push notifications**.

```json
{ "token": "firebase_messaging_token", "platform": "android" }
```

Upserts per `(user_id, token)`. Response: `{ "message": "Device registered for push notifications." }`

#### `POST /notifications/mark-read`
```json
// Mark specific:
{ "ids": [1, 2, 3] }
// Or mark all:
{ "all": true }
```
Response: `{ "message": "Notifications marked as read." }`

---

### 2.9 Samples — Auth

#### `GET /samples`
Samples **collected by the current user** (field official's own list), newest first.

```json
{
  "data": [{
    "id": 1,
    "sample_code": "SL-N-0001",
    "status": "collected",
    "status_label": "Collected",
    "qr_code": "SL0001",
    "collected_at": "2026-07-01 10:30:00",
    "received_at": null,
    "analyzed_at": null,
    "farmer": { "id": 1, "name": "Ramesh Kumar", "phone": "9876543210" },
    "plot": {
      "id": 1, "farmer_id": 1, "plot_name": "Plot A", "khasra_no": "123",
      "latitude": 33.1, "longitude": 74.2, "area_kanals": 5.5, "age_of_plants": 3.0,
      "soil_type": "Sandy", "crop": "Rice"
    },
    "collector": { "id": 2, "name": "Field Officer" },
    "lab": { "id": 1, "name": "Main Lab" },
    "created_at": "2026-07-01 10:30:00"
  }]
}
```

#### `GET /samples/{id}`
Full detail via `SampleResource` — includes farmer (all locations), plot (soil type/crop/color), collector, lab, and `statusHistory`.

**Status enum:** `collected → received → testing → reported`

#### PDF documents
| Endpoint | Notes |
|----------|-------|
| `GET /samples/{id}/acknowledgement/pdf` | Any status |
| `GET /samples/{id}/report/pdf` | **404 unless status = `reported`** |

Access rules: Admin = all; Farmer = own sample only; Lab Manager / Field Official = same lab. Others = 403.

---

### 2.10 QR Codes & Sample Collection — `permission:collect-samples`

#### `GET /qr-codes/available`
Unused QR codes for the **user's lab** (lab_id taken from the authenticated user).

```json
{ "data": [ { "id": 1, "code": "SL0001", "prefix": "SL", "sequence": 1 } ] }
```

#### `POST /qr-codes/validate`
```json
{ "code": "SL0001", "lab_id": 1 }
```
```json
// Valid
{ "valid": true, "message": "QR code is valid.", "qr_code": { "id": 1, "code": "SL0001", "prefix": "SL", "sequence": 1 } }
// Invalid variants
{ "valid": false, "message": "QR code not found in system." }
{ "valid": false, "message": "QR code already used." }
{ "valid": false, "message": "QR code belongs to a different lab." }
```

#### `POST /samples/collect`
The core field-collection flow. See §3.3 for the full pipeline.

```json
{
  "request_id": "REQ-000001",     // optional — link to a sample request
  "farmer_id": 1,
  "plot_id": 1,
  "qr_code": "SL0001",
  "lab_id": 1,
  "collected_at": "2026-07-01T10:30:00"
}
```

Response `201`:

```json
{
  "message": "Sample collected successfully.",
  "data": {
    "id": 1,
    "sample_code": "SL-N-0001",
    "status": "collected",
    "lab_id": 1,
    "collected_at": "2026-07-01 10:30:00"
  },
  "request_id": "REQ-000001"
}
```

---

### 2.11 Targets — Auth

#### `GET /targets`
Targets for the user's zone, newest first.

```json
{
  "targets": [{
    "id": 1,
    "zone_id": 6,
    "zone_name": "North Zone",
    "target_count": 100,
    "start_date": "2026-07-01",
    "end_date": "2026-07-31",
    "collected_count": 45,
    "achieved_count": 30,
    "progress_percent": 30,
    "remaining_count": 70,
    "is_exceeded": false,
    "created_by": "Admin",
    "created_at": "2026-07-01 10:00:00"
  }]
}
```

Returns `403` if user has no zone. Definitions:

| Field | Meaning |
|-------|---------|
| `collected_count` | samples collected in the window (`source = field`, in zone) |
| `achieved_count` | collected **and** at least `received` in the window |
| `progress_percent` | `min(100, round(achieved / target * 100))` |
| `remaining_count` | `max(0, target − achieved)` |
| `is_exceeded` | `collected_count >= target_count` |

---

## 3. Important Implementation Details

### 3.1 Login flow (`Api\AuthController::login`)

1. Validate `email` + `password`.
2. Rate-limit check: key `api-login|{IP}`, **5 attempts, 60s window**.
3. `Auth::attempt()`; on failure `RateLimiter::hit()` and `422`.
4. Reject **deactivated** accounts (`is_active = false`).
5. Clear limiter, create Sanctum token (`createToken('flutter-app')`).
6. Eager-load the user's `lab` and `zone` into the response.

### 3.2 Farmer registration

- Phone: `digits:10` + `regex:/^[6789]/` + unique across `farmers.phone` → prevents duplicate registrations.
- Aadhaar: `digits:12` mandatory.
- `parentage` mandatory (disambiguates same-name farmers).
- `created_by` is always set server-side to the authenticated user — never accepted from input.
- All location ids validated as `exists:locations,id`.

### 3.3 Sample collection pipeline (`SampleCollectionController::collect`)

1. **Validate** request payload (request_id optional, farmer/plot/qr/lab/collected_at required).
2. **Link request** (if `request_id`): must exist and be `pending`/`assigned`.
3. **Validate QR**: must exist, unused, and belong to the submitted lab.
4. **Farmer check**: must exist and have a `zone_id`.
5. **Target gate**: an **active** target must exist for the farmer's zone, and it must **not** be exceeded — otherwise `422`.
6. **DB transaction** (`lockForUpdate` on the lab row — race-safe code generation):
   - Generate code: `{LAB_CODE}-{ZONE_CODE}-{NNNN}` where NNNN = count of existing samples for that lab+zone + 1, zero-padded to 4.
   - Create `SoilSample` (`status = collected`, `source = field`).
   - Insert `SampleStatusHistory` (`null → collected`).
   - Mark QR `is_used = true`, set `used_by`, `used_at`, `soil_sample_id`.
7. **Outside transaction**: if a request was linked, set it to `collected` and attach `soil_sample_id`.
8. Wrap the whole thing in try/catch → `500` with generic message on failure (details only logged).

### 3.4 Sample code generation

```
LAB_CODE - ZONE_CODE - NNNN
   SL    -    N      - 0001
```

- `LAB_CODE` from the `labs.code`.
- `ZONE_CODE` from `locations.code` of the farmer's zone (fallback `XX`).
- Counter is **per (lab, zone)** — scoped via `where('lab_id')` + `whereHas('farmer', zone_id)`.
- Safe under concurrency because the lab row is `lockForUpdate` within a transaction.

### 3.5 Location hierarchy resolution

- `children()` maps parent type → child type via `['state'=>'district', 'district'=>'zone', 'zone'=>'panchayat', 'panchayat'=>'village']`.
- `ancestors()` walks `parent_id` upward collecting the id chain, then batch-loads and orders most-specific-first. Used by `prepare-farmer` to rebuild `state_id`/`district_id` from just a `zone_id`.

### 3.6 Request → Farmer prep (`prepareFarmer`)

1. Load the sample request.
2. If a farmer exists with the request phone → return it.
3. Else resolve `district_id`/`state_id` from the request's `zone_id` (via `ancestors` logic).
4. Create farmer with `name`, `phone`, zone ids, `lab_id` (from the acting user), `created_by` (user).
5. Return full farmer object. The client can then send Aadhaar/parentage through the normal `POST /farmers` update path.

### 3.7 Push notifications

- Devices registered via `POST /notifications/register-device` (FCM token) → `device_tokens` table, upserted per `(user_id, token)`.
- Server-side `NotificationService::sendToRole(...)` sends to all users of a role (e.g. Field Officials) via **FCM v1**.
- Announcements in `AppSettings` trigger push when `new_announcement` trigger is enabled.
- `is_read` tracking is per-user; zone-wide notifications appear for every user in the zone.

### 3.8 Working hours (app-side enforcement)

- Config lives in app settings and is served by `GET /front-page` as `working_hours`.
- `days` = PHP `date('N')` (Mon=1 … Sun=7).
- The Flutter app blocks collection and shows a warning banner outside working hours; the dashboard greys out collection cards with a lock icon.

### 3.9 Sample status lifecycle

`collected → received → testing → reported`

- Every transition recorded in `sample_status_histories` (`from_status`, `to_status`, `changed_by`, `notes`).
- Transitions performed by lab staff via the Livewire admin (`SampleQueue::markReceived`, `SampleTestingForm`), not the public API.
- Reports are only downloadable once `status = reported`.

### 3.10 Front-page / maintenance / update gating

- `GET /front-page` is public and returns `maintenance_mode`, `required_version`, `force_update`, `store_url`.
- The app compares `required_version` against its own version and forces the store redirect when `force_update` is true.
- `CheckMaintenanceMode` middleware returns `503` to non-admins when maintenance is on.

---

## 4. Sample Request Statuses

| Status | Meaning |
|--------|---------|
| `pending` | Submitted via public form, not yet claimed |
| `assigned` | Claimed by a field official (`accept`) |
| `collected` | Sample collected and linked (`/samples/collect`) |

## 5. Sample Statuses

| Value | Label | Description |
|-------|-------|-------------|
| `collected` | Collected | Sample taken in the field |
| `received` | Received | Arrived at the lab |
| `testing` | Testing | Being analyzed |
| `reported` | Reported | Results published, report available |

---

## 6. Security Notes

- `aadhar_no` is returned **in full** by farmer endpoints — treat as protected PII (Aadhaar Act 2016). Prefer masking (last 4 digits) in any new client UI.
- Known gaps (from security audit, pending fix):
  - `GET /requests/{id}`, `POST /requests/{id}/accept`, `prepare-farmer` lack zone scoping — any authenticated user can access.
  - `GET /farmers/search`, `GET /farmers/{id}`, `GET /farmers/{id}/plots` lack lab/zone scoping.
  - `POST /samples/collect` and `POST /qr-codes/validate` accept `lab_id` from the client instead of trusting the authenticated user's lab.
  - Sanctum tokens never expire; password change does not revoke existing tokens.
- CORS is currently `*` (safe for native apps, tighten before exposing a web client).
