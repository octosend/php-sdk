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

define('OCTOSEND_API_URL',	'https://api.octosend.com');
define('OCTOSEND_API_VERSION',	'3.0');

class API
{
        function __construct($url = null, $version = null)
        {
                $url = $url == null ? OCTOSEND_API_URL : $url;
                $version = $version == null ? OCTOSEND_API_VERSION : $version;
                $this->_url = $url . '/' . $version;
                $this->_key = null;
        }
        
        function _get_domain_from_name($name)
        {
                $md = $this->domain_get($name);
                return $this->_get_domain_from_md($md);
        }

        function _get_domain_from_md($md)
        {
                return new Domain($this, $md);
        }
        
        function _get_spooler_from_token($token)
        {
                $md = $this->spoolers_get($token);
                return $this->_get_spooler_from_md($md);
        }
        
        function _get_spooler_from_md($md)
        {
                return new Spooler($this, $md);
        }
        
        function _get_mail_from_md($md)
        {
                return new Mail($this, $md);
        }
        
        function _get_template_from_md($domainname, $md)
        {
                return new Template($this, $domainname, $md);
        }
        
        function rest_call($remote_method, $params = null, $verb = 'POST')
        {
                $cparams = [
                        'http' => [
                                'method' => $verb,
                                    'ignore_errors' => true,
                                    'header' => [
                                            'Content-type: application/json'
                                    ]
                        ]
                ];
                if ($this->_key)
                        $cparams['http']['header'][] = 'X-RMTA-API-Key: '.$this->_key;
                
                $url = $this->_url . '/' . $remote_method;
                
                if ($params !== null) {
                        if ($verb == 'POST') {
                                $params = json_encode($params);
                                if (json_last_error() !== JSON_ERROR_NONE)
                                        throw new Exception("cannot decode JSON parameters");
                                $cparams['http']['content'] = $params;
                        }
                        else {
                                /* XXX do we need this?, is it safe? */
                                $params = http_build_query($params);
                                $url .= '?'.$params;
                        }
                }
                
                $context = stream_context_create($cparams);
                $fp = fopen($url, 'rb', false, $context);
                if (!$fp)
                        throw new Exception("fopen() failed");
                
                $ret = stream_get_contents($fp);
                fclose($fp);
                if ($ret === false)
                        throw new Exception("stream_get_contents() failed");
                
                $http_version = null;
                $http_status_code = null;
                $http_status = null;
                $http_headers = array();
                foreach($http_response_header as $k => $v) {
                        if ($k == 0) {
                                $t = explode(' ', $v, 3);
                                $http_version = $t[0];
                                $http_status_code = $t[1];
                                $http_status = $t[2];
                        }
                        else {
                                $t = explode( ':', $v, 2);
                                $http_headers[trim($t[0])] = trim($t[1]);
                        }
                }
                
                if ($http_headers["Content-Type"] == "application/json") {
                        $content = json_decode($ret, true);
                        if (json_last_error() !== JSON_ERROR_NONE)
                                throw new Exception("cannot decode JSON response");
                } else {
                        $content = $ret;
                }
                
                if ($http_status_code != "200")
                        throw new APIError($http_version, $http_status_code, $http_status, $http_headers, $content);
                
                return $content;
        }
        
        function authenticate($username, $password)
        {
                $json = $this->rest_call('authenticate', [
                        'username' => $username,
                        'password' => $password
                ]);
                if (!isset($json['api-key']))
                        throw new RemoteCallError("authentication failed");
                $this->_key  = $json["api-key"];
        }
        
        /* Domains */
        function domains_count($args)
        {
                $params = null;
                if ($args != null) {
                        $params = [];
                        foreach ($args as $k => $v) {
                                $params[$k] = $v;
                        }
                }
                return $this->rest_call('domains/count', $params, "POST");
        }
        
        function domains_fetch($args, $offset, $limit, $reverse)
        {
                $params = [];
                $params['offset']  = $offset;
                $params['limit']   = $limit;
                $params['reverse'] = $reverse;
                if ($args != null)
                        foreach ($args as $k => $v) {
                                $params[$k] = $v;
                        }
                return $this->rest_call('domains/fetch', $params, "POST");
        }
        
        function domains_create($name)
        {
                $params = ['domain' => $name ];
                return $this->rest_call('domains/create', $params, "POST");
        }
        
        function domains_delete($name)
        {
                $params = ['domain' => $name ];
                return $this->rest_call('domains/delete', $params, "POST");
        }
        
        function domain_get($name)
        {
                return $this->rest_call('domain/'.$name, null, "GET");
        }
        
        /* Spoolers */
        function spoolers_count($args)
        {
                $params = null;
                if ($args != null) {
                        $params = [];
                        foreach ($args as $k => $v) {
                                $params[$k] = $v;
                        }
                }
                return $this->rest_call('spoolers/count', $params, "POST");
        }
        
        function spoolers_fetch($args, $offset, $limit, $reverse)
        {
                $params = [];
                $params['offset']  = $offset;
                $params['limit']   = $limit;
                $params['reverse'] = $reverse;
                if ($args != null)
                        foreach ($args as $k => $v) {
                                $params[$k] = $v;
                        }
                return $this->rest_call('spoolers/fetch', $params);
        }
        
        function spoolers_create($domain, $type)
        {
                $params = ['domain' => $domain, 'type' => $type ];
                return $this->rest_call('spoolers/create', $params);
        }
        
        function spoolers_get($token)
        {
                return $this->rest_call('spooler/'.$token, null, "GET");
        }

        /* Spooler */
        function spooler_ready($token)
        {
                return $this->rest_call('spooler/'.$token.'/ready');
        }

        function spooler_cancel($token)
        {
                return $this->rest_call('spooler/'.$token.'/cancel');
        }

        function spooler_finish($token)
        {
                return $this->rest_call('spooler/'.$token.'/finish');
        }

        function spooler_pause($token)
        {
                return $this->rest_call('spooler/'.$token.'/pause');
        }

        function spooler_resume($token)
        {
                return $this->rest_call('spooler/'.$token.'/resume');
        }

        function spooler_name($token, $name)
        {
                $params = [ 'name' => $name ];
                return $this->rest_call('spooler/'.$token.'/name', $params);
        }

        function spooler_start($token, $start)
        {
                $params = [ 'start' => $start ];
                return $this->rest_call('spooler/'.$token.'/start', $params);
        }

        function spooler_tags($token, $tags)
        {
                $params = [ 'tags' => $tags ];
                return $this->rest_call('spooler/'.$token.'/tags', $params);
        }

        function spooler_headers($token, $headers)
        {
                $method = "GET";
                $params = null;
                if ($headers) {
                        $params = [ "headers" => $headers ];
                        $method = "POST";
                }
                return $this->rest_call('spooler/'.$token.'/headers', $params, $method);
        }

        function spooler_variables($token, $variables)
        {
                $method = "GET";
                $params = null;
                if ($variables) {
                        $params = [ "variables" => $variables ];
                        $method = "POST";
                }
                return $this->rest_call('spooler/'.$token.'/variables', $params, $method);
        }

        function spooler_spool($token, $mails)
        {
                $params = [];
                $params["recipients"] = [];
                foreach ($mails as $mail) {
                        $entry = [];
                        $entry["recipient"] = $mail->_recipient;
                        array_push($params["recipients"], $entry);
                }
                return $this->rest_call('spooler/'.$token.'/spool', $params);
        }

        /* Spooler Message */
        function get_spooler_message($spooler_token)
        {
                return $this->rest_call('spooler/' . $spooler_token . '/message', null, "GET");
        }

        function post_spooler_message($spooler_token, $sender, $recipient, $subject, $headers, $variables, $parts, $attachments, $unsubscribe)
        {
                $params = [];
                if ($sender)
                        $params["sender"] = $sender;
                if ($recipient)
                        $params["recipient"] = $recipient;
                if ($subject)
                        $params["subject"] = $subject;
                if ($headers)
                        $params["headers"] = $headers;
                if ($variables)
                        $params["variables"] = $variables;
                if ($parts)
                        $params["parts"] = $parts;
                if ($attachments)
                        $params["attachments"] = $attachments;
                if ($unsubscribe)
                        $params["unsubscribe"] = $unsubscribe;
                return $this->rest_call('spooler/' . $spooler_token . '/message', $params, "POST");
        }



        /* resources */
        function spooler_resources_part($spooler_token, $scope, $type, $content)
        {
                $params = [];
                $params["type"] = $type;
                $params["content"] = $content;

                $url = "/404";
                if ($scope == "spooler")
                        $url = "spooler/".$spooler_token."/resources/part";
                if ($scope == "mail")
                        $url = "spooler/".$spooler_token."/mails/resources/part";
                return $this->rest_call($url, $params, "POST");
        }

        function spooler_resources_attachment($spooler_token, $scope, $type, $content)
        {
                $params = [];
                $params["type"] = $type;
                $params["content"] = base64_encode($content);

                $url = "/404";
                if ($scope == "spooler")
                        $url = "spooler/".$spooler_token."/resources/attachment";
                if ($scope == "mail")
                        $url = "spooler/".$spooler_token."/mails/resources/attachment";
                return $this->rest_call($url, $params, "POST");
        }

        function spooler_resources_unsubscribe($spooler_token, $scope, $type, $content)
        {
                $params = [];
                $params["type"] = $type;
                $params["content"] = $content;

                $url = "/404";
                if ($scope == "spooler")
                        $url = "spooler/".$spooler_token."/resources/unsubscribe";
                if ($scope == "mail")
                        $url = "spooler/".$spooler_token."/mails/resources/unsubscribe";
                return $this->rest_call($url, $params, "POST");
        }

        
        /* Spooler Message Parts */
        function spooler_message_parts_part($spooler_token, $type, $content)
        {
                $params = [];
                $params["type"] = $type;
                $params["content"] = $content;
                return $this->rest_call('spooler/' . $spooler_token . '/resources/part', $params, "POST");
        }

        function spooler_message_parts_attachment($spooler_token, $type, $content)
        {
                $params = [];
                $params["type"] = $type;
                $params["content"] = base64_encode($content);
                return $this->rest_call('spooler/' . $spooler_token . '/resources/attachment', $params, "POST");
        }

        function spooler_mails_resources_part($spooler_token, $type, $content)
        {
                $params = [];
                $params["type"] = $type;
                $params["content"] = $content;
                return $this->rest_call('spooler/' . $spooler_token . '/mails/resources/part', $params, "POST");
        }

        function spooler_mails_resources_attachment($spooler_token, $type, $content)
        {
                $params = [];
                $params["type"] = $type;
                $params["content"] = base64_encode($content);
                return $this->rest_call('spooler/' . $spooler_token . '/mails/resources/attachment', $params, "POST");
        }

        function spooler_message_parts($spooler_token, $parts, $attachments)
        {
                $params = [];
                $params["parts"] = $parts;
                $params["attachments"] = $attachments;
                return $this->rest_call('spooler/' . $spooler_token . '/message/parts', $params, "POST");
        }

        /* Spool */
        function spooler_spool_preview($spooler_token, $email)
        {
                #$params = [];
                #$params['mails'] = [];
                #foreach ($emails as $k) {
                #array_push($params['mails'], $k->_serialize());
                #}
                return $this->rest_call('spooler/' . $spooler_token . '/preview', null, "POST");
        }
        
        function spooler_spool_draft($spooler_token, $emails)
        {
                $params = [];
                $params['mails'] = [];
                foreach ($emails as $k) {
                        array_push($params['mails'], $k->_serialize());
                }
                return $this->rest_call('spooler/' . $spooler_token . '/draft', $params, "POST");
        }

        function spooler_spool_batch($spooler_token, $emails)
        {
                $params = [];
                $params['mails'] = [];
                foreach ($emails as $k) {
                        array_push($params['mails'], $k->_serialize());
                }
                return $this->rest_call('spooler/' . $spooler_token . '/spool', $params, "POST");
        }


        /* Mails */
        function spooler_mails_count($spooler_token, $args)
        {
                if ($args != null) {
                        $params = [];
                        foreach ($args as $k => $v) {
                                $params[$k] = $v;
                        }
                        return $this->rest_call('spooler/' . $spooler_token . '/mails/count', $params, "POST");
                }
                else {
                        return $this->rest_call('spooler/' . $spooler_token . '/mails/count', null, "POST");
                }
        }

        function spooler_mails_fetch($spooler_token, $args, $offset, $limit, $reverse)
        {
                $params = [];
                $params['offset']  = $offset;
                $params['limit']   = $limit;
                $params['reverse'] = $reverse;

                if ($args != null)
                        foreach ($args as $k => $v)
                                $params[$k] = $v;

                return $this->rest_call('spooler/' . $spooler_token . '/mails/fetch', $params);
        }


        /* Templates */
        function templates_domain_count($domainname, $args)
        {
                $params = null;
                if ($args != null) {
                        $params = [];
                        foreach ($args as $k => $v)
                                $params[$k] = $v;
                }
                return $this->rest_call('templates/domain/'. $domainname . '/count', $params);
        }

        function templates_domain_fetch($domainname, $args, $offset, $limit, $reverse)
        {
                $params = [];
                $params['offset']  = $offset;
                $params['limit']   = $limit;
                $params['reverse'] = $reverse;

                if ($args != null)
                        foreach ($args as $k => $v)
                                $params[$k] = $v;
		
                return $this->rest_call('templates/domain/' . $domainname . '/fetch', $params); 
        }

        function templates_domain_get($domainname, $name)
        {
                $params = [];
                $params['name'] = $name;

                return $this->rest_call('templates/domain/' . $domainname . '/get', $params); 
        }

        function templates_domain_get_content($domainname, $name)
        {
                $params = [];
                $params['name'] = $name;

                return $this->rest_call('templates/domain/' . $domainname . '/get-content', $params); 
        }

        function templates_domain_create($domainname, $name, $type, $content)
        {
                $params = [];
                $params['name'] = $name;
                $params['type'] = $type;
                $params['content'] = $content;

                return $this->rest_call('templates/domain/' . $domainname . '/create', $params); 
        }

        function templates_domain_set_content($domainname, $name, $content)
        {
                $params = [];
                $params['name'] = $name;
                $params['content'] = $content;
		
                return $this->rest_call('templates/domain/' . $domainname . '/set-content', $params); 
        }

        function templates_domain_remove($domainname, $name)
        {
                $params = [];
                $params['name'] = $name;

                return $this->rest_call('templates/domain/' . $domainname . '/remove', $params); 
        }


	
	
        /* Events */
        function events_count($kind, $identifier, $event)
        {
                $params = [];
                $params["event"] = $event;
                return $this->rest_call('events/'.$kind.'/'.$identifier.'/count', $params);
        }

        function events_fetch($kind, $identifier, $event, $offset, $count)
        {
                $params = [];
                $params["event"]  = $event;
                $params["offset"] = $offset;
                $params["limit"]  = $count;
                return $this->rest_call('events/'.$kind.'/'.$identifier.'/fetch', $params);
        }


        /* Statistics */
        function statistics_global($period, $format = null, $timestamp = null)
        {
                $params = [];
                $params["period"] = $period;
                if ($format)
                        $params["groupBy"] = $format;
                if ($timestamp)
                        $params["timestamp"] = $timestamp;
                return $this->rest_call('statistics/global', $params, "POST");
        }

        function statistics_domain($domain, $period, $format = null, $timestamp = null)
        {
                $params = [];
                $params["period"] = $period;
                if ($format)
                        $params["groupBy"] = $format;
                if ($timestamp)
                        $params["timestamp"] = $timestamp;
                return $this->rest_call('statistics/domain/'.$domain, $params, "POST");
        }

        function statistics_spooler($token, $format = null)
        {
                $params = [];
                if ($format)
                        $params["groupBy"] = $format;
                return $this->rest_call('statistics/spooler/'.$token, $params, "POST");
        }
}

?>
