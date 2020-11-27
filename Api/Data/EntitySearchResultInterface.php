<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterEntity\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface EntitySearchResultInterface extends SearchResultsInterface
{
    /**
     * @return EntityInterface[]
     */
    public function getItems();

    /**
     * @param EntityInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
