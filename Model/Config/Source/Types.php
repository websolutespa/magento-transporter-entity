<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterEntity\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Websolute\TransporterBase\Api\TransporterListInterface;

class Types implements OptionSourceInterface
{
    /**
     * @var TransporterListInterface
     */
    private $transporterList;

    /**
     * @param TransporterListInterface $transporterList
     */
    public function __construct(
        TransporterListInterface $transporterList
    ) {
        $this->transporterList = $transporterList;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];

        $allDownloaders = $this->transporterList->getAllDownloaderList();
        foreach ($allDownloaders as $allDownloader) {
            $downaloders = $allDownloader->getDownloaders();
            $types = array_keys($downaloders);

            foreach ($types as $type) {
                $options[] = [
                    'value' => $type,
                    'label' => 'Downlaoder: ' . $type
                ];
            }
        }

        $allManipulators = $this->transporterList->getAllManipulatorList();
        foreach ($allManipulators as $allManipulator) {
            $manipulators = $allManipulator->getManipulators();
            $types = array_keys($manipulators);

            foreach ($types as $type) {
                $options[] = [
                    'value' => $type,
                    'label' => 'Manipulator: ' . $type
                ];
            }
        }

        $allUploaders = $this->transporterList->getAllUploaderList();
        foreach ($allUploaders as $allUploader) {
            $uploaders = $allUploader->getUploaders();
            $types = array_keys($uploaders);

            foreach ($types as $type) {
                $options[] = [
                    'value' => $type,
                    'label' => 'Uploader: ' . $type
                ];
            }
        }


        return $options;
    }
}
