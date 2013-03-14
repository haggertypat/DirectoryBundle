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
