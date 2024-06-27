<?php
namespace Sheilla\NailArt\Model\Customer;

class CustomerRegisterRequest
{
    public ?string $username = null;
    public ?string $name = null;
    public ?string $phone = null;
    public ?string $password = null;
}