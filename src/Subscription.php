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
use ActiveCollab\Payments\Common\Traits\GatewayedObject;
use ActiveCollab\Payments\Customer\CustomerInterface;
use ActiveCollab\Payments\Gateway\GatewayInterface;
use ActiveCollab\Payments\Subscription\SubscriptionInterface;
use Carbon\Carbon;
use InvalidArgumentException;

class Subscription implements SubscriptionInterface
{
    use GatewayedObject, CommonOrder;

    /**
     * Construct a new order instance.
     *
     * @param CustomerInterface      $customer
     * @param string                 $reference
     * @param DateTimeValueInterface $timestamp
     * @param string                 $period
     * @param string                 $currency
     * @param array                  $items
     */
    public function __construct(CustomerInterface $customer, $reference, DateTimeValueInterface $timestamp, $period, $currency, array $items)
    {
        $this->validateCustomer($customer);
        $this->validateOrderId($reference);
        $this->validateBillingPeriod($period);
        $this->validateCurrency($currency);
        $this->validateItems($items);

        $this->customer = $customer;
        $this->setReference($reference);
        $this->setTimestamp($timestamp);
        $this->billing_period = $period;
        $this->currency = $currency;
        $this->items = $items;
    }

    public function setGateway(GatewayInterface &$gateway)
    {
        return $this->setGatewayByReference($gateway);
    }

    /**
     * @var string
     */
    private $billing_period;

    /**
     * Return billing period.
     *
     * @return string
     */
    public function getBillingPeriod(): string
    {
        return $this->billing_period;
    }

    /**
     * @var DateTimeValueInterface|Carbon
     */
    private $next_billing_timestamp;

    /**
     * Return next billing timestamp.
     *
     * @return DateTimeValueInterface
     */
    public function getNextBillingTimestamp(): DateTimeValueInterface
    {
        if (empty($this->next_billing_timestamp)) {
            $this->next_billing_timestamp = $this->calculateNextBillingTimestamp($this->getTimestamp());
        }

        return $this->next_billing_timestamp;
    }

    /**
     * Set next billing timestamp.
     *
     * @param  DateTimeValueInterface      $value
     * @return SubscriptionInterface|$this
     */
    public function &setNextBillingTimestamp(DateTimeValueInterface $value): SubscriptionInterface
    {
        $this->next_billing_timestamp = $value;

        return $this;
    }

    /**
     * Calculate next billing period based on reference timestamp.
     *
     * @param  DateTimeValueInterface $reference
     * @return DateTimeValueInterface
     */
    public function calculateNextBillingTimestamp(DateTimeValueInterface $reference): DateTimeValueInterface
    {
        /** @var DateTimeValueInterface|Carbon $result */
        $result = clone $reference;

        if ($this->getBillingPeriod() == self::MONTHLY) {
            $result->addMonth();
        } elseif ($this->getBillingPeriod() == self::YEARLY) {
            $result->addYear();
        }

        return $result;
    }

    /**
     * Validate period value.
     *
     * @param string $value
     */
    protected function validateBillingPeriod($value)
    {
        if ($value != self::MONTHLY && $value != self::YEARLY) {
            throw new InvalidArgumentException('Monthly and yearly periods are supported');
        }
    }
}
