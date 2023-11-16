<?php
declare(strict_types=1);

namespace PHPFuse\Roles;

use PHPFuse\Roles\Exceptions\RolesException;
use PHPFuse\Roles\Interfaces\RoleInterface;

class Role implements RoleInterface
{
    protected const ROLES = [
        1 => "admin",
        2 => "editor",
        3 => "author",
        4 => "member"
    ];

    private $userRole;
    private $data;

    /**
     * Set the current user role This will usally come from the database
     * @param int|null $userRole
     */
    public function __construct(?int $userRole = null)
    {
        if (!is_null($userRole)) {
            $this->setUserRole($userRole);
        }
    }

    /**
     * Set the current user role This will usally come from the database
     * @param int $userRole
     * @return void
     */
    public function setUserRole(int $userRole): void
    {
        $this->userRole = $userRole;
    }

    /**
     * Set propagate a role with permissinons. Default will role permission be in a denied state.
     * @param array|int     $roleKey  e.g r=1&i=1&u=1&d=1 Or [self::AUTHOR => "r=1&i=1&u=1&d=0"]
     * @param string|null   $queryStr r=1&i=1&u=1&d=1
     */
    public function set(array|int $roleKey, ?string $queryStr = null): void
    {
        if (is_array($roleKey)) {
            foreach ($roleKey as $role => $perm) {
                $this->data[$role] = new Permission($perm);
            }
        } else {
            $this->data[$roleKey] = new Permission($queryStr);
        }
    }

    /**
     * Get role int number from role key
     * @param  string $roleTitle
     * @return int
     */
    public static function getRole(string $roleTitle): int
    {
        $roleTitle = strtolower($roleTitle);
        $role = array_search($roleTitle, static::ROLES);
        if ($role === false) {
            throw new RolesException("The user role ({$roleTitle}) does not exist.", 1);
        }
        return $role;
    }

    /**
     * Check if role exists
     * @param  int $role
     * @return bool
     */
    public function hasRole(int $role): bool
    {
        return isset(static::ROLES[$role]);
    }

    /**
     * Get role
     * @param  int|null $roleKey
     * @return object
     */
    public function getPermission(?int $roleKey): object
    {
        if (is_null($this->userRole)) {
            throw new RolesException("You need to specify the current users role with @setUserRole or the @constructer.", 1);
        }
        $this->validateRole($roleKey);
        return $this->data[$roleKey];
    }

    /**
     * List all currently supported roles
     * @return array
     */
    public function getSupportedRoles(): array
    {
        return static::ROLES;
    }

    /**
     * Check if the current user has the "right" permission
     * @return bool
     */
    public function hasRead(): bool
    {
        return ($this->getPermission($this->userRole)->getRead() > 0);
    }

    /**
     * Check if the current user has the "insert" permission
     * @return bool
     */
    public function hasInsert(): bool
    {
        return ($this->getPermission($this->userRole)->getInsert() > 0);
    }

    /**
     * Check if the current user has the "update" permission
     * @return bool
     */
    public function hasUpdate(): bool
    {
        return ($this->getPermission($this->userRole)->getUpdate() > 0);
    }

    /**
     * Check if the current user has the "delete" permission
     * @return bool
     */
    public function hasDelete(): bool
    {
        return ($this->getPermission($this->userRole)->getDelete() > 0);
    }

    /**
     * Validate roles
     * @param  int $role
     * @return bool
     */
    final protected function validateRole(int $role): bool
    {
        if (!$this->hasRole($role)) {
            throw new RolesException("The user role ({$role}) does not exist.", 1);
        }
        if (!isset($this->data[$role])) {
            $this->placeholder($role);
        }
        return true;
    }

    /**
     * Placeholder
     * @param  int $role
     * @return void
     */
    final protected function placeholder(int $role): void
    {
        $this->data[$role] = new Permission(NULL);
    }
}
