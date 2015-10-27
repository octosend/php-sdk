<?php
/*
 * Copyright (c) 2015, Dalenys
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

namespace OctoSend;

class SpoolersFilter
{
        function __construct($ctx)
        {
                $this->_ctx = $ctx;
                $this->_params = null;
        }

        private function filter_param_array($key, $value)
        {
                if ($this->_params == null)
                        $this->_params = [];
                if (!array_key_exists($key, $this->_params))
                        $this->_params[$key] = [];
                $this->_params[$key][] = $value;
                return $this;
        }

        private function filter_param($key, $value)
        {
                if ($this->_params == null)
                        $this->_params = [];
                $this->_params[$key] = $value;
                return $this;
        }

        function domain($domain)
        { return $this->filter_param_array('domains', $domain); }

        function type($type)
        { return $this->filter_param_array('types', type); }

        function state($state)
        { return $this->filter_param_array('states', $state); }

        function nameContains($match)
        { return $this->filter_param('nameContains', $match); }

        function createdAfter($timestamp)
        { return $this->filter_param('createdAfter', $timestamp); }
	
        function createdBefore($timestamp)
        { return $this->filter_param('createdBefore', $timestamp); }
	
        function startAfter($timestamp)
        { return $this->filter_param('startAfter', $timestamp); }
	
        function startBefore($timestamp)
        { return $this->filter_param('startBefore', $timestamp); }
	
        function endAfter($timestamp)
        { return $this->filter_param('endAfter', $timestamp); }
	
        function endBefore($timestamp)
        { return $this->filter_param('endBefore', $timestamp); }
       
        function count()
        { return $this->_ctx->spoolers_count($this->_params); }

        function fetch($offset = 0, $limit = 100, $reverse = false)
        {
                $l = $this->_ctx->spoolers_fetch($this->_params, $offset, $limit, $reverse);
                $res = [];
                foreach ($l as $md)
                        array_push($res, $this->_ctx->_get_spooler_from_md($md));
                return $res;
        }
}

?>
