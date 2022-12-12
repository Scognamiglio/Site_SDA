<?php


class appAll extends appParent
{
private $listApp = [
    'all' => 'Liste les différentes applications possible',
    'newChar' => "Permet la conception et l'édition d'un personnage"
    ];
    function init(){
        $return = [
            'notBack' => true,
            'data' => [
                'select' => [
                    'name' => 'app',
                    'options' => [
                        'all' => [
                            'label'=>'all',
                            'help'=>'Liste les différentes applications possible'
                        ],
                        'newChar' => [
                            'label'=>'newChar',
                            'help'=>"Permet la conception et l'édition d'un personnage"
                        ]
                    ]
                ]
            ],
            'up' => 'redirect'
        ];
        return $return;
    }
}