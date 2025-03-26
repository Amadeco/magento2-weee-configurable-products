<?php
/**
 * Amadeco WeeeConfigurableProducts module
 *
 * @category  Amadeco
 * @package   Amadeco_WeeeConfigurableProducts
 * @copyright Ilan Parmentier
 */
declare(strict_types=1);

namespace Amadeco\WeeeConfigurableProducts\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Weee\Model\Total\Quote\Weee as BaseWeee;

/**
 * Class TotalQuoteWeee
 */
class Weee extends BaseWeee
{
    /**
     * @param Address $address
     * @param Total $total
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     */
    protected function process(
        Address $address,
        Total $total,
        $item
    ): void {
        // Add a reference to the quote item on the product model
        $item->getProduct()->setQuoteItem($item);

        parent::process($address, $total, $item);
    }
}