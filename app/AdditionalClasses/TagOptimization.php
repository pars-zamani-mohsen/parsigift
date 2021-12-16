<?php


namespace App\AdditionalClasses;


use DOMDocument;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;

class TagOptimization
{
    /**
     * Seo: Check tags and optimization
     *
     * @param string $html_text
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SeoTagOptimization(string $html_text)
    {
        try {
            // create new DOMDocument
            $document = new DOMDocument('1.0', 'UTF-8');

            // set error level
            $internalErrors = libxml_use_internal_errors(true);

            // load HTML
            $document->loadHTML(mb_convert_encoding($html_text, 'HTML-ENTITIES', 'UTF-8'));
            /*        $document->loadHTML('<?xml encoding="utf-8"?> ' . $html_text);*/

            $document->preserveWhiteSpace = false;

            // Restore error level
            libxml_use_internal_errors($internalErrors);

            $document = $this->SeoATagOptimization($document);
            $document = $this->SeoImgTagOptimization($document);

            return $document->saveHTML(); // saveXML // saveHTML

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Seo: Check "a" tag and optimized
     *
     * @param $document
     * @return mixed
     */
    public function SeoATagOptimization($document)
    {
        try {
            foreach ($document->getElementsByTagName('a') as $a_element) {
                /*
                    help for dom: https://www.php.net/manual/en/class.domelement.php
                */
                $href = $a_element->getAttribute('href');
                $class = $a_element->getAttribute('class');
                $style = $a_element->getAttribute('style');
                $target = $a_element->getAttribute('target');
                $rel = $a_element->getAttribute('rel');
                $data_anchor = $a_element->getAttribute('data-anchor');
                $title = $a_element->getAttribute('title');

                /* check href link */
                if (strpos($href, 'http://') !== false || strpos($href, 'https://') !== false) {
                    /* check link location */
                    if (strpos($href, URL::to('/')) !== false) {
                        /* internal link */
                        $rel = 'follow';

                    } else {
                        /* external link */
                        $_tagFollow = 'follow';
                        if (strpos($rel, 'unfollow') !== false) { $_tagFollow = 'unfollow'; }
                        $rel = "noopener, noreferrer, $_tagFollow";
                        $target = '_blank';
                    }

                } else {
                    /* site marker */
                    if (strpos($href, '#') !== false) {
                        $rel = '';
                        $target = '';
                        $title = ($title) ? ($title . ' ' . $a_element->nodeValue) : $a_element->nodeValue;
                    }
                }

                $link = $document->createElement('a');
                $link->nodeValue = $a_element->nodeValue;
                $link->setAttribute('href', $href);
                if ($class) $link->setAttribute('class', $class);
                if ($style) $link->setAttribute('style', $style);
                if ($target) $link->setAttribute('target', $target);
                if ($rel) $link->setAttribute('rel', $rel);
                if ($data_anchor) $link->setAttribute('data-anchor', $data_anchor);
                if ($title) $link->setAttribute('title', $title);

                $a_element->parentNode->replaceChild($link, $a_element);
            }

            return $document;

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Seo: Check "img" tag and optimized
     *
     * @param $document
     * @return mixed
     */
    public function SeoImgTagOptimization($document)
    {
        try {
            foreach ($document->getElementsByTagName('img') as $a_element) {
                /*
                    help for dom: https://www.php.net/manual/en/class.domelement.php
                */
                $src = $a_element->getAttribute('src');
                $class = $a_element->getAttribute('class');
                $style = $a_element->getAttribute('style');
                $alt = $a_element->getAttribute('alt');
                $title = $a_element->getAttribute('title');
                $width = $a_element->getAttribute('width');
                $height = $a_element->getAttribute('height');

                /* create new element */
                $link = $document->createElement('img');
                $link->setAttribute('src', $src);
                if ($class) $link->setAttribute('class', $class);
                if ($style) $link->setAttribute('style', $style);
                if ($title) $link->setAttribute('title', $title);
                if ($alt) $link->setAttribute('alt', $alt);
                if ($width) $link->setAttribute('width', $width);
                if ($height) $link->setAttribute('height', $height);
                $link->setAttribute('loading', "lazy");

                $a_element->parentNode->replaceChild($link, $a_element);
            }

            return $document;

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }
}
