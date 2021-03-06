<?php
/*
 * Copyright 2012 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/


/**
 * This helper is based on code from Facebook's Phabricator project
 *
 *   https://github.com/facebook/phabricator
 *
 * Specifically, it is an adapation of the PhutilReadableSerializer class.
 *
 * @package raven
 */
class Raven_Serializer
{
    /**
     * Serialize an object (recursively) into something safe for data
     * sanitization and encoding.
     */
    public static function serialize($value)
    {
        if (is_object($value)) {
            return 'Object '.get_class($value);
        } elseif (is_resource($value)) {
            return 'Resource '.get_resource_type($value);
        } else if (is_array($value)) {
            $new = array();
            foreach ($value as $k=>$v) {
                $new[$k] = self::serialize($v);
            }
            return $new;
        } else {
            return self::serializeValue($value);
        }
    }

    public static function serializeValue($value)
    {
        if ($value === null) {
            return 'null';
        } else if ($value === false) {
            return 'false';
        } else if ($value === true) {
            return 'true';
        } else if (is_float($value) && (int)$value == $value) {
            return $value.'.0';
        } else {
            return print_r($value, true);
        }
    }
}