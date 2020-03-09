<?php

namespace App\Http\Controllers\API;

use App\Models\Busqueda;
use App\Models\BusquedaResultado;
use App\Models\BusquedaCancion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\WeatherRequest;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(title="API Recomendaciones", version="1.0")
 *
 * @OA\Server(url="http://weather.test/")
 */

class SugerenciasController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sugerencia",
     *     summary="Mostrar lista de canciones recomendadas.",
     *     @OA\Parameter(
     *          name="city",
     *          description="Nombre de la ciudad",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="lat",
     *          description="Latitud geográfica",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="lon",
     *          description="Longitud geográfica",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="X-Requested-With",
     *          description="XMLHttpRequest",
     *          example="XMLHttpRequest",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar lista de canciones recomendadas."
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ha ocurrido un error : Ocurrio un error al obtener el clima.",
     *         description="Ha ocurrido un error : Ocurrio un error al obtener el token de spotify.",
     *         description="Ha ocurrido un error : Ocurrio un error al obtener el playlist.",
     *         description="Ha ocurrido un error : Ocurrio un error al obtener los tracks."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error : message."
     *     )
     *
     * )
     */

    public function getSugestion(WeatherRequest $request)
    {
        try {

            DB::beginTransaction();

            $busqueda = Busqueda::create([
                'city' => $request->city,
                'lat' => $request->lat,
                'lon' => $request->lon
            ]);

            $weather = $this->getWeather($request->city, $request->lat, $request->lon);
            $temp = $weather['main']['temp'];

            switch ($temp) {

                case $temp > 30:
                    $type = 'party';
                    break;
                case $temp <= 30 && $temp >= 15:
                    $type = 'pop';
                    break;
                case $temp <= 14 && $temp >= 10:
                    $type = 'rock';
                    break;
                case $temp < 10:
                    $type = 'classical';
                    break;
            }

            BusquedaResultado::create([
                'busqueda_id' => $busqueda->id,
                'city' => $weather['name'],
                'lat' => $weather['coord']['lat'],
                'lon' => $weather['coord']['lon'],
                'temp' => $temp,
                'timezone' => $weather['timezone'],
                'type' => $type
            ]);

            $token = $this->getSpotifyToken();
            $playlist_id = $this->getPlaylistId($token, $type);
            $tracks = $this->getSongsList($token, $playlist_id);
            $songs = [];

            foreach ($tracks['items'] as $track) {
                $songs[] = " Track: " . $track['track']['name'] . " Artist: " .$track['track']['artists'][0]['name'];
                BusquedaCancion::create([
                    'busqueda_id' => $busqueda->id,
                    'track_id' => $track['track']['id'],
                    'track' => $track['track']['name'],
                    'artisat' => $track['track']['artists'][0]['name']
                ]);
            }

            DB::commit();
            return response()->json(['temperature' => $temp, 'tracks' => $songs], 200);

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['message' => "Ha ocurrido un error : ".$e->getMessage()], 500);

        }

    }

    private function getWeather($city, $lat, $lon)
    {
        $api_id = env('OPEN_WEATHER_KEY_ID');

        if (!is_null($city)) {

            $response = Http::get("http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$api_id&units=metric");

        } elseif (!is_null($lat) && !is_null($lon)) {

            $response = Http::get("http://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$api_id&units=metric");

        }

        $response = $response->json();

        if (isset($response['main'])) {

            return $response;

        } else {

            throw new \Exception('Ocurrio un error al obtener el clima.');

        }

    }

    private function getSpotifyToken()
    {

        $spotify_id = env('SPOTIFY_USER_ID');
        $spotify_secret = env('SPOTIFY_SECRET_ID');

        $response = Http::withBasicAuth($spotify_id, $spotify_secret)
            ->asForm()->post("https://accounts.spotify.com/api/token", ['grant_type' => 'client_credentials']);

        $response = $response->json();

        if (isset($response['access_token'])) {

            return $response['access_token'];

        } else {

            throw new \Exception('Ocurrio un error al obtener el token de spotify');

        }

    }


    private function getPlaylistId($token, $type)
    {
        $playlist = Http::withHeaders([
            'Authorization' => "Bearer $token"
        ])->get("https://api.spotify.com/v1/browse/categories/$type/playlists?limit=1");

        $playlist = $playlist->json();

        if (isset($playlist['playlists'])) {

            return $playlist['playlists']['items'][0]['id'];

        } else {

            throw new \Exception('Ocurrio un error al obtener el playlist.');

        }


    }

    private function getSongsList($token, $playlist_id)
    {

        $tracks = Http::withHeaders([
            'Authorization' => "Bearer $token"
        ])->get("https://api.spotify.com/v1/playlists/$playlist_id/tracks?limit=10&fields=items(track(name%2Cid%2Calbum(name)%2Cartists(name)))");

        $tracks = $tracks->json();

        if (isset($tracks['items'])) {

            return $tracks;

        } else {

            throw new \Exception('Ocurrio un error al obtener los tracks.');

        }

    }


}
