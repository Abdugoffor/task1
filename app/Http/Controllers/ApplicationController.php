<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use App\Jobs\SendEmailCommentJob;
use App\Models\Application;
use App\Models\Comment;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{

    public function index()
    {
        $applications = Application::all();
        return response()->json(['applications' => $applications], 200);
    }

    public function requests(Request $request)
    {
        // dd($request->message);
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        $user = Auth::user();
        $application = Application::create([
            'user_id' => $user->id,
            'message' => $request->message,
        ]);
        return response()->json(['application' => $application], 200);
    }

    public function getall()
    {
        $user = Auth::user();
        $application = Application::where('user_id', $user->id)->get();
        return response()->json(['application' => $application], 200);
    }

    public function sendemail(Application $application, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }
        $application->update([
            'status' => 'resolved'
        ]);
        
        $application = new ApplicationResource($application);
        
        // dd($application->user);
        $comment = new Comment();
        $comment->application_id = $application->id;
        $comment->comment = $request->comment;
        $comment->save();
        dispatch(new SendEmailCommentJob($application->user, $comment->comment));
        return response()->json(['message' => 'Ответ отправлен'], 200);
    }
}
