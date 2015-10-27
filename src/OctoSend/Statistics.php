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

class Statistics
{
        function __construct($type, $md)
        {
                $this->_type     = $type;
                $this->_metadata = $md;
                if ($this->_type == "global")
                        $this->_result = new StatisticsResult($this->_metadata);
        }

        function filter($key1 = null, $key2 = null)
        {
                if ($this->_type == "global")
                        return new StatisticsResult($this->_metadata);
                if (! strpos($this->_type, '+'))
                        return new StatisticsResult($this->_metadata[$key1]);
                return new StatisticsResult($this->_metadata[$key1][$key2]);
        }

        function spooling($key)
        { return $this->_result->spooling($key); }

        function routing($key)
        { return $this->_result->routing($key); }

        function activity($key)
        { return $this->_result->activity($key); }
}

?>
