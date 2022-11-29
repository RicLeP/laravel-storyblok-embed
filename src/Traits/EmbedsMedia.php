<?php


namespace Riclep\StoryblokEmbed\Traits;

use DOMDocument;
use DOMXPath;
use Embed\DataInterface;
use Embed\Embed;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

trait EmbedsMedia
{
	/**
	 * The Embed\Embed object.
	 *
	 * @var array|null
	 */
	private ?array $_embedData;

	/**
	 * Initialises the Embed object.
	 */
	protected function initEmbedsMedia(): void
	{
		$this->_embedData = $this->content ? $this->makeRequest() : null;
	}

	protected function makeRequest()
	{
		if (!config('storyblok-embed.cache')) {
			$response = $this->extractData($this->content);
		} else {
			$cache = Cache::getFacadeRoot();

			if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
				$cache = $cache->tags('storyblok');
			}

			$hash = md5($this->content);

			$response = $cache->remember($hash , config('storyblok-embed.cache_duration') * 60, function () {
				return $this->extractData($this->content);
			});
		}

		return $response;
	}

	/**
	 * Extracts the key fields from the Embed object and allows
	 * caching of the response.
	 *
	 * @param $response
	 * @return array
	 */
	protected function extractData($response): array
	{
		$embed = new Embed();

		$response = $embed->get($response);

		return [
			'title' => $response->title,
			'description' => $response->description,
			'url' => (string) $response->url,
			'keywords' => $response->keywords,
			'image' => $response->image,
			'code' => $response->code,
			'feeds' => $response->feeds,
			'authorName' => $response->authorName,
			'authorUrl' => (string) $response->authorUrl,
			'providerName' => $response->providerName,
			'publishedTime' => $response->publishedTime,
			'language' => $response->language,
		];
	}

	/**
	 * Returns the embed code looking for a view in storyblok.embeds or the package.
	 * If neither are found the raw embed code is returned.
	 *
	 * @return View|string
	 */
	public function render()
	{
		$view = 'default';

		if (!$this->_embedData) {
			return '';
		}

		if ($this->_embedData['code']) {
			return view('laravel-storyblok-embed::code', $this->extractScriptsFromCode());
		}

		return view('laravel-storyblok-embed::' . $view, [
			'embed' => $this->_embedData,
		]);
	}

	/**
	 * Render the embed using you own view, the embed object is passed to the view.
	 *
	 * @param $view
	 * @return View
	 */
	public function renderUsing($view): View
	{
		return view($view, [
			'embed' => $this->_embedData,
		]);
	}

	/**
	 * Returns the raw embed code.
	 *
	 * @return array
	 */
	protected function extractScriptsFromCode(): array
	{
		$doc = new DOMDocument();
		$doc->loadHTML($this->_embedData['code'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

		$xpath = new DOMXpath($doc);

		$scripts = [];

		foreach($xpath->query('//script') as $queryResult) {
			$scripts[] = $queryResult->ownerDocument->saveHTML($queryResult);
			$queryResult->parentNode->removeChild($queryResult);
		}

		return [
			'html' => $doc->saveHTML(),
			'scripts' => $scripts
		];
	}
}