# RBAC Architecture: Roles, Permissions & Menu Visibility

> **Purpose**: This document describes the complete Role-Based Access Control (RBAC) architecture used by Super Admin. It is written as a **platform-agnostic specification** so the system can be re-implemented in ASP.NET C#, Node.js, Python (Django/Flask), Java (Spring Boot), or any other framework.

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Database Schema](#2-database-schema)
3. [Entity Relationships (ERD)](#3-entity-relationships-erd)
4. [Core Entities](#4-core-entities)
5. [Authentication Flow](#5-authentication-flow)
6. [Permission Resolution Algorithm](#6-permission-resolution-algorithm)
7. [Menu Visibility Algorithm](#7-menu-visibility-algorithm)
8. [Middleware Pipeline](#8-middleware-pipeline)
9. [Permission Checking Methods](#9-permission-checking-methods)
10. [Granting & Revoking Access](#10-granting--revoking-access)
11. [Operation Audit Log](#11-operation-audit-log)
12. [Configuration Flags](#12-configuration-flags)
13. [Default Seed Data](#13-default-seed-data)
14. [API Reference for Porting](#14-api-reference-for-porting)
15. [Implementation Checklist by Platform](#15-implementation-checklist-by-platform)

---

## 1. System Overview

Super Admin uses a **classic RBAC (Role-Based Access Control)** model with the following characteristics:

- **Separate auth system**: Admin users are completely isolated from front-end application users. They have their own database tables, authentication guard, session, and login page.
- **Two-path permission assignment**: Permissions can be assigned to a user **directly** OR **through roles** (indirect). Both paths are merged when evaluating access.
- **Route-based permissions**: Each permission is bound to one or more HTTP route patterns (method + path). The system intercepts every request and checks whether the current user's resolved permissions match the requested route.
- **Role-based menu visibility**: Sidebar menu items can be restricted to specific roles. Menu items also support an optional direct permission binding.
- **Super-administrator bypass**: Users with the `administrator` role slug bypass ALL permission checks automatically.

### High-Level Architecture

```
┌─────────────┐     ┌─────────────┐     ┌─────────────────┐
│   Request    │────▶│ Middleware   │────▶│   Controller    │
│  (HTTP)      │     │  Pipeline   │     │   (CRUD Page)   │
└─────────────┘     └──────┬──────┘     └─────────────────┘
                           │
              ┌────────────┼────────────┐
              ▼            ▼            ▼
        ┌──────────┐ ┌──────────┐ ┌──────────┐
        │  Auth    │ │Permission│ │  Log     │
        │  Check   │ │  Check   │ │Operation │
        └──────────┘ └────┬─────┘ └──────────┘
                          │
                    ┌─────┴─────┐
                    ▼           ▼
              ┌──────────┐ ┌──────────┐
              │  Direct  │ │  Role    │
              │  Perms   │ │  Perms   │
              └──────────┘ └──────────┘
```

---

## 2. Database Schema

The system requires **9 tables**. All table names are configurable via a config file.

### 2.1 Primary Tables

#### `admin_users` — Administrator Accounts

| Column           | Type         | Constraints        | Description                          |
| ---------------- | ------------ | ------------------ | ------------------------------------ |
| `id`             | INT (PK)     | auto-increment     | Primary key                          |
| `username`       | VARCHAR(190) | unique, not null   | Login username                       |
| `password`       | VARCHAR(60)  | not null           | Bcrypt-hashed password               |
| `name`           | VARCHAR(255) | not null           | Display name                         |
| `avatar`         | VARCHAR(255) | nullable           | Avatar image path or URL             |
| `remember_token` | VARCHAR(100) | nullable           | Session "remember me" token          |
| `created_at`     | TIMESTAMP    |                    |                                      |
| `updated_at`     | TIMESTAMP    |                    |                                      |

#### `admin_roles` — Roles

| Column       | Type        | Constraints      | Description                                  |
| ------------ | ----------- | ---------------- | -------------------------------------------- |
| `id`         | INT (PK)    | auto-increment   | Primary key                                  |
| `name`       | VARCHAR(50) | unique, not null | Human-readable name (e.g., "Editor")         |
| `slug`       | VARCHAR(50) | unique, not null | Machine-readable key (e.g., "editor")        |
| `created_at` | TIMESTAMP   |                  |                                              |
| `updated_at` | TIMESTAMP   |                  |                                              |

> **Important**: The slug `administrator` has special meaning — users with this role bypass all permission checks.

#### `admin_permissions` — Permissions

| Column        | Type         | Constraints      | Description                                           |
| ------------- | ------------ | ---------------- | ----------------------------------------------------- |
| `id`          | INT (PK)     | auto-increment   | Primary key                                           |
| `name`        | VARCHAR(50)  | unique, not null | Human-readable name (e.g., "Manage Posts")            |
| `slug`        | VARCHAR(50)  | unique, not null | Machine-readable key (e.g., "manage-posts")           |
| `http_method` | VARCHAR(255) | nullable         | Comma-separated HTTP methods (e.g., "GET,POST")       |
| `http_path`   | TEXT         | nullable         | Newline-separated route patterns (e.g., "/posts*")    |
| `created_at`  | TIMESTAMP    |                  |                                                       |
| `updated_at`  | TIMESTAMP    |                  |                                                       |

**Special values:**
- `slug = '*'` → Wildcard permission (matches everything)
- `http_path = '*'` → Matches all paths
- `http_method = ''` (empty) → Matches ALL HTTP methods
- `http_path` supports inline method override: `GET,POST:/specific-path`

#### `admin_menu` — Sidebar Menu Items

| Column       | Type         | Constraints    | Description                                        |
| ------------ | ------------ | -------------- | -------------------------------------------------- |
| `id`         | INT (PK)     | auto-increment | Primary key                                        |
| `parent_id`  | INT          | default 0      | Parent menu item ID (0 = root level)               |
| `order`      | INT          | default 0      | Sort order within the same parent                  |
| `title`      | VARCHAR(50)  | not null       | Display title                                      |
| `icon`       | VARCHAR(50)  | not null       | Icon CSS class (e.g., "icon-users")                |
| `uri`        | VARCHAR(255) | nullable       | Route URI (null/empty = parent folder, no link)    |
| `permission` | VARCHAR(255) | nullable       | Direct permission slug binding (optional)          |
| `created_at` | TIMESTAMP    |                |                                                    |
| `updated_at` | TIMESTAMP    |                |                                                    |

### 2.2 Pivot Tables (Many-to-Many Joins)

#### `admin_role_users` — User ↔ Role

| Column    | Type | Index                         |
| --------- | ---- | ----------------------------- |
| `role_id` | INT  | composite: (role_id, user_id) |
| `user_id` | INT  | composite: (role_id, user_id) |

#### `admin_role_permissions` — Role ↔ Permission

| Column          | Type | Index                                |
| --------------- | ---- | ------------------------------------ |
| `role_id`       | INT  | composite: (role_id, permission_id)  |
| `permission_id` | INT  | composite: (role_id, permission_id)  |

#### `admin_user_permissions` — User ↔ Permission (Direct)

| Column          | Type | Index                                |
| --------------- | ---- | ------------------------------------ |
| `user_id`       | INT  | composite: (user_id, permission_id)  |
| `permission_id` | INT  | composite: (user_id, permission_id)  |

#### `admin_role_menu` — Role ↔ Menu

| Column    | Type | Index                         |
| --------- | ---- | ----------------------------- |
| `role_id` | INT  | composite: (role_id, menu_id) |
| `menu_id` | INT  | composite: (role_id, menu_id) |

#### `admin_operation_log` — Audit Trail

| Column       | Type         | Constraints    | Description                    |
| ------------ | ------------ | -------------- | ------------------------------ |
| `id`         | INT (PK)     | auto-increment | Primary key                    |
| `user_id`    | INT          | indexed        | The admin user who acted       |
| `path`       | VARCHAR(255) | not null       | Request path                   |
| `method`     | VARCHAR(10)  | not null       | HTTP method                    |
| `ip`         | VARCHAR(255) | not null       | Client IP address              |
| `input`      | TEXT         | not null       | JSON-encoded request payload   |
| `created_at` | TIMESTAMP    |                |                                |
| `updated_at` | TIMESTAMP    |                |                                |

---

## 3. Entity Relationships (ERD)

```
admin_users ──M:N──▶ admin_roles ──M:N──▶ admin_permissions
     │                    │
     │                    │
     └──M:N──▶ admin_permissions        (direct user-permission)
                          │
admin_menu ──M:N──▶ admin_roles          (menu visibility by role)
     │
     └──────▶ permission (column)        (menu visibility by permission slug)

admin_operation_log ──N:1──▶ admin_users (audit trail)
```

**Relationships Summary:**

| Relationship                  | Type | Via Pivot Table            |
| ----------------------------- | ---- | -------------------------- |
| User ↔ Role                   | M:N  | `admin_role_users`         |
| User ↔ Permission (direct)    | M:N  | `admin_user_permissions`   |
| Role ↔ Permission             | M:N  | `admin_role_permissions`   |
| Role ↔ Menu                   | M:N  | `admin_role_menu`          |
| Menu → Permission (optional)  | Column reference | `admin_menu.permission` |
| OperationLog → User           | N:1  | FK `user_id`               |

---

## 4. Core Entities

### 4.1 Administrator (User Model)

The admin user model implements:
- Standard authentication (login/logout with session + remember token)
- `HasPermissions` trait providing all permission-checking methods
- `roles()` → BelongsToMany → Role
- `permissions()` → BelongsToMany → Permission (direct assignments)

### 4.2 Role

- `administrators()` → BelongsToMany → Administrator
- `permissions()` → BelongsToMany → Permission
- `menus()` → BelongsToMany → Menu
- `can(slug)` → checks if role has a specific permission
- On delete: automatically detaches from all administrators and permissions

### 4.3 Permission

- `roles()` → BelongsToMany → Role
- `shouldPassThrough(request)` → evaluates if the current HTTP request matches this permission's method+path pattern
- `http_method` stored as comma-separated string, accessed as array
- `http_path` stored as newline-separated patterns
- On delete: automatically detaches from all roles

### 4.4 Menu

- Uses a tree structure (`parent_id` self-referencing)
- `roles()` → BelongsToMany → Role
- `permission` column → optional direct permission slug
- `allNodes()` → retrieves all menu items, optionally eager-loading roles
- `toTree()` → builds nested array from flat rows
- `withPermission()` → returns whether `menu_bind_permission` config is enabled

---

## 5. Authentication Flow

### 5.1 Login Process

```
1. User visits /admin/auth/login
2. Submits username + password
3. AuthController validates input
4. Calls Auth::guard('admin')->attempt(credentials, remember)
5. On success: regenerate session, redirect to admin dashboard
6. On failure: increment rate limiter, show error
```

### 5.2 Guard Configuration

The admin panel uses its own authentication guard, completely separate from the main application:

```
Guard name: "admin"
Driver: session
Provider: "admin" (Eloquent provider using Administrator model)
```

This is configured in `config/admin.php → auth.guards` and merged into the application's `auth.php` config at boot time.

### 5.3 Login Throttling

- Configurable via `auth.throttle_logins` (boolean)
- `auth.throttle_attempts` → max failed attempts (default: 5)
- `auth.throttle_timeout` → lockout duration in seconds (default: 900)

---

## 6. Permission Resolution Algorithm

This is the core algorithm that determines whether a user has access to a specific route.

### 6.1 Middleware Execution Order

Every admin request passes through this middleware pipeline (in order):

```
1. admin.auth       → Is user logged in? If not, redirect to login.
2. admin.throttle   → Rate limiting for requests.
3. admin.pjax       → PJAX support (partial page loads).
4. admin.log        → Log the operation to audit trail.
5. admin.bootstrap  → Run admin bootstrap file.
6. admin.permission → CHECK PERMISSIONS (described below).
```

### 6.2 Permission Check Algorithm (Pseudocode)

```
function handlePermissionCheck(request):
    // Step 1: Global disable check
    IF config('check_route_permission') is FALSE:
        RETURN pass  // All permissions disabled globally

    // Step 2: Skip if no authenticated user, or if route has explicit middleware args
    IF no authenticated user OR route has explicit permission middleware args:
        RETURN pass

    // Step 3: Check exempted routes
    IF request.path matches any exempt route:
        RETURN pass
    
    // Exempt routes (always accessible):
    //   - auth/login
    //   - auth/logout
    //   - _handle_action_
    //   - _handle_form_
    //   - _handle_selectable_
    //   - _handle_renderable_
    //   - Any paths in config('auth.excepts')

    // Step 4: Check route-level middleware permission
    IF route has middleware starting with "admin.permission:":
        Extract method and args from middleware string
        // e.g., "admin.permission:allow,administrator,editor"
        //   method = "allow", args = ["administrator", "editor"]
        // e.g., "admin.permission:check,manage-posts"
        //   method = "check", args = ["manage-posts"]
        // e.g., "admin.permission:deny,guest"
        //   method = "deny", args = ["guest"]
        Call Permission::{method}(args)
        RETURN pass (method throws 403 on failure)

    // Step 5: Resolve ALL user permissions and match against request
    allPermissions = getUserAllPermissions(user)
    // This merges:
    //   a) Direct user permissions (admin_user_permissions pivot)
    //   b) Role-inherited permissions (user → roles → permissions)

    FOR EACH permission IN allPermissions:
        IF permission.shouldPassThrough(request):
            RETURN pass

    // Step 6: No matching permission found
    RETURN 403 error page
```

### 6.3 `allPermissions()` Resolution

```
function getUserAllPermissions(user):
    // Get permissions from all user's roles
    rolePermissions = user.roles
        .eagerLoad('permissions')
        .flatMap(role => role.permissions)
    
    // Get direct user permissions
    directPermissions = user.permissions
    
    // Merge both sources
    RETURN rolePermissions.merge(directPermissions)
```

### 6.4 `shouldPassThrough(request)` — Permission-to-Request Matching

```
function shouldPassThrough(permission, request):
    // If permission has no method AND no path, it passes everything
    IF permission.http_method is EMPTY AND permission.http_path is EMPTY:
        RETURN true
    
    // Parse each path line
    FOR EACH path_line IN permission.http_path.split("\n"):
        method = permission.http_method  // e.g., ["GET", "POST"]
        path = path_line
        
        // Check for inline method override (e.g., "GET,POST:/users")
        IF path contains ":":
            [method_override, path] = path.split(":")
            method = method_override.split(",")
        
        // Prepend the admin route prefix
        full_path = config('route.prefix') + path  // e.g., "admin/users*"
        
        // Match request path using wildcard matching
        IF request.pathMatches(full_path):
            // If no methods specified, any method matches
            IF method is EMPTY:
                RETURN true
            // Otherwise, check the HTTP method matches
            IF request.method IN method.map(uppercase):
                RETURN true
    
    RETURN false
```

### 6.5 Super-Administrator Bypass

Before any permission check, the system checks:

```
function isAdministrator(user):
    RETURN user.roles.any(role => role.slug == "administrator")
```

If `true`, **ALL permission checks are skipped** — the administrator has unrestricted access.

---

## 7. Menu Visibility Algorithm

The sidebar menu is rendered from a tree structure. Each menu item undergoes **two visibility checks** before being displayed.

### 7.1 Menu Building Process

```
1. Load all menu items from admin_menu table, ordered by (parent_id, order)
2. If config('check_menu_roles') is true, eager-load roles relationship
3. Build nested tree array from flat rows using parent_id
4. Render tree recursively; for each item, apply visibility filter
```

### 7.2 Visibility Check (Per Menu Item)

Each menu item is wrapped in a visibility check:

```
function isMenuItemVisible(user, menuItem):
    // Check 1: Role-based visibility
    IF NOT user.visible(menuItem.roles):
        RETURN false
    
    // Check 2: Permission-based visibility
    IF NOT user.can(menuItem.permission):
        RETURN false
    
    RETURN true
```

### 7.3 `user.visible(roles)` Algorithm

```
function visible(user, roles):
    // If no roles are assigned to the menu item, it's visible to everyone
    IF roles is EMPTY:
        RETURN true
    
    // Extract role slugs
    roleSlugs = roles.map(r => r.slug)
    
    // User sees it if they have ANY of the required roles, OR if they're admin
    RETURN user.inRoles(roleSlugs) OR user.isAdministrator()
```

### 7.4 `user.can(permission)` Algorithm

```
function can(user, permissionSlug):
    // Empty/null permission means no restriction
    IF permissionSlug is EMPTY:
        RETURN true
    
    // Administrators bypass all checks
    IF user.isAdministrator():
        RETURN true
    
    // Check direct user permissions
    IF user.permissions.any(p => p.slug == permissionSlug):
        RETURN true
    
    // Check role-inherited permissions
    IF user.roles.flatMap(r => r.permissions).any(p => p.slug == permissionSlug):
        RETURN true
    
    RETURN false
```

### 7.5 Menu Item with Children

When a menu item has children, it acts as a collapsible folder. The parent's visibility controls the entire group. Each child also has its own independent visibility check via recursive rendering.

```
Render each menu item:
    IF isMenuItemVisible(user, item):
        IF item has children:
            Render as collapsible parent
            FOR EACH child IN item.children:
                Recursively render child (with its own visibility check)
        ELSE:
            Render as clickable link
```

### 7.6 Menu Permission Binding

When `menu_bind_permission` is `true` in config, the menu management UI shows a permission dropdown allowing admins to bind a permission slug directly to a menu item. This provides **double-layer control**:

- **Layer 1 (Roles)**: Which roles can see this menu item? (via `admin_role_menu` pivot)
- **Layer 2 (Permission)**: Does the user also hold this specific permission? (via `admin_menu.permission` column)

Both checks must pass for the menu item to appear.

---

## 8. Middleware Pipeline

### 8.1 Complete Middleware Stack

The `admin` middleware group runs on all admin routes:

| Order | Middleware          | Alias              | Purpose                                        |
| ----- | ------------------- | ------------------ | ---------------------------------------------- |
| 1     | `Authenticate`      | `admin.auth`       | Redirect guests to login page                  |
| 2     | `Throttle`          | `admin.throttle`   | Rate-limit requests                            |
| 3     | `Pjax`              | `admin.pjax`       | Handle PJAX partial page loads                 |
| 4     | `LogOperation`      | `admin.log`        | Write to operation_log table                   |
| 5     | `Bootstrap`         | `admin.bootstrap`  | Execute admin bootstrap file                   |
| 6     | `Permission`        | `admin.permission`  | **Check route permissions** (core RBAC)        |

### 8.2 Authentication Middleware Detail

```
function authenticate(request):
    Set default auth guard to "admin"
    
    IF user is guest AND request is not in exempt list:
        Redirect to login page
    
    RETURN next(request)
```

**Exempt routes** (no login required):
- Routes listed in `config('auth.excepts')` — default: `auth/login`, `auth/logout`

### 8.3 Operation Log Middleware Detail

```
function logOperation(request):
    IF logging is enabled
       AND request path is not in except list
       AND request method is in allowed methods
       AND user is authenticated:
        
        Record to admin_operation_log:
            user_id, path, method, ip, input (JSON)
        
        Filter sensitive fields from input:
            token → "*****-filtered-out-*****"
            password → "*****-filtered-out-*****"
    
    RETURN next(request)
```

---

## 9. Permission Checking Methods

The system provides three high-level permission checking methods that can be used in **middleware** or **controller code**:

### 9.1 `Permission::check(slugs)` — Check Permission Slugs

```
Verifies user holds specific permission slug(s).
If user doesn't have the permission → 403 error.
Administrator role → always passes.

Usage in middleware: admin.permission:check,manage-posts
Usage in controller: Permission::check('manage-posts')
Usage with multiple: Permission::check(['manage-posts', 'view-users'])
```

### 9.2 `Permission::allow(roles)` — Allow Specific Roles

```
Allows only users with specific role slug(s).
If user doesn't have any of the roles → 403 error.
Administrator role → always passes.

Usage in middleware: admin.permission:allow,administrator,editor
Usage in controller: Permission::allow(['editor', 'writer'])
```

### 9.3 `Permission::deny(roles)` — Deny Specific Roles

```
Blocks users with specific role slug(s).
If user HAS any of the roles → 403 error.
Administrator role → always passes (even if listed in deny).

Usage in middleware: admin.permission:deny,guest
Usage in controller: Permission::deny(['guest', 'viewer'])
```

### 9.4 `Permission::free()` — No Permission Check

```
Explicitly marks a route as free from permission checking.

Usage in middleware: admin.permission:free
Always returns true.
```

### 9.5 User-Level Check Methods

Available on the user model instance:

| Method                    | Returns | Description                                              |
| ------------------------- | ------- | -------------------------------------------------------- |
| `user.can(slug)`          | bool    | Does user have this permission (direct or via role)?     |
| `user.cannot(slug)`       | bool    | Inverse of `can()`                                       |
| `user.isRole(slug)`       | bool    | Does user have this role?                                |
| `user.inRoles([slugs])`   | bool    | Does user have ANY of these roles?                       |
| `user.isAdministrator()`  | bool    | Is user in the "administrator" role?                     |
| `user.visible(roles[])`   | bool    | Is user visible for these roles? (empty = visible to all) |
| `user.allPermissions()`   | list    | All permissions merged from direct + role-inherited       |

---

## 10. Granting & Revoking Access

### 10.1 Via Admin UI — Built-in Management Pages

The admin panel provides four CRUD pages under the **Auth** menu section:

#### Users Management (`/admin/auth/users`)

- Create/edit admin user accounts
- Assign **roles** via multi-select dropdown
- Assign **direct permissions** via multi-select dropdown
- Password is hashed with bcrypt before storage
- The first user (ID=1) cannot be deleted (protection)

#### Roles Management (`/admin/auth/roles`)

- Create/edit roles with name and slug
- Assign **permissions** to roles via listbox (dual-pane selector)
- The "administrator" role cannot be deleted (protection)
- Deleting a role auto-detaches from all users and permissions

#### Permissions Management (`/admin/auth/permissions`)

- Create/edit permissions with name, slug, HTTP methods, and HTTP paths
- HTTP methods: multi-select from GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD
- HTTP path: textarea for newline-separated patterns
- Supports inline method override per path: `GET,POST:/specific-path`
- Deleting a permission auto-detaches from all roles

#### Menu Management (`/admin/auth/menu`)

- Split-pane: tree view (left) + create form (right)
- Drag-and-drop reordering within the tree
- Each menu item can be assigned:
  - **Parent** (for nesting)
  - **Title** + **Icon** + **URI**
  - **Roles** (multi-select: which roles can see this item)
  - **Permission** (dropdown: optional permission slug binding, only when `menu_bind_permission` is enabled)

### 10.2 Programmatic Access Management

#### Grant a role to a user

```
user.roles().attach(roleId)
// or
user.roles().sync([roleId1, roleId2])  // replaces all
```

#### Revoke a role from a user

```
user.roles().detach(roleId)
```

#### Grant a permission to a role

```
role.permissions().attach(permissionId)
```

#### Revoke a permission from a role

```
role.permissions().detach(permissionId)
```

#### Grant a direct permission to a user

```
user.permissions().attach(permissionId)
```

#### Revoke a direct permission from a user

```
user.permissions().detach(permissionId)
```

#### Assign a menu item to specific roles

```
menu.roles().sync([roleId1, roleId2])
```

#### Remove role restriction from a menu item

```
menu.roles().detach()  // visible to all roles again
```

### 10.3 Cascade Behavior on Delete

| Entity Deleted | Automatic Cleanup                                     |
| -------------- | ----------------------------------------------------- |
| User           | Detach all roles, detach all direct permissions        |
| Role           | Detach all users, detach all permissions               |
| Permission     | Detach all roles                                      |
| Menu           | Detach all roles                                      |

---

## 11. Operation Audit Log

Every admin action is logged to `admin_operation_log`:

### What is logged

- `user_id` — who performed the action
- `path` — the request URI (truncated to 255 chars)
- `method` — HTTP method (GET, POST, PUT, DELETE, etc.)
- `ip` — client IP address (respects X-Forwarded-For proxy header)
- `input` — JSON-encoded request body with sensitive fields filtered

### Configuration

```
operation_log:
    enable: true/false
    allowed_methods: [GET, POST, PUT, DELETE, ...]   # Which methods to log
    except: ["admin/auth/logs*"]                     # Paths to exclude from logging
    filter_input:                                    # Fields to mask in logged input
        token: "*****-filtered-out-*****"
        password: "*****-filtered-out-*****"
```

### Log query format

Supports method prefix filtering in `except` config:
- `admin/auth/logs*` → excludes all methods to paths matching `admin/auth/logs*`
- `get:admin/dashboard` → excludes only GET requests to `admin/dashboard`

---

## 12. Configuration Flags

All configuration lives in a single config file (`config/admin.php`):

| Config Key                 | Type   | Default | Description                                              |
| -------------------------- | ------ | ------- | -------------------------------------------------------- |
| `check_route_permission`   | bool   | `true`  | Enable/disable the route permission middleware entirely  |
| `check_menu_roles`         | bool   | `true`  | Eager-load roles on menu items for visibility filtering  |
| `menu_bind_permission`     | bool   | `true`  | Show permission binding dropdown on menu management page |
| `auth.guard`               | string | `admin` | Authentication guard name                                |
| `auth.throttle_logins`     | bool   | `true`  | Enable login brute-force protection                      |
| `auth.throttle_attempts`   | int    | `5`     | Max failed login attempts before lockout                 |
| `auth.throttle_timeout`    | int    | `900`   | Lockout duration in seconds                              |
| `auth.remember`            | bool   | `true`  | Show "remember me" checkbox on login form                |
| `auth.redirect_to`         | string | `auth/login` | Where to redirect unauthenticated users             |
| `auth.excepts`             | array  | `[auth/login, auth/logout]` | Routes exempt from auth check         |
| `operation_log.enable`     | bool   | `true`  | Enable operation audit logging                           |
| `route.prefix`             | string | `admin` | URL prefix for all admin routes                          |

---

## 13. Default Seed Data

On installation, the system creates:

### Default User

| Field    | Value          |
| -------- | -------------- |
| username | `admin`        |
| password | `admin` (hash) |
| name     | Administrator  |

### Default Role

| Field | Value           |
| ----- | --------------- |
| name  | Administrator   |
| slug  | `administrator` |

The default user is assigned the `administrator` role.

### Default Permissions

| Name             | Slug              | HTTP Method | HTTP Path                                        |
| ---------------- | ----------------- | ----------- | ------------------------------------------------ |
| All permission   | `*`               | (any)       | `*`                                              |
| Dashboard        | `dashboard`       | GET         | `/`                                              |
| Login            | `auth.login`      | (any)       | `/auth/login` + `/auth/logout`                   |
| User setting     | `auth.setting`    | GET,PUT     | `/auth/setting`                                  |
| Auth management  | `auth.management` | (any)       | `/auth/roles` + `/auth/permissions` + `/auth/menu` + `/auth/logs` |

The `administrator` role is assigned the `*` (All permission) permission.

### Default Menu Items

| Title         | Parent      | Icon             | URI               | Order |
| ------------- | ----------- | ---------------- | ----------------- | ----- |
| Dashboard     | (root)      | `icon-chart-bar` | `/`               | 1     |
| Admin         | (root)      | `icon-server`    | (none — folder)   | 2     |
| Users         | Admin       | `icon-users`     | `auth/users`      | 3     |
| Roles         | Admin       | `icon-user`      | `auth/roles`      | 4     |
| Permission    | Admin       | `icon-ban`       | `auth/permissions` | 5     |
| Menu          | Admin       | `icon-bars`      | `auth/menu`       | 6     |
| Operation log | Admin       | `icon-history`   | `auth/logs`       | 7     |

The "Admin" menu folder is assigned to the `administrator` role.

---

## 14. API Reference for Porting

When implementing this system in another language/framework, you need to implement these core interfaces:

### 14.1 User Model Interface

```
interface IAdminUser:
    // Relationships
    roles() → ManyToMany<Role>
    permissions() → ManyToMany<Permission>  // direct
    
    // Permission checks
    can(slug: string) → bool
    cannot(slug: string) → bool
    isRole(slug: string) → bool
    inRoles(slugs: string[]) → bool
    isAdministrator() → bool
    visible(roles: Role[]) → bool
    allPermissions() → Permission[]
```

### 14.2 Role Model Interface

```
interface IRole:
    // Relationships
    administrators() → ManyToMany<User>
    permissions() → ManyToMany<Permission>
    menus() → ManyToMany<Menu>
    
    // Permission checks
    can(slug: string) → bool
    cannot(slug: string) → bool
```

### 14.3 Permission Model Interface

```
interface IPermission:
    // Properties
    slug: string
    http_method: string[]   // stored as comma-separated, accessed as array
    http_path: string       // newline-separated patterns
    
    // Relationships
    roles() → ManyToMany<Role>
    
    // Route matching
    shouldPassThrough(request: HttpRequest) → bool
```

### 14.4 Menu Model Interface

```
interface IMenu:
    // Properties
    parent_id: int          // 0 = root
    order: int
    title: string
    icon: string
    uri: string?
    permission: string?     // permission slug
    
    // Relationships
    roles() → ManyToMany<Role>
    
    // Tree operations
    allNodes() → MenuNode[]
    toTree() → NestedMenuNode[]
    withPermission() → bool
```

### 14.5 Permission Middleware Interface

```
interface IPermissionMiddleware:
    handle(request, next):
        // Implement the algorithm from Section 6.2
    
    static check(slugs: string[]) → void | throw 403
    static allow(roles: string[]) → void | throw 403
    static deny(roles: string[]) → void | throw 403
    static free() → true
```

### 14.6 Operation Log Interface

```
interface IOperationLog:
    user_id: int
    path: string
    method: string
    ip: string
    input: string   // JSON
```

---

## 15. Implementation Checklist by Platform

Use this checklist when porting the RBAC system:

### Database Layer

- [ ] Create all 9 database tables (4 primary + 4 pivot + 1 log)
- [ ] Implement configurable table names
- [ ] Create seed data (default user, role, permissions, menus)
- [ ] Implement cascade cleanup on entity deletion

### Models / Entities

- [ ] `AdminUser` with roles/permissions relationships and `HasPermissions` trait
- [ ] `Role` with users/permissions/menus relationships
- [ ] `Permission` with roles relationship and route-matching logic
- [ ] `Menu` with roles relationship and tree-building logic
- [ ] `OperationLog` with user relationship

### Authentication

- [ ] Separate auth guard/provider for admin (isolated from app users)
- [ ] Session-based auth with "remember me" support
- [ ] Login throttling / brute-force protection
- [ ] Login, logout, and user settings pages

### Authorization Middleware

- [ ] Auth middleware (redirect guests to login)
- [ ] Permission middleware implementing the full resolution algorithm
- [ ] Support for route-level middleware parameters (`allow`, `deny`, `check`, `free`)
- [ ] Configurable exempt routes (bypass permission checking)
- [ ] Administrator role bypasses all checks

### Menu System

- [ ] Tree builder (flat rows → nested structure via parent_id)
- [ ] Role-based visibility filtering per menu item
- [ ] Optional permission-based visibility per menu item
- [ ] Drag-and-drop reordering (frontend)

### Audit Logging

- [ ] Log all requests to operation_log table
- [ ] Configurable method filtering
- [ ] Configurable path exclusions
- [ ] Sensitive field masking in request input

### Admin UI Pages

- [ ] User CRUD (with role + direct permission assignment)
- [ ] Role CRUD (with permission assignment via listbox)
- [ ] Permission CRUD (with HTTP method + path fields)
- [ ] Menu management (tree view + create/edit form + role assignment)
- [ ] Operation log viewer

### Platform-Specific Notes

#### ASP.NET C#

- Use `ClaimsIdentity` / custom `AuthenticationHandler` for the separate admin guard
- Implement as middleware pipeline (`IMiddleware`) 
- Use Entity Framework Core with Fluent API for many-to-many relationships
- Consider using `IAuthorizationHandler` + `IAuthorizationRequirement` for policy-based checks

#### Node.js (Express/NestJS)

- Use Passport.js or custom session strategy for isolated admin auth
- Express middleware chain for the permission pipeline
- Sequelize or Prisma for ORM with M:N through tables
- NestJS: use Guards + Decorators for declarative permission checks

#### Python (Django)

- Use a custom `AuthenticationBackend` for admin auth
- Django middleware classes for the pipeline
- Django ORM `ManyToManyField` with `through` models for pivot tables
- Custom `@permission_required` decorator for views

#### Java (Spring Boot)

- Use a separate `AuthenticationProvider` and `SecurityFilterChain` for admin
- Implement as `OncePerRequestFilter` in the Spring Security filter chain
- JPA `@ManyToMany` with `@JoinTable` for pivot relationships
- Custom `@PreAuthorize` annotations for declarative permission checks

---

## Appendix: Request Flow Diagram

```
HTTP Request to /admin/posts
        │
        ▼
┌─────────────────────────────┐
│  1. admin.auth              │  → Is user logged in?
│     IF guest → redirect     │     No → /admin/auth/login
│     to login page           │     Yes → continue
└────────────┬────────────────┘
             ▼
┌─────────────────────────────┐
│  2. admin.throttle          │  → Rate limit check
└────────────┬────────────────┘
             ▼
┌─────────────────────────────┐
│  3. admin.pjax              │  → PJAX header handling
└────────────┬────────────────┘
             ▼
┌─────────────────────────────┐
│  4. admin.log               │  → Write to operation_log
│     {user, path, method,    │
│      ip, filtered_input}    │
└────────────┬────────────────┘
             ▼
┌─────────────────────────────┐
│  5. admin.bootstrap         │  → Run bootstrap.php
└────────────┬────────────────┘
             ▼
┌─────────────────────────────────────────────────────────┐
│  6. admin.permission                                    │
│                                                         │
│  IF check_route_permission disabled → PASS              │
│  IF route in exempt list → PASS                         │
│  IF user.isAdministrator() → PASS                       │
│                                                         │
│  IF route has explicit middleware (e.g., allow/deny):   │
│     Execute that specific check                         │
│  ELSE:                                                  │
│     Resolve allPermissions (direct + role-based)        │
│     FOR EACH permission:                                │
│       Match http_method + http_path against request     │
│       IF match → PASS                                   │
│     No match found → 403 DENY                           │
└────────────┬────────────────────────────────────────────┘
             ▼
┌─────────────────────────────┐
│  Controller Action          │  → Render page
│  (with optional in-code     │
│   Permission::check())      │
└─────────────────────────────┘
```

---

*This document is based on the Super Admin codebase, which inherits its RBAC architecture from [laravel-admin](https://laravel-admin.org/docs/en) and [open-admin](https://open-admin.org/docs). The design has been in production use across thousands of Laravel applications.*

