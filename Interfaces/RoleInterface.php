<?php

namespace PHPFuse\Roles\Interfaces;

interface RoleInterface
{
    /**
     * Set the current user role This will usally come from the database
     * @param int $userRole
     * @return void
     */
    public function setUserRole(int $userRole): void;

    /**
     * Set propagate a role with permissinons. Default will role permission be in a denied state.
     * @param array|int     $roleKey  e.g r=1&i=1&u=1&d=1 Or [self::AUTHOR => "r=1&i=1&u=1&d=0"]
     * @param string|null   $queryStr r=1&i=1&u=1&d=1
     */
    public function set(array|int $roleKey, ?string $queryStr = null): void;
    
    /**
     * Get role int number from role key
     * @param  string $roleTitle
     * @return int
     */
    public static function getRole(string $roleTitle): int;

    /**
     * Check if role exists
     * @param  int $role
     * @return bool
     */
    public function hasRole(int $role): bool;

    /**
     * Get role
     * @param  int|null $roleKey
     * @return object
     */
    public function getPermission(?int $roleKey): object;

    /**
     * List all currently supported roles
     * @return array
     */
    public function getSupportedRoles(): array;

    /**
     * Check if the current user has the "right" permission
     * @return bool
     */
    public function hasRead(): bool;

    /**
     * Check if the current user has the "insert" permission
     * @return bool
     */
    public function hasInsert(): bool;

    /**
     * Check if the current user has the "update" permission
     * @return bool
     */
    public function hasUpdate(): bool;

    /**
     * Check if the current user has the "delete" permission
     * @return bool
     */
    public function hasDelete(): bool;
}
