<?php

namespace TheSupportGroup\Common\Validator\Format;

use TheSupportGroup\Common\Validator\Contracts\Format\FormatInterface;

class Json implements FormatInterface
{
    public function reformat($messages)
    {
        return json_encode($messages);
    }
}
