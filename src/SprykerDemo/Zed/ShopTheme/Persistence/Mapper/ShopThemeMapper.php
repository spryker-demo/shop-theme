<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence\Mapper;

use Generated\Shared\Transfer\ShopThemeDataTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ShopTheme\Persistence\SpyShopTheme;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

class ShopThemeMapper
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected UtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \SprykerDemo\Zed\ShopTheme\Persistence\Mapper\ShopThemeStoreRelationMapper
     */
    protected ShopThemeStoreRelationMapper $shopThemeStoreRelationMapper;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \SprykerDemo\Zed\ShopTheme\Persistence\Mapper\ShopThemeStoreRelationMapper $shopThemeStoreRelationMapper
     */
    public function __construct(
        UtilEncodingServiceInterface $utilEncodingService,
        ShopThemeStoreRelationMapper $shopThemeStoreRelationMapper
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->shopThemeStoreRelationMapper = $shopThemeStoreRelationMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     * @param \Orm\Zed\ShopTheme\Persistence\SpyShopTheme $shopThemeEntity
     *
     * @return \Orm\Zed\ShopTheme\Persistence\SpyShopTheme
     */
    public function mapShopThemeTransferToShopThemeEntity(
        ShopThemeTransfer $shopThemeTransfer,
        SpyShopTheme $shopThemeEntity
    ): SpyShopTheme {
        $shopThemeEntity->setName($shopThemeTransfer->getName());
        $shopThemeEntity->setIdShopTheme($shopThemeTransfer->getIdShopTheme());
        $shopThemeEntity->setNew($shopThemeTransfer->getIdShopTheme() === null);

        $shopThemeData = $shopThemeTransfer->getShopThemeData()->toArray(true, true);
        unset(
            $shopThemeData[ShopThemeDataTransfer::DELETE_BACKOFFICE_LOGO],
            $shopThemeData[ShopThemeDataTransfer::DELETE_MP_LOGO],
            $shopThemeData[ShopThemeDataTransfer::DELETE_LOGO],
            $shopThemeData[ShopThemeDataTransfer::BACKOFFICE_LOGO_FILE],
            $shopThemeData[ShopThemeDataTransfer::MP_LOGO_FILE],
            $shopThemeData[ShopThemeDataTransfer::LOGO_FILE],
        );
        $shopThemeEntity->setData($shopThemeData
            ? $this->utilEncodingService->encodeJson($shopThemeData)
            : '{}');

        return $shopThemeEntity;
    }

    /**
     * @param \Orm\Zed\ShopTheme\Persistence\SpyShopTheme $shopThemeEntity
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function mapShopThemeEntityToShopThemeTransfer(
        SpyShopTheme $shopThemeEntity,
        ShopThemeTransfer $shopThemeTransfer
    ): ShopThemeTransfer {
        $shopThemeTransfer->setIdShopTheme($shopThemeEntity->getIdShopTheme());
        $shopThemeTransfer->setName($shopThemeEntity->getName());
        $shopThemeTransfer->setStatus($shopThemeEntity->getStatus());
        /** @var array<mixed> $decodedData*/
        $decodedData = $shopThemeEntity->getData()
            ? $this->utilEncodingService->decodeJson($shopThemeEntity->getData(), true)
            : [];
        $shopThemeTransfer->setData($decodedData);
        $shopThemeTransfer->setShopThemeData((new ShopThemeDataTransfer())->fromArray($decodedData, true));

        return $shopThemeTransfer;
    }

    /**
     * @param \Orm\Zed\ShopTheme\Persistence\SpyShopTheme $shopThemeEntity
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function mapShopThemeEntityToShopThemeTransferWithStoreRelation(
        SpyShopTheme $shopThemeEntity,
        ShopThemeTransfer $shopThemeTransfer
    ): ShopThemeTransfer {
        $shopThemeTransfer = $this->mapShopThemeEntityToShopThemeTransfer($shopThemeEntity, $shopThemeTransfer);
        $storeRelationTransfer = $this->shopThemeStoreRelationMapper->mapShopThemeStoreEntitiesToStoreRelationTransfer(
            $shopThemeEntity->getSpyShopThemeStores(),
            (new StoreRelationTransfer())->setIdEntity($shopThemeEntity->getIdShopTheme()),
        );
        $shopThemeTransfer->setStoreRelation($storeRelationTransfer);

        return $shopThemeTransfer;
    }

    /**
     * @param array<\Orm\Zed\ShopTheme\Persistence\SpyShopTheme> $shopThemeEntities
     *
     * @return array<\Generated\Shared\Transfer\ShopThemeTransfer>
     */
    public function mapShopThemeEntitiesToShopThemeTransfersWithStoreRelation(array $shopThemeEntities): array
    {
        $shopThemeTransfers = [];

        foreach ($shopThemeEntities as $shopThemeEntity) {
            $shopThemeTransfers[] = $this
                ->mapShopThemeEntityToShopThemeTransferWithStoreRelation(
                    $shopThemeEntity,
                    new ShopThemeTransfer(),
                );
        }

        return $shopThemeTransfers;
    }

    /**
     * @param array<\Orm\Zed\ShopTheme\Persistence\SpyShopTheme> $shopThemeEntities
     *
     * @return array<\Generated\Shared\Transfer\ShopThemeTransfer>
     */
    public function mapShopThemeEntitiesToShopThemeTransfers(array $shopThemeEntities): array
    {
        $shopThemeTransfers = [];

        foreach ($shopThemeEntities as $shopThemeEntity) {
            $shopThemeTransfers[] = $this
                ->mapShopThemeEntityToShopThemeTransfer(
                    $shopThemeEntity,
                    new ShopThemeTransfer(),
                );
        }

        return $shopThemeTransfers;
    }
}
