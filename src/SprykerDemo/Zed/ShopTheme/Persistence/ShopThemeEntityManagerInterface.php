<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence;

use Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;

interface ShopThemeEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function saveShopTheme(
        ShopThemeTransfer $shopThemeTransfer
    ): ShopThemeTransfer;

    /**
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deleteShopTheme(int $idShopTheme): void;

    /**
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer
     */
    public function activate(int $idShopTheme): ActivateShopThemeActionResponseTransfer;

    /**
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deactivate(int $idShopTheme): void;
}
