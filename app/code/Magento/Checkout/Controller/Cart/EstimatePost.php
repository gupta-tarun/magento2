<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Checkout\Controller\Cart;

use Magento\Framework;
use Magento\Checkout\Model\Cart as CustomerCart;

class EstimatePost extends \Magento\Checkout\Controller\Cart
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     */
    public function __construct(
        Framework\App\Action\Context $context,
        Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $resultRedirectFactory
        );
    }

    /**
     * Initialize shipping information
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $country = (string)$this->getRequest()->getParam('country_id');
        $postcode = (string)$this->getRequest()->getParam('estimate_postcode');
        $city = (string)$this->getRequest()->getParam('estimate_city');
        $regionId = (string)$this->getRequest()->getParam('region_id');
        $region = (string)$this->getRequest()->getParam('region');

        $this->cart->getQuote()->getShippingAddress()
            ->setCountryId($country)
            ->setCity($city)
            ->setPostcode($postcode)
            ->setRegionId($regionId)
            ->setRegion($region)
            ->setCollectShippingRates(true);
        $this->quoteRepository->save($this->cart->getQuote());
        return $this->_goBack();
    }
}
