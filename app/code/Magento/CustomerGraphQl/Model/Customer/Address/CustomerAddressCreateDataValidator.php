<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\CustomerGraphQl\Model\Customer\Address;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\CustomerGraphQl\Model\Customer\Address\Validator as CustomerAddressValidator;
use Magento\Framework\App\RequestInterface;

/**
 * Customer address create data validator
 */
class CustomerAddressCreateDataValidator
{
    /**
     * @var GetAllowedAddressAttributes
     */
    private $getAllowedAddressAttributes;

    /**
     * @var CustomerAddressValidator
     */
    private $customerAddressValidator;

    /**
     * @param GetAllowedAddressAttributes $getAllowedAddressAttributes
     * @param CustomerAddressValidator $customerAddressValidator
     */
    public function __construct(
        GetAllowedAddressAttributes $getAllowedAddressAttributes,
        CustomerAddressValidator $customerAddressValidator
    ) {
        $this->getAllowedAddressAttributes = $getAllowedAddressAttributes;
        $this->customerAddressValidator = $customerAddressValidator;
    }

    /**
     * @param array $addressData
     * @throws GraphQlInputException
     * @throws \Exception
     */
    public function validate(array $addressData): void
    {
        $messages = $this->customerAddressValidator->validateAddress($addressData);

        $errorInput = [];

        if (!empty($messages)) {
            foreach ($messages as $message => $messageText) {
                $errorInput[] = $messageText;
            }
        }

        if ($errorInput) {
            throw new GraphQlInputException(
                __('Required parameters are missing: %1', [implode(', ', $errorInput)])
            );
        }
    }

    /**
     * @param array $addressData
     * @return array
     * @throws \Exception
     */
    public function newValidate($addressData)
    {
        /** @var RequestInterface $emptyRequest */
        $emptyRequest = $this->addressExtractor->getRequest();
        $emptyRequest->setParams($addressData);

        return $this->addressExtractor->validateAddress($emptyRequest);

    }
}
