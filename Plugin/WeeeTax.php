<?php
/**
 * Amadeco WeeeConfigurableProducts module
 *
 * @category   Amadeco
 * @package    Amadeco_WeeeConfigurableProducts
 * @copyright  Ilan Parmentier
 */
declare(strict_types=1);

namespace Amadeco\WeeeConfigurableProducts\Plugin;

use Magento\Framework\DataObject;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\Website;
use Magento\Weee\Model\Tax as BaseWeeeTax;
use Psr\Log\LoggerInterface;

/**
 * WeeeTax Plugin
 *
 * Ensures that the Fixed Product Tax (FPT) of simple products is used for configurable products.
 */
class WeeeTax
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * WeeeTax constructor
     *
     * @param ProductRepositoryInterface $productRepository Product repository for fetching products
     * @param LoggerInterface $logger Logger for potential error tracking
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger
    ) {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * Around plugin for getProductWeeeAttributes method
     *
     * Retrieves WEEE (Waste Electrical and Electronic Equipment) attributes
     * for configurable products by using the associated simple product's attributes
     *
     * @param BaseWeeeTax $subject The WEEE tax model
     * @param callable $proceed Proceed callback to execute original method
     * @param Product $product The product being processed
     * @param null|false|\Magento\Quote\Model\Quote\Address $shipping Shipping address
     * @param null|false|\Magento\Quote\Model\Quote\Address $billing Billing address
     * @param Website|null $website Website context
     * @param bool|null $calculateTax Whether to calculate tax
     * @param bool $round Whether to round tax values
     * @return DataObject[] WEEE tax attributes
     */
    public function aroundGetProductWeeeAttributes(
        BaseWeeeTax $subject,
        callable $proceed,
        Product $product,
        $shipping = null,
        $billing = null,
        $website = null,
        $calculateTax = null,
        $round = true
    ): array {
        if ($product->getTypeId() !== Configurable::TYPE_CODE) {
            return $proceed($product, $shipping, $billing, $website, $calculateTax, $round);
        }

        $simpleProductId = $this->findSimpleProductId($product);
        if ($simpleProductId === null) {
            return $proceed($product, $shipping, $billing, $website, $calculateTax, $round);
        }

        try {
            // Fetch the simple product and use it for WEEE tax calculation
            $simpleProduct = $this->productRepository->getById($simpleProductId);
            return $proceed($simpleProduct, $shipping, $billing, $website, $calculateTax, $round);
        } catch (\Exception $e) {
            // Log any errors during product retrieval
            $this->logger->error(
                'Error retrieving simple product for WEEE tax',
                [
                    'simple_product_id' => $simpleProductId,
                    'error' => $e->getMessage()
                ]
            );
            return $proceed($product, $shipping, $billing, $website, $calculateTax, $round);
        }
    }

    /**
     * Find the associated simple product ID for a configurable product
     *
     * @param Product $product Configurable product
     * @return int|null Simple product ID or null if not found
     */
    private function findSimpleProductId(Product $product): ?int
    {
        $quoteItem = $product->getQuoteItem();
        if (!$quoteItem) {
            return null;
        }

        $itemsCollection = $quoteItem->getQuote()->getItemsCollection();
        foreach ($itemsCollection as $item) {
            if ($item->getProductType() === Type::TYPE_SIMPLE &&
                $item->getParentItemId() === $quoteItem->getId()) {
                return (int)$item->getProductId();
            }
        }

        return null;
    }
}
