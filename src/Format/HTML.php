<?php

namespace TheSupportGroup\Common\Validator\Format;

use TheSupportGroup\Common\Validator\Contracts\Format\FormatInterface;

class HTML implements FormatInterface
{
    public function reformat($messages)
    {
        $li_lists = '';

        $ul = "<ul>\n%s</ul>";

        foreach ($messages as $message) {
            foreach ($message as $content) {
                $li = "<li>%s</li>\n";

                $li_lists .= sprintf($li, $content);
            }
        }

        return sprintf($ul, $li_lists);
    }
}
