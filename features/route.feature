Feature: Manage route
  @createSchema
  @route
  Scenario: Throws error in JSON format when Route not found
  Given I am authenticated as "admin"
  And I add "Accept" header equal to "application/ld+json"
  And I send a "GET" request to "/api/not/found/route"
  Then the response status code should be 400
  And the response should be in JSON
  And the JSON matches expected template:
  """
  {
    "type":"https:\/\/tools.ietf.org\/html\/rfc2616#section-10",
    "title":"An error occurred",
    "detail":"No route found for \u0022GET \/api\/not\/found\/route\u0022 (from \u0022http:\/\/127.0.0.1:8000\/api\/login_check\u0022)",
    "violations":[
      {
        "propertyPath":"",
        "message":"No route found for \u0022GET \/api\/not\/found\/route\u0022 (from \u0022http:\/\/127.0.0.1:8000\/api\/login_check\u0022)"
      }
    ]
  }
  """