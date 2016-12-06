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

        public function sendTransactionnalEmail(
            $fromEmail,
            $fromName,
            $toEmail,
            $toName = null,
            $subject,
            $html = null,
            $text = null,
            array $tags = [],
            $draft = false
        ) {
            if (is_null($html) && is_null($text)) {

                throw new \Exception("No HTML nor text content specified");
            }

            return $this->_ctx->domain_send_transactionnal($this->_metadata['name'], $fromEmail, $fromName,
                $toEmail, $toName, $subject, $html, $text, $tags);
        }


        /**
         * Get reporting for specified $flux and $date
         *
         * @param string $flux the flux name
         * @param \DateTime $date the date
         */
        public function reporting(
            $flux = Reporting::TYPE_ACTIVITY,
            \DateTime $date
        ) {

            if (!in_array(
                $flux,
                [
                    Reporting::TYPE_ACTIVITY,
                    Reporting::TYPE_SPOOLING,
                    Reporting::TYPE_ROUTING,
                    Reporting::TYPE_CLEANUP
                ])
            ) {

                throw new \InvalidArgumentException("Invalid flux '$flux'");
            }

            $filename = $this->_ctx->domain_reporting_get_filename($this->_metadata['name'], $flux, $date->format('Y-m-d'));

            return new Reporting($filename);
        }
}

?>
