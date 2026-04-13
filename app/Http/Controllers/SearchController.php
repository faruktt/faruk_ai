<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index() {
        $history = collect();
        if (auth()->check()) {
            $history = SearchHistory::where('user_id', auth()->id())
                        ->whereNotNull('conversation_id')
                        ->latest()
                        ->get()
                        ->unique('conversation_id');
        }
        return view('search', compact('history'));
    }

    public function search(Request $request) {
        $query = $request->query_text;
        $conversationId = $request->conversation_id ?? (string) Str::uuid();

        try {
            // ১. টাভিলি সার্চ
            $searchResponse = Http::post('https://api.tavily.com/search', [
                'api_key' => env('TAVILY_API_KEY'),
                'query' => $query,
                'max_results' => 3,
            ]);
            $results = $searchResponse->json()['results'] ?? [];

            $context = "";
            foreach ($results as $item) {
                $context .= "Source: " . $item['url'] . "\nContent: " . $item['content'] . "\n\n";
            }

            // ২. আগের চ্যাট হিস্ট্রি নেওয়া (AI Context এর জন্য)
            $messages = [['role' => 'system', 'content' => "You are Faruk AI. Use context: $context"]];

            $chatHistory = SearchHistory::where('conversation_id', $conversationId)
                            ->orderBy('created_at', 'asc')->take(10)->get();

            foreach ($chatHistory as $chat) {
                $messages[] = ['role' => 'user', 'content' => $chat->query];
                $messages[] = ['role' => 'assistant', 'content' => $chat->answer];
            }
            $messages[] = ['role' => 'user', 'content' => $query];

            // ৩. Groq AI কল
            $groqResponse = Http::withHeaders(['Authorization' => 'Bearer ' . env('GROQ_API_KEY')])
                ->post("https://api.groq.com/openai/v1/chat/completions", [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => $messages,
                ]);

            $answer = $groqResponse->json()['choices'][0]['message']['content'] ?? "Error generating answer.";

            // ৪. ডাটাবেসে সেভ (লগইন থাকলে)
            if (auth()->check()) {
                SearchHistory::create([
                    'user_id' => auth()->id(),
                    'conversation_id' => $conversationId,
                    'query' => $query,
                    'answer' => $answer,
                    'sources' => $results
                ]);
                return redirect()->route('search.history', $conversationId);
            }

            // ৫. গেস্ট ইউজার হলে (ডাটাবেসে সেভ হবে না, শুধু সেশনে থাকবে)
            $guestResult = (object)[
                'query' => $query,
                'answer' => $answer,
                'sources' => $results
            ];
            return back()->with('result', $guestResult);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function showHistory($id) {
        $userId = auth()->id();

        // সাইডবারের জন্য হিস্ট্রি
        $history = SearchHistory::where('user_id', $userId)
                    ->whereNotNull('conversation_id')
                    ->latest()->get()->unique('conversation_id');

        // বর্তমান কনভারসেশনের মেসেজগুলো
        $conversationMessages = SearchHistory::where('conversation_id', $id)
                                ->where('user_id', $userId)
                                ->orderBy('created_at', 'asc')->get();

        // মোট কয়টি প্রশ্ন করা হয়েছে তার কাউন্ট
        $messageCount = $conversationMessages->count();

        if($conversationMessages->isEmpty()) return redirect()->route('search.index');

        return view('search', [
            'history' => $history,
            'conversationMessages' => $conversationMessages,
            'currentConversationId' => $id,
            'messageCount' => $messageCount // কাউন্ট পাঠানো হচ্ছে
        ]);
    }
}
