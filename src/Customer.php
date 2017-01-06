<?php

/*
 * This file is part of the Active Collab Dummy Payment Gateway project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\DummyPaymentGateway;

use ActiveCollab\Payments\Address\AddressInterface;
use ActiveCollab\Payments\Customer\CustomerInterface;
use ActiveCollab\Payments\Gateway\GatewayInterface;
use ActiveCollab\Payments\PaymentMethod\PaymentMethodInterface;
use ActiveCollab\User\IdentifiedVisitor;
use BadMethodCallException;

class Customer extends IdentifiedVisitor implements CustomerInterface
{
    /**
     * @var AddressInterface|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $phone_number;

    public function getReference(GatewayInterface $gateway)
    {
        return $this->getEmail();
    }

    public function getOurReference()
    {
        return $this->getEmail();
    }

    public function getDefaultPaymentMethod(GatewayInterface $gateway): ?PaymentMethodInterface
    {
        return null;
    }

    public function listPaymentMethods(GatewayInterface $gateway): array
    {
        return [];
    }

    public function addPaymentMethod(GatewayInterface $gateway, bool $set_as_default, ...$arguments): PaymentMethodInterface
    {
        throw new BadMethodCallException('Not implemented yet');
    }

    public function getAddress(): ?AddressInterface
    {
        return $this->address;
    }

    public function &setAddress(AddressInterface $value = null): CustomerInterface
    {
        $this->address = $value;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function &setPhoneNumber(string $value = null): CustomerInterface
    {
        $this->phone_number = trim($value);

        return $this;
    }
}
