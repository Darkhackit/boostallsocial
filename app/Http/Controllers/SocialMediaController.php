<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SocialMediaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(SocialMedia::all()->map(function ($social_mdeia) {
            return [
                'id' => $social_mdeia->id,
                'name' => $social_mdeia->name,
                'image' => $social_mdeia->image,
                'format' => $social_mdeia->format,
                'show_link' => $social_mdeia->show_link,
//                'popular_service' => $social_mdeia->popular_service,
//                'show_comments' => $social_mdeia->show_comments,
//                'show_quantity' => $social_mdeia->show_quantity,
//                'show_usernames' => $social_mdeia->show_usernames,
//                'show_username' => $social_mdeia->show_username,
//                'show_hashtags' => $social_mdeia->show_hashtags,
//                'show_answer_number' => $social_mdeia->show_answer_number,
//                'show_groups' => $social_mdeia->show_groups,
            ];
        }));
    }

    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:social_media'],
            'image' => ['required','url'],
            'input_format' => ['required'],
        ]);
        $social_media = new SocialMedia();
        $this->extracted($request, $social_media);
        $social_media->save();

        return response()->json($social_media);
    }
    public function show(SocialMedia $socialMedia): JsonResponse
    {
        return response()->json([
            'id' => $socialMedia->id,
            'name' => $socialMedia->name,
            'image' => $socialMedia->image,
            'format' => $socialMedia->format,
            'show_link' => $socialMedia->show_link,
            'popular_service' => $socialMedia->popular_service,
            'show_comments' => $socialMedia->show_comments,
            'show_quantity' => $socialMedia->show_quantity,
            'show_usernames' => $socialMedia->show_usernames,
            'show_username' => $socialMedia->show_username,
            'show_hashtags' => $socialMedia->show_hashtags,
            'show_answer_number' => $socialMedia->show_answer_number,
            'show_groups' => $socialMedia->show_groups,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, SocialMedia $socialMedia): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:providers,name,'.$socialMedia->id],
            'image' => ['required'],
            'input_format' => ['required'],
        ]);
        $this->extracted($request, $socialMedia);
        $socialMedia->update();

        return response()->json($socialMedia);
    }

    /**
     * @param Request $request
     * @param SocialMedia $socialMedia
     * @return void
     */
    public function extracted(Request $request, SocialMedia $socialMedia): void
    {
        $socialMedia->name = $request->name;
        $socialMedia->image = $request->image;
        $socialMedia->format = $request->input_format;
        $socialMedia->show_link = $request->show_link;
        $socialMedia->popular_service = $request->popular_service;
        $socialMedia->show_comments = $request->show_comments;
        $socialMedia->show_quantity = $request->show_quantity;
        $socialMedia->show_usernames = $request->show_usernames;
        $socialMedia->show_username = $request->show_username;
        $socialMedia->show_hashtags = $request->show_hashtags;
        $socialMedia->show_answer_number = $request->show_answer_number;
        $socialMedia->show_groups = $request->show_groups;
    }

    public function delete(SocialMedia $socialMedia): JsonResponse
    {
        $socialMedia->delete();
        return response()->json(['success' => true]);
    }
    public function names(): JsonResponse
    {
        return response()->json(SocialMedia::query()->pluck('name'));
    }
    public function getService(): JsonResponse
    {
        $s = \request('q');
        return response()->json([
            'data' => SocialMedia::query()
                ->when(\request('q'),function ($query,$search) {
                    $query->where('name','like',"%{$search}%");
                })
                ->get()
                ->map(function ($social) {
                    return [
                        'name' => $social->name,
                        'image' => $social->image
                    ];
                }),
        ]);
    }
    public function service_details($val): JsonResponse
    {
        $service = SocialMedia::whereName($val)->first();

        try {
            $location = Http::get("http://www.geoplugin.net/json.gp?ip=".$this->getIp())->json();
            return response()->json([
                'name' => $service->name,
                'format' => $service->format,
                'show_link' => $service->show_link,
                'show_comments' => $service->show_comments,
                'show_quantity' => $service->show_quantity,
                'show_usernames' => $service->show_usernames,
                'show_username' => $service->show_username,
                'show_hashtags' => $service->show_hashtags,
                'show_answer_number' => $service->show_answer_number,
                'show_groups' => $service->show_groups,
                'countries' => $service->countries->map(function ($country) use ($location) {
                    return [
                        'name' => $country->name,
                        'price' => number_format($country->price * $location['geoplugin_currencyConverter'],2),
                        'min' => $country->min,
                        'max' => $country->max,
                        'currency' => $location['geoplugin_currencyCode'] ,
                        'description' => $country->description,

                    ];
                })
            ]);
        }catch (\Exception $exception) {

        }

    }
    public function getIp(): ?string
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
//        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
        return  "196.61.40.241";
    }
}
