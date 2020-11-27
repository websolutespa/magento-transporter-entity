<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterEntity\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class EntityResourceModel extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('transporter_entity', 'entity_id');
    }
}
