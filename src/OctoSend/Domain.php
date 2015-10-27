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

class Domain
{
        function __construct($ctx, $md)
        {
                $this->_ctx  = $ctx;
                $this->_metadata = $md;
        }

        function name()
        {
                return $this->_metadata['name'];
        }

        function spoolers()
        {
                $filter = new SpoolersFilter($this->_ctx);
                $filter->domain($this->_metadata['name']);
                return $filter;
        }

        function spooler($token)
        {
                return $this->_ctx->_get_spooler_from_token($token);
        }

        function createSpooler($type)
        {
                $md = $this->_ctx->spoolers_create($this->_metadata['name'], $type);
                return $this->_ctx->_get_spooler_from_md($md);
        }

        function templates()
        {
                return new TemplatesManager($this->_ctx, $this->_metadata['name']);
        }

        function events($type)
        {
                return new Events($this->_ctx, "domain", $this->_metadata['name'], $type);
        }

        function statistics($period, $format, $timestamp = null)
        {
                return new Statistics($format, $this->_ctx->statistics_domain($this->_metadata['name'],
                        $period, $format, $timestamp));
        }
}

?>
