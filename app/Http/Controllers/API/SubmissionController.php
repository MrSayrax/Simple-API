<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Jobs\ProcessSubmission;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{

    /**
     * Store a submission.
     *
     * @group Submissions
     * @bodyParam name string required The name of the submitter. Example: John Doe
     * @bodyParam email string required The email of the submitter. Example: john.doe@example.com
     * @bodyParam message string required The message from the submitter. Example: This is a test message
     * @response 200 {
     *     "message": "Submitted successfully"
     * }
     * @response 500 {
     *     "message": "Submission Failed"
     * }
     *
     * @param  StoreSubmissionRequest  $request
     * @return JsonResponse
     */
    public function store(StoreSubmissionRequest $request): JsonResponse
    {

        try {
            // Validation and Job dispatching
            $validated = $request->validated();
            ProcessSubmission::dispatch($validated);
            return response()->json(['message' => 'Submitted successfully']);
        } catch (Exception $e) {
            // Log the error
            Log::error($e->getMessage());
            // If we need to save all trace, just uncomment string bellow
            // report($e)

            // Return error response
            return response()->json(['message' => 'Submission Failed'], 500);
        }
    }
}
