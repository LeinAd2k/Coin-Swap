<?php

namespace app\models;

use yii\base\Model;

class ExchangeForm extends Model
{
    public $id;
    public $currency_id_from;
    public $currency_id_to;
    public $currency_from;
    public $currency_to;
    public $amount;
    public $sum;
    public $client_wallet;
    public $client_card;
    public $email;
    public $verifyCode;
    public $order;
    public $key;
    public $admin_email;

    public function rules()
    {
        return [
            [['currency_id_from', 'currency_id_to', 'amount', 'sum', 'client_wallet', 'email'], 'required'],
            ['amount', 'integer', 'integerOnly' => false, 'min' => 0.00001, 'max' => 1000000, 'tooSmall' => '{attribute} must be not less than 0.00001'],
            ['sum', 'integer', 'integerOnly' => false],
            ['amount', 'validateAmount'],
            ['sum', 'validateSum'],
            ['email', 'email'],
            ['client_card', 'string'],
            ['client_wallet', 'match', 'pattern' => '/^([a-zA-Z0-9_-]){10,50}$/'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [

            'currency_id_from' => 'Currency',
            'currency_id_to' => 'Currency',
            'amount' => 'Send',
            'sum' => 'Receive',
            'verifyCode' => 'Verification Code',
            'client_card' => 'Your electronic card number',
            'client_wallet' => 'Your wallet address',
            'email' => 'Your e-mail',
        ];
    }

    public function validateSum()
    {
        $this->currency_to = Currency::find()
            ->select('min_payout, max_payout, name')
            ->where(['id' => $this->currency_id_to])
            ->one();

        if ($this->currency_to->min_payout && $this->sum < $this->currency_to->min_payout) {
            $this->addError('sum', 'Minimal ' . $this->currency_to->name . ' payout sum is ' . $this->currency_to->min_payout);
        };

        if ($this->currency_to->max_payout && $this->sum > $this->currency_to->max_payout) {
            $this->addError('sum', 'Maximal ' . $this->currency_to->name . ' payout sum is ' . $this->currency_to->max_payout);
        };
    }

    public function validateAmount()
    {
        $this->currency_from = Currency::find()
            ->select('max_income, name')
            ->where(['id' => $this->currency_id_from])
            ->one();

        if ($this->currency_from->max_income && $this->amount > $this->currency_from->max_income) {
            $this->addError('amount', 'Maximal ' . $this->currency_from->name . ' income amount is ' . $this->currency_from->max_income);
        };
    }

    public static function calculateSum($currency_id_from, $currency_id_to, $amount)
    {
        $currency_from = Currency::find()
            ->select('buy')
            ->where(['id' => $currency_id_from])
            ->one();
        $currency_to = Currency::find()
            ->select('sell')
            ->where(['id' => $currency_id_to])
            ->one();
        $result = $amount * $currency_from->buy / $currency_to->sell;

        return floatval(number_format(round($result, 5), 5, '.', ''));
    }

    public static function calculateAmount($currency_id_from, $currency_id_to, $sum)
    {
        $currency_from = Currency::find()
            ->select('buy')
            ->where(['id' => $currency_id_from])
            ->one();
        $currency_to = Currency::find()
            ->select('sell')
            ->where(['id' => $currency_id_to])
            ->one();
        $result = $sum / $currency_from->buy * $currency_to->sell;

        return floatval(number_format(round($result, 5), 5, '.', ''));
    }
}
