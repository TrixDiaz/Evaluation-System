<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display the quiz.
     */
    public function show(Quiz $quiz)
    {
        // Debug the quiz and its questions
        // dd([
        //     'quiz' => $quiz->toArray(),
        //     'questions' => $quiz->questions->toArray()
        // ]);

        // Check if published or user has permission to view
        if (!$quiz->is_published) {
            abort(403, 'This quiz is not available');
        }

        return view('quizzes.show', compact('quiz'));
    }

    /**
     * Submit a quiz attempt.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'studentInfo.name' => 'required|string|max:255',
                'studentInfo.section' => 'required|string|max:255',
                'answers' => 'required|array',
                'answers.*' => 'required'
            ]);

            // Create quiz attempt
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => auth()->id() ?? null,
                'student_name' => $validated['studentInfo']['name'],
                'student_section' => $validated['studentInfo']['section'],
                'answers' => $validated['answers'],
                'score' => 0, // Will calculate below
                'completed_at' => now(),
            ]);

            // Calculate score
            $score = 0;
            foreach ($validated['answers'] as $questionId => $answer) {
                $question = $quiz->questions->firstWhere('id', $questionId);
                if (!$question) continue;

                switch ($question->question_type) {
                    case 'rating':
                        if ((int)$answer === (int)$question->rating_correct_answer) {
                            $score++;
                        }
                        break;

                    case 'multiple_choice':
                        if (isset($question->answer_content[0]['multiple_choice']['choices'])) {
                            $correctAnswer = collect($question->answer_content[0]['multiple_choice']['choices'])
                                ->filter(fn($choice) => $choice['is_correct'] ?? false)
                                ->keys()
                                ->first();

                            if ((int)$answer === (int)$correctAnswer) {
                                $score++;
                            }
                        }
                        break;

                    case 'text':
                        // For text answers, you might want to implement more sophisticated comparison
                        $submittedAnswer = strtolower(trim($answer));
                        $correctAnswer = strtolower(trim($question->correct_answer ?? ''));
                        if ($submittedAnswer === $correctAnswer) {
                            $score++;
                        }
                        break;
                }
            }

            // Update attempt with final score
            $attempt->update(['score' => $score]);

            // Create quiz completion
            QuizCompletion::create([
                'quiz_id' => $quiz->id,
                'user_id' => auth()->id() ?? null,
                'quiz_attempt_id' => $attempt->id,
                'feedback' => [
                    'total_questions' => $quiz->questions->count(),
                    'correct_answers' => $score,
                    'percentage' => ($score / $quiz->questions->count()) * 100
                ],
                'completed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('quiz.results', $attempt->id)
                ->with('success', 'Quiz completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Quiz submission error: ' . $e->getMessage());
            return back()->with('error', 'There was an error submitting your quiz. Please try again.');
        }
    }

    /**
     * Display the quiz results.
     */
    public function results(QuizAttempt $attempt)
    {
        return view('quizzes.results', [
            'attempt' => $attempt->load(['quiz', 'completion'])
        ]);
    }
}
