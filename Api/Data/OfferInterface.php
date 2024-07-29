<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Api\Data;

/**
 * Interface OfferInterface
 */
interface OfferInterface
{
    const MAIN_TABLE   = 'dnd_offers';
    const PERSIST_NAME = 'dnd_offers_offer';

    /**
     * Constants defined for keys of data array
     */
    const ID           = 'id';
    const STATUS       = 'status';
    const STORE_IDS    = 'store_ids';
    const NAME         = 'name';
    const CATEGORY_IDS = 'category_ids';
    const IMAGE        = 'image';
    const TARGET_URL   = 'target_url';
    const START_DATE   = 'start_date';
    const END_DATE     = 'end_date';

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param int $id
     * @return OfferInterface
     */
    public function setId(int $id): OfferInterface;

    /**
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * @param bool $status
     * @return OfferInterface
     */
    public function setStatus(bool $status): OfferInterface;

    /**
     * @return array
     */
    public function getStoreIds(): array;

    /**
     * @param array $storeIds
     * @return OfferInterface
     */
    public function setStoreIds(array $storeIds): OfferInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return OfferInterface
     */
    public function setName(string $name): OfferInterface;

    /**
     * @return array
     */
    public function getCategoryIds(): array;

    /**
     * @param array $categoryIds
     * @return OfferInterface
     */
    public function setCategoryIds(array $categoryIds): OfferInterface;

    /**
     * @return string
     */
    public function getImage(): string;

    /**
     * @param string $Image
     * @return OfferInterface
     */
    public function setImage(string $Image): OfferInterface;

    /**
     * @return string
     */
    public function getTargetUrl(): string;

    /**
     * @param string $targetUrl
     * @return OfferInterface
     */
    public function setTargetUrl(string $targetUrl): OfferInterface;

    /**
     * @return string|null
     */
    public function getStartDate(): ?string;

    /**
     * @param string $date
     * @return OfferInterface
     */
    public function setStartDate(string $date): OfferInterface;

    /**
     * @return string|null
     */
    public function getEndDate(): ?string;

    /**
     * @param string $date
     * @return OfferInterface
     */
    public function setEndDate(string $date): OfferInterface;
}
