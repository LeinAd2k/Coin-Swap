<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ConfirmForm is the model behind the contact form.
 */
class ConfirmForm extends Model

{
    public $crypto_wallet;
    public $crypto_card;
    public $client_wallet;
    public $client_card;
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'currency_name_from' => 'From',
            'currency_name_to' => 'To',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {

            /*Yii::$app->mailer->compose('confirm_req_mail.php', [
                'from' =>$this->currency_name_from,
                'to' => $this->currency_name_to,
                'sum' => $this->sum,
                'amount' => $this->amount,
                'email' => $this->email,
            ])
                ->setTo($email)
                ->setFrom([$this->email => 'CryptoExchanger'])
                ->setSubject('New exchange request')
                ->send();*/

            return true;
        }
        return false;
    }
}
