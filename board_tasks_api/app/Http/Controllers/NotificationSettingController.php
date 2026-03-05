<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationSetting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NotificationSettingController extends Controller
{
    use AuthorizesRequests;

    public function show(Request $request)
    {
        $setting = NotificationSetting::where('user_id', $request->user()->id)
            ->first();

        return response()->json($setting);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'days_before' => 'required|integer|min:1|max:30'
        ]);

        $data['user_id'] = $request->user()->id;

        $setting = NotificationSetting::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return response()->json($setting);
    }
    
    public function destroy(NotificationSetting $notificationSetting)
    {
        $this->authorize('delete', $notificationSetting);

        $notificationSetting->delete();

        return response()->json([
            'message' => 'Notification setting deleted'
        ]);
    }

}