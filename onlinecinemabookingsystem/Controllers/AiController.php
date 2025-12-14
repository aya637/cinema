<?php
// app/controllers/AiController.php

class AiController extends Controller {
    
    public function recommend() {
        // 1. Get User Input
        $input = json_decode(file_get_contents('php://input'), true);
        $userPrompt = $input['prompt'] ?? '';

        if (empty($userPrompt)) {
            $this->json(['message' => 'Please ask something!'], 400);
        }

        // 2. Load Movies
        $movieModel = $this->model('Movie');
        // Fetch movies (Page 1, 100 items, 'all' statuses)
        $movies = $movieModel->getAll(1, 100, 'all'); 
        
        // 3. Prepare Context
        $movieContext = [];
        if (!empty($movies)) {
            foreach ($movies as $m) {
                // Sanitize text
                $title = str_replace(["\n", "\r", '"'], " ", $m['title']);
                $desc = str_replace(["\n", "\r", '"'], " ", substr($m['description'] ?? '', 0, 150));
                
                $movieContext[] = "ID: {$m['id']} | Title: $title | Desc: $desc";
            }
        } else {
             $this->json(['message' => "I'm sorry, I can't access the movie database right now."]);
        }
        
        $movieString = implode("\n", $movieContext);

        // 4. Call Google Gemini
        $botResponse = $this->callGemini($userPrompt, $movieString);

        // 5. Return Response
        $this->json(['message' => $botResponse]);
    }

    private function callGemini($userQuery, $movieData) {
        
        // ====================================================
        // ⚠️ PASTE YOUR KEY HERE
        // ====================================================
        $apiKey = 'AIzaSyDjMpeDsF2hK778_5ylLSCuGtkv-wfnPnE'; 

        // FIX: Using 'gemini-flash-latest' which appeared in your approved list
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $apiKey;

        // System Instructions + User Prompt
        $fullPrompt = "You are CineBot, a helpful AI concierge for 'screenWave' cinemas.
        
        Here is our CURRENT MOVIE LIST:
        $movieData
        
        USER REQUEST: '$userQuery'
        
        YOUR GOAL:
        Recommend a movie from the list above.
        
        RULES:
        1. Only recommend movies from the provided list.
        2. If no exact match, suggest the closest one.
        3. Keep it short (max 3 sentences).
        4. BOLD the movie title (e.g. <b>Title</b>).
        5. END with this exact HTML button: 
           <br><a href='/onlinecinemabookingsystem/public/browse/show/ID' class='inline-block mt-2 bg-indigo-600 text-white px-3 py-1 rounded text-xs font-bold'>Book Ticket for ID</a>
           (Replace 'ID' with the movie's actual database ID).";

        // Gemini JSON Structure
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $fullPrompt]
                    ]
                ]
            ]
        ];

        // Setup cURL
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        // ⚠️ DISABLE SSL VERIFICATION (Fix for XAMPP/Localhost)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        
        // Debug: Connection Errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return "Connection Error: " . $error;
        }
        
        curl_close($ch);
        $result = json_decode($response, true);

        // Debug: API Errors
        if (isset($result['error'])) {
            return "API Error: " . $result['error']['message'];
        }

        // Extract Text from Gemini Response
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        return "I'm having trouble thinking of a recommendation right now.";
    }
}