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

class Score
{
        function __construct($scoringData)
        {
            if (true === isset($scoringData['score'])) {
                $this->_score = $scoringData['score'];
            } else {
                $this->_score = 0;
            }
            if (true === isset($scoringData['block'])) {
                $this->_block = $scoringData['block'];
            } else {
                $this->_block = true;
            }

            if (true === isset($scoringData['details'])) {
                $this->_details = $scoringData['details'];
            } else {
                $this->_details = [];
            }
        }

        function score() {
            return $this->_score;
        }

        function block() {
            return $this->_block;
        }

        function details() {
            return $this->_details;
        }
}
