<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterEntity\Model;

use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Websolute\TransporterEntity\Api\Data\EntityInterface;
use Websolute\TransporterEntity\Api\Data\EntitySearchResultInterface;
use Websolute\TransporterEntity\Api\Data\EntitySearchResultInterfaceFactory;
use Websolute\TransporterEntity\Api\EntityRepositoryInterface;
use Websolute\TransporterEntity\Model\EntityModelFactory as EntityFactory;
use Websolute\TransporterEntity\Model\ResourceModel\Entity\EntityCollection;
use Websolute\TransporterEntity\Model\ResourceModel\Entity\EntityCollectionFactory;
use Websolute\TransporterEntity\Model\ResourceModel\EntityResourceModel;

class EntityRepository implements EntityRepositoryInterface
{
    /**
     * @var EntityFactory
     */
    private $entityFactory;

    /**
     * @var EntityCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var EntitySearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @var EntityResourceModel
     */
    private $entityResourceModel;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param EntityModelFactory $entityFactory
     * @param EntityCollectionFactory $collectionFactory
     * @param EntitySearchResultInterfaceFactory $entitySearchResultInterfaceFactory
     * @param EntityResourceModel $entityResourceModel
     * @param Json $serializer
     */
    public function __construct(
        EntityFactory $entityFactory,
        EntityCollectionFactory $collectionFactory,
        EntitySearchResultInterfaceFactory $entitySearchResultInterfaceFactory,
        EntityResourceModel $entityResourceModel,
        Json $serializer
    ) {
        $this->entityFactory = $entityFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultFactory = $entitySearchResultInterfaceFactory;
        $this->entityResourceModel = $entityResourceModel;
        $this->serializer = $serializer;
    }

    /**
     * @param int $id
     * @return EntityInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): EntityInterface
    {
        $entity = $this->entityFactory->create();
        $this->entityResourceModel->load($entity, $id);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('Unable to find TransporterEntity with ID "%1"', $id));
        }
        return $entity;
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     * @throws AlreadyExistsException
     */
    public function save(EntityInterface $entity)
    {
        $this->entityResourceModel->save($entity);
        return $entity;
    }

    /**
     * @param EntityInterface $entity
     * @throws Exception
     */
    public function delete(EntityInterface $entity)
    {
        $this->entityResourceModel->delete($entity);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return EntitySearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): EntitySearchResultInterface
    {
        $collection = $this->collectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param EntityCollection $collection
     */
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, EntityCollection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param EntityCollection $collection
     */
    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, EntityCollection $collection)
    {
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param EntityCollection $collection
     */
    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, EntityCollection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param EntityCollection $collection
     * @return EntitySearchResultInterface
     */
    private function buildSearchResult(
        SearchCriteriaInterface $searchCriteria,
        EntityCollection $collection
    ): EntitySearchResultInterface {
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param int $activityId
     * @return array
     */
    public function getAllByActivityIdGroupedByIdentifier(int $activityId): array
    {
        $entities = $this->getAllByActivityId($activityId);

        $result = [];
        foreach ($entities as $entity) {
            $identifier = $entity->getIdentifier();
            if (!array_key_exists($identifier, $result)) {
                $result[$identifier] = [];
            }

            $type = $entity->getType();
            $result[$identifier][$type] = $entity;
        }

        return $result;
    }

    /**
     * @param int $activityId
     * @return EntityInterface[]
     */
    public function getAllByActivityId(int $activityId): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(EntityModel::ACTIVITY_ID, ['eq' => $activityId]);
        $collection->load();

        /** @var EntityInterface[] $entities */
        $entities = $collection->getItems();

        return $entities;
    }

    /**
     * @param int $activityId
     * @return array
     */
    public function getAllIdentifiersByActivityId(int $activityId): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect(EntityModel::IDENTIFIER);
        $collection->addFieldToFilter(EntityModel::ACTIVITY_ID, ['eq' => $activityId]);
        $collection->getSelect()->group(EntityModel::IDENTIFIER);
        return $collection->getColumnValues(EntityModel::IDENTIFIER);
    }

    /**
     * @param int $activityId
     * @param string $identifier
     * @return array
     */
    public function getAllByActivityIdAndIdentifierGroupedByIdentifier(int $activityId, string $identifier): array
    {
        $entities = $this->getAllByActivityIdAndIdentifier($activityId, $identifier);

        $result = [];
        foreach ($entities as $entity) {
            $type = $entity->getType();
            $result[$type] = $entity;
        }

        return $result;
    }

    /**
     * @param int $activityId
     * @return EntityInterface[]
     */
    public function getAllByActivityIdAndIdentifier(int $activityId, string $identifier): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(EntityModel::ACTIVITY_ID, ['eq' => $activityId]);
        $collection->addFieldToFilter(EntityModel::IDENTIFIER, ['eq' => $identifier]);
        $collection->load();

        /** @var EntityInterface[] $entities */
        $entities = $collection->getItems();

        return $entities;
    }

    /**
     * @param int $activityId
     * @return array
     */
    public function getAllDataManipulatedByActivityIdGroupedByIdentifier(int $activityId): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(EntityModel::ACTIVITY_ID, ['eq' => $activityId]);
        $collection->addFieldToSelect(EntityModel::DATA_ORIGINAL);
        $collection->addFieldToSelect(EntityModel::DATA_MANIPULATED);
        $collection->addFieldToSelect(EntityModel::TYPE);
        $collection->addFieldToSelect(EntityModel::IDENTIFIER);
        $collection->load();

        /** @var EntityInterface[] $entities */
        $entities = $collection->getItems();

        $result = [];
        foreach ($entities as $entity) {
            $identifier = $entity->getIdentifier();
            $type = $entity->getType();
            $manipulatedData = $this->serializer->unserialize($entity->getDataManipulated());
            if (!array_key_exists($identifier, $result)) {
                $result[$identifier] = [];
            }
            $result[$identifier][$type] = $manipulatedData;
        }

        return $result;
    }
}
