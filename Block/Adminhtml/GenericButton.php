<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Block\Adminhtml;

use Dnd\Offers\Api\Data\OfferInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

/**
 * Class GenericButton
 */
class GenericButton
{
    /** @var UrlInterface */
    private UrlInterface $urlBuilder;

    /** @var Registry */
    private Registry $registry;

    /**
     * GenericButton constructor.
     *
     * @param UrlInterface $urlBuilder
     * @param Registry $registry
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Registry $registry
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->registry = $registry;
    }

    /**
     * @return UrlInterface
     */
    public function getUrlBuilder(): UrlInterface
    {
        return $this->urlBuilder;
    }

    /**
     * @return int|null
     */
    public function getOfferId(): ?int
    {
        $offer = $this->registry->registry(OfferInterface::PERSIST_NAME);

        return $offer ? $offer->getId() : null;
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
