<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Model;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Model\ResourceModel\Offer as OfferResourceModel;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Offer
 */
class Offer extends AbstractModel implements OfferInterface
{
    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(OfferResourceModel::class);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return ((int) $this->_getData(OfferInterface::ID)) ?: null;
    }

    /**
     * @param int $id
     * @return OfferInterface
     */
    public function setId($id): OfferInterface
    {
        $this->setData(OfferInterface::ID, $id);

        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return (bool) $this->_getData(OfferInterface::STATUS);
    }

    /**
     * @param bool $status
     * @return OfferInterface
     */
    public function setStatus(bool $status): OfferInterface
    {
        $this->setData(OfferInterface::STATUS, $status);

        return $this;
    }

    /**
     * @return int
     */
    public function getStoreIds(): array
    {
        return ($this->_getData(OfferInterface::STORE_IDS))
            ? explode(',', $this->_getData(OfferInterface::STORE_IDS))
            : [];
    }

    /**
     * @param array $storeIds
     * @return OfferInterface
     */
    public function setStoreIds(array $storeIds): OfferInterface
    {
        $this->setData(OfferInterface::STORE_IDS, implode(',', $storeIds));

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->_getData(OfferInterface::NAME);
    }

    /**
     * @param string $name
     * @return OfferInterface
     */
    public function setName(string $name): OfferInterface
    {
        $this->setData(OfferInterface::NAME, $name);

        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryIds(): array
    {
        return ($this->_getData(OfferInterface::STORE_IDS))
            ? explode(',', $this->_getData(OfferInterface::STORE_IDS))
            : [];
    }

    /**
     * @param array $categoryIds
     * @return OfferInterface
     */
    public function setCategoryIds(array $categoryIds): OfferInterface
    {
        $this->setData(OfferInterface::STORE_IDS, implode(',', $categoryIds));

        return $this;
    }


    /**
     * @return string
     */
    public function getImage(): string
    {
        return (string) $this->_getData(OfferInterface::IMAGE);
    }

    /**
     * @param string $Image
     * @return OfferInterface
     */
    public function setImage(string $Image): OfferInterface
    {
        $this->setData(OfferInterface::IMAGE, $Image);

        return $this;
    }

    /**
     * @return string
     */
    public function getTargetUrl(): string
    {
        return (string) $this->_getData(OfferInterface::TARGET_URL);
    }

    /**
     * @param string $targetUrl
     * @return OfferInterface
     */
    public function setTargetUrl(string $targetUrl): OfferInterface
    {
        $this->setData(OfferInterface::TARGET_URL, $targetUrl);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->getData(OfferInterface::START_DATE);
    }

    /**
     * @param string $date
     * @return OfferInterface
     */
    public function setStartDate(string $date): OfferInterface
    {
        $this->setData(OfferInterface::START_DATE, $date);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->getData(OfferInterface::END_DATE);
    }

    /**
     * @param string $date
     * @return OfferInterface
     */
    public function setEndDate(string $date): OfferInterface
    {
        $this->setData(OfferInterface::END_DATE, $date);

        return $this;
    }
}
