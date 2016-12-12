<?php

namespace Tequilarapido\Testing\Concerns;

trait Debug
{
    /**
     * Dump response and status code.
     *
     * @return $this
     */
    public function dumpResponse()
    {
        dd($this->response->content(), $this->response->getStatusCode());

        return $this;
    }

    /**
     * Show response in chrome.
     */
    public function showInChrome()
    {
        // Prepare html response
        $content = $this->response->content();
        if ($showBaseUrl = env('SHOW_BASE_URL')) {
            $content = str_replace(env('APP_URL'), $showBaseUrl, $content);
            $content = str_replace('//www.', 'http://www.', $content);
            $content = str_replace('" /src/backend/', '"' . $showBaseUrl . '/src/backend/', $content);
        }

        // Create html temp file
        $filename = rtrim(env('SHOW_TMP_DIR', sys_get_temp_dir()), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . uniqid() . '.html';
        file_put_contents($filename, $content);

        // Open the html using chrome
        $command = 'open -n -a Google\ Chrome '
            . escapeshellarg($filename)
            . ' --args '
            . implode([
                '--user-data-dir="/tmp/chrome_dev_session"',
                '--disable-web-security',
                '--start-maximized',
                '--no-first-run',
                '--no-default-browser-check',
                '--disable-translate',
                '--disable-default-apps',
                '--disable-popup-blocking',
                '--disable-zero-browsers-open-for-tests',
                '--auto-open-devtools-for-tabs',
            ], ' ');

        system($command);
        die('Switch to your browser to see last response content.');
    }

    /**
     * Stop tests execution.
     */
    public function stop()
    {
        die('Test stopped.');
    }
}
