<?php

namespace BetterPayment\Core\Controller\ErrorUrl;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

class Index implements HttpGetActionInterface
{
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;

    public function __construct(RedirectFactory $redirectFactory, ManagerInterface $messageManager)
    {
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->messageManager->addErrorMessage(__('Payment failed in Payment Gateway page'));
        return $this->redirectFactory->create()->setPath('checkout/cart');
    }
}
