Feature:
  In order to receive help from the website
  As a User
  I want to be able to send a message to the website's support

  Scenario: Requesting contact as a guest
    When I go to the contact page
    And I specify my name as "Löræm ìpsûm"
    And I specify my email as "ipsum@example.com"
    And I send the contact form
    Then I should be notified that the contact request has been submitted successfully
    #And the email with contact request should be sent to "contact@goodshop.com"
