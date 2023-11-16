# Roles
Create and manage user roles


### Initiate
Initiate and set the current user role. This will usally come from the database.
```php
use PHPFuse\Roles\Role;

$loggedInUserRole = 3;
$roles = new Role($loggedInUserRole);
```

### Specify the roles
Propagate the roles with permissions for specific page
```php
$roles->set(Role::getRole("ADMIN"), "r=1&i=1&u=1&d=1");
$roles->set(Role::getRole("EDITOR"), "read=1&insert=1&update=1&delete=1");
$roles->set([
    Role::getRole("AUTHOR") => "r=1&i=1&u=1&d=0",
    Role::getRole("MEMBER") => "r=1&i=0&u=0&d=0",
]);
```

### Validate permissions 
Now you only need to validate the permissions with in every specific controller method
```php
var_dump($roles->hasRead(), $roles->hasInsert(), $roles->hasUpdate(), $roles->hasDelete());
// Result: bool(true) bool(true) bool(true) bool(false)
```
