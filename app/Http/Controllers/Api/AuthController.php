<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailCodeJob;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function registr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $code = rand(11111, 99999);

        dispatch(new SendEmailCodeJob($user->email, $code));

        UserCode::create([
            'user_id' => $user->id,
            'code' => $code,
        ]);

        $success['token'] = $user->createToken('Token')->plainTextToken;

        $response = [
            'success' => true,
            'message' => 'Код отправлен на вашу электронную почту. Подтвердите в течение 2 минут!',
            'data' => $success,
        ];
        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('Token')->plainTextToken;

            $response = [
                'success' => true,
                'data' => $success,
            ];
            return response()->json($response, 200);
        }
    }

    public function verified_code(Request $request)
    {
        // return response()->json([ 'valid' => auth()->check() ]);
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $user = $request->user();

        $usercode = UserCode::where('user_id', $user->id)->first();
        // dd($usercode->code, $request->code);
        if ($usercode->code == $request->code) {
            $user->email_verified_at = Carbon::now();
            $user->save();
            $response = [
                'success' => true,
                'message' => 'Код правильный. вы можете отправить заявку ',
            ];
            return response()->json($response, 200);
        } else {
            return response()->json(['message' => 'Код неправильный']);
        }
    }
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Вы вышли из системы']);
    }
}
