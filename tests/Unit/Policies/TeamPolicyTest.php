<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Policies\TeamPolicy;
use App\Policies\BasePolicy;
use App\Models\User;
use App\Entities\Team;
use Mockery;

class TeamPolicyTest extends TestCase
{
    protected $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TeamPolicy();
    }

    public function test_policy_can_be_instantiated(): void
    {
        $this->assertInstanceOf(TeamPolicy::class, $this->policy);
        $this->assertInstanceOf(BasePolicy::class, $this->policy);
    }

    public function test_view_any_returns_true(): void
    {
        $user = Mockery::mock(User::class);

        $result = $this->policy->viewAny($user);

        $this->assertTrue($result);
    }

    public function test_view_returns_true(): void
    {
        $user = Mockery::mock(User::class);

        $result = $this->policy->view($user);

        $this->assertTrue($result);
    }

    public function test_create_returns_true(): void
    {
        $user = Mockery::mock(User::class);

        $result = $this->policy->create($user);

        $this->assertTrue($result);
    }

    public function test_update_returns_true(): void
    {
        $user = Mockery::mock(User::class);

        $result = $this->policy->update($user);

        $this->assertTrue($result);
    }

    public function test_delete_returns_true_when_user_can_delete(): void
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('canDelete')
            ->once()
            ->andReturn(true);

        $result = $this->policy->delete($user);

        $this->assertTrue($result);
    }

    public function test_delete_returns_false_when_user_cannot_delete(): void
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('canDelete')
            ->once()
            ->andReturn(false);

        $result = $this->policy->delete($user);

        $this->assertFalse($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

