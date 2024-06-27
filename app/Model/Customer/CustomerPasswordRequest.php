<?php
namespace Sheilla\NailArt\Model\Customer;

class CustomerPasswordRequest
{
    public ?string $id = null;
    public ?string $oldPassword = null;
    public ?string $newPassword = null;
}