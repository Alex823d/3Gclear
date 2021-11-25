<?php

namespace App\Modules\Landing\Http\Controllers\Client;

use App\Modules\Landing\Http\Requests\ContactSendRequest;
use App\Modules\Landing\Http\Resources\Blog\BlogItemResource;
use App\Modules\Landing\Http\Resources\Brand\BrandItemResource;
use App\Modules\Landing\Http\Resources\Product\ProductItemResource;
use App\Modules\Landing\Http\Resources\Project\ProjectItemResource;
use App\Modules\Landing\Http\Resources\Service\ServiceItemResource;
use App\Modules\Landing\Http\Resources\Team\TeamItemResource;
use App\Modules\Landing\Mail\ContactSend;
use App\Modules\Pages\Http\Resources\Client\PageMetaInfoResource;
use App\Modules\Pages\Models\Blog;
use App\Modules\Pages\Models\Brand;
use App\Modules\Pages\Models\Page;
use App\Modules\Pages\Models\Product;
use App\Modules\Pages\Models\Project;
use App\Modules\Pages\Models\Service;
use App\Modules\Pages\Models\Team;
use App\Modules\Pages\Services\Client\BlogData;
use App\Modules\Pages\Services\Client\BrandData;
use App\Modules\Pages\Services\Client\ProjectData;
use App\Modules\Pages\Services\Client\ServiceData;
use App\Modules\Pages\Services\Client\TeamData;
use Butschster\Head\Contracts\MetaTags\MetaInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;
use SeoData;
use View;

/**
 * @property MetaInterface meta
 */
class LandingPageController extends Controller
{

    /**
     * @var MetaInterface
     */
    protected $meta;

    protected array $directionArray = [];

    /**
     * LandingPageController constructor.
     *
     * @param MetaInterface $meta
     */
    public function __construct(MetaInterface $meta)
    {
        $this->meta = $meta;
    }


    public function home(Request $request)
    {
        if (app()->getLocale()=="ge"){


        }elseif (app()->getLocale()=="en"){

        }elseif (app()->getLocale()=="ru"){

        }
    }


 /**
     * @param Request $request
     *
     * @return \Inertia\Response
     */
    public function contact(Request $request): Response
    {
        $allSeoData = SeoData::setTitle(__('seo.contact.title'))
            ->setDescription(__('seo.contact.description'))
            ->setKeywords(__('seo.contact.description'))
            ->setOgTitle(__('seo.contact.title'))
            ->setOgDescription(__('seo.contact.description'))
            ->getSeoData();

        View::composer('app', function ($view) use ($allSeoData) {
            $view->with('allSeoData', $allSeoData);
        });

        return Jetstream::inertia()->render($request, 'Landing/Contact/Index');
    }



    public function mail(Request $request)
    {
        if ($request->method() == 'POST') {
            $request->validate([
                'name' => 'required|string|max:55',
                'email' => 'required|email',
                'message' => 'required|max:1024'
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => 'subject',
                'message' => $request->message
            ];
//            dd($data);
//            $mailTo = Setting::where(['key' => 'email'])->first();
            $mailTo = "vakhobatsikadze@gmail.com";
//            if (($mailTo !== null) && $mailTo->value) {
                Mail::to($mailTo)->send(new ContactSend($data));
//            }
//
        }
//        return redirect()->route("client.contact.index");
    }
}
