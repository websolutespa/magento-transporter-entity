<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterEntity\Model\ResourceModel\Entity;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Websolute\TransporterEntity\Model\EntityModel;
use Websolute\TransporterEntity\Model\ResourceModel\EntityResourceModel;

class EntityCollection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'transporter_entity_collection';
    protected $_eventObject = 'entity_collection';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(EntityModel::class, EntityResourceModel::class);
    }
}
