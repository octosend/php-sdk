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

class Spoolers
{
        function __construct($ctx, $domain = null)
        {
                $this->_ctx = $ctx;
                if ($domain)
                        $this->domain = $domain;
                $this->_cache = [];
        }

        function count()
        { return $this->_ctx->spoolers_count(); }

        function create($type)
        {
                if (! property_exists($this, "domain"))
                        throw new \Exception("spooler must be created in a domain");

                $md = $this->_ctx->spoolers_create($this->domain->name(), $type);
                $token = $md['token'];
                $this->_cache[$token] = new Spooler($this->_ctx, $md);
                return $this->_cache[$token];
        }

        function get($token)
        {
                if (isset($this->_cache[$token]))
                        return $this->_cache[$token];
                $md = $this->_ctx->spoolers_get($token);
                $this->_cache[$token] = new Spooler($this->_ctx, $md);
                return $this->_cache[$token];
        }

        function filter()
        {
                return new SpoolersFilter($this->_ctx);
        }
}

?>
