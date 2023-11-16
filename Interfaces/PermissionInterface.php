<?php

namespace PHPFuse\Roles\Interfaces;

interface PermissionInterface
{
   /**
     * Add read permission
     * @param  int $role
     * @return self
     */
    public function read(int $role): self;

    /**
     * Add insert permission
     * @param  int $role
     * @return self
     */
    public function insert(int $role): self;

    /**
     * Add update permission
     * @param  int $role
     * @return self
     */
    public function update(int $role): self;

    /**
     * Add delete permission
     * @param  int $role
     * @return self
     */
    public function delete(int $role): self;
}
