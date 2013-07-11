<?php

namespace Level3\Messages;

use Level3\Hal\Formatter\FormatterFactory;
use Level3\Hal\Resource;
use Level3\Hal\ResourceFactory;
use Teapot\StatusCode;

class ResponseFactory
{
    private $resourceFactory;
    private $formatterFactory;

    public function __construct(ResourceFactory $resourceFactory, FormatterFactory $formatterFactory)
    {
        $this->resourceFactory = $resourceFactory;
        $this->formatterFactory = $formatterFactory;
    }

    public function create(Request $request, Resource $resource, $statusCode = StatusCode::OK)
    {
        $formatter = $this->getResourceFormatter($request);
        $resource->setFormatter($formatter);
        $response = new Response($resource, $statusCode);
        $response->prepare($request);
        $response->setContentType($formatter->getContentType());
        return $response;
    }

    private function getResourceFormatter(Request $request)
    {
        $contentTypes = $request->getAcceptableContentTypes();
        return $this->formatterFactory->create($contentTypes);

    }

    public function createFromDataAndStatusCode(Request $request, array $data, $statusCode)
    {
        $resource = $this->resourceFactory->create(null, $data);
        return $this->create($request, $resource, $statusCode);
    }
}