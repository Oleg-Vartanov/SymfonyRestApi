Delete user
------------

### Request

```
DELETE http://domain/api/users/{{id}}
```

Authorization header: `Bearer {{token}}`

### Response

**Success**

200

```
{
    "message": "User was deleted."
}
```

**Error**

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