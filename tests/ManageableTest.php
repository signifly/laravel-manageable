<?php

namespace Signifly\Manageable\Test;

use Signifly\Manageable\Test\Models\User;
use Signifly\Manageable\Test\Models\Order;

class ManageableTest extends TestCase
{
    /** @test */
    public function it_tracks_who_creates_an_order()
    {
        $user = User::first();
        $this->actingAs($user);

        $order = Order::create([
            'title' => 'some title',
        ]);

        tap($order->fresh(), function ($order) use ($user) {
            $this->assertTrue($order->creator->is($user));
            $this->assertNotNull($order->fresh()->created_by);
        });
    }

    /** @test */
    public function it_tracks_who_updates_an_order()
    {
        $user = User::first();
        $this->actingAs($user);

        $order = Order::create([
            'title' => 'some title',
        ]);

        $order->title = 'some new title';
        $order->save();

        tap($order->fresh(), function ($order) use ($user) {
            $this->assertTrue($order->editor->is($user));
            $this->assertNotNull($order->fresh()->updated_by);
        });
    }

    /** @test */
    public function it_updates_the_editor()
    {
        $userA = User::first();
        $userB = User::latest()->first();
        $this->actingAs($userA);

        $order = Order::create([
            'title' => 'some title',
        ]);

        $order->title = 'some new title';
        $order->save();

        tap($order->fresh(), function ($order) use ($userA) {
            $this->assertTrue($order->editor->is($userA));
            $this->assertNotNull($order->fresh()->updated_by);
        });

        // Update again with user B
        $this->actingAs($userB);

        $order->title = 'another new title';
        $order->save();

        tap($order->fresh(), function ($order) use ($userB) {
            $this->assertTrue($order->editor->is($userB));
            $this->assertEquals($userB->id, $order->updated_by);
        });
    }

    /** @test */
    public function it_does_not_track_if_a_user_is_not_authenticated()
    {
        $user = User::first();

        $order = Order::create([
            'title' => 'some title',
        ]);

        $order->title = 'some new title';
        $order->save();

        tap($order->fresh(), function ($order) {
            $this->assertNull($order->created_by);
            $this->assertNull($order->updated_by);
        });
    }
}
