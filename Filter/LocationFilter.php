<?php

namespace CCETC\DirectoryBundle\Filter;

use Sonata\AdminBundle\Form\Type\Filter\NumberType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

use Sonata\DoctrineORMAdminBundle\Filter\Filter;

class LocationFilter extends Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data['value'] || !$data['type']) {
            return;
        }
        $address = $data['value'];
        $miles = $data['type'];
        
        $queryBuilder->leftjoin($alias.'.location', 'listLoc');
        $queryBuilder->leftjoin('listLoc.distances', 'dist');
        $queryBuilder->andWhere('dist.distance <= :distance');
        $queryBuilder->setParameter('distance', $miles);        
        $queryBuilder->leftjoin('dist.userLocation', 'userLoc');
        $queryBuilder->leftjoin('userLoc.aliases', 'aliases');
        $queryBuilder->andWhere('aliases.alias = :alias');
        $queryBuilder->setParameter('alias', $address);        
        
        $this->active = true;
        
        return;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getRenderSettings()
    {
        return array('ccetc_directory_type_filter_location', array(
        ));
    }
}
