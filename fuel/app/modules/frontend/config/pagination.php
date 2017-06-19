<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */
$uri = parse_url($_SERVER['REQUEST_URI']);
$path = $uri['path'];
$query = isset($uri['query'])? explode("=", $uri['query']): false;
$nextPage=0;
$prePage = 0;
if($query){
    $nextPage = $query[1]+1;
    $prePage = $query[1]-1;
}

return array(

    // the active pagination template
    'active'                      => 'default',

    'default'                     => array(
        'wrapper'                 => "<div class=\"pager cb\"><ul>\n\t{pagination}\n</ul></div>\n",

        'first'                   => "<span class=\"first\">\n\t{link}\n</span>\n",
        'first-marker'            => "&laquo;&laquo;",
        'first-link'              => "\t\t<a href=\"{$path}\"><span>{page}</span></a>\n",

        'first-inactive'          => "",
        'first-inactive-link'     => "",

        'previous'                => "<li class=\"prev\">\n\t{link}\n</li>\n",
        'previous-marker'         => "≪前のページへ",
        'previous-link'           => "\t\t<a href=\"{$path}?page={$prePage}\" rel=\"prev\">≪前のページへ</a>\n",

        'previous-inactive'       => "<li class=\"previous-inactive\">\n\t{link}\n</li>\n",
        'previous-inactive-link'  => "\t\t<a href=\"{uri}\" rel=\"prev\"><span>{page}</span></a>\n",

        'regular'                 => "<li>\n\t{link}\n</li>\n",
        'regular-link'            => "\t\t<a href=\"{$path}?page={page}\"><span>{page}</span></a>\n",

        'active'                  => "<li class=\"active\">\n\t{link}\n</li>\n",
        'active-link'             => "\t\t<a href=\"#\"><span>{page}</span></a>\n",

        'next'                    => "<li class=\"next\">\n\t{link}\n</li>\n",
        'next-marker'            => "次のページへ≫",
        'next-link'               => "\t\t<a href=\"{$path}?page={$nextPage}\" rel=\"next\">次のページへ≫</a>\n",

        'next-inactive'           => "<li class=\"next-inactive\">\n\t{link}\n</li>\n",
        'next-inactive-link'      => "\t\t<a href=\"{uri}\" rel=\"next\"><span>{page}</span></a>\n",

        'last-inactive'           => "",
        'last-inactive-link'      => "",
    ),
);
