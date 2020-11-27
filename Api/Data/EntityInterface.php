<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterEntity\Api\Data;

use DateTime;
use Exception;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\DataObject;

interface EntityInterface extends ExtensibleDataInterface
{
    /**
     * @return int
     */
    public function getActivityId(): int;

    /**
     * @param int $activityId
     * @return void
     */
    public function setActivityId(int $activityId);

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type);

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @param string $identifier
     * @return void
     */
    public function setIdentifier(string $identifier);

    /**
     * @return string
     */
    public function getDataOriginal(): string;

    /**
     * @param string $value
     * @return void
     */
    public function setDataOriginal(string $value);

    /**
     * @return string
     */
    public function getDataManipulated(): string;

    /**
     * @param string $value
     * @return void
     */
    public function setDataManipulated(string $value);

    /**
     * @return DataObject
     */
    public function getExtra(): DataObject;

    /**
     * @param DataObject $value
     * @return void
     */
    public function setExtra(DataObject $value);

    /**
     * @param array $value
     * @return void
     */
    public function addExtraArray(array $value);

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getCreatedAt(): DateTime;

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getUpdatedAt(): DateTime;
}
