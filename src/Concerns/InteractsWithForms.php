<?php

namespace Tequilarapido\Testing\Concerns;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

trait InteractsWithForms
{
    protected function submitFormWithoutAssertingPageLoad($buttonText, $inputs = [], $uploads = [])
    {
        $this->makeRequestWithoutAssertingPageLoadUsingForm($this->fillForm($buttonText, $inputs), $uploads);

        return $this;
    }

    protected function makeRequestWithoutAssertingPageLoadUsingForm(Form $form, array $uploads = [])
    {
        $files = $this->convertUploadsForTesting($form, $uploads);

        return $this->makeRequestWithoutAssertingPageLoad(
            $form->getMethod(), $form->getUri(), $this->extractParametersFromForm($form), [], $files
        );
    }

    protected function makeRequestWithoutAssertingPageLoad($method, $uri, $parameters = [], $cookies = [], $files = [])
    {
        $uri = $this->prepareUrlForRequest($uri);

        $this->call($method, $uri, $parameters, $cookies, $files);

        $this->clearInputs()->followRedirects();

        $this->currentUri = $this->app->make('request')->fullUrl();

        $this->crawler = new Crawler($this->response->getContent(), $this->currentUri);

        return $this;
    }

    /**
     * Disable honeytime.
     *
     * @return $this
     */
    protected function disableHoneypotTime()
    {
        config()->set('app.honeybot.honeytime.rule', '');

        return $this;
    }

    /**
     * Prepare backend translated model form data
     *
     * @param $common
     * @param $translated
     * @return array
     */
    public function translatedFormData($common, $translated)
    {
        $formatted = [];
        foreach (p_supported_locales() as $locale) {
            foreach ($translated as $key => $value) {
                $formatted[$locale . '[' . $key . ']'] = "[$locale] $value";
            }
        }

        return array_merge($common, $formatted);
    }
}
