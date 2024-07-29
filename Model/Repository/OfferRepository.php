<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Model\Repository;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Api\OfferRepositoryInterface;
use Dnd\Offers\Model\Offer;
use Dnd\Offers\Model\OfferFactory;
use Dnd\Offers\Model\OptionSource\Status;
use Dnd\Offers\Model\ResourceModel\Collection as OffersCollection;
use Dnd\Offers\Model\ResourceModel\Collection;
use Dnd\Offers\Model\ResourceModel\CollectionFactory;
use Dnd\Offers\Model\ResourceModel\Offer as OfferResource;
use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Api\Data\BookmarkSearchResultsInterfaceFactory;

/**
 * Class OfferRepository
 */
class OfferRepository implements OfferRepositoryInterface
{
    /** @var BookmarkSearchResultsInterfaceFactory */
    private BookmarkSearchResultsInterfaceFactory $searchResultsFactory;

    /** @var OfferFactory */
    private OfferFactory $offerFactory;

    /** @var OfferResource */
    private OfferResource $offerResource;

    /** @var CollectionFactory */
    private CollectionFactory $offerCollectionFactory;

    /** @var array */
    private array $offers = [];

    /**
     * OfferRepository constructor.
     *
     * @param BookmarkSearchResultsInterfaceFactory $searchResultsFactory
     * @param OfferFactory $offerFactory
     * @param OfferResource $offerResource
     * @param CollectionFactory $offerCollectionFactory
     */
    public function __construct(
        BookmarkSearchResultsInterfaceFactory $searchResultsFactory,
        OfferFactory $offerFactory,
        OfferResource $offerResource,
        CollectionFactory $offerCollectionFactory
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->offerFactory = $offerFactory;
        $this->offerResource = $offerResource;
        $this->offerCollectionFactory = $offerCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(OfferInterface $offer): OfferInterface
    {
        try {
            if ($offer->getId()) {
                $offer = $this->getById($offer->getId());
            }
            $this->offerResource->save($offer);
            unset($this->offers[$offer->getId()]);
        } catch (Exception $e) {
            if ($offer->getId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save offer with ID %1. Error: %2',
                        [$offer->getId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new offer. Error: %1', $e->getMessage()));
        }

        return $offer;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): OfferInterface
    {
        if (!isset($this->offers[$id])) {
            /** @var Offer $offer */
            $offer = $this->offerFactory->create();
            $this->offerResource->load($offer, $id);
            if (!$offer->getId()) {
                throw new NoSuchEntityException(__('Offer with specified ID "%1" not found.', $id));
            }
            $this->offers[$id] = $offer;
        }

        return $this->offers[$id];
    }

    /**
     * @inheritdoc
     */
    public function delete(OfferInterface $offer): bool
    {
        try {
            $this->offerResource->delete($offer);
            unset($this->offers[$offer->getId()]);
        } catch (\Exception $e) {
            if ($offer->getId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove offer with ID %1. Error: %2',
                        [$offer->getId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove offer. Error: %1', $e->getMessage()));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $id): bool
    {
        $this->delete($this->getById($id));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function enable(OfferInterface $offer): bool
    {
        $offer->setStatus((bool) Status::ENABLED);
        $this->offerResource->save($offer);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function disable(OfferInterface $offer): bool
    {
        $offer->setStatus((bool) Status::DISABLED);
        $this->offerResource->save($offer);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria,
        int $categoryId = null,
        int $storeId = null
    ): SearchResultsInterface
    {
        /** @var OffersCollection $offerCollection */
        $offerCollection = $this->offerCollectionFactory->create()
            ->addFieldToFilter('status', 1);

        if ($storeId) {
            $this->prepareFinsetSqlCondition($offerCollection, 'store_ids', [0, $storeId]);
        }
        if ($categoryId) {
            $this->prepareFinsetSqlCondition($offerCollection, 'category_ids', [$categoryId]);
        }

        $this->applyScheduleCondition($offerCollection);

        return $this->getSearchResults($offerCollection, $searchCriteria);
    }

    /**
     * @param OffersCollection $offerCollection
     * @param string $fieldName
     * @param array $values
     * @return void
     */
    private function prepareFinsetSqlCondition(
        OffersCollection $offerCollection,
        string $fieldName,
        array $values
    ): void
    {
        foreach ($values as $value) {
            $condition[] = ['finset' => $value];
        }
        $offerCollection->addFieldToFilter(
            $fieldName,
            $condition
        );
    }

    /**
     * @param OffersCollection $offerCollection
     * @return void
     */
    private function applyScheduleCondition(OffersCollection $offerCollection): void
    {
        $offerCollection->getSelect()->where(
            '(ISNULL(main_table.' . OfferInterface::START_DATE . ') OR main_table.'
            . OfferInterface::START_DATE . ' <= CURDATE()) AND '
            . '(ISNULL(main_table.' . OfferInterface::END_DATE
            . ') OR main_table.' . OfferInterface::END_DATE . ' >= CURDATE())'
        );
    }

    /**
     * @param Collection $offerCollection
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    private function getSearchResults(
        Collection $offerCollection,
        SearchCriteriaInterface $searchCriteria
    ): SearchResultsInterface
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $this->applySearchCriteriaToCollection($offerCollection, $searchCriteria);
        $searchResults->setTotalCount($offerCollection->getSize());
        $offers = [];

        /** @var OfferInterface $offer */
        foreach ($offerCollection->getItems() as $offer) {
            $offers[] = $this->getById((int)$offer->getId());
        }

        $searchResults->setItems($offers);

        return $searchResults;
    }

    /**
     * @param Collection $offerCollection
     * @param SearchCriteriaInterface $searchCriteria
     * @return void
     */
    protected function applySearchCriteriaToCollection(
        Collection $offerCollection,
        SearchCriteriaInterface $searchCriteria
    ): void
    {
        if ($sortOrders = $searchCriteria->getSortOrders()) {
            $this->addOrderToCollection($sortOrders, $offerCollection);
        }

        $offerCollection->setCurPage($searchCriteria->getCurrentPage());
        $offerCollection->setPageSize($searchCriteria->getPageSize());
    }

    /**
     * @param SortOrder[]|null $sortOrders
     * @param Collection $offerCollection
     * @return void
     */
    private function addOrderToCollection(?array $sortOrders, Collection $offerCollection): void
    {
        /** @var SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $offerCollection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_DESC) ? SortOrder::SORT_DESC : SortOrder::SORT_ASC
            );
        }
    }
}
