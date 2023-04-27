<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Provider;
use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CountryController extends Controller
{
    public function index() :JsonResponse
    {
        return response()->json(Country::all()->map(function ($target_country) {
            return [
                'id' => $target_country->id,
                'name' => $target_country->name,
                'price' => $target_country->price,
                'service_id' => $target_country->service_id,
                'provider' => $target_country->provider?->name,
                'social_media' => $target_country->social_media?->name,
            ];
        }));
    }
    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required'],
            'price' => ['required'],
            'min' => ['required'],
            'max' => ['required'],
            'social_media' => ['required'],
            'provider' => ['required'],
            'service_id' => ['required'],
        ]);
        $provider = Provider::whereName($request->provider)->first();
        $social = SocialMedia::whereName($request->social_media)->first();

        $checkIfCountryExistInSocial = $social->countries->where('name',$request->name)->first();
        if($checkIfCountryExistInSocial) {
            return  response()->json(["errors" => [
                "social_media" => ['Country Already exist in the selected Social Media Service']
            ]],422);
        }

        $target_country = new Country();
        return $this->extracted($request, $target_country, $provider, $social);
    }

    public function show(Country $country)
    {
        return response()->json([
            'id' => $country->id,
            'name' => $country->name,
            'price' => $country->price,
            'min' => $country->min,
            'service_id' => $country->service_id,
            'provider' => $country->provider->name,
            'max' => $country->max,
            'description' => $country->description,
            'social_media' => $country->social_media->name,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Country $country): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required'],
            'price' => ['required'],
            'min' => ['required'],
            'max' => ['required'],
            'social_media' => ['required'],
            'provider' => ['required'],
            'service_id' => ['required'],
        ]);
        $provider = Provider::whereName($request->provider)->first();
        $social = SocialMedia::whereName($request->social_media)->first();
        $checkIfCountryExistInSocial = $social->countries->where('name',$request->name)->first();
       if ($checkIfCountryExistInSocial) {
           if($checkIfCountryExistInSocial->name !== $country->name) {
               return  response()->json(["errors" => [
                   "social_media" => ['Country Already exist in the selected Social Media Service']
               ]],422);
           }
       }

        return $this->extracted($request, $country, $provider, $social);
    }

    /**
     * @param Request $request
     * @param Country $country
     * @param $provider
     * @param $social
     * @return JsonResponse
     */
    public function extracted(Request $request, Country $country, $provider, $social): JsonResponse
    {
        $country->name = $request->name;
        $country->price = $request->price;
        $country->min = $request->min;
        $country->service_id = $request->service_id;
        $country->provider_id = $provider?->id;
        $country->social_media_id = $social?->id;
        $country->max = $request->max;
        $country->description = $request->description;
        $country->save();

        return response()->json($country);
    }

    public function delete(Country $country)
    {
        $country->delete();
        return response()->json(['success' => true]);
    }
}
