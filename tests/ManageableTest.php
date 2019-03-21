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

        $this->assertTrue($order->creator->is($user));
        $this->assertNotNull($order->fresh()->created_by);
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

        $this->assertTrue($order->editor->is($user));
        $this->assertNotNull($order->fresh()->updated_by);
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

        $this->assertNull($order->created_by);
        $this->assertNull($order->updated_by);
    }
}
