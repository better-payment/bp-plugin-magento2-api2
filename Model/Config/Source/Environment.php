<?php

namespace BetterPayment\Core\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Environment implements OptionSourceInterface
{

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'test',
                'label' => 'Test'
            ],
            [
                'value' => 'production',
                'label' => 'Production'
            ],
        ];
    }
}
