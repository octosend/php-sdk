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

class Message
{
        function __construct($ctx, $token, $scope)
        {
                $this->_ctx = $ctx;
                $this->_token = $token;
                $this->_scope = $scope;

                $this->_sender    = null;
                $this->_recipient = null;
                $this->_subject   = null;
                $this->_headers   = null;
                $this->_variables = null;

                $this->_parts = null;
        }

        function _reload($data)
        {
                if (isset($data['sender']))
                        $this->sender($data['sender']);
                if (isset($data['recipient']))
                        $this->recipient($data['recipient']);
                if (isset($data['subject']))
                        $this->subject($data['subject']);
                if (isset($data['headers']))
                        $this->headers($data['headers']);
                if (isset($data['variables']))
                        $this->variables($data['variables']);
                $parts = isset($data['parts']) ? $data['parts'] : null;
                $attachments = isset($data['attachments']) ?
                    $data['attachments'] : null;
                $unsubscribe = isset($data['unsubscribe']) ?
                    $data['unsubscribe'] : null;
                if ($parts || $attachments || $unsubscribe) {
                        $this->_parts = new MessageParts($this->_ctx,
                            $this->_token, $this->_scope);
                        $this->_parts->_reload($parts, $attachments, $unsubscribe);
                }
        }

        function _serialize()
        {
                $ret = [];

                if ($this->_sender)
                        $ret["sender"] = $this->_sender;
                if ($this->_recipient)
                        $ret["recipient"] = $this->_recipient;
                if ($this->_subject)
                        $ret["subject"] = $this->_subject;
                if ($this->_headers)
                        $ret["headers"] = $this->_headers;
                if ($this->_variables)
                        $ret["variables"] = $this->_variables;

                if ($this->_parts) {
                        $mp = $this->_parts->_serialize();
                        if (isset($mp['parts']))
                                $ret['parts'] = $mp['parts'];
                        if (isset($mp['attachments']))
                                $ret['attachments'] = $mp['attachments'];
                        if (isset($mp['unsubscribe']))
                                $ret['unsubscribe'] = $mp['unsubscribe'];
                }
                return $ret;
        }

        function reset()
        {
                $this->_sender    = null;
                $this->_recipient = null;
                $this->_subject   = null;
                $this->_headers   = null;
                $this->_variables = null;
        }
        
        function sender($sender = null)
        {
                if ($sender === null)
                        return $this->_sender;
                $this->_sender = $sender;
        }

        function recipient($recipient = null)
        {
                if ($recipient === null)
                        return $this->_recipient;
                $this->_recipient = $recipient;
        }
        
        function subject($subject = null)
        {
                if ($subject === null)
                        return $this->_subject;
                $this->_subject = $subject;
        }

        function headers($headers = null)
        {
                if ($headers === null)
                        return $this->_headers;
                $this->_headers = $headers;
        }

        function variables($variables = null)
        {
                if ($variables === null)
                        return $this->_variables;
                $this->_variables = $variables;
        }

        function parts()
        {
                if ($this->_parts == null)
                        $this->_parts = new MessageParts($this->_ctx,
                            $this->_token, $this->_scope);
                return $this->_parts;
        }
        
        function save()
        {
                if ($this->_scope != "spooler")
                        return;

                $parts = null;
                $attachments = null;
                $unsubscribe = null;
                if ($this->_parts) {
                        $mp = $this->_parts->_serialize();
                        $parts = isset($mp['parts']) ? $mp['parts'] : null;
                        $attachments = isset($mp['attachments']) ? $mp['attachments'] : null;
                        $unsubscribe = isset($mp['unsubscribe']) ? $mp['unsubscribe'] : null;
                }
                $this->_ctx->post_spooler_message($this->_token,
                    $this->_sender,
                    $this->_recipient,
                    $this->_subject,
                    $this->_headers,
                    $this->_variables,
                    $parts,
                    $attachments,
                    $unsubscribe);
        }
}

?>
