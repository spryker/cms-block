<?php

namespace Spryker\Zed\CmsBlock\Persistence;


use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTemplateTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method CmsBlockPersistenceFactory getFactory()
 */
class CmsBlockQueryContainer extends AbstractQueryContainer implements CmsBlockQueryContainerInterface
{

    const COLUMN_TEMPLATE_NAME = 'template_name';

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlock()
    {
        return $this->getFactory()
            ->createCmsBlockQuery();
    }

    /**
     * @param int $idCmsBlock
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockById($idCmsBlock)
    {
        return $this->queryCmsBlock()
            ->filterByIdCmsBlock($idCmsBlock);
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByName($name)
    {
        return $this->queryCmsBlock()
            ->filterByName($name);
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate()
    {
        return $this->queryCmsBlock()
            ->joinCmsTemplate()
                ->withColumn(SpyCmsBlockTemplateTableMap::COL_TEMPLATE_NAME, static::COLUMN_TEMPLATE_NAME);
    }
}