<?php
/**
 * OpenAI Quiz Question Generator
 * Fetches questions from ChatGPT API based on category and difficulty
 */

// Load environment variables
if (!function_exists('getEnvVar')) {
  function getEnvVar($key) {
    static $env = null;
    
    if ($env === null) {
      $envFile = __DIR__ . '/.env';
      $env = [];
      
      if (file_exists($envFile)) {
        $lines = file($envFile, FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
          $line = trim($line);
          if (empty($line) || $line[0] === '#') {
            continue;
          }
          if (strpos($line, '=') === false) {
            continue;
          }
          list($name, $value) = explode('=', $line, 2);
          $env[trim($name)] = trim($value);
        }
      }
    }
    
    return $env[$key] ?? null;
  }
}

// Fetch questions from ChatGPT
if (!function_exists('fetchQuestionsFromChatGPT')) {
  function fetchQuestionsFromChatGPT($category, $difficulty, $count) {
    $apiKey = getEnvVar('OPENAI_API_KEY');
    
    if (!$apiKey) {
      die('OpenAI API key not configured in .env file.');
    }

    $prompt = "Generate exactly $count multiple choice quiz questions about $category at $difficulty level. 
Format your response as JSON array with this structure (no markdown, just raw JSON):
[
  {
    \"question\": \"Question text?\",
    \"options\": {
      \"A\": \"Option A\",
      \"B\": \"Option B\",
      \"C\": \"Option C\",
      \"D\": \"Option D\"
    },
    \"correct\": \"A\"
  }
]

Make sure:
- Questions are appropriate for $difficulty level
- Options are plausible
- Exactly one correct answer
- All questions are about $category
- Return ONLY the JSON array, no extra text";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $apiKey,
      'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
      'model' => 'gpt-3.5-turbo',
      'messages' => [
        ['role' => 'user', 'content' => $prompt]
      ],
      'temperature' => 0.7,
      'max_tokens' => 2000
    ]));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Log response for debugging
    error_log("OpenAI API Response Code: $httpCode");
    error_log("OpenAI API Response: " . substr($response, 0, 500));

    if ($httpCode === 429) {
      die('API Rate Limit Exceeded. Please check your OpenAI account: https://platform.openai.com/account/billing/limits');
    }
    
    if ($httpCode === 401) {
      die('OpenAI API Authentication Failed. Check your API key in .env file.');
    }

    if ($httpCode !== 200) {
      die('Failed to fetch questions from ChatGPT. HTTP Code: ' . $httpCode . '. Response: ' . substr($response, 0, 200));
    }

    $data = json_decode($response, true);
    $content = $data['choices'][0]['message']['content'] ?? '';
    
    // Parse JSON from response
    $questions = json_decode($content, true);
    
    if (!is_array($questions)) {
      error_log('Invalid JSON from ChatGPT: ' . $content);
      die('Failed to parse questions from ChatGPT.');
    }

    return $questions;
  }
}

// Cache questions in session
if (!function_exists('cacheQuestionsInSession')) {
  function cacheQuestionsInSession($category, $difficulty, $count) {
    if (!isset($_SESSION['quiz_questions']) || empty($_SESSION['quiz_questions'])) {
      $questions = fetchQuestionsFromChatGPT($category, $difficulty, $count);
      $_SESSION['quiz_questions'] = $questions;
      $_SESSION['quiz_current_index'] = 0;
      $_SESSION['quiz_answers'] = [];
    }
    return $_SESSION['quiz_questions'];
  }
}

// Get current question
if (!function_exists('getCurrentQuestion')) {
  function getCurrentQuestion() {
    if (!isset($_SESSION['quiz_questions']) || !isset($_SESSION['quiz_current_index'])) {
      return null;
    }
    $index = $_SESSION['quiz_current_index'];
    return $_SESSION['quiz_questions'][$index] ?? null;
  }
}

// Move to next question
if (!function_exists('nextQuestion')) {
  function nextQuestion() {
    if (isset($_SESSION['quiz_current_index'])) {
      $_SESSION['quiz_current_index']++;
    }
  }
}

// Check if there are more questions
if (!function_exists('hasMoreQuestions')) {
  function hasMoreQuestions() {
    if (!isset($_SESSION['quiz_questions']) || !isset($_SESSION['quiz_current_index'])) {
      return false;
    }
    return $_SESSION['quiz_current_index'] < count($_SESSION['quiz_questions']);
  }
}

// Store answer
if (!function_exists('storeAnswer')) {
  function storeAnswer($answer) {
    if (!isset($_SESSION['quiz_answers'])) {
      $_SESSION['quiz_answers'] = [];
    }
    $_SESSION['quiz_answers'][] = [
      'question_index' => $_SESSION['quiz_current_index'],
      'answer' => $answer
    ];
  }
}

// Calculate final score
if (!function_exists('calculateFinalScore')) {
  function calculateFinalScore() {
    if (!isset($_SESSION['quiz_questions']) || !isset($_SESSION['quiz_answers'])) {
      return ['correct' => 0, 'total' => 0];
    }

    $correct = 0;
    $total = count($_SESSION['quiz_questions']);

    foreach ($_SESSION['quiz_answers'] as $response) {
      $index = $response['question_index'];
      $answer = $response['answer'];
      
      if (isset($_SESSION['quiz_questions'][$index])) {
        $question = $_SESSION['quiz_questions'][$index];
        if ($question['correct'] === $answer) {
          $correct++;
        }
      }
    }

    return ['correct' => $correct, 'total' => $total];
  }
}
?>
