<?php

if ( ! function_exists('between'))
{
    /**
     * 更新日時でバージョン管理されたCSSファイルのHTMLタグを吐き出す
     *
     * @param int $base
     * @param int $from
     * @param int $to
     * @return boolean
     */
    function between($base, $from, $to) : bool
    {
        if ($base < $from)
        {
            return false;
        }

        if ($base > $to)
        {
            return false;
        }

        return true;
    }
}

if ( ! function_exists('now'))
{
    /**
     * 現在の日時取得
     *
     * @return string
     */
    function now()
    {
        return date("Y-m-d H:i:s");
    }
}

if ( ! function_exists('array_to_preg_ortext()'))
{
    function array_to_preg_text($array)
    {
        return '('.implode("|", $array).')';
    }
}

