<?php

namespace Meema\MediaConvert\Sources;

use DOMDocument;
use DOMNodeList;
use Meema\MediaConvert\Contracts\Source as SourceContract;
use RecursiveIteratorIterator;

class WebsiteSource implements SourceContract
{
    /**
     * Handles in getting the text from source.
     *
     * @param  string $data
     * @return string
     */
    public function handle(string $data): string
    {
        $articles = $this->getDOMDocumentArticle($data);

        if ($articles === null) {
            return '';
        }

        return $this->getTextFromArticle($articles);
    }

    /**
     * Get the DOM Node List of article tag.
     *
     * @return DOMNodeList|null
     */
    protected function getDOMDocumentArticle(string $url)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML(file_get_contents($url));
        $element = $dom->getElementsByTagName('article')->item(0);

        if ($element !== null) {
            return $element->childNodes;
        }
    }

    /**
     * Get text from the articles DOM Node List.
     *
     * @param DOMNodeList $articles
     * @return string
     */
    protected function getTextFromArticle(DOMNodeList $articles): string
    {
        $text = '';

        for ($i = 0; $i < $articles->length; $i++) {
            // Check element if there is a childNodes
            if ($articles->item($i)->childNodes === null) {
                continue;
            }

            $dit = new RecursiveIteratorIterator(
                new RecursiveDOMIterator($articles->item($i)),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($dit as $node) {
                if ($node->nodeName === 'p') {
                    $text .= $node->textContent.' ';
                }
            }
        }

        return $text;
    }
}
