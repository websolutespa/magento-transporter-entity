<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterEntity\Model;

use Magento\Framework\Api\Search\SearchResult;
use Websolute\TransporterEntity\Api\Data\EntitySearchResultInterface;

class EntitySearchResult extends SearchResult implements EntitySearchResultInterface
{

}
