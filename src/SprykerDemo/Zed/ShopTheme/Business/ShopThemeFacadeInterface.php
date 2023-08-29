<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business;

use Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ShopThemeFacadeInterface
{
    /**
     * Specification:
     *  - Get activated theme for frontend.
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findShopThemeById(int $idShopTheme): ?ShopThemeTransfer;

    /**
     * Specification:
     *  - Get activated theme for frontend.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function getActiveTheme(?StoreTransfer $storeTransfer = null): ShopThemeTransfer;

    /**
     * Specification:
     *  - Saves theme for frontend.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function saveShopTheme(ShopThemeTransfer $shopThemeTransfer): ShopThemeTransfer;

    /**
     * Specification:
     *  - Deletes theme for frontend.
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deleteShopTheme(int $idShopTheme): void;

    /**
     * Specification:
     *  - Deactivate theme.
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deactivateTheme(int $idShopTheme): void;

    /**
     * Specification:
     *  - Activates theme.
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer
     */
    public function activateTheme(int $idShopTheme): ActivateShopThemeActionResponseTransfer;

    /**
     *  Specification:
     *  - Validates store relation for the shop theme.
     *  - If the theme with $shopThemeId is active and provided stores are assigned to another active themes then return false.
     *  - Otherwise returns true
     *
     * @api
     *
     * @param int $shopThemeId
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return bool
     */
    public function validateStoreRelationForShopThemeByShopThemeId(int $shopThemeId, StoreRelationTransfer $storeRelation): bool;
}
