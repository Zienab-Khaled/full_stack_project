<?php

namespace Tests\Unit;

use App\Http\Requests\PostRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PostRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_post_data_passes_validation()
    {
        $data = [
            'title' => 'Valid Post Title',
            'content' => 'This is valid post content with sufficient length.',
            'status' => 'published',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_title_is_required()
    {
        $data = [
            'content' => 'This is valid post content.',
            'status' => 'published',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('title'));
    }

    public function test_title_must_be_string()
    {
        $data = [
            'title' => 123,
            'content' => 'This is valid post content.',
            'status' => 'published',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('title'));
    }

    public function test_title_must_not_exceed_255_characters()
    {
        $data = [
            'title' => str_repeat('a', 256),
            'content' => 'This is valid post content.',
            'status' => 'published',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('title'));
    }

    public function test_content_is_required()
    {
        $data = [
            'title' => 'Valid Post Title',
            'status' => 'published',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('content'));
    }

    public function test_content_must_be_string()
    {
        $data = [
            'title' => 'Valid Post Title',
            'content' => 123,
            'status' => 'published',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('content'));
    }

    public function test_status_is_required()
    {
        $data = [
            'title' => 'Valid Post Title',
            'content' => 'This is valid post content.',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('status'));
    }

    public function test_status_must_be_published_or_draft()
    {
        $data = [
            'title' => 'Valid Post Title',
            'content' => 'This is valid post content.',
            'status' => 'invalid_status',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('status'));
    }

    public function test_status_published_is_valid()
    {
        $data = [
            'title' => 'Valid Post Title',
            'content' => 'This is valid post content.',
            'status' => 'published',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_status_draft_is_valid()
    {
        $data = [
            'title' => 'Valid Post Title',
            'content' => 'This is valid post content.',
            'status' => 'draft',
        ];

        $validator = Validator::make($data, (new PostRequest())->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_custom_error_messages_are_defined()
    {
        $request = new PostRequest();
        $messages = $request->messages();

        $this->assertArrayHasKey('title.required', $messages);
        $this->assertArrayHasKey('title.max', $messages);
        $this->assertArrayHasKey('content.required', $messages);
        $this->assertArrayHasKey('status.required', $messages);
        $this->assertArrayHasKey('status.in', $messages);
    }

    public function test_authorization_returns_true()
    {
        $request = new PostRequest();

        $this->assertTrue($request->authorize());
    }
}
