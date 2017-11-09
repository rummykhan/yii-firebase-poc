<?php

namespace app\components;

use yii\base\Component;
use yii\web\Response;

class CORSMiddleware extends Component
{
    public function init()
    {
        $this->addHeadersToResponseBeforeSend();
        parent::init();
    }

    protected function addHeadersToResponseBeforeSend()
    {
        $headers = [
            'Access-Control-Allow-Methods' => 'GET, POST, DELETE, PUT, OPTIONS',
            'Access-Control-Allow-Headers' => 'Authorization, Content-type, Access-Control-Allow-Origin',
            'Access-Control-Allow-Origin' => '*',
        ];

        \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) use ($headers) {
            foreach ($headers as $name => $value) {
                \Yii::$app->response->headers->add($name, $value);
            }
        });
    }
}