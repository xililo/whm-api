<?php

declare(strict_types=1);

namespace Xililo\WhmApi;

use Xililo\WhmApi\Resources\Accounts;
use Xililo\WhmApi\Resources\Authentication;
use Xililo\WhmApi\Resources\HostingPlans;

final class Whm
{
    private readonly WhmClient $client;

    private ?Authentication $authentication = null;

    private ?Accounts $accounts = null;

    private ?HostingPlans $hostingPlans = null;

    public function __construct(Config $config)
    {
        $this->client = new WhmClient($config);
    }

    public function client(): WhmClient
    {
        return $this->client;
    }

    public function auth(): Authentication
    {
        return $this->authentication ??= new Authentication($this->client);
    }

    public function accounts(): Accounts
    {
        return $this->accounts ??= new Accounts($this->client);
    }

    public function hostingPlans(): HostingPlans
    {
        return $this->hostingPlans ??= new HostingPlans($this->client);
    }
}
