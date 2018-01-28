<?php

namespace Signifly\Manageable\Test;

use Signifly\Manageable\Test\Models\User;
use Signifly\Manageable\Test\Models\Order;

class ManageableTest extends TestCase
{
    /** @test */
    function it_tracks_who_creates_an_order()
    {
        $user = User::first();
        $this->actingAs($user);

        $order = Order::create([
            'title' => 'some title',
        ]);

        $this->assertEquals($user->id, $order->creator->id);
        $this->assertNotNull($order->created_by);
    }

    /** @test */
    function it_tracks_who_updates_an_order()
    {
        $user = User::first();
        $this->actingAs($user);

        $order = Order::create([
            'title' => 'some title',
        ]);

        $order->title = 'some new title';
        $order->save();

        $this->assertEquals($user->id, $order->editor->id);
        $this->assertNotNull($order->updated_by);
        $this->assertEquals('some new title', $order->fresh()->title);
    }

    /** @test */
    function it_does_not_track_if_a_user_is_not_authenticated()
    {
        $user = User::first();

        $order = Order::create([
            'title' => 'some title',
        ]);

        $this->assertNull($order->created_by);
        $this->assertNull($order->updated_by);
    }
}
