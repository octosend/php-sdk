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

class TemplatesManager
{
        function __construct($ctx, $domain)
        {
                $this->_ctx    = $ctx;
                $this->_domain = $domain;
        }

        function filter()
        {
                return new TemplatesFilter($this->_ctx, $this->_domain);
        }

        function template($name)
        {
                $md = $this->_ctx->templates_domain_get($this->_domain, $name);
                return $this->_ctx->_get_template_from_md($this->_domain, $md);
        }

        function create($name, $type, $content)
        {
                $md = $this->_ctx->templates_domain_create($this->_domain, $name, $type, $content);
                return $this->_ctx->_get_template_from_md($this->_domain, $md);
        }
}

?>
