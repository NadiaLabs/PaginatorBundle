<?php
namespace NadiaProject\Bundle\PaginatorBundle\Doctrine\ORM\Query\Hydrator;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;

class CountHydrator extends AbstractHydrator
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    protected function hydrateAllData()
    {
        return $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
