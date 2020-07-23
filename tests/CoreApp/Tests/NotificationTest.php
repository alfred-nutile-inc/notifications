<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 2/12/15
 * Time: 8:23 PM
 */

namespace AlfredNutileInc\Tests;

use AlfredNutileInc\Notifications\NotificationFacade as Notify;
use Approve\Profile\User;
use Mockery as m;

class Notification extends \TestCase {

    public function setUp()
    {
        parent::setUp();
        $this->refreshDb();
        $this->be(User::find('mock-user-1'));
    }

    /**
     * @test
     */
    public function should_get_all_notifications()
    {
        $notifications = Notify::setRead(-1)->getAll();
        $this->assertEquals(10, count($notifications->toArray()));
    }

    /**
     * @test
     */
    public function should_get_20_notifications()
    {
        $notifications = Notify::setLimit(20)->setRead(-1)->getAll();
        $this->assertEquals(20, count($notifications->toArray()));
    }

    /**
     * @test
     */
    public function should_get_all_notifications_for_project()
    {

        $notifications = Notify::setLimit(5)->setRead(-1)->from('mock-project-1');
        $this->assertEquals(5, count($notifications->toArray()));


        $notifications = Notify::setLimit(5)->setRead(-1)->from('mock-project-2');
        $this->assertEquals(0, count($notifications->toArray()));

    }


    /**
     * @test
     */
    public function should_get_all_notifications_unread_for_project()
    {
        $notifications = Notify::setRead(0)->setLimit(20)->from('mock-project-1');
        $this->assertLessThan(20, count($notifications->toArray()));

    }

    /**
     * @test
     */
    public function should_get_all_notifications_unread_for_user()
    {
        $notifications = Notify::setRead(0)->setLimit(20)->to('mock-user-1');
        $this->assertLessThan(20, count($notifications->toArray()));
        $this->assertGreaterThan(3, count($notifications->toArray()));
    }

    /**
     * @test
     */
    public function should_get_all_notifications_unread_for_user_in_a_project()
    {
        $notifications = Notify::setRead(0)->setLimit(20)->setFromId('mock-project-1')->to('mock-user-1');
        $this->assertLessThan(20, count($notifications->toArray()));
        $this->assertGreaterThan(3, count($notifications->toArray()));

        $notifications = Notify::setRead(0)->setFromId('mock-project-3')->setLimit(20)->to('mock-user-1');
        $this->assertEquals(0, count($notifications->toArray()));
    }

    /**
     * @test
     */
    public function should_get_all_notifications_for_user()
    {
        $notifications = Notify::setRead(-1)->setLimit(20)->setFromId('mock-project-1')->to('mock-user-1');
        $this->assertEquals(20, count($notifications->toArray()));
        $this->assertGreaterThan(5, count($notifications->toArray()));
    }

    /**
     * @test
     */
    public function should_get_10_unread_notifications_for_user()
    {
        $notifications = Notify::setRead(0)->setLimit(20)->setFromId('mock-project-1')->to('mock-user-1');
        $this->assertLessThan(20, count($notifications->toArray()));
        $this->assertGreaterThan(5, count($notifications->toArray()));
    }



    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}