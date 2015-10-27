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

class MailsFilter
{
        function __construct($ctx, $spooler_token)
        {
                $this->_ctx = $ctx;
		$this->_spooler_token = $spooler_token;
                $this->_params = null;
        }

	private function filter_param_array($key, $value)
        {
                if ($this->_params == null)
                        $this->_params = [];
		if (!array_key_exists($key, $this->_params))
			$this->_params[$key] = [];
		$this->_params[$key][] = $value;
		return $this;
        }
        private function filter_param($key, $value)
        {
                if ($this->_params == null)
                        $this->_params = [];
                $this->_params[$key] = $value;
		return $this;
        }

	function destination($match)
        { return $this->filter_param_array('destinations', $match); }

	function router($router)
        { return $this->filter_param_array('routers', $router); }

	function routingStatus($status)
        { return $this->filter_param_array('routingStatus', $status); }
	
	function recipientContains($match)
        { return $this->filter_param('recipientContains', $match); }

	function spooledAfter($timestamp)
        { return $this->filter_param('spooledAfter', $timestamp); }
	
	function spooledBefore($timestamp)
        { return $this->filter_param('spooledBefore', $timestamp); }

	function opened($bool = true)
        { return $this->filter_param('activityOpened', $bool); }

	function clicked($bool = true)
        { return $this->filter_param('activityClicked', $bool); }

	function unsubscribe($bool = true)
        { return $this->filter_param('activityUnsubscribe', $bool); }

	function abuse($bool = true)
        { return $this->filter_param('activityAbuse', $bool); }

        function count()
        { return $this->_ctx->spooler_mails_count($this->_spooler_token, $this->_params); }

        function fetch($offset = 0, $limit = 1000, $reverse = false)
        {
                $l = $this->_ctx->spooler_mails_fetch($this->_spooler_token, $this->_params, $offset, $limit, $reverse);
                $res = [];
                foreach ($l as $md)
		    array_push($res, $this->_ctx->_get_mail_from_md($md));
                return $res;
        }
}

?>
