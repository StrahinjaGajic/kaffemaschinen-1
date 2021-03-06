<?php

namespace App\Http\Controllers\Admin;

use App\Models\Database\PageUberUns;
use App\Models\Database\PageWirKaufen;
use Illuminate\Http\Request;
use App\DataGrid\Facade as DataGrid;
use App\Models\Database\PageHome;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PageController extends Base
{

    public function home()
    {
        $dataGrid = DataGrid::model(PageHome::query())
            ->column('title', ['sortable' => true, 'label' => __('lang.title')])
            ->linkColumn(__('lang.edit'), [], function ($model) {
                return "<a href='" . route('admin.home.edit', $model->id) . "' >".__('lang.edit')."</a>";
            })
            ->linkColumn('destroy', ['label' => __('lang.destroy')], function ($model) {
                return  "<a href=' " . route('admin.home.destroy', $model->id) . " ' >".__('lang.destroy')."</a>";
            })
            ->setPagination(100);

        return view('admin.page.home.index')->with('dataGrid', $dataGrid);
    }


    public function homeCreate()
    {
        return view('admin.page.home.create');
    }


    public function homeStore(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'button' => 'required',
            'color' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png|max:2048'
        ]);

        $image = $request->image;
        $name = time() . $image->getClientOriginalName();
        $folder = '\front\assets\img\slider\\';
        $savePath = public_path($folder);
        Image::make($image->getRealPath())->resize(1140, 480)->save($savePath . $name);
        $dbPath = $folder . $name;


        PageHome::create([
            'title' => $request->title,
            'description' => $request->description,
            'button' => $request->button,
            'color' => $request->color,
            'image' => $dbPath
        ]);

        return redirect()->route('admin.page.home');
    }


    public function homeEdit($id)
    {
        $popup = PageHome::findOrFail($id);
        return view('admin.page.home.edit')
            ->with('popup', $popup);
    }


    public function homeUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'heading' => 'required',
            'button' => 'required',
            'body' => 'required',
            'url' => 'required|url',
            'color' => 'required',
        ]);

        $popup = PageHome::findorfail($id);
        $popup->heading = $request->heading;
        $popup->button = $request->button;
        $popup->body = $request->body;
        $popup->url = $request->url;
        $popup->color = $request->color;
        $popup->update();

        return redirect()->route('admin.page.home');

    }


    public function homeDestroy($id)
    {
        $slider = PageHome::findorfail($id);
        File::delete(public_path($slider->image));
        $slider->delete();
        return redirect()->back();
    }


    public function uberUns()
    {
        $dataGrid = DataGrid::model(PageUberUns::query()->where('key', '=', 'image'))
            ->column('banner_name', ['sortable' => true, 'label' => __('lang.banner-name')])
            ->linkColumn('destroy', ['label' => __('lang.destroy')], function ($model) {
                return  "<a href=' " . route('admin.uber-uns.destroy', $model->id) . " ' >".__('lang.destroy')."</a>";
            })
            ->setPagination(100);

        $text = PageUberUns::where('key', '=', 'text')->first();

        return view('admin.page.uberUns.index')
            ->with(['text' => $text, 'dataGrid' => $dataGrid]);
    }


    public function textUpdateUberUns(Request $request, $id)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        $text = PageUberUns::findorfail($id);
        $text->value = $request->body;
        $text->update();

        return redirect()->back()
            ->with(['success' => __('lang.update-success')]);
    }


    public function bannerUberUnsCreate()
    {
        return view('admin.page.uberUns.create');
    }


    public function bannerUberUnsStore(Request $request)
    {
        $this->validate($request, [
            'banner_name' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png|max:2048'
        ]);

        $image = $request->image;
        $name = time() . $image->getClientOriginalName();
        $folder = '\front\assets\img\about\\';
        $savePath = public_path($folder);
        Image::make($image->getRealPath())->resize(1140, 480)->save($savePath . $name);
        $dbPath = $folder . $name;

        PageUberUns::create([
            'banner_name' => $request->banner_name,
            'key' => 'image',
            'value' => $dbPath
        ]);

        return redirect()->route('admin.page.uber-uns');
    }

    public function bannerUberUnsDestroy($id)
    {
        $banner = PageUberUns::findorfail($id);
        File::delete(public_path($banner->value));
        $banner->delete();

        return redirect()->back();
    }


    public function wirKaufen()
    {
        $description = PageWirKaufen::all()->first();

        return view('admin.page.wirKaufen.index')
            ->with('description', $description);
    }


    public function updateWirKaufen(Request $request, $id)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $description = PageWirKaufen::findorfail($id);
        $description->body = $request->body;
        $description->update();

        return redirect()->back()->with(['success' => __('lang.update-success')]);
    }
}
