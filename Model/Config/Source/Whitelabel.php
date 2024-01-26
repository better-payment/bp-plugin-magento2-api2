<?php

namespace BetterPayment\Core\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Whitelabel implements OptionSourceInterface
{

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'betterpayment',
                'label' => __('Better Payment'),
                'test' => [
                    'api_url' => 'https://testapi.betterpayment.de',
                    'dash_url' => 'https://testdashboard.betterpayment.de'
                ],
                'production' => [
                    'api_url' => 'https://api.betterpayment.de',
                    'dash_url' => 'https://dashboard.betterpayment.de'
                ]
            ],
            ['value' => 'solvendi', 'label' => __('Solvendi')],
            ['value' => 'diagonal', 'label' => __('Diagonal-Payment')],
            ['value' => 'collectai', 'label' => __('collectAI')],
            ['value' => 'betterbill', 'label' => __('BetterBill GmBh')],
            ['value' => 'kleverpay', 'label' => __('Kleverpay')],
            ['value' => 'abilitapay', 'label' => __('abilitaPay')],
            ['value' => 'vr_dienste', 'label' => __('VR-Dienste')],
            ['value' => 'vr_straubing', 'label' => __('Raiffeisenbank Straubing')],
            ['value' => 'continentalpay', 'label' => __('Continental Pay')],
            ['value' => 'vrkg', 'label' => __('Volksbank Bad Kissingen')],
            ['value' => 'demondo', 'label' => __('Demondo Paygate')],
            ['value' => 'vivapago', 'label' => __('Vivapago')],
        ];
    }
}
