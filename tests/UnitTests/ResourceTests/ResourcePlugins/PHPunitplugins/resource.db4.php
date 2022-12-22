<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.db3.php
 * Type:     resource
 * Name:     db
 * Purpose:  Fetches templates from a database
 * -------------------------------------------------------------
 */

use Smarty\Template;
use Smarty\Template\Source;

class Smarty_Resource_Db4 extends Smarty\Resource\BasePlugin
{
    public function populate(Source $source, Template $_template = null)
    {
        $source->filepath = 'db4:';
        $source->uid = sha1($source->resource);
        $source->timestamp = 0;
        $source->exists = true;
    }

    public function getContent(Source $source)
    {
        if ($source->is_config) {
            return "foo = 'bar'\n";
        }

        return '{$x="hello world"}{$x}';
    }
}
