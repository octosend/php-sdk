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

class Reporting
{
    const TYPE_ACTIVITY = 'activity';
    const TYPE_SPOOLING = 'spooling';
    const TYPE_ROUTING = 'routing';
    const TYPE_CLEANUP = 'cleanup';

    protected $url;
    protected $handle = false;

    public function __construct($url)
    {
            $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Download file to path
     *
     * @param string $path the path to save file to
     *
     * @return void
     */
    public function download($path)
    {
        $path = realpath($path);

        if (!is_writable($path)) {

            throw new \RuntimeException("Unable to write reporting file to '".$path."'");
        }

        if (is_dir($path)) {

            $path .= DIRECTORY_SEPARATOR.pathinfo($this->url, \PATHINFO_BASENAME);
        }

        $handle = fopen($this->url, 'r');

        if (false === $handle) {

            throw new \RuntimeException("Unable to open reporting file '".$this->url."'");
        }

        if(false === file_put_contents($path, $handle)) {

            throw new \RuntimeException("Unable to write reporting file to '".$path."'");
        }
    }

    /**
     * Return each notification of the reporting file
     *
     * @return void
     */
    public function each()
    {
        $this->handle = fopen("compress.zlib://".$this->url, 'r');

        if (false === $this->handle) {

            throw new \RuntimeException("Unable to open reporting file '".$this->url."'");
        }

        foreach ($this->getLines() as $line) {

            yield json_decode($line, true);
        }
    }

    protected function readChunks()
    {
        while (!feof($this->handle)) {

            $chunk = fread($this->handle, 8192);

            if (strlen($chunk) > 0) {

                yield $chunk;
            }
        }

        fclose($this->handle);
    }

    protected function getLines()
    {
        $lastLine = false;

        foreach ($this->readChunks() as $chunk) {

            // Split by lines
            // Handle last element as it can be unterminated
            $lines = explode("\n", $lastLine.$chunk);
            $lastLine = array_pop($lines);

            foreach ($lines as $line) {

                yield $line;
            }
        }

        if (!empty($lastLine)) {

            yield $lastLine;
        }
    }
}
