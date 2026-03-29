<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use Illuminate\Support\Facades\Http;


Route::get('/telegram-chat-id', function () {
    $token = env('TELEGRAM_BOT_TOKEN');
    try {
        $response = Http::timeout(30)                  // wait up to 30 seconds
            ->withoutVerifying()                       // ignore SSL for local dev
            ->get("https://api.telegram.org/bot{$token}/getUpdates");

        if ($response->failed()) {
            return "Error: " . $response->body();
        }

        $updates = $response->json('result');
        if (empty($updates)) {
            return "No updates yet. Make sure you've sent /start to the bot.";
        }

        // Get the most recent update
        $last = end($updates);
        $chatId = $last['message']['chat']['id'];
        $username = $last['message']['chat']['username'] ?? 'no username';

        return "Chat ID: {$chatId}<br>Username: @{$username}";
    } catch (\Exception $e) {
        return "Exception: " . $e->getMessage();
    }
});



Route::get('/tp', function () {
    $token = env('TELEGRAM_BOT_TOKEN');
    $updates = Http::withoutVerifying()
        ->timeout(30)
        ->get("https://api.telegram.org/bot{$token}/getUpdates")
        ->json('result');

    if (empty($updates)) {
        return 'No updates';
    }

    foreach ($updates as $update) {
        $chatId = $update['message']['chat']['id'];
        $text = $update['message']['text'] ?? '';

        if (str_starts_with($text, '/register ')) {
            $phone = substr($text, 10); // extract phone number
            $phone = preg_replace('/\D/', '', $phone); // clean

            $customer = \App\Models\Customer::where('phone', $phone)->first();
            if ($customer) {
                $customer->telegram_chat_id = $chatId;
                $customer->save();
                // Optionally send a confirmation
                Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => 'شماره شما با موفقیت ثبت شد. اکنون رسید‌ها به تلگرام ارسال می‌شوند.'
                ]);
            }
        }
    }

    return 'Processed';
});



