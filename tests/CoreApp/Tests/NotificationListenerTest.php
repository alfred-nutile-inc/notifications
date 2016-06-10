<?php


namespace AlfredNutileInc\DiffTool\Tests;

use AlfredNutileInc\Notifications\Notification;
use AlfredNutileInc\Notifications\NotificationCategory;
use AlfredNutileInc\DiffTool\Notifications\NotificationsListener;
use Illuminate\Support\Facades\Event;
use Mockery as m;

class NotificationListenerTest extends \TestCase {


    public function setUp()
    {
        parent::setUp();

    }

    /**
     * @test
     */
    public function should_make_dto_from_event()
    {
        //Arrange
        $listener = new NotificationsListener($this->app);
        $message = (object) ['project_id' => 'mock-project-1', 'request_id' => 'mock-request-1', 'stage' => 1] ;

        //Act
        $listener->filesUploadReadyToCompareNotice($message);

        //Assert
        $this->assertInstanceOf('AlfredNutileInc\DiffTool\DiffToolDTO', $listener->getDto());
    }

    /**
     * @test
     */
    public function should_have_0_for_whom_and_stop()
    {
        //Arrange
        $listener = m::mock('AlfredNutileInc\DiffTool\Notifications\NotificationsListener[setCategory]', [$this->app]);
        $message = (object) ['project_id' => 'mock-project-5', 'request_id' => 'mock-request-1', 'stage' => 1] ;

        $listener->shouldNotHaveReceived('setCategory');

        //Act
        $listener->filesUploadReadyToCompareNotice($message);

        //Assert
        //See shouldNot
    }

    /**
     * @test
     */
    public function should_make_category_if_does_not_exist()
    {
        //Arrange
        $this->removeCategory('diff_tool.file_uploads_ready_to_compare');
        $before = NotificationCategory::all()->count();
        $listener = new NotificationsListener($this->app);
        $listener->setEventName('diff_tool.file_uploads_ready_to_compare');
        $message = (object) ['project_id' => 'mock-project-1', 'request_id' => 'mock-request-1', 'stage' => 1] ;

        //Act
        $listener->filesUploadReadyToCompareNotice($message);

        //Assert
        $after = NotificationCategory::all()->count();
        $this->assertGreaterThan($before, $after);
    }

    /**
     * @test
     */
    public function should_make_message()
    {
        //Arrange
        $listener = new NotificationsListener($this->app);
        $listener->setEventName('diff_tool.file_uploads_ready_to_compare');
        $message = (object) ['project_id' => 'mock-project-1', 'request_id' => 'mock-request-1', 'stage' => 1] ;

        //Act
        $listener->filesUploadReadyToCompareNotice($message);

        //Assert
        $this->assertNotNull($listener->getMessageId());
        $this->assertNotNull($listener->getMessage());
    }

    /**
     * @test
     */
    public function should_make_many_notifications()
    {
        //Arrange
        $before = Notification::all()->count();
        $listener = new NotificationsListener($this->app);
        $listener->setEventName('diff_tool.file_uploads_ready_to_compare');
        $message = (object) ['project_id' => 'mock-project-1', 'request_id' => 'mock-request-1', 'stage' => 1] ;

        //Act
        $listener->filesUploadReadyToCompareNotice($message);

        //Assert
        $after = Notification::all()->count();
        $this->assertGreaterThan($before, $after);
        $this->assertEquals(2, count($listener->getNotificationsMade()));
    }

    public function removeCategory($cat_name)
    {
        if($results = NotificationCategory::where('name', $cat_name)->first())
        {
            $results->delete();
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

} 