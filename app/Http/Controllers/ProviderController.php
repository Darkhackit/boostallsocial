<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ProviderController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Provider::all()->map(function ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'url' => $provider->url,
                'type' => $provider->type
            ];
        }));
    }
    /**
     * @throws ValidationException
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
            $this->validate($request,[
                'name' => ['required','unique:providers'],
                'url' => ['required','url','unique:providers'],
                'api_key' => ['required','unique:providers'],
                'type' => ['required']
            ]);

            $provider = new Provider();
            $provider->name = $request->name;
            $provider->url = $request->url;
            $provider->api_key = $request->api_key;
            $provider->type = $request->type;

            $provider->save();

            return response()->json($provider);
    }
    public function service(Provider $provider): \Illuminate\Http\JsonResponse
    {
        $data = [
            'action' => 'services',
           $provider->name === 'qqtube' ? 'api_key' : 'key' => $provider->api_key,
        ];

        $service = Http::get($provider->url,$data)->json();

        return response()->json([
            'name' => $provider->name,
            'services' => $service
        ]);
    }
    public function show(Provider $provider): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'id' => $provider->id,
            'name' => $provider->name,
            'url' => $provider->url,
            'api_key' => $provider->api_key,
            'type' => $provider->type,
        ]);
    }
    public function update(Request $request, Provider $provider): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:providers,name,'.$provider->id],
            'url' => ['required','url','unique:providers,url'.$provider->id],
            'api_key' => ['required','unique:providers,api_key,'.$provider->id],
            'type' => ['required']
        ]);
        $provider->name = $request->name;
        $provider->url = $request->url;
        $provider->api_key = $request->api_key;
        $provider->type = $request->type;
        $provider->update();

        return response()->json($provider);
    }
    public function delete(Provider $provider): \Illuminate\Http\JsonResponse
    {
        $provider->delete();

        return response()->json(['success' => true]);
    }
    public function names(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Provider::query()->pluck('name'));
    }
}
