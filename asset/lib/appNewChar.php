<?php


class appNewChar extends appParent
{
    private $infoStep = [
        '0' => [
            'name' => [
                'requiered' => true,
            ],
            'age' => [
                'requiered' => true,
            ]
        ],
        '1' => [
            'image' => [
                'requiered' => true,
            ],
            'genre' => [
                'requiered' => true,
            ],
            'physique' => [
                'requiered' => true,
            ],
        ],
        '2' => [
            'vPrimaire' => [
                'requiered' => true,
            ],
            'vSecondaire' => [
                'requiered' => true,
            ]
        ],
        '3' => [
            'race' => [
                'requiered' => true,
            ]
        ],
        '4' => [
            'caractere' => [
                'requiered' => true,
            ],
            'objectif' => [
                'requiered' => true,
            ]
        ],
        '5' => [
            'story' => [
                'requiered' => true,
                'allChar' => 500
            ]
        ],
        '6' => [
            'don' => [
                'requiered' => true,
                'allChar' => 200
            ]
        ]
    ];
    function init(){
        if(isset($this->state[6]) && $this->state[6] == 2){
            $return = [
                'notBack' => true,
                'noSubmit' => true,
                'data' => [
                    'select' => [
                        'name' => 'step',
                        'label' => 'Choisis la partie de la fiche à consulter',
                        'options' => [
                            '0' => [
                                'label'=>'Nom&Prenom',
                            ],
                            '1' => [
                                'label'=>'Image&Genre',
                            ],
                            '2' => [
                                'label'=>'Voie',
                            ],
                            '3' => [
                                'label'=>'Race',
                            ],
                            '4' => [
                                'label'=>'Caractère',
                            ],
                            '5' => [
                                'label'=>'Story',
                            ],
                            '6' => [
                                'label'=>'Don',
                            ],

                        ]
                    ]
                ]
            ];

            return $return;
        }else{
            $this->data['step'] = $this->lastStep;
            return $this->{"step".$this->lastStep}();
        }
    }

    function beforeAllStep(){
        $allData = sql::createArrayOrder("select label,value from ficheData where idPerso='{$_SESSION['idPerso']}'",'label');
        $state = [];
        $lastStep = 0;
        foreach ($this->infoStep as $step=>$array){
            $allRequiered = 1;
            $nbrChamp = 0;
            foreach ($array as $label=>$opp){
                if(!empty($opp['allChar'])){
                    $size = countCharRecur($allData,"text-$label");
                    if($size > 1){
                        $nbrChamp = 1;
                    }
                    if($size < $opp['allChar']){
                        $allRequiered = 0;
                    }
                }else{
                    $nbrChamp += empty($allData[$label]) ? 0 : 1;
                    if(isset($opp['requiered']) && $opp['requiered'] && empty($allData[$label])){
                        $allRequiered = 0;
                    }
                }
            }
            $state[$step] = ($nbrChamp == count($array)) ? 2 : $allRequiered;
            if($state[$step]){
                $lastStep = $step + ($state[$step]==2 && count($this->infoStep)>$step+1 ? 1 : 0);
            }
        }
        $this->validate = false; // à édité
        $this->state = $state;
        $this->allData = $allData;
        $this->lastStep = $lastStep;
    }

    function step0(){
        $return = [
            'notBack' => true,
            'data' => [
                'label' => 'Bienvenue dans l\'outil de création de fiche !',
                'inputs' => [
                    [
                        'name' => 'name',
                        'label' => 'Ton identité',
                        'placeHolder' => 'Zheneos Hikari',
                    ],
                    [
                        'name' => 'age',
                        'label' => 'Ton âge',
                        'placeHolder' => '21',
                        'type' => 'number'
                    ]

                ]
            ]
        ];

        return $return;
    }

    function beforeStep1(){
        if(!$this->checkEmpty(['name','age'])){
            return false;
        }

        if($this->data['age'] < 15 || $this->data['age'] > 25){
            $this->addError('age',"l'âge doit être compris entre 15 et 25 ans");
        }

        $sql = "select 1 from perso where prenom like '" . explode(' ', $this->data['name'])[0] . " %'";
        if (sql::fetch($sql)) {
            $this->addError('name',"le premier mot du nom est déjà utilisé");
        }
        $this->insertOrUpdateData(['name','age']);

        return empty($this->error);


    }

    function step1(){
        $return = [
            'data' => [
                'input' =>
                    [
                        'name' => 'image',
                        'label' => 'Ton image',
                        'placeHolder' => 'https://i.pinimg.com/564x/b2/19/85/b21985a69dd8915046284383325458be.jpg'
                    ],
                'select' => [
                    'name' => 'genre',
                    'label' => 'Ton genre',
                    'options' => [
                        'Homme' => [
                            'label'=>'Homme',
                        ],
                        'Femme' => [
                            'label'=>'Femme',
                        ],
                        'Autre' => [
                            'label'=>'Autre',
                        ],
                    ]
                ],
                'textArea' => [
                    'name' => 'physique',
                    'label' => 'Décrivez physiquement votre personnage ? (Entre 100 et 1900 caractères)',
                    'max' => 1900
                ],

            ]
        ];
        return $return;
    }

    function beforeStep2(){
        if(!$this->checkEmpty(['genre','image','physique'])){
            return false;
        }

        if(!$this->checkNbrChar(['physique'],100,1900)){return false;}

        $this->insertOrUpdateData(['genre','image','physique']);

        return empty($this->error);
    }

    function step2(){
        $optVoie = [];
        foreach (sql::getJsonBdd("select value from botExtra where label='vAll'") as $key => $descr){
            $optVoie[$key] = [
                'label' => $key,
                'help' => $descr
            ];
        }
        $return = [
            'data' => [
                'selects' => [
                    [
                        'name' => 'vPrimaire',
                        'label' => 'Voie primaire',
                        'options' => $optVoie
                    ],
                    [
                        'name' => 'vSecondaire',
                        'label' => 'Voie secondaire',
                        'options' => $optVoie
                    ]
                ]
            ]
        ];
        if($this->validate){
            $return['data']['selects'][0]['disabled'] = true;
            $return['data']['selects'][1]['disabled'] = true;
        }
        return $return;
    }

    function beforeStep3(){
        if(!$this->checkEmpty(['vPrimaire','vSecondaire'])){
            return false;
        }
        if($this->data['vPrimaire'] == $this->data['vSecondaire']){
            $this->addError('vSecondaire',"Les deux voies choisis sont identique, Si c'est voulu, voir avec un MJ.");
            return false;
        }
        $this->insertOrUpdateData(['vPrimaire','vSecondaire']);
        return empty($this->error);
    }

    function step3(){
        $raceByVoie = sql::getJsonBdd( "select value from botExtra where label='raceByVoie'" );
        $raceDescr = sql::getJsonBdd( "select value from botExtra where label='race'" );
        $array = [ 'all' ];
        $result = sql::fetchAll( "SELECT value FROM ficheData WHERE idPerso='{$_SESSION['idPerso']}' AND label in ('vPrimaire','vSecondaire')" );
        foreach ( $result as $v ) {
            $array[] = $v[ 0 ];
        }

        $raceOpt = [];
        foreach ( $array as $v ) {
            $v = strtolower( $v );
            if ( !empty( $raceByVoie[ $v ] ) ) {
                foreach ( $raceByVoie[ $v ] as $race ) {
                    $race = ucwords( $race );
                    if ( !in_array( $race, array_keys($raceOpt) ) ) {
                        $raceOpt[$race] = [
                            'label' => $race,
                            'help' => $raceDescr[$race]
                        ];
                    }
                }
            }
        }

        $return = [
            'data' => [
                'selects' => [
                    [
                        'name' => 'race',
                        'label' => 'Ta race',
                        'options' => $raceOpt
                    ]
                ]
            ]
        ];
        if($this->validate){
            $return['data']['selects'][0]['disabled'] = true;
        }
        return $return;


    }

    function beforeStep4(){
        if(!$this->checkEmpty(['race'])){
            return false;
        }
        $this->insertOrUpdateData(['race']);
        return empty($this->error);
    }

    function step4(){
        $return = [
            'data' => [
                'textAreas' => [
                    [
                        'name' => 'caractere',
                        'label' => 'Décrivez la personnalité de votre personnage ? (Entre 200 et 1900 caractères)',
                        'max' => 1900
                    ],
                    [
                        'name' => 'objectif',
                        'label' => 'Quel objectif vous visez ? (entre 200 et 1900 caractères)',
                        'max' => 1900
                    ]
                ]
            ]
        ];
        return $return;
    }

    function beforeStep5(){
        $fStep = ['caractere','objectif'];
        if(!$this->checkEmpty($fStep)){return false;}
        if(!$this->checkNbrChar($fStep,200,1900)){return false;}
        $this->insertOrUpdateData($fStep);
        return empty($this->error);
    }

    function sameStep5(){
        return $this->same('story');
    }

    function step5(){
        return $this->stepChap('story','Décrit nous ton histoire',500);
    }

    function beforeStep6(){
        if($this->sameStep5() === false){return false;}
        $size = countCharRecur($this->allData,"text-story");

        if($size < 500){
            $this->addError("","Pas assez de caractère");
        }
        return empty($this->error);
    }

    function sameStep6(){
        return $this->same('don');
    }

    function step6(){
        return $this->stepChap('don','Décrit nous ton don',200);
    }

    function beforeStep7(){
        if($this->sameStep6() === false){return false;}
        $size = countCharRecur($this->allData,'text-don');

        if($size < 200){
            $this->addError("","Pas assez de caractère");
        }
        return empty($this->error);
    }

    function step7(){
        $return = [
            'notNext' => true,
            'data' => [
                'label' => 'Fiche finis ! Contactez un MJ pour la validation ! ;3',
            ]
        ];
        return $return;
    }

    function insertOrUpdateData($array){
        $idPerso = $_SESSION['idPerso'];
        $now = date("Y-m-d H:i:s");
        foreach ($array as $label){
            $value = addslashes($this->data[$label]);
            $sql = "insert into ficheData values ('$idPerso','$label','$value','$now')  ON DUPLICATE KEY UPDATE VALUE='$value',dateInsert='$now'";
            $this->allData[$label] = $this->data[$label];
            sql::query($sql);
        }
    }

    // Fonction identique
    function same($label){
        $field = [];
        foreach ($this->data as $key=>$val){
            $tmp = explode("-$label-",$key);
            if(count($tmp) > 1){
                $field = ["text-$label-{$tmp[1]}","title-$label-{$tmp[1]}"];
                break;
            }
        }
        if(!(empty($this->data[$field[0]]) && empty($this->data[$field[1]]))){ // Si donnée vide, rien à soumettre.
            if(!$this->checkEmpty($field)){return false;}
            if(!$this->checkNbrChar([$field[0]],50,1900)){return false;}
            $this->insertOrUpdateData($field);
        }

        return true;
    }

    function stepChap($field,$label,$minChar){
        $opt = [];
        $lastChap = 0;
        foreach ($this->allData as $key=>$val){
            $tmp = explode("title-$field-",$key);
            if(count($tmp) > 1){
                $opt[$tmp[1]] = ['label'=>$val];
                $lastChap = $lastChap < $tmp[1] ? $tmp[1] : $lastChap;
            }
        }
        if(isset($this->data["selectChapter$field"])){
            $lastChap = $this->data["selectChapter$field"];
        }
        $size = countCharRecur($this->allData,"text-$field");
        if(count($opt) == $lastChap){
            $opt[] = ['label' => "Chapitre ".count($opt)];
        }
        $opt[] = ['label' => 'Nouveau chapitre'];
        $return = [
            'data' => [
                'alert' => [
                    'class' => 'alert-'.($size >= $minChar ? 'success' : 'danger').' cibleTextArea',
                    'label' => "total de $size caractères sur les $minChar requis"
                ],
                'label' => $label,
                'select' => [
                    'change' => 'redirectStep',
                    'name' => "selectChapter$field",
                    'label' => 'Selectionner le chapitre à édité/rajouté',
                    'value' => $lastChap,
                    'options' => $opt
                ],
                'input' => [
                    'name' => "title-$field-$lastChap",
                    'label' => 'Nom du chapitre',
                    'placeHolder' => 'Introduction',
                ],
                'textArea' => [
                    'name' => "text-$field-$lastChap",
                    'blur' => "saveDataStep",
                    'focus' => 'showHelpCible',
                    'placeHolder' => 'Pour commencer...',
                    'max' => 1900,
                    'help' => 'Une fois un nom de chapitre renseigné <br>son contenu serra enregistrer(si il dépasse 50 caractères) à chaque fois que vous quitterez le texte (entrainant la fermeture du message bleu)'
                ],
            ]
        ];
        return $return;
    }

}