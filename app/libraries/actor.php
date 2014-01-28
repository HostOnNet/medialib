<?php

class Actor
{

    public static function haveName($description)
    {
        if (preg_match('/.*\[.*\].*/', $description))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function getName($description)
    {
        $name = '';

        if (preg_match('/.*[=,]+\s+(.*\[.*\]).*/', $description, $match))
        {
            $name = $match[1];
        }

        return $name;
    }

    public static function getNameEn($name)
    {
        if (preg_match('/(.*)\[.*/', $name, $match))
        {
            return trim($match[1]);
        }
    }

    public static function getNameJp($name)
    {
        if (preg_match('/.*\[(.*)\].*/', $name, $match))
        {
            return trim($match[1]);
        }
    }

    public static function getLinks($name)
    {
        echo " <b>$name</b> ";

        $links = explode("\n", Settings::get('meta_links'));

        foreach ($links as $link_tpl)
        {
            $link_tpl = trim($link_tpl);

            if (strlen($link_tpl) > 10)
            {
                $link_parts = explode("|", $link_tpl);
                echo "<a href='" . $link_parts[0] . urlencode($name) . "''>" . $link_parts[1] . "</a> &nbsp; ";
            }
        }
    }
}