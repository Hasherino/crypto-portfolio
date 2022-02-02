<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssetController extends Controller
{
    public function index(Request $request) {
        return Asset::getUserAssets($request->user_id);
    }

    public function show(Request $request, $id) {
        return Asset::getAsset($request->user_id, $id);
    }

    public function store(Request $request) {
        $asset = Asset::createAsset($request);

        if ($asset instanceof Response) {
            return $asset;
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset created successfully',
            'data' => $asset
        ], 201);
    }

    public function update(Request $request, $id) {
        $asset = Asset::updateAsset($request, $id);

        if ($asset instanceof Response) {
            return $asset;
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset updated successfully',
            'data' => $asset
        ], 201);
    }

    public function destroy(Request $request, $id) {
        if (!!$error = Asset::deleteAsset($request->user_id, $id)) {
            return $error;
        }

        return response()->json([
            'success' => true,
            'message' => 'Gear deleted successfully'
        ]);
    }
}
