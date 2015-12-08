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

class SpoolerBatch
{
    function __construct($ctx, $token)
    {
        $this->_ctx    = $ctx;
        $this->_token  = $token;
        $this->_mails  = [];
    }

    function mail($email)
    {
        $m = new Mail($this->_ctx, $this->_token, $email);
        array_push($this->_mails, $m);
        return $m;
    }

    function spool()
    {
        return $this->_ctx->spooler_spool_batch($this->_token, $this->_mails);
    }

    function draft()
    {
        return $this->_ctx->spooler_spool_draft($this->_token, $this->_mails);
    }
}
