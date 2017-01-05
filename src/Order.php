<?php

/*
 * This file is part of the Active Collab Dummy Payment Gateway project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

declare(strict_types=1);

namespace ActiveCollab\DummyPaymentGateway;

use ActiveCollab\DateValue\DateTimeValueInterface;
use ActiveCollab\DummyPaymentGateway\Traits\CommonOrder;
use ActiveCollab\Payments\Customer\CustomerInterface;
use ActiveCollab\Payments\Order\Calculator\Calculator;
use ActiveCollab\Payments\Order\OrderInterface;

class Order implements OrderInterface
{
    use CommonOrder;

    /**
     * Construct a new order instance.
     *
     * @param CustomerInterface      $customer
     * @param string                 $reference
     * @param DateTimeValueInterface $timestamp
     * @param string                 $currency
     * @param array                  $items
     */
    public function __construct(CustomerInterface $customer, $reference, DateTimeValueInterface $timestamp, $currency, array $items)
    {
        $this->validateCustomer($customer);
        $this->validateOrderId($reference);
        $this->validateCurrency($currency);
        $this->validateItems($items);

        $this->customer = $customer;
        $this->reference = $reference;
        $this->setTimestamp($timestamp);
        $this->currency = $currency;
        $this->items = $items;

        $this->calculation = (new Calculator())->calculate($this, 2);
    }
}
