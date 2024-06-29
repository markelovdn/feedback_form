<?php

namespace App\Http\Controllers\Feedback;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
use App\UseCases\AcceptFeedbackData;
use App\UseCases\FeedbackUseCases;
use DateTime;
use DateTimeZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackController extends Controller
{
    public function store(FeedbackStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $feedback = app(FeedbackUseCases::class)->acceptFeedback(new AcceptFeedbackData(
            title: $data['title'],
            description: $data['description'],
            datetime: $this->convertTimestampToDT($data['datetime']),
            rating: $data['rating'],
            serviceId: $data['serviceId'],
        ));

        return response()->json([
            'id' => $feedback->id
        ], 201);
    }

    public function show(Feedback $feedback): JsonResource
    {
        return new FeedbackResource($feedback);
    }

    private function convertTimestampToDT($microtime): DateTime
    {
        $dt = DateTime::createFromFormat('U', floor($microtime / 1000));
        $dt->setTimeZone(new DateTimeZone('Europe/Moscow'));
        return $dt;
    }
}
