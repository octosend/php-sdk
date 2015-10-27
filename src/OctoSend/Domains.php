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

class Domains
{
        function __construct($ctx)
        {
                $this->_ctx   = $ctx;
                $this->_cache = [];
        }

        function count()
        { return $this->_ctx->domains_count(null); }

        function create($name)  /* not supported yet */
        {
                $this->_ctx->domains_create($name);
                $this->_cache[$name] = new Domain($this->_ctx, $name);
                return $this->_cache[$name];
        }

        function delete($name)  /* not supported yet */
        {
                $this->_ctx->domains_delete($name);
                unset($this->_cache[$name]);
        }

        function get($name)
        {
                if (isset($this->_cache[$name]))
                        return $this->_cache[$name];
                $this->_ctx->domains_get($name);
                $this->_cache[$name] = new Domain($this->_ctx, $name);
                return $this->_cache[$name];
        }

        function filter()
        {
                return new DomainsFilter($this->_ctx);
        }
}

?>
