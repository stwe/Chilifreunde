<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class SeasonDatatable
 *
 * @package AppBundle\Datatables
 */
class SeasonDatatable extends AbstractDatatableView
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
        $repository = $this->em->getRepository('AppBundle:Plant');
        $router = $this->router;

        $formatter = function($line) use ($repository, $router) {
            $sum = $repository->getPlantSum($line['id']);
            null === $sum ? $line['allPlants'] = 0 : $line['allPlants'] = $sum;

            $route = $router->generate('season_show', array('id' => $line['id']));
            $line['title'] = '<a href="' . $route . '">' . $line['title'] . '</a>';

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->topActions->set(array(
            'start_html' => '<div class="row"><div class="col-sm-3">',
            'end_html' => '<hr></div></div>',
            'actions' => array(
                array(
                    'route' => $this->router->generate('season_new'),
                    'label' => $this->translator->trans('datatables.actions.new'),
                    'icon' => 'glyphicon glyphicon-plus',
                    'role' => 'ROLE_USER',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => $this->translator->trans('datatables.actions.new'),
                        'class' => 'btn btn-primary',
                        'role' => 'button'
                    ),
                )
            )
        ));

        $this->features->set(array(
            'scroll_x' => true,
            'extensions' => array(
                'buttons' =>
                    array(
                        'pdf' => array(
                            'extend' => 'pdf',
                            'exportOptions' => array(
                                'columns' => array(
                                    '0', // title column
                                    '1', // username column
                                    '2', // start column
                                    '3', // end column
                                    '4', // plants column
                                    '5', // allPlants column
                                )
                            )
                        ),
                    ),
                'responsive' => true
            )
        ));

        $this->ajax->set(array(
            'url' => $this->router->generate('season_private_results'),
            'type' => 'GET'
        ));

        $this->options->set(array(
            'class' => Style::BOOTSTRAP_3_STYLE . ' table-condensed',
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true
        ));

        $user = $this->em->getRepository('AppBundle:User')->findAll();

        $this->columnBuilder
            ->add('title', 'column', array(
                'title' => 'Titel',
            ))
            ->add('user.username', 'column', array(
                'title' => 'Benutzer',
                'filter' => array('select', array(
                    'select_options' => array('' => 'Alle') + $this->getCollectionAsOptionsArray($user, 'username', 'username'),
                    'search_type' => 'eq',
                )),
                'visible' => $options['show_user']
            ))
            ->add('start', 'datetime', array(
                'title' => 'Start',
                'date_format' => 'll',
                'filter' => array('daterange', array()),
            ))
            ->add('end', 'datetime', array(
                'title' => 'Ende',
                'date_format' => 'll',
                'filter' => array('daterange', array()),
            ))
            ->add('plants.chili.name', 'array', array(
                'title' => 'Sorten',
                'searchable' => false,
                'orderable' => false,
                'data' => 'plants[, ].chili.name',
                'count' => true,
            ))
            ->add('allPlants', 'virtual', array(
                'title' => 'Pflanzen',
            ))
            ->add('posts.title', 'array', array(
                'title' => 'Beiträge',
                'searchable' => false,
                'orderable' => false,
                'data' => 'posts[, ].title',
                'count' => true
            ))
            ->add(null, 'action', array(
                'title' => 'Aktionen',
                'actions' => array(
                    array(
                        'route' => 'season_show',
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
                        'route' => 'season_edit',
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
                        'render_if' => function($rowEntity) {
                            return ($rowEntity['user']['username'] == $this->getUser()->getUsername());
                        },
                        /*
                        'render_if' => array(
                            'user.username' => $this->getUser()->getUsername()
                        )
                        */
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
        return 'AppBundle\Entity\Season';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'season_datatable';
    }
}
