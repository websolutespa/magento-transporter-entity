<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterEntity\Api;

use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Websolute\TransporterEntity\Api\Data\EntityInterface;
use Websolute\TransporterEntity\Api\Data\EntitySearchResultInterface;

interface EntityRepositoryInterface
{
    /**
     * @param int $id
     * @return EntityInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): EntityInterface;

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function save(EntityInterface $entity);

    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function delete(EntityInterface $entity);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return EntitySearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): EntitySearchResultInterface;

    /**
     * @param int $activityId
     * @return EntityInterface[]
     * @throws Exception
     */
    public function getAllByActivityId(int $activityId): array;

    /**
     * @param int $activityId
     * @param string $identifier
     * @return EntityInterface[]
     * @throws Exception
     */
    public function getAllByActivityIdAndIdentifier(int $activityId, string $identifier): array;

    /**
     * @param int $activityId
     * @return array
     * @throws Exception
     */
    public function getAllByActivityIdGroupedByIdentifier(int $activityId): array;

    /**
     * @param int $activityId
     * @return array
     * @throws Exception
     */
    public function getAllIdentifiersByActivityId(int $activityId): array;

    /**
     * @param int $activityId
     * @param string $identifier
     * @return array
     */
    public function getAllByActivityIdAndIdentifierGroupedByIdentifier(int $activityId, string $identifier): array;

    /**
     * @param int $activityId
     * @return array
     */
    public function getAllDataManipulatedByActivityIdGroupedByIdentifier(int $activityId): array;
}
