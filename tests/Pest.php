<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Apply base TestCase + RefreshDatabase ke semua test di Feature & Unit
uses(TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');
