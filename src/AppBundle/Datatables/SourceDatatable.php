<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class SourceDatatable
 *
 * @package AppBundle\Datatables
*/
class SourceDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;

        $formatter = function($line) use ($router) {
            $route = $router->generate('source_show', array('id' => $line['id']));
            $line['name'] = '<a href="' . $route . '">' . $line['name'] . '</a>';

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->features->set(array(
            'scroll_x' => true,
            'extensions' => array(
                'buttons' =>
                    array(
                        'pdf' => array(
                            'extend' => 'pdf',
                            'exportOptions' => array(
                                'columns' => array(
                                    '0', // name column
                                    '1', // addresses column
                                )
                            )
                        ),
                    ),
                'responsive' => true
            )
        ));

        $this->ajax->set(array(
            'url' => $this->router->generate('source_private_results'),
            'type' => 'GET'
        ));

        $this->options->set(array(
            'class' => Style::BOOTSTRAP_3_STYLE . ' table-condensed',
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true
        ));

        $this->columnBuilder
            ->add('name', 'column', array(
                'title' => 'Name',
            ))
            ->add('addresses.city', 'array', array(
                'title' => 'Adressen',
                'searchable' => false,
                'count' => true,
                'data' => 'addresses[, ].city',
                'count_action' => array(
                    'route' => 'source_show',
                    'route_parameters' => array(
                        'id' => 'id'
                    ),
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => 'Comments',
                        'class' => 'badge alert-info',
                        'role' => 'button'
                    ),
                )
            ))
            ->add('public', 'boolean', array(
                'visible' => false,
            ))
            ->add(null, 'action', array(
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'source_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Anzeigen',
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Anzeigen',
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'source_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Ändern',
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Ändern',
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                        'render_if' => array(
                            'public' => false
                        )
                    )
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Source';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'source_datatable';
    }
}
