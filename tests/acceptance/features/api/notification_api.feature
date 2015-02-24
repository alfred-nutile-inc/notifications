@api
Feature: Notification API
  CRUD for notifications
  As a User
  So I can see unread messages and marked them read

  Background: Login
    And I do basic auth as "cloudappsteam+mock1@gmail.com" with password "APPROVE_MOCK_PWD"

  Scenario: Get All Notifications
    When I request "GET /api/v1/notifications"
    Then I get a "200" response
    And scope into the "data.notifications.0" property
    And the properties exist:
    """
    id
    notification_message
    """
    And the "read" property equals "0"

  Scenario: And I mark as read
    Given create notification with read "1" and id "mock-notification-put"
    Given I have the payload:
    """
      { "data":
        {
           "read": 1,
           "id": "mock-notification-put"
         }
      }
      """
    And I request "PUT /api/v1/notifications/mock-notification-put"
    Then I get a "200" response
    Then I request "GET /api/v1/notifications/mock-notification-put"
    And I get a "200" response
    And scope into the "data.notification" property
    And the "read" property equals "1"
