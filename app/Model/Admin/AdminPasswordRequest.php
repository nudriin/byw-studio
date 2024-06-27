<?php
namespace Sheilla\NailArt\Model\Admin;

class AdminPasswordRequest
{
    public ?string $id = null;
    public ?string $oldPassword = null;
    public ?string $newPassword = null;
}