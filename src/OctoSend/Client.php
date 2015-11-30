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

class Client
{
        function __construct($username, $password, $url = null)
        {
                $this->_api = new API($url, null);
                $this->_api->authenticate($username, $password);
        }

        function domains()
        { return new DomainsFilter($this->_api); }

        function spoolers()
        { return new SpoolersFilter($this->_api); }

        function domain($name)
        { return $this->_api->_get_domain_from_name($name); }

        function spooler($token)
        { return $this->_api->_get_spooler_from_token($token); }

        function statistics($period, $format, $timestamp = null)
        { return $this->_api->statistics_global($period, $format, $timestamp); }

        function tools()
        { return new Tools($this->_api); }
}

?>
