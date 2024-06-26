<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlock;

use Codeception\Actor;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsBlockBusinessTester extends Actor
{
    use _generated\CmsBlockBusinessTesterActions;

    /**
     * @param array<string> $storeNames
     *
     * @return array<int>
     */
    public function createStoresByNames(array $storeNames): array
    {
        $storeIds = [];
        foreach ($storeNames as $storeName) {
            $storeIds[] = $this->haveStore([StoreTransfer::NAME => $storeName])->getIdStore();
        }

        return $storeIds;
    }

    /**
     * @return void
     */
    public function ensureCmsBlockTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getCmsBlockQuery());
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    protected function getCmsBlockQuery(): SpyCmsBlockQuery
    {
        return SpyCmsBlockQuery::create();
    }
}
