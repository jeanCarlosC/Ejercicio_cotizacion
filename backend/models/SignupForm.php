<?php
namespace backend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $nombre;
    public $apellido;
    public $tipo_usuario;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este nombre de usuario ya está en uso.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['nombre', 'string','max'=>255],
            ['nombre','required'],
            ['tipo_usuario', 'string','max'=>45],
            ['tipo_usuario','required'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este correo ya está en uso.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Nombre de Usuario',
            'password' => 'Contraseña',
            'email' => 'Correo Electrónico',
            'nombre' =>'Nombre y apellido',
            'tipo_usuario' =>'Tipo de usuario',
            ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {

            if($this->tipo_usuario == "A")
            {
               $ultimo_admin = User::find()->where(['LIKE', 'codigo', 'A'])->orderby(['id'=>SORT_DESC])->one();
               if(!empty($ultimo_admin))
               {
                $codigo= explode('-', $ultimo_admin->codigo);
                $cod="A-".sprintf("%02d",$codigo[1]+1);
               }
               else
               {
                    $cod ="A-01";
               } 
            }
            else{

            $ultimo_user = User::find()->where(['LIKE', 'codigo', 'V'])->orderby(['id'=>SORT_DESC])->one();
            if(!empty($ultimo_user)){
            $codigo= explode('-', $ultimo_user->codigo);
            $cod="V-".sprintf("%02d",$codigo[1]+1);
            }
            else
            {
            $cod="V-01";
            }

            }
            
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->nombre = $this->nombre;
            $user->codigo = $cod;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
