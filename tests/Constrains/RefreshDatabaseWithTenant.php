<?php

declare(strict_types=1);

namespace Tests\Constrains;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

trait RefreshDatabaseWithTenant
{
    use RefreshDatabase {
        beginDatabaseTransaction as parentBeginDatabaseTransaction;
    }

    /**
     * The database connections that should have transactions.
     *
     * `null` is the default landlord connection
     * `tenant` is the tenant connection
     */
    protected array $connectionsToTransact = [null, 'tenant'];

    /**
     * We need to hook initialize tenancy _before_ we start the database
     * transaction, otherwise it cannot find the tenant connection.
     */
    public function beginDatabaseTransaction(): void
    {
        $this->initializeTenant();

        $this->parentBeginDatabaseTransaction();
    }

    public function initializeTenant(): void
    {
        $tenant = Tenant::firstOr(fn () => Tenant::factory()->name('Acme')->create());

        tenancy()->initialize($tenant);

        URL::forceRootUrl('http://acme.localhost');
    }
}
