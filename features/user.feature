Feature: Manage Users
  @createSchema @adminGetUsers
  Scenario: Get all users from SUPERADMIN
    Given I am authenticated as "admin"
    When I add "Content-type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "GET" request to "/api/users?pagination=false"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "hydra:member" has 75 elements

  @addUser
  Scenario: Customer from company "X" Create a new user
    Given I am authenticated as "customerX"
    When I add "Content-type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "POST" request to "/api/users" with body:
    """
    {
      "firstName": "test",
      "lastName": "Test",
      "company": "NotAuthorized",
      "email": "test@bilemo.de",
      "customer": "/api/customers/2"
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node "company" should be equal to "X"
    And the JSON matches expected template:
    """
    {
      "@context":"/api/contexts/User",
      "@id":"@string@",
      "@type":"User",
      "id":@integer@,
      "firstName":"test",
      "lastName":"Test",
      "email":"test@bilemo.de",
      "company":"X",
      "createdAt":"@string@.isDateTime()"
    }
    """

  @addUser
  Scenario: Read recently added user
    Given I am authenticated as "customerX"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "GET" request to "/api/users/76"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON matches expected template:
    """
    {
      "@context":"/api/contexts/User",
      "@id":"/api/users/76",
      "@type":"User",
      "id":76,
      "firstName":"test",
      "lastName":"Test",
      "email":"test@bilemo.de",
      "company":"@string@",
      "createdAt":"@string@.isDateTime()"
    }
    """

  @addUser
  Scenario: Throws an error when post user is invalid
    Given I am authenticated as "customerX"
    When I add "Content-type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "POST" request to "/api/users" with body:
    """
    {
      "firstName": "",
      "lastName": "Test",
      "email": "",
      "customer": "/api/customers/2"
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON matches expected template:
    """
    {
      "@context":"\/api\/contexts\/ConstraintViolationList",
      "@type":"ConstraintViolationList",
      "hydra:title":"An error occurred",
      "hydra:description":"firstName: First name must contain between 3 and 255 characters!\nfirstName: First name is required!\nemail: The email is required!\nemail: Email must contain between 3 and 255 characters!",
      "violations":[
        {
          "propertyPath":"firstName",
          "message":"First name must contain between 3 and 255 characters!"
        },
        {
          "propertyPath":"firstName",
          "message":"First name is required!"
        },
        {
          "propertyPath":"email",
          "message":"The email is required!"
        },
        {
          "propertyPath":"email",
          "message":"Email must contain between 3 and 255 characters!"
        }
      ]
    }
    """

  @invalidUser
  Scenario: Throws error when read invalid user
    Given I am authenticated as "customerX"
    And I add "Accept" header equal to "application/ld+json"
    When I send a "GET" request to "/api/users/20"
    Then the response status code should be 404
    And the response should be in JSON
    And the JSON node "hydra:description" should contain "The user with the given id: '20', does not exist."

  @createSchema @userNotLogged
  Scenario: Throws an error when customer is not authenticated
    When I add "Content-type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "GET" request to "/api/users"
    Then the response status code should be 401
    And the response should be in JSON
    And the JSON matches expected template:
    """
    {
      "code": 401,
      "message": "JWT Token not found"
    }
    """

  @createSchema @deleteUser
  Scenario: Delete user
    Given I am authenticated as "customerX"
    When I add "Content-type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "DELETE" request to "/api/users/1"
    Then the response status code should be 204
