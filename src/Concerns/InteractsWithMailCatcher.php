<?php

namespace Tequilarapido\Testing\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use stdClass;

trait InteractsWithMailCatcher
{
    /** @var string mailcatcher base url */
    protected $mailCatcherBaseUrl = 'http://localhost:1080';

    /** @var  Client mail catcher guzzle client */
    protected static $mailCatcher;

    /**
     * Setup mail catcher client
     * @before
     */
    protected function setupMailcatcher()
    {
        static::$mailCatcher = new Client(['base_uri' => $this->mailCatcherBaseUrl]);
        $this->purgeMails();
    }

    /**
     * free the mailcatcher client instance
     * @after
     */
    protected function tearDownMailcatcher()
    {
        static::$mailCatcher = null;
    }

    /**
     * Assert mails count.
     *
     * @param int $expectedCount
     * @param string $message
     * @return $this
     */
    public function assertMailsCount($expectedCount, $message = '')
    {
        $mails = $this->getMails();
        $this->assertEquals($expectedCount, count($mails), $message);

        return $this;
    }

    /**
     * Assert last message subject.
     *
     * @param string $expected
     * @param string $message
     * @return $this
     */
    public function assertMailSubject($expected, $message = '')
    {
        $this->assertSame($expected, $this->getLastMail()->subject, $message);

        return $this;
    }

    /**
     * Assert last message plain body contains.
     *
     * @param string $needle
     * @param string $message
     * @param bool $ignoreCase
     *
     * @return $this
     */
    public function assertMailPlainBodyContains($needle, $message = '', $ignoreCase = false)
    {
        $this->assertContains($needle, $this->getLatestMailPlainBody(), $message, $ignoreCase);

        return $this;
    }

    /**
     * Assert last message html body contains.
     *
     * @param string $needle
     * @param string $message
     * @param bool $ignoreCase
     * @return $this
     */
    public function assertMailHtmlBodyContains($needle, $message = '', $ignoreCase = false)
    {
        $this->assertContains($needle, $this->getLatestMailHtmlBody(), $message, $ignoreCase);

        return $this;
    }

    /**
     * Asserts that last message plain body is empty.
     *
     * @param string $message
     * @return $this
     */
    public function assertMailPlainBodyEmpty($message = '')
    {
        $actual = null;

        try {
            $actual = $this->getLatestMailPlainBody();
        } catch (RequestException $e) {
            if ($e->getCode() === 404) {
                $actual = '';
            }
        }

        static::assertEmpty($actual, $message);

        return $this;
    }

    /**
     * Assert that last html body is empty.
     *
     * @param string $message
     * @return $this
     */
    public function assertMailHtmlBodyEmpty($message = '')
    {
        $actual = null;

        try {
            $actual = $this->getLastHtmlBody();
        } catch (RequestException $e) {
            if ($e->getCode() === 404) {
                $actual = '';
            }
        }

        static::assertEmpty($actual, $message);

        return $this;
    }

    /**
     * clean all messages.
     */
    protected function purgeMails()
    {
        static::$mailCatcher->delete('/messages');
    }

    /**
     * Return inbox emails.
     *
     * @return array
     */
    protected function getMails()
    {
        $response = static::$mailCatcher->get('/messages');

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Return last mail.
     *
     * @return stdClass
     */
    protected function getLastMail()
    {
        return $this->getMail($this->getLastMailId());
    }

    /**
     * Get last mail plain body.
     *
     * @return string
     */
    protected function getLatestMailPlainBody()
    {
        return $this->getMailPlainBody($this->getLastMailId());
    }

    /**
     * Get last mail html body.
     *
     * @return string
     */
    protected function getLatestMailHtmlBody()
    {
        return $this->getMailHtmlBody($this->getLastMailId());
    }

    /**
     * Return mail by id.
     *
     * @param int $id
     * @return stdClass
     */
    protected function getMail($id)
    {
        if (!is_int($id)) {
            new \InvalidArgumentException('only numeric');
        }
        $path = sprintf('/messages/%s.json', urlencode($id));
        $response = static::$mailCatcher->get($path);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Return mails plain body.
     *
     * @param int $id
     * @return string
     */
    protected function getMailPlainBody($id)
    {
        if (!is_int($id)) {
            new \InvalidArgumentException('only numeric');
        }
        $path = sprintf('/messages/%s.plain', urlencode($id));
        $response = static::$mailCatcher->get($path);

        return $response->getBody()->getContents();
    }

    /**
     * @param int $id
     * @return string
     */
    protected function getMailHtmlBody($id)
    {
        if (!is_int($id)) {
            new \InvalidArgumentException('only numeric');
        }
        $path = sprintf('/messages/%s.html', urlencode($id));
        $response = static::$mailCatcher->get($path);

        return $response->getBody()->getContents();
    }

    /**
     * Return last mail ID
     *
     * @return int
     */
    private function getLastMailId()
    {
        return collect($this->getMails())
            ->max(function ($mail) {
                return $mail->id;
            });
    }
}
