<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class AddressDatatable
 *
 * @package AppBundle\Datatables
 */
class AddressDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $url = null;

        if (array_key_exists('sourceId', $options)) {
            $url = $this->router->generate('address_source_results', array('sourceId' => $options['sourceId']));
        } else {
            $url = $this->router->generate('address_user_results', array('userId' => $options['userId']));
        }

        $this->features->set(array(
            'scroll_x' => true,
            'extensions' => array(
                'buttons' =>
                    array(
                        'pdf'
                    ),
                'responsive' => true
            )
        ));

        $this->ajax->set(array(
            'url' => $url,
            'type' => 'GET'
        ));

        $this->options->set(array(
            'class' => Style::BOOTSTRAP_3_STYLE,
            'use_integration_options' => true,
            'force_dom' => false
        ));

        $this->columnBuilder
            ->add('company', 'column', array(
                'title' => 'Firma',
            ))
            ->add('name', 'column', array(
                'title' => 'Name',
            ))
            ->add('street', 'column', array(
                'title' => 'Straße',
            ))
            ->add('postcode', 'column', array(
                'title' => 'PLZ',
            ))
            ->add('city', 'column', array(
                'title' => 'Ort',
            ))
            ->add('country', 'column', array(
                'title' => 'Land',
            ))
            ->add('phoneNumber', 'column', array(
                'title' => 'Telefon',
            ))
            ->add('homepage', 'column', array(
                'title' => 'Homepage',
            ))
            ->add('source.id', 'column', array(
                'visible' => false,
            ))
            ->add('user.id', 'column', array(
                'visible' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Address';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'address_datatable';
    }
}
