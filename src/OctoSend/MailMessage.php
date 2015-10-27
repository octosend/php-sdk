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

class MailMessage
{
        function __construct($ctx, $token, $md)
        {
                $this->_ctx       = $ctx;
                $this->_token     = $token;
                $this->_metadata  = $md;
                $this->_structure = null;
        }

        function sender($sender = null)
        {
                if ($sender === null)
                        return $this->_metadata['sender'];
                return $this->_metadata['sender'];
        }

        function subject($subject = null)
        {
                if ($subject === null)
                        return $this->_metadata['subject'];
                return $this->_metadata['subject'];
        }

        function headers($headers = null)
        {
                if ($headers === null)
                        return $this->_metadata['headers'];
                return $this->_metadata['headers'];
        }

        function variables($variables = null)
        {
                if ($variables === null)
                        return $this->_metadata['variables'];
                return $this->_metadata['variables'];
        }        
}

?>
