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

class Mail
{
        function __construct($ctx, $token, $email)
        {
                $this->_ctx   = $ctx;
                $this->_token = $token;

                $this->_email   = $email;
                $this->_tags    = null;
                $this->_message = null;
        }

        function _serialize()
        {
                $ret = [];
                $ret["email"] = $this->_email;
                if ($this->_tags)
                        $ret["tags"] = $this->_tags;
                if ($this->_message) {
                        $m = $this->_message->_serialize();
                        if (isset($m['sender']))
                                $ret['sender'] = $m['sender'];
                        if (isset($m['recipient']))
                                $ret['recipient'] = $m['recipient'];
                        if (isset($m['subject']))
                                $ret['subject'] = $m['subject'];
                        if (isset($m['headers']))
                                $ret['headers'] = $m['headers'];
                        if (isset($m['variables']))
                                $ret['variables'] = $m['variables'];
                        if (isset($m['parts']))
                                $ret['parts'] = $m['parts'];
                        if (isset($m['attachments']))
                                $ret['attachments'] = $m['attachments'];
                }
                return $ret;
        }

        function tags($tags = null)
        {
                if ($tags === null)
                        return $this->_tags;
                $this->_tags = $tags;
        }

        function message()
        {
                if ($this->_message == null)
                        $this->_message = new Message($this->_ctx, $this->_token, "mail");
                return $this->_message;
        }

        function preview()
        {
                return $this->_ctx->spooler_spool_preview($this->_token, $this);
        }
        
        function draft()
        {
                return $this->_ctx->spooler_spool_draft($this->_token, [$this]);
        }
        
        function spool()
        {
                return $this->_ctx->spooler_spool_batch($this->_token, [$this]);
        }
}

?>
