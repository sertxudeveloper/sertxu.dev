<?php

declare(strict_types=1);

use App\Filament\Resources\FormSubmissions\FormSubmissionResource;
use App\Models\FormSubmission;

it('has the correct model assigned', function () {
    expect(FormSubmissionResource::getModel())->toBe(FormSubmission::class);
});

it('belongs to the Forms navigation group', function () {
    $reflection = new ReflectionClass(FormSubmissionResource::class);
    $property = $reflection->getProperty('navigationGroup');
    $property->setAccessible(true);

    expect($property->getValue())->toBe('Forms');
});
