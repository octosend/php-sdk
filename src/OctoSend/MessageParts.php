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

class MessageParts
{
        function __construct($ctx, $token, $scope)
        {
                $this->_ctx = $ctx;
                $this->_token = $token;
                $this->_scope = $scope;

                $this->_parts = null;
                $this->_attachments = null;
                $this->_unsubscribe = null;
        }

        function _reload($parts, $attachments, $unsubscribe)
        {
                $this->_parts = $parts;
                $this->_attachments = $attachments;
                $this->_unsubscribe = $unsubscribe;
        }

        function _serialize()
        {
                $ret = [];
                if ($this->_parts)
                        $ret["parts"] = $this->_parts;
                if ($this->_attachments)
                        $ret["attachments"] = $this->_attachments;
                if ($this->_unsubscribe)
                        $ret["unsubscribe"] = $this->_unsubscribe;
                return $ret;
        }

        function reset()
        {
                $this->_parts = null;
                $this->_attachments = null;
                $this->_unsubscribe = null;
        }

        function part($type, $content)
        {
                if ($this->_parts == null)
                        $this->_parts = [];
                $id = $this->_ctx->spooler_resources_part($this->_token, $this->_scope, $type, $content);
                array_push($this->_parts, $id);
        }

        function attachment($type, $content, $filename)
        {
                if ($this->_attachments == null)
                        $this->_attachments = [];
                $id = $this->_ctx->spooler_resources_attachment(
                    $this->_token,
                    $this->_scope,
                    $type,
                    $content,
                    $filename
                );
                array_push($this->_attachments, $id);
        }

        function unsubscribe($type, $content)
        {
                $id = $this->_ctx->spooler_resources_unsubscribe($this->_token,
                    $this->_scope, $type, $content);
                $this->_unsubscribe = $id;
        }
}

?>
