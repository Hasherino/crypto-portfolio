<?php

namespace App\Models;

use GuzzleHttp\Client;
use HttpRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'value',
        'currency'
    ];

    public static function getUserAssets($user_id) {
        $assets = User::findOrFail($user_id)->assets()->get();

        if(!!$assets) {
            $totalValue = 0;

            foreach ($assets as $asset) {
                $value = $asset->value * self::getExchangeRate($asset);
                $asset['value_in_usd'] = $value;
                $totalValue += $value;
            }
        }
        
        $data['assets'] = $assets;
        $data['total_value_in_usd'] = $totalValue;

        return $data;
    }

    public static function getAsset($user_id, $id) {
        $asset = Asset::findOrFail($id);

        if($asset->user_id != $user_id) {
            return response()->json([
                'success' => false,
                'message' => 'This asset does not belong to you'
            ], 400);
        }

        $asset['value_in_usd'] = $asset->value * self::getExchangeRate($asset);

        return $asset;
    }

    public static function createAsset(Request $request) {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:50|unique:assets,label,user_id',
            'value' => 'required|numeric|gt:0',
            'user_id' => 'required|exists:users,id',
            'currency' => ['required', Rule::in(\Config::get('currencies'))]
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        return Asset::create($validator->validated());
    }

    public static function updateAsset(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'label' => 'string|max:50|unique:assets,label,user_id',
            'value' => 'numeric|gt:0',
            'currency' => [Rule::in(\Config::get('currencies'))]
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $asset = Asset::findOrFail($id);

        if($asset->user_id != $request->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'This asset does not belong to you'
            ], 400);
        }

        $asset->update($validator->validated());

        return $asset;
    }

    public static function deleteAsset($user_id, $id) {
        $asset = Asset::findOrFail($id);

        if($asset->user_id != $user_id) {
            return response()->json([
                'success' => false,
                'message' => 'This asset does not belong to you'
            ], 400);
        }

        $asset->delete();
    }

    private static function getExchangeRate($asset) {
        $response = Http::withHeaders([
            'X-CoinAPI-Key' => '6856ED36-17D0-4625-B31F-71E7669998EA'
        ])->get('https://rest.coinapi.io/v1/exchangerate/' . $asset->currency . '/USD');

        return json_decode($response->body())->rate;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
