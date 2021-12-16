<?php


namespace App\AdditionalClasses;


use App\AdditionalClasses\Date;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;

class Schema
{
    /**
     * Create schema for article and blog and news
     *
     * @param $instance
     * @param string $type 'Article'|'BlogPosting'|'NewsArticle'
     * @return array|\Illuminate\Http\RedirectResponse|null
     */
    public function createArticleBlogSchema($instance, string $type = 'Article')
    {
        try {
            if (!$instance) return null;
            if (!in_array($type, array('Article', 'BlogPosting', 'NewsArticle'))) $type = 'Article';

            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => $type,
                'mainEntityOfPage' => array('@type' => 'WebPage', '@id' => URL::to('/') . '/' . $instance->slug),
                'headline' => $instance->title,
//            'description' => '',
                'image' => asset($instance->picture),
                'author' => array('@type' => 'Person', 'name' => $instance->publisher->name, 'url' => URL::to('/') . '/author/' . $instance->publisher->id),
//            'publisher' => array(
//                '@type' => 'Organization',
//                'name' => '',
//                'logo' => array('@type' => 'ImageObject', 'url' => ''),
//            ),
                'datePublished' => Date::timestampToShamsiWithDay_andNameOfMonth($instance->created_at),
                'dateModified' => Date::timestampToShamsiWithDay_andNameOfMonth($instance->updated_at),
            );
            return $schema;

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Create schema for FAQ Page
     *
     * @param $instance
     * @return array|\Illuminate\Http\RedirectResponse|null
     */
    public function createFAQSchema($instance)
    {
        try {
            if (!$instance) return null;

            $faq = array();
            foreach ($instance as $item) {
                $faq[] = array(
                    '@type' => 'Question',
                    'name' => $item->title,
                    'acceptedAnswer' => array('@type' => 'Answer', 'text' => $item->content),
                );
            }

            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faq,
            );
            return $schema;

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Create schema for video object
     *
     * @param $instance
     * @return array|\Illuminate\Http\RedirectResponse|null
     */
    public function createVideoSchema($instance)
    {
        try {
            if (!$instance) return null;

            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'VideoObject',
                'name' => $instance->video->title,
                'description' => $instance->video->description,
                'thumbnailUrl' => asset($instance->video->picture),
                'uploadDate' => Date::timestampToShamsiWithDay($instance->created_at),
                'duration' => $this->createTimeInterval($instance->duration),
//            'publisher' => array(
//                '@type' => 'Organization',
//                'name' => $instance,
//                'logo' => array('@type' => 'ImageObject', 'url' => '', 'width' => '', 'height' => ''),
//            ),
                'contentUrl' => asset($instance->file),
//            'embedUrl' => $instance,
//            'potentialAction' => array(
//                '@type' => 'SeekToAction',
//                'target' => "seektoaction targer url '$instance'={seek_to_second_number}",
//                "startOffset-input" => "required name=seek_to_second_number",
//            ),
            );

            return $schema;

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Create schema for Breadcrumb
     *
     * @param $instance
     * @return array|\Illuminate\Http\RedirectResponse|null
     */
    public function createBreadcrumbSchema($instance)
    {
        try {
            if (!$instance) return null;

            $i = 1;
            $listItem = array();
            foreach ($instance as $item) {
                $listItem[] = array(
                    '@type' => 'ListItem',
                    'position' => $i,
                    'name' => $item->title,
                    'item' => URL::to('/') . '/' . $item->slug,
                );
                $i++;
            }

            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $listItem,
            );
            return $schema;

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Create time interval
     * @param int $second
     * @return string
     * @Example PT01M13S
     */
    public function createTimeInterval(int $second)
    {
        try {
            $minutes = floor($second / 60);
            $second_m = $second % 60;
            $minutes = (strlen((string) floor($second / 60)) == 1) ? '0' . floor($second / 60) : floor($second / 60);
            return 'PT'.$minutes.'M'.$second_m.'S';

        } catch (\Exception $e) {
            Session::flash('alert', $e->getMessage());
            return redirect()->back();
        }
    }
}
