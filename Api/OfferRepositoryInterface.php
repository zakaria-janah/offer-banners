<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Api;

use Dnd\Offers\Api\Data\OfferInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface OfferRepositoryInterface
 */
interface OfferRepositoryInterface
{
    /**
     * Save
     *
     * @param OfferInterface $offer
     * @return OfferInterface
     */
    public function save(OfferInterface $offer): OfferInterface;

    /**
     * @param int $id
     * @param int $store
     * @return OfferInterface
     */
    public function getById(int $id): OfferInterface;

    /**
     * Delete
     *
     * @param OfferInterface $offer
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(OfferInterface $offer): bool;

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): bool;

    /**
     * Enable
     *
     * @param OfferInterface $offer
     * @return bool
     */
    public function enable(OfferInterface $offer): bool;

    /**
     * Disable
     *
     * @param OfferInterface $offer
     * @return bool
     */
    public function disable(OfferInterface $offer): bool;

    /**
     * Lists
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param int|null $categoryId
     * @param int|null $storeId
     * @return SearchResultsInterface
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria,
        int $categoryId = null,
        int $storeId = null
    ): SearchResultsInterface;
}
