<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\ViewModel;

use Dnd\Offers\Api\Data\OfferInterface;
use Dnd\Offers\Api\OfferRepositoryInterface;
use Dnd\Offers\Model\ImageProcessor;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class OffersProviderViewModel
 */
class OffersProviderViewModel implements ArgumentInterface
{
    /** @var SearchCriteriaBuilderFactory */
    protected SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;

    /** @var OfferRepositoryInterface */
    protected OfferRepositoryInterface $offerRepository;

    /** @var Resolver */
    protected Resolver $layerResolver;

    /** @var StoreManagerInterface */
    protected StoreManagerInterface $storeManager;

    /** @var ImageProcessor */
    protected ImageProcessor $imageProcessor;
    
    /**
     * OffersProviderViewModel constructor.
     *
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param OfferRepositoryInterface $offerRepository
     * @param Resolver $layerResolver
     * @param StoreManagerInterface $storeManager
     * @param ImageProcessor $imageProcessor
     */
    public function __construct(
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        OfferRepositoryInterface $offerRepository,
        Resolver $layerResolver,
        StoreManagerInterface $storeManager,
        ImageProcessor $imageProcessor
    ) {
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->offerRepository = $offerRepository;
        $this->layerResolver = $layerResolver;
        $this->storeManager = $storeManager;
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * @return OfferInterface[]
     */
    public function getOffers(): array
    {
        /** @var SearchCriteriaBuilder $searchCriteriaBuilder * */
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        return $this->offerRepository->getList(
                $searchCriteriaBuilder->create(),
                (int)$this->getCurrentCategory()->getId(),
                (int)$this->storeManager->getStore()->getId()
            )->getItems();
    }

    /**
     * @param string $image
     * @return string
     */
    public function getImageUrl(string $image): string
    {
        return $this->imageProcessor->getThumbnailUrl($image);
    }

    /**
     * @return CategoryInterface
     */
    private function getCurrentCategory(): CategoryInterface
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }
}
