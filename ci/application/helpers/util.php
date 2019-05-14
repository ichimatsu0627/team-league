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
