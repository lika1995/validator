<?php
namespace Lika\Validate;

class Validate
{
    private $passed = false, $errors = [], $pdo = null;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param $source
     * @param array $items
     * @return $this
     *
     *
     */
    public function check($source, $items = [])
    {
        foreach($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value){
                $value = $source[$item];


                if($rule == 'required' && empty($value)){
                    $this->addError("Поле {$item} обязательно для заполнения ");
                } else if(!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$item} должен быть не менее {$rule_value} символов");
                            }
                            break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$item} должен быть не более {$rule_value} символов");
                            }
                            break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} должен совпадать {$item}");
                            }
                            break;

                        case 'unique':
                            $stmt = $this->pdo->prepare("SELECT * FROM {$rule_value} WHERE {$item} = ?");
                            $stmt->bindValue(1, $value);
                            $stmt->execute();
                            $check = $stmt->fetchAll(PDO::FETCH_OBJ);


                            if(count($check) > 0){
                                $this->addError("{$item} уже занят.");
                            }
                            break;

                        case 'email':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                                $this->addError("{$item} не email, введите корректные данные");
                            }
                            break;
                    }
                }

            }
        }
        if(empty($this->errors())){
            $this->passed = true;
        }
        return $this;
    }

    public function addError($error){
        $this->errors[] = $error;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passed()
    {
        return $this->passed;
    }
}