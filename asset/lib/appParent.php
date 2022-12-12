<?php


class appParent
{
    private $multiField = ['select','input','textArea'];
    public $error = [];
    public $data;
    function process($data){
        if(in_array('beforeAllStep',get_class_methods($this))){
            $this->beforeAllStep();
        }
        if(empty($data) || !isset($data['step'])){
            $response = $this->init();
        }else{
            $this->data = $data;

            $before = null;
            if(isset($data['act']) && $data['act'] == 'same'){
                $before = "sameStep".$data['step'];
            }
            else if(!(isset($data['noSubmit']) && $data['noSubmit'] == 1)){
                $this->error = [];
                $before = "beforeStep".$data['step'];
            }
            $check = (!empty($before) && in_array($before,get_class_methods($this))) ? $this->$before() : true;
            $response = ($check) ? $this->{"step".$data['step']}() : $this->error;
        }


        $this->pubResponse($response);
    }

    function pubResponse($response){
        if(!empty($this->error)){
            retour([
                'status' => 'error',
                'response' => $response
            ]);
        }
        $response['data']['step'] = $this->data['step']+1;
        foreach ($this->multiField as $field){
            if(!empty($response['data'][$field])){
                $response['data'][$field."s"] = [$response['data'][$field]];
                unset($response['data'][$field]);
            }

            if(isset($response['data'][$field."s"]) && !empty($this->allData)){
                foreach ($response['data'][$field."s"] as $i=>$arr){
                    if(isset($this->allData[$arr['name']]) && !empty($this->allData[$arr['name']])){
                        $response['data'][$field."s"][$i]['value'] = $this->allData[$arr['name']];
                    }
                }
            }
        }
        retour([
            'status' => 'success',
            'response' => $response
        ]);
    }

    function addError($field,$msg){
        if(empty($this->error['field']) || !in_array($field,$this->error)){
            $this->error['field'][] = $field;
        }
        $this->error['msg'][] = $msg;
    }

    function checkEmpty($array){
        foreach ($array as $field){
            if(empty($this->data[$field])){
                $this->addError($field,"La donnée $field ne peut pas être vide");
            }
        }
        return empty($this->error);
    }

    function checkNbrChar($array, $min = 0, $max = 0){
        foreach ($array as $field){
            $size = strlen($this->data[$field]);
            if($min!=0 && $size < $min){
                $this->addError($field,"La donnée $field à encore besoin de ".($min-$size)." caractères");
            }
            if($max!=0 && $size > $max){
                $this->addError($field,"La donnée $field à ".($size-$max)." caractères en trop");
            }
        }
        return empty($this->error);
    }

}