<?php

namespace myerm\sms;


interface SmsInterface
{
    public function send($sMobile, $sContent);
}