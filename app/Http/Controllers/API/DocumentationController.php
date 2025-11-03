<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

class DocumentationController extends Controller
{
    public function index()
    {
        $routes = Route::getRoutes()->getRoutes();

        $apiRoutes = [];

        foreach ($routes as $route) {
            if (strpos($route->uri, 'api/v1') !== false && $route->methods[0] !== 'HEAD') {
                $apiRoutes[] = [
                    'method' => $route->methods[0],
                    'uri' => $route->uri,
                    'name' => $route->getName(),
                    'action' => $route->getActionName(),
                ];
            }
        }

        return view('api.documentation', compact('apiRoutes'));
    }

    public function test(\Illuminate\Http\Request $request)
    {
        $method = $request->input('method');
        $url = $request->input('url');
        $headers = $request->input('headers', []);
        $body = $request->input('body', []);

        // Add Authorization header if user is logged in
        if (Auth::check()) {
            $token = Auth::user()->createToken('api-test')->plainTextToken;
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        // Make HTTP request
        $client = new Client();

        try {
            $response = $client->request($method, $url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            $responseData = [
                'status' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
                'body' => json_decode($response->getBody(), true) ?? $response->getBody(),
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}