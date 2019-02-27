<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\CustomerGraphQl\Model\Customer\Address;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\CustomerGraphQl\Model\Customer\Address\Validator as CustomerAddressValidator;

/**
 * Customer address update data validator. Patch update is allowed
 */
class CustomerAddressUpdateDataValidator
{
    /**
     * @var CustomerAddressValidator
     */
    private $customerAddressValidator;

    /**
     * @param CustomerAddressValidator $customerAddressValidator
     */
    public function __construct(
        CustomerAddressValidator $customerAddressValidator
    ) {
        $this->customerAddressValidator = $customerAddressValidator;
    }

    /**
     * Validate customer address update data
     *
     * @param array $addressData
     * @return void
     * @throws GraphQlInputException
     */
    public function validate(array $addressData): void
    {
        $messages = $this->customerAddressValidator->validateAddress($addressData);

        $errorInput = [];

        if (!empty($messages)) {
            foreach ($messages as $messageText) {
                $errorInput[] = $messageText;
            }
        }

        if ($errorInput) {
            throw new GraphQlInputException(
                __('Required parameters are missing: %1', [implode(', ', $errorInput)])
            );
        }
    }
}
