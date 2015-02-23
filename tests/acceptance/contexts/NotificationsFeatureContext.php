<?php

use AlfredNutileInc\CoreApp\Notifications\Notification;
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class NotificationsFeatureContext extends FeatureContext {


    /**
     * @Given /^create notification with read "([^"]*)" and id "([^"]*)"$/
     */
    public function createNotificationWithReadAndId($arg1, $arg2)
    {
        $this->iResetFromNotifications($arg2);
        Notification::create([
            'id' => $arg2,
            'from_type' => 'Approve\Projects\Project',
            'from_id'   => 'mock-project-1',
            'to_id'     => 'mock-user-1',
            'to_type'   => 'Approve\Profile\User',
            'read'      => $arg1,
            'notification_message_id' => 'mock-message-' . rand(1,20),
            'notification_category_id' => 'mock-category-' . rand(1,5),
            'created_at' => $this->faker->dateTimeThisYear()
        ]);
    }

    /**
     * @Given /^I reset "([^"]*)" from notifications/
     */
    public function iResetFromNotifications($notice_id)
    {
        if($results = Notification::find($notice_id))
            $results->delete();
    }

}
