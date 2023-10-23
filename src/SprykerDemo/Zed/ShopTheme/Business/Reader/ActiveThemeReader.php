<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\Reader;

use Generated\Shared\Transfer\ShopThemeCriteriaTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface;
use SprykerDemo\Zed\ShopTheme\ShopThemeConfig;

class ActiveThemeReader implements ActiveThemeReaderInterface
{
    /**
     * @var \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface
     */
    protected ShopThemeRepositoryInterface $repository;

    /**
     * @param \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface $repository
     */
    public function __construct(ShopThemeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findActiveTheme(StoreTransfer $storeTransfer): ?ShopThemeTransfer
    {
        return $this->repository->findShopTheme(
            (new ShopThemeCriteriaTransfer())
                ->setStoreName($storeTransfer->getName())
                ->setStatus(ShopThemeConfig::STATUS_ACTIVE),
        );
    }
}
