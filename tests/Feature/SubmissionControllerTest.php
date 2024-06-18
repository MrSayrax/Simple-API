<?php

namespace Feature;

use App\Http\Requests\StoreSubmissionRequest;
use App\Jobs\ProcessSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Faker\Factory as Faker;

/**
 * Tests for the SubmissionController class
 */
class SubmissionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a valid submission successfully stores the data
     * and dispatches the job.
     */
    public function test_store_submission_success()
    {
        // Fake the queue
        Queue::fake();

        // Create a Faker instance
        $faker = Faker::create();

        // Define the request data using Faker
        $data = [
            'name' => $faker->name,
            'email' => $faker->safeEmail,
            'message' => $faker->sentence
        ];

        // Perform the HTTP POST request
        $response = $this->postJson('/api/submit', $data);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Submitted successfully']);

        // Assert the job was pushed to the queue
        Queue::assertPushed(ProcessSubmission::class, function ($job) use ($data) {
            return $job->data === $data;
        });
    }

    /**
     * Test that a submission fails with a validation error
     * and does not dispatch the job.
     */
    public function test_store_submission_failure()
    {
        // Fake the queue
        Queue::fake();

        // Create a Faker instance
        $faker = Faker::create();

        // Define the request data using Faker
        $data = [
            'name' => $faker->name,
            'email' => $faker->safeEmail,
            'message' => $faker->sentence
        ];

        // Mock validation to throw an exception
        $this->withoutExceptionHandling();
        $this->app->instance(StoreSubmissionRequest::class, new class extends StoreSubmissionRequest {
            public function validated($key = null, $default = null)
            {
                throw new \Exception('Validation Error');
            }
        });

        // Perform the HTTP POST request
        $response = $this->postJson('/api/submit', $data);

        // Assert the response
        $response->assertStatus(500);
        $response->assertJson(['message' => 'Submission Failed']);

        // Assert the job was not pushed to the queue
        Queue::assertNothingPushed();
    }

    /**
     * Test that a submission fails validation when the name field is empty.
     */
    public function test_store_submission_validation_error_name()
    {
        $this->validateField('name', '');
    }

    /**
     * Test that a submission fails validation when the email field is not a valid email.
     */
    public function test_store_submission_validation_error_email()
    {
        $this->validateField('email', 'not-an-email');
    }

    /**
     * Test that a submission fails validation when the message field is empty.
     */
    public function test_store_submission_validation_error_message()
    {
        $this->validateField('message', '');
    }

    /**
     * Test that a submission fails validation when the name field exceeds the maximum length.
     */
    public function test_store_submission_validation_error_name_max_length()
    {
        $this->validateField('name', str_repeat('a', 256));
    }

    /**
     * Test that a submission fails validation when the email field exceeds the maximum length.
     */
    public function test_store_submission_validation_error_email_max_length()
    {
        $this->validateField('email', str_repeat('a', 244) . '@example.com');
    }

    /**
     * Test that a submission fails validation when the message field exceeds the maximum length.
     */
    public function test_store_submission_validation_error_message_max_length()
    {
        $this->validateField('message', str_repeat('a', 2001));
    }

    /**
     * Helper method to perform validation tests on specific fields.
     *
     * @param string $field The field to validate.
     * @param string $value The invalid value for the field.
     */
    private function validateField(string $field, string $value): void
    {
        // Create a Faker instance
        $faker = Faker::create();

        // Define the request data using Faker
        $data = [
            'name' => $faker->name,
            'email' => $faker->safeEmail,
            'message' => $faker->sentence
        ];

        // Set the specific field to the invalid value
        $data[$field] = $value;

        // Perform the HTTP POST request
        $response = $this->postJson('/api/submit', $data);

        // Assert the response
        $response->assertStatus(422); // Unprocessable Entity for validation errors

        // Assert the specific validation error message
        $response->assertJsonValidationErrors($field);
    }
}
