<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence;

use Orm\Zed\ShopTheme\Persistence\SpyShopThemeQuery;
use Orm\Zed\ShopTheme\Persistence\SpyShopThemeStoreQuery;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerDemo\Zed\ShopTheme\Persistence\Mapper\ShopThemeMapper;
use SprykerDemo\Zed\ShopTheme\Persistence\Mapper\ShopThemeStoreRelationMapper;
use SprykerDemo\Zed\ShopTheme\ShopThemeDependencyProvider;

/**
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface getRepository()
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface getEntityManager()
 * @method \SprykerDemo\Zed\ShopTheme\ShopThemeConfig getConfig()
 */
class ShopThemePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShopTheme\Persistence\SpyShopThemeQuery
     */
    public function createShopThemeQuery(): SpyShopThemeQuery
    {
        return SpyShopThemeQuery::create();
    }

    /**
     * @return \Orm\Zed\ShopTheme\Persistence\SpyShopThemeStoreQuery
     */
    public function createShopThemeStoreQuery(): SpyShopThemeStoreQuery
    {
        return SpyShopThemeStoreQuery::create();
    }

    /**
     * @return \SprykerDemo\Zed\ShopTheme\Persistence\Mapper\ShopThemeMapper
     */
    public function createShopThemeMapper(): ShopThemeMapper
    {
        return new ShopThemeMapper(
            $this->getUtilEncodingService(),
            $this->createShopThemeStoreRelationMapper(),
        );
    }

    /**
     * @return \SprykerDemo\Zed\ShopTheme\Persistence\Mapper\ShopThemeStoreRelationMapper
     */
    public function createShopThemeStoreRelationMapper(): ShopThemeStoreRelationMapper
    {
        return new ShopThemeStoreRelationMapper();
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ShopThemeDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
