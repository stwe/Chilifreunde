<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class ChiliDatatable
 *
 * @package AppBundle\Datatables
 */
class ChiliDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;

        $formatter = function($line) use ($router) {
            $route = $router->generate('chili_show', array('id' => $line['id']));
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
                                    '1', // alternativeNames column
                                    '4', // heat column
                                    '5', // origin column
                                    '6', // fruitcolor column
                                    '7', // maturity column
                                    '8'  // species column
                                )
                            )
                        ),
                    ),
                'responsive' => true
            )
        ));

        $this->ajax->set(array(
            'url' => $this->router->generate('chili_private_results'),
            'type' => 'GET'
        ));
        
        $this->options->set(array(
            'class' => Style::BOOTSTRAP_3_STYLE . ' table-condensed',
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'length_menu' => array(10, 50, -1)
        ));

        $fruitcolor = $this->em->getRepository('AppBundle:Fruitcolor')->findAll();
        $maturity = $this->em->getRepository('AppBundle:Maturity')->findAll();
        $species = $this->em->getRepository('AppBundle:Species')->findAll();

        $this->columnBuilder
            ->add('name', 'column', array(
                'title' => 'Name',
            ))
            ->add('alternativeNames', 'column', array(
                'title' => 'Alt. Namen',
            ))
            ->add('images.fileName', 'gallery', array(
                'title' => 'Bilder',
                'relative_path' => 'images',
                'imagine_filter' => 'thumbnail_50_x_50',
                'imagine_filter_enlarged' => 'thumbnail_500_x_500',
                'enlarge' => true,
                'view_limit' => 4
            ))
            ->add('heat.value', 'column', array(
                'visible' => false,
                'filter' => array('text', array(
                    'search_type' => 'eq'
                ))
            ))
            ->add('heat.heat', 'progress_bar', array(
                'title' => 'Schärfe',
                'filter' => array('slider', array(
                    'min' => 0.0,
                    'max' => 11.0,
                    'cancel_button' => true,
                    'property' => 'heat.value',
                    'formatter' => ':chili:slider.js.twig'
                )),
                'value_min' => '0',
                'value_max' => '10',
                'multi_color' => true
            ))
            ->add('origin', 'column', array(
                'title' => 'Herkunft',
            ))
            ->add('fruitcolor.color', 'column', array(
                'title' => 'Fruchtfarbe',
                'filter' => array('select', array(
                    'select_options' => array('' => 'Alle') + $this->getCollectionAsOptionsArray($fruitcolor, 'color', 'color'),
                    'search_type' => 'eq'
                )),
            ))
            ->add('maturity.description', 'column', array(
                'title' => 'Reifezeit',
                'filter' => array('select', array(
                    'select_options' => array('' => 'Alle') + $this->getCollectionAsOptionsArray($maturity, 'description', 'description'),
                    'search_type' => 'eq'
                ))
            ))
            ->add('species.name', 'column', array(
                'title' => 'Art',
                'filter' => array('select', array(
                    'select_options' => array('' => 'Alle') + $this->getCollectionAsOptionsArray($species, 'name', 'name'),
                    'search_type' => 'eq'
                ))
            ))
            ->add('public', 'boolean', array(
                'visible' => false,
            ))
            ->add(null, 'action', array(
                'title' => 'Aktionen',
                'actions' => array(
                    array(
                        'route' => 'chili_show',
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
                        'route' => 'chili_edit',
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
        return 'AppBundle\Entity\Chili';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'chili_datatable';
    }
}
