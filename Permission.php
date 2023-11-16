<?php
declare(strict_types=1);

namespace PHPFuse\Roles;

use PHPFuse\Roles\Exceptions\RolesException;
use PHPFuse\Roles\Interfaces\PermissionInterface;

class Permission implements PermissionInterface
{
    protected const SYMLINK = [
        "r" => "read",
        "i" => "insert",
        "u" => "update",
        "d" => "delete"
    ];

    protected int $read = 0;
    protected int $insert = 0;
    protected int $update = 0;
    protected int $delete = 0;

    /**
     * propagate th permissions
     * @param string|null $propagate
     */
    public function __construct(?string $propagate = null)
    {
        $this->propagate($propagate);
    }

    /**
     * Roles class will use call to read the proteted permissions objects
     * @param  string $name
     * @param  array  $args
     * @return int
     */
    public function __call(string $name, array $args): int
    {
        $name = substr(strtolower($name), 3);
        if (in_array($name, static::SYMLINK)) {
            return $this->{$name};
        }
        throw new RolesException("The role {$name} does not exists in Permission class!", 1);
    }

    /**
     * Add read permission
     * @param  int $role
     * @return self
     */
    public function read(int $role): self
    {
        $this->read = $role;
        return $this;
    }

    /**
     * Add insert permission
     * @param  int $role
     * @return self
     */
    public function insert(int $role): self
    {
        $this->insert = $role;
        return $this;
    }

    /**
     * Add update permission
     * @param  int $role
     * @return self
     */
    public function update(int $role): self
    {
        $this->update = $role;
        return $this;
    }

    /**
     * Add delete permission
     * @param  int $role
     * @return self
     */
    public function delete(int $role): self
    {
        $this->delete = $role;
        return $this;
    }

    /**
     * propagate the permission
     * @param  string|null $propagate
     * @return void
     */
    final protected function propagate(?string $propagate = null): void
    {
        if (!is_null($propagate)) {
            parse_str($propagate, $arr);
            foreach ($arr as $role => $permInt) {
                $roleMethod = $this->getSymlink($role);
                if (is_null($roleMethod)) {
                    $roleMethod = $role;
                }
                if (method_exists($this, $roleMethod)) {
                    $this->{$roleMethod}((int)$permInt);
                }
            }
        }
    }

    /**
     * Get get the permission name from shortcut
     * @param  string $roleMethod (r, i, u, d)
     * @return string|null
     */
    final protected function getSymlink(string $roleMethod): ?string
    {
        return (static::SYMLINK[$roleMethod] ?? null);
    }
}
