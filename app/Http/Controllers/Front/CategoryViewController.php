<?php

namespace App\Http\Controllers\Front;

use DB;
use App\Http\Controllers\Controller;
use App\Models\Database\Category;
use App\Models\Database\Configuration;
use App\Models\Database\Product;
use Illuminate\Http\Request;

class CategoryViewController extends Controller
{
    public function allCategoryView(Request $request)
    {
        $productOnCategoryPage = Configuration::getConfiguration('schoengebraucht_catalog_no_of_product_category_page');

        if ($request->has('slug')) {
            $category = Category::where('slug', '=', $request->slug)->get()->first();
            $collection = $category->products();
            $maxPrice = $collection->max('price');
        } else {
            $maxPrice = Product::max('price');
            $collection = Product::whereNotNull('id');
        }

        $view = request('view') ? request('view') : $productOnCategoryPage;
        $mode = request('mode') == 'list' ? 'list' : 'grid';
        $orderBy = $request->order_by ? $request->order_by : null;

        if ($request->has('q')) {
            
            $products = Product::where('name', 'LIKE', '%' . $request->q . '%')->paginate($view);
            $request->flash();

            return view('front.catalog.view')
            ->with('params', $request->all())
            ->with('products', $products)
            ->with('mode', $mode)
            ->with('maxPrice', $maxPrice);
        }

        if ($request->has('price_from') && $request->has('price_to')) {
            $inputPriceFrom = $request->price_from;
            $inputPriceTo = $request->price_to;
            if ($inputPriceFrom == 0 && $inputPriceTo == $maxPrice) {
                $collection = $collection;
            } else {
                $collection->whereBetween('price', [$inputPriceFrom, $inputPriceTo]);
            }
        }

        $collection = isset($orderBy) ? $collection->orderBy(getBeforeLastChar($orderBy, '_'), getAfterLastChar($orderBy, '_')) : $collection;
        $products = $collection->where('status', '=', '1')->paginate($view);

        $request->flash();

        return view('front.catalog.view')
        ->with('params', $request->all())
        ->with('products', $products)
        ->with('mode', $mode)
        ->with('maxPrice', $maxPrice);
    }

    public function getPriceRanges(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'url' => urldecode(route('all.category.view', $request->query(), false))
            ]);
        }
    }
}