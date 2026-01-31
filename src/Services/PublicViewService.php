<?php

namespace QuangPhuc\WebsiteReseller\Services;

use Botble\Base\Facades\AdminHelper;
use Botble\Slug\Models\Slug;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use QuangPhuc\WebsiteReseller\Models\Category;
use QuangPhuc\WebsiteReseller\Models\Theme;

class PublicViewService
{

    public function handleFrontRoutes(Slug|array $slug): Slug|array|Builder
    {
        if (! $slug instanceof Slug) {
            return $slug;
        }

        $condition = [
            'id' => $slug->reference_id,
        ];

        if (AdminHelper::isPreviewing()) {
            Arr::forget($condition, 'status');
        }

        switch ($slug->reference_type) {
//            case Package::class:
//                /**
//                 * @var Package $package
//                 */
//                $package = Package::query()
//                    ->where($condition)
//                    ->firstOrFail();
//
//
//
//                return [
//                    'view' => 'package',
////                    'default_view' => 'plugins/blog::themes.post',
//                    'data' => compact('package'),
//                    'slug' => $package->slug,
//                ];
            case Category::class:
                /**
                 * @var Category $category
                 */
                $category = Category::query()
                    ->where($condition)
                    ->firstOrFail();


//                SeoHelper::setTitle($category->name)
//                    ->setDescription($category->description);
//
//                $meta = new SeoOpenGraph();
//                if ($category->image) {
//                    $meta->setImage(RvMedia::getImageUrl($category->image));
//                }
//                $meta->setDescription($category->description);
//                $meta->setUrl($category->url);
//                $meta->setTitle($category->name);
//                $meta->setType('article');
//
//                SeoHelper::setSeoOpenGraph($meta);

                $themes = $category->themes()->latest()->paginate();

                return [
                    'view' => 'website-reseller.themes.category',
//                    'default_view' => 'plugins/blog::themes.category',
                    'data' => compact('category', 'themes'),
                    'slug' => $category->slug,
                ];
            case Theme::class:
                $theme = Theme::query()
                    ->where($condition)
                    ->firstOrFail();

                if (request()->get('preview')) {

                    return [
                        'view' => 'website-reseller.themes.preview',
//                    'default_view' => 'plugins/blog::themes.tag',
                        'data' => compact('theme'),
                        'slug' => $theme->slug,
                    ];
                } else {

                    return [
                        'view' => 'website-reseller.themes.detail',
//                    'default_view' => 'plugins/blog::themes.tag',
                        'data' => compact('theme'),
                        'slug' => $theme->slug,
                    ];
                }

        }

        return $slug;
    }
}
