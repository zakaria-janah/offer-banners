<?php
/**
 * @author  Zakaria J. <m.janah.zakaria@gmail.com>
 * @package Dnd_Offers
 */

declare(strict_types=1);

namespace Dnd\Offers\Test\Unit\Model\Repository;

use Dnd\Offers\Model\Offer;
use Dnd\Offers\Model\OfferFactory;
use Dnd\Offers\Model\Repository\OfferRepository;
use Dnd\Offers\Model\ResourceModel\Collection;
use Dnd\Offers\Model\ResourceModel\CollectionFactory;
use Dnd\Offers\Model\ResourceModel\Offer as ResourceOffer;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Ui\Api\Data\BookmarkSearchResultsInterfaceFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Offer repository test.
 */
class OfferRepositoryTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var OfferRepository $model
     */
    private $model;

    /**
     * @var BookmarkSearchResultsInterfaceFactory|MockObject $entityFactory
     */
    private $searchResultsFactory;

    /**
     * @var ResourceOffer|MockObject $resource
     */
    private $resource;

    /**
     * @var OfferFactory|MockObject $entityFactory
     */
    private $entityFactory;

    /**
     * @var CollectionFactory|MockObject
     */
    private $entityCollectionFactory;

    /**
     * Prepare test objects.
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->searchResultsFactory = $this->createMock(BookmarkSearchResultsInterfaceFactory::class);
        $this->resource = $this->createMock(ResourceOffer::class);
        $this->entityFactory =  $this->createMock(OfferFactory::class);
        $this->entityCollectionFactory = $this->createMock(CollectionFactory::class);
        $this->model = new OfferRepository(
            $this->searchResultsFactory,
            $this->entityFactory,
            $this->resource,
            $this->entityCollectionFactory
        );
    }

    /**
     * Test save.
     */
    public function testSave(): void
    {
        $offer = $this->objectManager->getObject(Offer::class);
        $this->resource->expects($this->once())
            ->method('save')
            ->with($offer);
        $this->model->save($offer);
    }

    /**
     * Test offer by id.
     */
    public function testGetById(): void
    {
        $entity = $this->objectManager->getObject(Offer::class)->setId(1);
        $this->entityFactory->method('create')
            ->willReturn($entity);
        $this->assertEquals($this->model->getById(1)->getId(), 1);
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        $entity = $this->objectManager->getObject(Offer::class)->setId(1);
        $this->resource->expects($this->once())->method('delete')->with($entity)
            ->willReturn(true);
        $this->assertTrue($this->model->delete($entity));
    }

    /**
     * @return void
     */
    public function testDeleteById(): void
    {
        $entity = $this->objectManager->getObject(Offer::class)->setId(1);
        $this->resource->expects($this->once())->method('getById')->with(1)
            ->willReturn(1);
        $this->assertTrue($this->model->deleteById($entity->getId()));
    }

    /**
     * @return void
     */
    public function testGetList(): void
    {
        $searchCriteriaMock = $this->createMock(SearchCriteria::class);
        $collectionMock = $this->createMock(Collection::class);

        $searchResultsMock = $this->getMockForAbstractClass(SearchResultsInterface::class);
        $searchResultFactory = $this->getMockBuilder(SearchResultsInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();

        $searchResultsMock->expects($this->once())->method('setSearchCriteria')->with($searchCriteriaMock);
        $this->entityCollectionFactory->expects($this->once())->method('create')->willReturn($collectionMock);
        $collectionMock->expects($this->once())->method('getSize')->willReturn(1);
        $searchResultsMock->expects($this->once())->method('setTotalCount')->with(1);
        $collectionMock->expects($this->once())->method('getItems')->willReturn([]);
        $searchResultsMock->expects($this->once())->method('setItems')->with([]);
        $searchResultFactory->expects($this->once())->method('create')->willReturn($searchResultsMock);

        $this->assertEquals($searchResultsMock, $this->model->getList($searchCriteriaMock));
    }
}
