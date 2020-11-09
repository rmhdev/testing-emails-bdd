Feature:
  In order to receive help from the website
  As a User
  I want to be able to send a message to the website's team

  Scenario: Requesting contact as a guest
    When I go to the contact page
    And I specify my name as "Löræm ìpsûm"
    And I specify my email as "ipsum@user.example.com"
    And I send the contact form
    Then I should see a confirmation that the message has been sent successfully

  @email
  Scenario: Sending email after requesting contact as a guest
    When I go to the contact page
    And I specify my name as "Löræm ìpsûm"
    And I specify my email as "ipsum@user.example.com"
    And I send the contact form
    Then an email should be sent to "contact@mywebsite.example.com"
