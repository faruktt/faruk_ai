<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

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
        $request->validate([
            'query_text' => 'nullable|string',
            'file_upload' => 'nullable|mimes:jpg,jpeg,png,webp,pdf,txt|max:10240',
        ]);


        if (!auth()->check()) {
            $guestSearchCount = session('guest_search_count', 0);
            if ($guestSearchCount >= 2) {
                return back()->with('error', 'You’ve used your 2 free searches as a guest. Please log in to continue.');
            }
        }

        $query = $request->query_text ?? "Analyze this.";
        $conversationId = $request->conversation_id ?? (string) Str::uuid();
        $fileName = null;
        $filePath = null;
        $isImage = false;
        $fileContent = "";
        $base64Image = null;
        $mimeType = "";


        if (auth()->check() && $request->hasFile('file_upload')) {
            $todayUploads = SearchHistory::where('user_id', auth()->id())
                            ->whereDate('created_at', now()->today())
                            ->whereNotNull('file_name')
                            ->count();

            if ($todayUploads >= 2) {
                return back()->with('error', 'You’ve reached your daily limit of 2 image/file uploads. Please try again tomorrow.');
            }
        }

        try {

            if ($request->hasFile('file_upload')) {
                $file = $request->file('file_upload');
                $fileName = $file->getClientOriginalName();
                $mimeType = $file->getMimeType();
                $extension = $file->getClientOriginalExtension();


                $filePath = $file->store('uploads', 'public');

                if (str_starts_with($mimeType, 'image/')) {
                    $isImage = true;
                    $base64Image = base64_encode(file_get_contents($file->getPathname()));
                } elseif ($extension == 'pdf') {
                    $parser = new PdfParser();
                    $pdf = $parser->parseFile($file->getPathname());
                    $fileContent = $pdf->getText();
                } elseif ($extension == 'txt') {
                    $fileContent = file_get_contents($file->getPathname());
                }
            }


            $searchResponse = Http::post('https://api.tavily.com/search', [
                'api_key' => env('TAVILY_API_KEY'),
                'query' => $query,
                'max_results' => 3,
            ]);
            $results = $searchResponse->json()['results'] ?? [];

            $webContext = "";
            foreach ($results as $item) {
                $webContext .= "Source: " . $item['url'] . "\nContent: " . $item['content'] . "\n\n";
            }


            if ($isImage) {
                $model = "meta-llama/llama-4-scout-17b-16e-instruct";
                $promptWithContext = "Web Context: \n" . $webContext . "\n\nUser Question: " . $query;

                $messages = [
                    [
                        "role" => "user",
                        "content" => [
                            ["type" => "text", "text" => $promptWithContext],
                            [
                                "type" => "image_url",
                                "image_url" => ["url" => "data:{$mimeType};base64,{$base64Image}"]
                            ]
                        ]
                    ]
                ];
            } else {
                $model = "llama-3.3-70b-versatile";
                $systemContent = "You are Faruk AI. Use context and files to answer.\n\nWeb Context:\n" . $webContext;
                if (!empty($fileContent)) {
                    $systemContent .= "\n\nFile Content ($fileName):\n" . substr($fileContent, 0, 10000);
                }

                $messages = [['role' => 'system', 'content' => $systemContent]];
                $chatHistory = SearchHistory::where('conversation_id', $conversationId)
                                ->orderBy('created_at', 'asc')->take(6)->get();

                foreach ($chatHistory as $chat) {
                    $messages[] = ['role' => 'user', 'content' => $chat->query];
                    $messages[] = ['role' => 'assistant', 'content' => $chat->answer];
                }
                $messages[] = ['role' => 'user', 'content' => $query];
            }


            $groqResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            ])->timeout(120)->post("https://api.groq.com/openai/v1/chat/completions", [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_completion_tokens' => 2048,
            ]);

            if ($groqResponse->failed()) {
                Log::error("Groq Error: " . $groqResponse->body());
                return back()->with('error', 'Groq API Error occurred.');
            }

            $answer = $groqResponse->json()['choices'][0]['message']['content'] ?? "Error generating answer.";


            if (!auth()->check()) {
                session(['guest_search_count' => $guestSearchCount + 1]);
                $guestResult = (object)[
                    'query' => $query,
                    'answer' => $answer,
                    'sources' => $results,
                    'file_name' => $fileName,
                    'file_path' => $filePath
                ];
                return back()->with('result', $guestResult);
            }


            SearchHistory::create([
                'user_id' => auth()->id(),
                'conversation_id' => $conversationId,
                'query' => $query,
                'answer' => $answer,
                'sources' => $results,
                'file_name' => $fileName,
                'file_path' => $filePath
            ]);
            return redirect()->route('search.history', $conversationId);

        } catch (\Exception $e) {
            Log::error("Search Error: " . $e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function showHistory($id) {
        $userId = auth()->id();
        $history = SearchHistory::where('user_id', $userId)->whereNotNull('conversation_id')->latest()->get()->unique('conversation_id');
        $conversationMessages = SearchHistory::where('conversation_id', $id)->where('user_id', $userId)->orderBy('created_at', 'asc')->get();
        $messageCount = $conversationMessages->count();

        if($conversationMessages->isEmpty()) return redirect()->route('search.index');

        return view('search', [
            'history' => $history,
            'conversationMessages' => $conversationMessages,
            'currentConversationId' => $id,
            'messageCount' => $messageCount
        ]);
    }
}
