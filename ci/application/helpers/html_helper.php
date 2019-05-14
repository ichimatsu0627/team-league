<?php

if ( ! function_exists('css_nocache_tag'))
{
    /**
     * 更新日時でバージョン管理されたCSSファイルのHTMLタグを吐き出す
     *
     * @param $filename CSSのファイル名
     * @return CSSタグ
     */
    function css_nocache_tag(string $filepath) : string
    {
        // ファイルの更新日時をクエリとして付与
        $filepath .= '?v=' . filemtime(DOCUMENT_ROOT_PATH . $filepath);

        return "<link type=\"text/css\" rel=\"stylesheet\" href=\"{$filepath}\" />";
    }
}

if ( ! function_exists('js_nocache_tag'))
{
    /**
     * 更新日時でバージョン管理されたJSファイルのHTMLタグを吐き出す
     *
     * @param $filename JSのファイル名
     * @return JSタグ
     */
    function js_nocache_tag(string $filepath) : string
    {
        // ファイルの更新日時をクエリとして付与
        $filepath .= '?v=' . filemtime(DOCUMENT_ROOT_PATH . $filepath);

        return "<script type=\"text/javascript\" src=\"{$filepath}\" ></script>";
    }
}
