<?php

namespace GraphQLClient;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

/**
 * Class LumentTestGraphQLClient
 *
 * @package parku\AppBundle\Tests\GraphQL
 */
class LaravelTestGraphQLClient extends Client
{
    use MakesHttpRequests;

    private $app;

    private $response;

    public function __construct(Application $app, $baseUrl)
    {
        parent::__construct($baseUrl);

        $this->app = $app;
    }

    protected function postQuery(array $data): array
    {
        $this->response = $this->postJson($this->baseUrl, $data);

        if ($this->response->getStatusCode() >= 400) {
            throw new GraphQLException(sprintf(
                'Mutation failed with status code %d and error %s',
                $this->response->getStatusCode(),
                $this->response->getContent()
            ), $this->response->getStatusCode());
        }

        $responseBody = json_decode($this->response->getContent(), true);

        if (isset($responseBody['errors'])) {
            throw new GraphQLException(sprintf(
                'Mutation failed with error %s', json_encode($responseBody['errors'])
            ), $this->response->getStatusCode());
        }

        return $responseBody;
    }
}
