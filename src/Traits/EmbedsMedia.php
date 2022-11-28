<?php


namespace Riclep\StoryblokEmbed\Traits;

use DOMDocument;
use DOMXPath;
use Embed\DataInterface;
use Embed\Embed;
use Embed\Extractor;

trait EmbedsMedia
{
	/**
	 * The Embed\Embed object.
	 *
	 * @var DataInterface|null
	 */
	private Extractor|null $_embed;

	/**
	 * Initialises the Embed object.
	 */
	protected function initEmbedsMedia(): void
	{
		$embed = new Embed();

		$info = $embed->get($this->content);
dd($info->code);
dd($embed->get($this->content));
		$this->_embed = $this->content ? $embed->get($this->content) : null;
	}


	/**
	 * Returns the embed code looking for a view in storyblok.embeds or the package.
	 * If neither are found the raw embed code is returned.
	 *
	 * @return string
	 */
	public function html(): string
	{
		if (!$this->_embed) {
			return '';
		}

		return $this->_embed->code;
	}


	/**
	 * Returns the embed code looking for a view in storyblok.embeds or the package.
	 * If neither are found the raw embed code is returned.
	 *
	 * @return \Illuminate\View\View
	 */
	public function htmlVue(): \Illuminate\View\View
	{
		if (!$this->_embed) {
			return '';
		}

		return view('laravel-storyblok-embed::embed', $this->extractScripts());
	}





	/**
	 * Returns the raw embed code.
	 *
	 * @return array
	 */
	protected function extractScripts(): array
	{
		$doc = new DOMDocument();
		$doc->loadHTML($this->_embed->code, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

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






	/**
	 * Returns the embed code looking for a view in storyblok.embeds or the package.
	 * If neither are found the raw embed code is returned.
	 *
	 * @return string
	 */
//	public function html(): string
//	{
//		if (!$this->_embed) {
//			return '';
//		}
//
//		if (method_exists($this, 'embedView')) {
//			$method = 'embedView';
//		} else {
//			$method = 'baseEmbedView';
//		}
//
//		if ($this->{$method}()) {
//			return (string) view($this->{$method}(), [
//				'embed' => $this->_embed,
//			]);
//		}
//
//		return $this->rawEmbed();
//	}

	/**
	 * Returns the raw embed code.
	 *
	 * @return string|null
	 */
	public function rawEmbed(): ?string
	{
		$doc = new DOMDocument();
		$doc->loadHTML($this->_embed->code, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

		$xpath = new DOMXpath($doc);

		$scripts = [];

		foreach($xpath->query('//script') as $queryResult) {
			$scripts[] = $queryResult->ownerDocument->saveHTML($queryResult);
			$queryResult->parentNode->removeChild($queryResult);
		}

		dd($scripts, $doc->saveHTML());

		return $doc->saveHTML();
	}

	/**
	 * Returns the Embed\Embed object.
	 *
	 * @return Embed
	 */
	public function embed(): Embed
	{
		return $this->_embed;
	}

	/**
	 * Returns a path to a view to use for embedding this type of media.
	 * If the view can not be found it should return false.
	 *
	 * @return false|string
	 */
	protected function baseEmbedView(): bool|string
	{
		if (view()->exists(config('storyblok.view_path') . 'embeds.' . strtolower($this->_embed->providerName))) {
			return config('storyblok.view_path') . 'embeds.' . strtolower($this->_embed->providerName);
		}

		if (view()->exists('laravel-storyblok::embeds.' . strtolower($this->_embed->providerName))) {
			return 'laravel-storyblok::embeds.' . strtolower($this->_embed->providerName);
		}

		return false;
	}


	/**
	 * Returns the embed code
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->html();
	}
}