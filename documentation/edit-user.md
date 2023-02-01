Edit user
------------

### Request

```
PATCH http://domain/api/users/{{id}}
```

Authorization header: `Bearer {{token}}`

Params:

| Param                 | Validation rules                            |
|-----------------------|---------------------------------------------|
| first_name (optional) | It should have 2 characters or more.                                       |
| last_name (optional)  | It should have 2 characters or more.        |
| email (optional)      | Is a valid email adress.                    |
| phone (optional)      | Starts with '+', contains 7 or more digits. |
| password (optional)   | Should not be blank.                        |

Body:
```
{
    "first_name": "John",
    "last_name": "Woodcock",
    "email": "testmail1@email.com",
    "phone": "+486468464684",
    "password": "qwerty123"
}
```
### Response

**Success**

200
```
{
    "message": "User was edited."
}
```

**Errors**

401
```
{
    "code": 401,
    "message": "Invalid JWT Token"
}
```

403
```
{
    "errors": {
        "message": "Access Denied."
    }
}
```

422 (Validation errors)
```
{
    "errors": [
        {
            "property": "email",
            "message": "This value is not a valid email address."
        },
        {
            "property": "firstName",
            "message": "This value is too short. It should have 2 characters or more."
        },
        {
            "property": "lastName",
            "message": "This value is too short. It should have 2 characters or more."
        },
        {
            "property": "phone",
            "message": "This value is not valid."
        },
        {
            "property": "password",
            "message": "This value should not be blank."
        }
    ]
}
```