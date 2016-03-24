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

class Spooler
{
        function __construct($ctx, $metadata)
        {
                $this->_ctx      = $ctx;
                $this->_token    = $metadata["token"];
                $this->_metadata = $metadata;
                $this->_message  = null;
        }

        function type()
        {
                return $this->_metadata['type'];
        }

        function token()
        {
                return $this->_token;
        }

        function state()
        {
                return $this->_metadata['state'];
        }

        function domain()
        {
                return $this->_ctx->_get_domain_from_name($this->_metadata['domain']);
        }

        function message()
        {
                if ($this->_message)
                        return $this->_message;
                $this->_message = new Message($this->_ctx, $this->_token, "spooler");
                $this->_message->_reload($this->_ctx->get_spooler_message($this->_token));
                return $this->_message;
        }

        function name($name = null)
        {
                if ($name === null)
                        return $this->_metadata['name'];
                $this->_metadata = $this->_ctx->spooler_name($this->_token, $name);
                return $this->_metadata['name'];
        }

        function start($start = null)
        {
                if ($start === null)
                        return $this->_metadata['start'];
                $this->_metadata = $this->_ctx->spooler_start($this->_token, $start);
                return $this->_metadata['start'];
        }

        function tags($tags = null)
        {
                if ($tags === null)
                        return $this->_metadata['tags'];
                $this->_metadata = $this->_ctx->spooler_tags($this->_token, $tags);
                return $this->_metadata['tags'];
        }

        function ready()
        {
                $this->_ctx->spooler_ready($this->_token);
        }

        function finish()
        {
                $this->_ctx->spooler_finish($this->_token);
        }

        function cancel()
        {
                $this->_ctx->spooler_cancel($this->_token);
        }

        function queue()
        {
                return new Queue($this->_ctx, $this->_token);
        }

        function mails()
        {
                return new MailsFilter($this->_ctx, $this->_token);
        }

        function events($type)
        {
                return new Events($this->_ctx, "spooler", $this->_token, $type);
        }

        function statistics($format = null)
        {
                return new Statistics($format, $this->_ctx->statistics_spooler($this->_token, $format));
        }

        function score()
        {
                return new Score($this->_ctx->score_spooler($this->_token));
        }

        function batch()
        {
                return new SpoolerBatch($this->_ctx, $this->_token);
        }

        function mail($email)
        {
                return new Mail($this->_ctx, $this->_token, $email);
        }
}

?>
