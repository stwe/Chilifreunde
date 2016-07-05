<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class PlantDatatable
 *
 * @package AppBundle\Datatables
*/
class PlantDatatable extends AbstractDatatableView
{
    /**
     * Get User.
     *
     * @return mixed|null
     */
    private function getUser()
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->securityToken->getToken()->getUser();
        } else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;

        $formatter = function($line) use ($router) {
            $route = $router->generate('chili_show', array('id' => $line['chili']['id']));
            $line['chili']['name'] = '<a href="' . $route . '">' . $line['chili']['name'] . '</a>';

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
                        'pdf'
                    ),
                'responsive' => true
            )
        ));

        $this->ajax->set(array(
            'url' => $this->router->generate('plant_results', array('seasonId' => $options['seasonId'])),
            'type' => 'GET'
        ));

        $this->options->set(array(
            'class' => Style::BOOTSTRAP_3_STYLE . ' table-condensed',
            'use_integration_options' => true,
            'force_dom' => false
        ));

        $this->columnBuilder
            ->add('chili.name', 'column', array(
                'title' => 'Chili',
            ))
            ->add('chili.public', 'boolean', array(
                'title' => 'Öffentliche Chili',
                'true_label' => 'Ja',
                'false_label' => 'Nein'
            ))
            ->add('chili.heat.heat', 'progress_bar', array(
                'title' => 'Schärfe',
                'value_min' => '0',
                'value_max' => '10',
                'multi_color' => true
            ))
            ->add('chili.species.name', 'column', array(
                'title' => 'Art',
            ))
            ->add('sowing', 'column', array(
                'title' => 'Aussaat',
            ))
            ->add('quantity', 'column', array(
                'title' => 'Pflanzen',
            ))
            ->add('location.name', 'column', array(
                'title' => 'Standort',
            ))
            ->add('source.name', 'column', array(
                'title' => 'Bezugsquelle',
            ))
            ->add('season.id', 'column', array(
                'visible' => false,
            ))
            ->add('season.user.username', 'column', array(
                'visible' => false,
            ))
            ->add(null, 'action', array(
                'title' => 'Aktionen',
                'actions' => array(
                    array(
                        'route' => 'plant_edit',
                        'route_parameters' => array(
                            'seasonId' => 'season.id',
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
                        'render_if' => function($row) {
                            return ($row['season']['user']['username'] == $this->getUser()->getUsername());
                        },
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
        return 'AppBundle\Entity\Plant';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'plant_datatable';
    }
}
