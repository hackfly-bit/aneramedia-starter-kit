# File Tree: aneramedia-starter-kit

**Generated:** 10/30/2025, 12:15:38 AM
**Root Path:** `d:\aneramedia-starter-kit`

```
├── .trae
│   └── rules
│       └── project_rules.md
├── app
│   ├── Console
│   │   └── Commands
│   │       └── SyncAclCommand.php
│   ├── Exceptions
│   │   └── Handler.php
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Api
│   │   │   │   ├── auth
│   │   │   │   │   └── AuthController.php
│   │   │   │   ├── init
│   │   │   │   ├── menus
│   │   │   │   │   └── MenuController.php
│   │   │   │   ├── permissions
│   │   │   │   │   └── PermissionController.php
│   │   │   │   ├── roles
│   │   │   │   │   └── RoleController.php
│   │   │   │   ├── users
│   │   │   │   │   └── UserController.php
│   │   │   │   └── BaseController.php
│   │   │   └── Controller.php
│   │   ├── Middleware
│   │   │   ├── CheckAcl.php
│   │   │   └── ForceJsonResponse.php
│   │   ├── Requests
│   │   │   ├── auth
│   │   │   │   ├── LoginRequest.php
│   │   │   │   └── RegisterRequest.php
│   │   │   ├── permissions
│   │   │   │   ├── StorePermissionRequest.php
│   │   │   │   └── UpdatePermissionRequest.php
│   │   │   ├── roles
│   │   │   │   ├── StoreRoleRequest.php
│   │   │   │   └── UpdateRoleRequest.php
│   │   │   └── users
│   │   │       ├── AssignRoleRequest.php
│   │   │       ├── StoreUserRequest.php
│   │   │       └── UpdateUserRequest.php
│   │   ├── Resources
│   │   │   ├── auth
│   │   │   │   └── UserResource.php
│   │   │   ├── permissions
│   │   │   │   └── PermissionResource.php
│   │   │   ├── roles
│   │   │   │   └── RoleResource.php
│   │   │   └── users
│   │   │       └── UserResource.php
│   │   └── Responses
│   │       └── ApiResponse.php
│   ├── Models
│   │   ├── Menu.php
│   │   ├── Permission.php
│   │   ├── PersonalAccessToken.php
│   │   ├── Role.php
│   │   └── User.php
│   ├── Providers
│   │   └── AppServiceProvider.php
│   ├── Services
│   │   └── MenuService.php
│   └── Traits
│       └── HasUlid.php
├── bootstrap
│   ├── app.php
│   └── providers.php
├── config
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── cors.php
│   ├── database.php
│   ├── filesystems.php
│   ├── laratrust.php
│   ├── laratrust_seeder.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── sanctum.php
│   ├── services.php
│   └── session.php
├── database
│   ├── factories
│   │   ├── MenuFactory.php
│   │   └── UserFactory.php
│   ├── migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2025_10_28_165858_create_personal_access_tokens_table.php
│   │   ├── 2025_10_28_171522_laratrust_setup_tables.php
│   │   ├── 2025_10_28_190754_create_menus_table.php
│   │   ├── 2025_10_28_190818_create_menu_role_table.php
│   │   └── 2025_10_29_151751_convert_tables_to_ulid_primary_keys.php
│   ├── seeders
│   │   ├── DatabaseSeeder.php
│   │   ├── LaratrustSeeder.php
│   │   ├── MenuSeeder.php
│   │   ├── PermissionSeeder.php
│   │   ├── RolePermissionSeeder.php
│   │   └── RoleSeeder.php
│   ├── .gitignore
│   └── database.sqlite
├── public
│   ├── .htaccess
│   ├── index.php
│   └── robots.txt
├── resources
│   └── views
├── routes
│   ├── v1
│   │   ├── auth.php
│   │   ├── menu.php
│   │   ├── permissions.php
│   │   ├── roles.php
│   │   └── users.php
│   ├── api.php
│   ├── console.php
│   ├── test.php
│   └── web.php
├── storage
│   ├── app
│   │   ├── private
│   │   │   └── .gitignore
│   │   ├── public
│   │   │   └── .gitignore
│   │   └── .gitignore
│   ├── framework
│   │   ├── sessions
│   │   │   └── .gitignore
│   │   ├── testing
│   │   │   └── .gitignore
│   │   ├── views
│   │   │   ├── .gitignore
│   │   │   ├── 034fdbb9b36d5ad12f35c28c044f8545.blade.php
│   │   │   ├── 0394513dc526afce2ce92621c4d2abdd.php
│   │   │   ├── 2f2f69f942afbb010779298e9a2bd8d9.blade.php
│   │   │   ├── 32d86f809c7042478dd31229b0478a81.php
│   │   │   ├── 344cfbf15f088e60c2ebf13cdd939558.php
│   │   │   ├── 41a11b92d3977174603328b6fa34b312.php
│   │   │   ├── 771b1095d6700184dc67b7967897e791.php
│   │   │   ├── 964aa853df6b6323a02d40aed9a59b28.blade.php
│   │   │   ├── 9a1cef097efdfcbbe1d832eba681933a.php
│   │   │   ├── a1d678e8e7be7e546f253096b7be54ca.php
│   │   │   ├── b45cac6caa1804305e672e99d2097f47.blade.php
│   │   │   ├── cdac00b43f0b7330b1dc7b8e27813528.php
│   │   │   ├── d5ee34bdcd57d042eeec7fc260d83bb9.blade.php
│   │   │   ├── dd21705e7d8dfcb2e61bb391a310b9c2.blade.php
│   │   │   ├── ef511d3923f105b71c7a293f7226d7d7.php
│   │   │   ├── f9862777e6c30bbaf9a99e57f4c06d1f.blade.php
│   │   │   └── fd2a2fcd4e9c743a8c79a5fc93b30a77.php
│   │   └── .gitignore
│   ├── logs
│   │   └── .gitignore
│   ├── admin_token.txt
│   └── user_token.txt
├── tests
│   ├── Feature
│   │   ├── AuthTest.php
│   │   ├── ExampleTest.php
│   │   ├── MenuTest.php
│   │   ├── MiddlewareDebugTest.php
│   │   ├── PermissionManagementTest.php
│   │   ├── RoleManagementTest.php
│   │   └── UserControllerTest.php
│   ├── Unit
│   │   └── ExampleTest.php
│   └── TestCase.php
├── .editorconfig
├── .env.example
├── .gitattributes
├── .gitignore
├── DEVELOPMENT_TASKS.md
├── README.md
├── artisan
├── boost.json
├── composer.json
├── phpunit.xml
├── rest-api-testing.md
└── structure.md
```

---
*Generated by FileTree Pro Extension*
