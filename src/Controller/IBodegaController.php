<?php

namespace App\Controller;

use App\Entity\Bodega;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface IBodegaController
{
    public function showAll(): JsonResponse;

    public function show(?Bodega $bodega = null): JsonResponse;

    public function add(Request $request): JsonResponse;

    public function update(Request $request, ?Bodega $bodega = null): JsonResponse;

    public function delete(?Bodega $bodega = null): JsonResponse;
}
