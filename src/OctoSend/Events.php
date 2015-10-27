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

class Events
{
        function __construct($ctx, $kind, $ident, $type)
        {
                $this->_ctx   = $ctx;
                $this->_kind  = $kind;
                $this->_ident = $ident;
                $this->_type  = $type;
        }

        function count()
        { return $this->_ctx->events_count($this->_kind, $this->_ident, $this->_type); }

        function fetch($offset, $count)
        {
                $l = $this->_ctx->events_fetch($this->_kind, $this->_ident, $this->_type, $offset, $count);
                
                $res = [];
                foreach ($l as $n)
                        array_push($res, new Event($n));
                return $res;
        }
}

?>
